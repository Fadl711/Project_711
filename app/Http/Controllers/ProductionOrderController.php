<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\ProductionLine;
use App\Models\RawMaterialTransaction;
use App\Models\Sale;
use App\Models\SaleInvoice;
use Illuminate\Http\Request;

class ProductionOrderController extends Controller
{
    // عرض قائمة أوامر الإنتاج
    public function index(Request $request)
    {
        
        $productionOrders = ProductionOrder::with(['product', 'line'])
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('production_system.production_orders.index', compact('productionOrders'));
    }

    // عرض نموذج إنشاء أمر إنتاج
    public function create()
    {
        $products = Product::all();
        $lines = ProductionLine::all();
        $salesOrders = SaleInvoice::where('transaction_type',6 )
        ->get();
       

        return view('production_system.production_orders.create', compact('products', 'lines', 'salesOrders'));
    }

    // حفظ أمر الإنتاج الجديد
    public function store(Request $request)
    {
          try {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'line_id' => 'required|exists:production_lines,id',
            'planned_quantity' => 'required|numeric|min:0.001',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'estimated_cost' => 'required|numeric|min:0',
            'sales_order_id' => 'nullable|exists:sales_invoices,sales_invoice_id',
            'notes' => 'nullable|string'
        ]);

        $validated['order_number'] = 'PO-' . strtoupper(uniqid());
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        ProductionOrder::create($validated);

        return redirect()->route('production_orders.index')
            ->with('success', 'تم إنشاء أمر الإنتاج بنجاح');
                } catch (\Exception $e) {
            // في حالة حدوث خطأ، إعادة التوجيه مع رسالة خطأ
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء محاولة حفظ البيانات: ' . $e->getMessage());
}
    }

 
    public function show(ProductionOrder $productionOrder)
    {
         $rawMaterialTransaction = RawMaterialTransaction::with([
            'productionOrder', 
            'material', 
            'warehouse',
            'issuedByUser'
        ])
        ->where('production_order_id',$productionOrder->id )->get();
      $manufacturingCosts=  RawMaterialTransaction::where('production_order_id',$productionOrder->id)->get();
        return view('production_system.production_orders.show', [
            'order' => $productionOrder->load([
                'product',
                'line',
                'manufacturingCosts',
                'creator',
                'approver'
            ]),'rawMaterialTransaction'=>$rawMaterialTransaction,

        ]);

    }

    // عرض نموذج تعديل أمر إنتاج
   public function edit(ProductionOrder $productionOrder)
    {
        if ($productionOrder->status === 'completed') {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل أمر إنتاج مكتمل');
        }

        return view('production_system.production_orders.edit', [
            'order' => $productionOrder,
            'products' =>  Product::all(),
            'lines' => ProductionLine::active()->get(),
            'statuses' => ProductionOrder::getStatuses(),
            'priorities' => ProductionOrder::getPriorities(),
              
        ]);
    }

    // تحديث أمر الإنتاج
    public function update(Request $request, ProductionOrder $productionOrder)
    {
      if ($productionOrder->status === 'completed') {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل أمر إنتاج مكتمل');
        }
          try {

        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'line_id' => 'required|exists:production_lines,id',
            'planned_quantity' => 'required|numeric|min:0.001',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:draft,planned,paused,in_progress,completed,canceled',
            'estimated_cost' => 'required|numeric|min:0',
            'sales_order_id' => 'nullable|exists:sales_invoices,sales_invoice_id',
            'notes' => 'nullable|string',
            'produced_quantity' => 'sometimes|numeric|min:0',
            'approved_quantity' => 'sometimes|numeric|min:0',
            'actual_cost' => 'sometimes|numeric|min:0'
        ]);

        $productionOrder->update($validated);

        return redirect()->route('production-orders.show', $productionOrder)
            ->with('success', 'تم تحديث أمر الإنتاج بنجاح');
               } catch (\Exception $e) {
            // في حالة حدوث خطأ، إعادة التوجيه مع رسالة خطأ
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء محاولة حفظ البيانات: ' . $e->getMessage());
}
    }

    // حذف أمر إنتاج
    public function destroy(ProductionOrder $productionOrder)
    {
        if (!$productionOrder->canBeModified()) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف أمر الإنتاج في حالته الحالية');
        }

        $productionOrder->delete();

        return redirect()->route('production-orders.index')
            ->with('success', 'تم حذف أمر الإنتاج بنجاح');
    }

    // تغيير حالة أمر الإنتاج
    public function changeStatus(Request $request, ProductionOrder $productionOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,planned,in_progress,paused,completed,canceled',
            'cancellation_reason' => 'required_if:status,canceled|string|max:500'
        ]);

        // تسجيل التواريخ الفعلية
        if ($validated['status'] === 'in_progress' && is_null($productionOrder->actual_start)) {
            $validated['actual_start'] = now();
        }

        if ($validated['status'] === 'completed') {
            $validated['actual_end'] = now();
            $validated['approved_by'] = auth()->id();
        }

        $productionOrder->update($validated);

        return redirect()->back()
            ->with('success', 'تم تحديث حالة أمر الإنتاج بنجاح');
    }
}