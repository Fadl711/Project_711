<?php

namespace App\Http\Controllers\ProductionSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionLine;
use App\Models\Department;
use App\Models\MainAccount;
use App\Models\Plant;
use App\Models\ProductionStage;
use App\Models\SubAccount;
use Illuminate\Support\Facades\Auth;

class ProductionLineController extends Controller
{
    //





    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function dashboard()
    {
        $totalLines= ProductionLine::count();
        $activeLines= ProductionLine::count();
        $pendingMaintenance= ProductionLine::count();
        // dd($totalLines);
      

        $productionLines = ProductionLine::with(['department', 'plant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('production_system.dashboard', compact('activeLines','pendingMaintenance','totalLines', 'productionLines'));
    }

    /**
     * عرض قائمة خطوط الإنتاج
     */
    public function index()
    {
        $productionLines = ProductionLine::with(['department', 'plant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('production_system.production_lines.create', compact('productionLines'));
    }

    /**
     * عرض نموذج إنشاء خط إنتاج جديد
     */
    public function create()
    {
        $departments = Department::all();
        $plants = Plant::all();

        return view('production_system.production_lines.create', compact('departments', 'plants'));
    }

   
      public function store(Request $request)
    {
        try {
            // التحقق من صحة البيانات
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:production_lines',
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'department_id' => 'required|exists:departments,id',
                'plant_id' => 'required|exists:plants,id',
                'automation_level' => 'required|in:manual,semi-auto,full-auto',
                'design_capacity' => 'required|numeric|min:0',
                'current_capacity' => 'required|numeric|min:0',
                'status' => 'required|in:active,inactive,maintenance,retired',
                'commissioning_date' => 'required|date',
                'last_calibration_date' => 'nullable|date',
                'hourly_operating_cost' => 'required|numeric|min:0',
                'energy_consumption' => 'nullable|numeric|min:0',
                'specifications' => 'nullable|json',
                'safety_requirements' => 'nullable|json',
            ]);

            // إضافة المستخدم الذي أنشأ السجل
            $validated['created_by'] = Auth::id();

            // إنشاء سجل جديد
            ProductionLine::create($validated);

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('production_lines.create')
                ->with('success', 'تم إنشاء خط الإنتاج بنجاح');

        } catch (\Exception $e) {
            // في حالة حدوث خطأ، إعادة التوجيه مع رسالة خطأ
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء محاولة حفظ البيانات: ' . $e->getMessage());
}}

  
    public function show(ProductionLine $productionLine)
    {
        $productionLine->load(['department','stages', 'plant', 'creator', 'updater']);

        return view('production_system.production_lines.show', compact('productionLine'));
    }

    /**
     * عرض نموذج تعديل خط إنتاج
     */
    public function edit(ProductionLine $productionLine,$id)
    {
       $departments = Department::all();
        $plants = Plant::all();

        return view('production_system.production_lines.create', [
            'productionLine' => $productionLine,
            'departments' => $departments,
            'plants' => $plants
        ]);
    }

    /**
     * تحديث بيانات خط الإنتاج
     */
    public function update(Request $request, ProductionLine $productionLine)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:production_lines,code,' . $productionLine->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'plant_id' => 'required|exists:plants,id',
            'automation_level' => 'required|in:manual,semi-auto,full-auto',
            'design_capacity' => 'required|numeric|min:0',
            'current_capacity' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,maintenance,retired',
            'commissioning_date' => 'required|date',
            'last_calibration_date' => 'nullable|date',
            'hourly_operating_cost' => 'required|numeric|min:0',
            'energy_consumption' => 'required|numeric|min:0',
            'specifications' => 'nullable|json',
            'safety_requirements' => 'nullable|json',
        ]);

        $validated['updated_by'] = Auth::id();
        $validated['current_efficiency'] = $this->calculateEfficiency($validated['current_capacity'], $validated['design_capacity']);

        $productionLine->update($validated);

        return redirect()->route('production_system.production_lines.show', $productionLine->id)
            ->with('success', 'تم تحديث بيانات خط الإنتاج بنجاح');
    }

    /**
     * حذف خط إنتاج
     */
    public function destroy(ProductionLine $productionLine)
    {
        $productionLine->delete();

        return redirect()->route('production_system.production_lines.index')
            ->with('success', 'تم حذف خط الإنتاج بنجاح');
    }

    /**
     * حساب كفاءة الخط الإنتاجي
     */
    private function calculateEfficiency($currentCapacity, $designCapacity)
    {
        if ($designCapacity == 0) {
            return 0;
        }

        return round(($currentCapacity / $designCapacity) * 100, 2);
    }

    /**
     * API للحصول على خطوط الإنتاج حسب المصنع
     */
    public function getByPlant(Plant $plant)
    {
        $lines = $plant->productionLines()
            ->where('status', 'active')
            ->get(['id', 'name', 'current_capacity']);

        return response()->json($lines);
    }

    /**
     * API للحصول على خطوط الإنتاج حسب القسم
     */
    public function getByDepartment(Department $department)
    {
        $lines = $department->productionLines()
            ->where('status', 'active')
            ->get(['id', 'name', 'current_capacity']);

        return response()->json($lines);
    }
}