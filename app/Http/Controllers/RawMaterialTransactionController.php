<?php

namespace App\Http\Controllers;

use App\Models\RawMaterialTransaction;
use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\SubAccount;

use App\Models\User;
use Illuminate\Http\Request;

class RawMaterialTransactionController extends Controller
{
    /**
     * عرض قائمة الحركات
     */
    public function index()
    {
        $transactions = RawMaterialTransaction::with([
            'productionOrder', 
            'material', 
            'warehouse',
            'issuedByUser'
        ])
        ->latest()->paginate(10);
        
        return view('production_system.raw-material-transactions.index', compact('transactions'));
    }

    /**
     * عرض نموذج إنشاء حركة جديدة
     */
    public function create()
    {
        $productionOrders = ProductionOrder::all();
        $materials = Product::all();
    $warehouses = SubAccount::select(['sub_account_id', 'name_the_known', 'sub_name'])
       ->where('account_class', 3)->get();
        $users = User::all();
        return view('production_system.raw-material-transactions.create', ['locations'=>$warehouses], compact(
            'productionOrders',
            'materials',
            'warehouses',
            'users'
        ));
    }

    /**
     * حفظ الحركة الجديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'material_id' => 'required|',
            'planned_quantity' => 'required|numeric|min:0',
            'actual_quantity' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'warehouse_id' => 'required|',
            'location_id' => 'nullable|',
            'issued_by' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $validated['total_cost'] = $validated['actual_quantity'] * $validated['unit_cost'];
        $validated['location_id'] = $validated['warehouse_id'] ;
try{
     $RawMaterial =  RawMaterialTransaction::create($validated);
           return response()->json(['success' => 'تم تسجيل حركة المواد الخام بنجاح', 'entrie_id' => $RawMaterial->id]);

        } catch (\Exception $e) {
            // DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage());
        }
      
    }

    /**
     * عرض تفاصيل حركة محددة 
     */
    public function show($id)
    {
                    // $rawMaterialTransaction = RawMaterialTransaction::where('id', $id)->first();
 $rawMaterialTransaction = RawMaterialTransaction::with([
            'productionOrder', 
            'material', 
            'warehouse',
            'issuedByUser'
        ])->where('id', $id)->first();
        return view('production_system.raw-material-transactions.show', compact('rawMaterialTransaction'));
    }

    /**
     * عرض نموذج تعديل حركة
     */
    public function edit($id)
    {
        $productionOrders = ProductionOrder::active()->get();
        $materials = Product::rawMaterials()->get();
        $warehouses = SubAccount::active()->get();
        $locations = SubAccount::all();
        $users = User::all();
        
        return view('production_system.raw-material-transactions.show', compact(
            'rawMaterialTransaction',
            'productionOrders',
            'materials',
            'warehouses',
            'locations',
            'users'
        ));
    }

    /**
     * تحديث الحركة
     */
    public function update(Request $request, RawMaterialTransaction $rawMaterialTransaction)
    {
        $validated = $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'material_id' => 'required|exists:inventory_items,id',
            'planned_quantity' => 'required|numeric|min:0',
            'actual_quantity' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'warehouse_id' => 'required|exists:warehouses,id',
            'location_id' => 'nullable|exists:warehouse_locations,id',
            'issued_by' => 'required|exists:users,id',
            'received_by' => 'nullable|exists:users,id',
            'return_date' => 'nullable|date',
            'returned_quantity' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['total_cost'] = $validated['actual_quantity'] * $validated['unit_cost'];
        
        $rawMaterialTransaction->update($validated);
        
        return redirect()->route('raw-material-transactions.index')
            ->with('success', 'تم تحديث حركة المواد الخام بنجاح');
    }

    /**
     * حذف الحركة
     */
    public function destroy($id )
    {
        $id->delete();
        
        return redirect()->route('raw-material-transactions.index')
            ->with('success', 'تم حذف حركة المواد الخام بنجاح');
    }
}