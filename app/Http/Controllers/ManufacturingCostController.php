<?php

namespace App\Http\Controllers;

use App\Models\DailyEntrie;
use App\Models\ManufacturingCost;
use App\Models\ProductionOrder;
use App\Models\GeneralLedgerAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufacturingCostController extends Controller
{
    // عرض جميع التكاليف
    public function index()
    {
        $costs = ManufacturingCost::with(['productionOrder', 'glAccount', 'creator'])
            ->orderBy('cost_date', 'desc')
            ->paginate(20);

                $costTypes = ManufacturingCost::getCostTypes();


        return view('production_system.manufacturing_costs.index', compact('costs','costTypes'));
    }

    // عرض نموذج إنشاء تكلفة جديدة
    public function create()
    {
        $productionOrders = ProductionOrder::all();
        $glAccounts = SubAccount::where('type_account', 4)->get();
        $costTypes = ManufacturingCost::getCostTypes();

        return view('production_system.manufacturing_costs.create', compact('productionOrders', 'glAccounts', 'costTypes'));
    }

    // حفظ التكلفة الجديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_order_id' => 'required',
            'cost_type' => 'required|in:material,labor,overhead,energy,depreciation,other',
            'amount' => 'required|numeric|min:0',
            'gl_account_id' => 'required',
            'cost_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'details' => 'nullable|'
        ]);

        DB::transaction(function () use ($validated) {
            $cost = new ManufacturingCost($validated);
            $cost->created_by = auth()->id();
            $cost->save();

            // هنا يمكنك إضافة أي عمليات محاسبية إضافية مثل تحديث دفتر الأستاذ
        });

        return redirect()->route('manufacturing-costs.index')
            ->with('success', 'تم تسجيل التكلفة بنجاح');
    }

    // عرض تفاصيل تكلفة معينة
    public function show(ManufacturingCost $manufacturingCost)
    {
        return view('production_system.manufacturing_costs.show', compact('manufacturingCost'));
    }

    // عرض نموذج تعديل تكلفة
    public function edit(ManufacturingCost $manufacturingCost)
    {
        $productionOrders = ProductionOrder::active()->get();
        $glAccounts = DailyEntrie::where('account_type', 'expense')->get();
        $costTypes = ManufacturingCost::getCostTypes();

        return view('production_system.manufacturing_costs.edit', compact('manufacturingCost', 'productionOrders', 'glAccounts', 'costTypes'));
    }

    // تحديث التكلفة
    public function update(Request $request, ManufacturingCost $manufacturingCost)
    {
        $validated = $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'cost_type' => 'required|in:material,labor,overhead,energy,depreciation,other',
            'amount' => 'required|numeric|min:0',
            'gl_account_id' => 'required|exists:general_ledger_accounts,id',
            'cost_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'details' => 'nullable|json'
        ]);

        DB::transaction(function () use ($manufacturingCost, $validated) {
            $manufacturingCost->update($validated);

            // هنا يمكنك إضافة أي عمليات محاسبية إضافية مثل تحديث دفتر الأستاذ
        });

        return redirect()->route('manufacturing-costs.index')
            ->with('success', 'تم تحديث التكلفة بنجاح');
    }

    // حذف تكلفة
    public function destroy(ManufacturingCost $manufacturingCost)
    {
        DB::transaction(function () use ($manufacturingCost) {
            $manufacturingCost->delete();

            // هنا يمكنك إضافة أي عمليات محاسبية إضافية مثل تحديث دفتر الأستاذ
        });

        return redirect()->route('manufacturing-costs.index')
            ->with('success', 'تم حذف التكلفة بنجاح');
    }

    // تقارير تحليل التكاليف
    public function costAnalysis()
    {
        $analysis = ManufacturingCost::select([
                'cost_type',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as count')
            ])
            ->groupBy('cost_type')
            ->get();
  $costTypes = [
        'material' => 'تكلفة المواد',
        'labor' => 'تكلفة العمالة',
        'overhead' => 'تكاليف صناعية غير مباشرة',
        'energy' => 'تكلفة الطاقة',
        'depreciation' => 'استهلاك المعدات',
        'other' => 'تكاليف أخرى'
    ];
        return view('production_system.manufacturing_costs.analysis', compact('analysis','costTypes'));
    }

    // تحليل تكاليف أوامر الإنتاج
    public function productionOrderCosts($orderId)
    {
        $costs = ManufacturingCost::where('production_order_id', $orderId)
            ->with('glAccount')
            ->orderBy('cost_date')
            ->get();

        $total = $costs->sum('amount');

        return view('manufacturing_costs.order_costs', compact('costs', 'total'));
    }
}