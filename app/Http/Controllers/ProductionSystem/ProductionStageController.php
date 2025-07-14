<?php

namespace App\Http\Controllers\ProductionSystem;

use App\Http\Controllers\Controller;
use App\Models\ProductionLine;
use App\Models\ProductionStage;
use Illuminate\Http\Request;

class ProductionStageController extends Controller
{
    //
      public function index(Request $request)
    {
        $query = ProductionStage::with('productionLine')
            ->orderBy('line_id')
            ->orderBy('sequence');

        if ($request->filled('line_id')) {
            $query->where('line_id', $request->line_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $stages = $query->paginate(15);
        $lines = ProductionLine::active()->get();

        return view('production_system.production-stages.index', compact('stages', 'lines'));
    }
     public function create()
    {
        $lines = ProductionLine::active()->get();
        return view('production_system.production-stages.create', compact('lines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(ProductionStage::getValidationRules());
        try {

        // تحويل البيانات JSON
        // $validated['equipment_settings'] = $this->parseJsonInput($request->equipment_settings);
        // $validated['quality_parameters'] = $this->parseJsonInput($request->quality_parameters);

        ProductionStage::create($validated);

        return redirect()->route('production-stages.index')
            ->with('success', 'تم إنشاء مرحلة الإنتاج بنجاح');
               } catch (\Exception $e) {
            // في حالة حدوث خطأ، إعادة التوجيه مع رسالة خطأ
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء محاولة حفظ البيانات: ' . $e->getMessage());
}
    }

    public function show(ProductionStage $productionStage)
    {
        return view('production_system.production-stages.show', [
            'productionStage' => $productionStage->load('productionLine')
        ]);
    }

    public function edit(ProductionStage $productionStage)
    {
        $lines = ProductionLine::active()->get();
        return view('production_system.production-stages.edit', compact('productionStage', 'lines'));
    }

    public function update(Request $request, ProductionStage $productionStage)
    {
        $validated = $request->validate(ProductionStage::getValidationRules($productionStage->stage_id));

        // تحويل البيانات JSON
        // $validated['equipment_settings'] = $request->equipment_settings;
        // $validated['quality_parameters'] = $request->quality_parameters;

        $productionStage->update($validated);

        return redirect()->route('production-stages.show', $productionStage)
            ->with('success', 'تم تحديث مرحلة الإنتاج بنجاح');
    }

    public function destroy(ProductionStage $productionStage)
    {
        $productionStage->delete();

        return redirect()->route('production-stages.index')
            ->with('success', 'تم حذف مرحلة الإنتاج بنجاح');
    }

    public function toggleStatus(ProductionStage $productionStage)
    {
        $productionStage->update(['is_active' => !$productionStage->is_active]);

        return redirect()->back()
            ->with('success', 'تم تحديث حالة المرحلة بنجاح');
    }

    protected function parseJsonInput($input)
    {
        if (empty($input)) {
            return null;
        }

        try {
            return json_decode($input, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return null;
        }
    }



}
