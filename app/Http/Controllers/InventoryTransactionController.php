<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Enum\InventoryTransactionNnum;
// use App\InventoryTransactionNnum;
// use App\InventoryTransaction as AppInventoryTransaction;
use App\Models\Currency;
use App\Models\InventoryTransaction;
use App\Models\InventoryItem;
use App\Models\MainAccount;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductionOrder;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryTransactionController extends Controller
{
   

    public function index(Request $request)
    {
        $query = InventoryTransaction::with(['item', 'warehouse', 'productionOrder'])
            ->orderBy('transaction_date', 'desc');

        // الفلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        // الفلترة حسب المخزن
        if ($request->filled('warehouse')) {
            $query->where('warehouse_id', $request->warehouse);
        }

        // الفلترة حسب التاريخ
        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->paginate(25);

        $warehouses = SubAccount::all();
        $types = InventoryTransaction::TRANSACTION_TYPES;

        return view('production_system.inventory-transactions.index', compact('transactions', 'warehouses', 'types'));
    }

    public function create()
    {
            $Currency_name=Currency::all();
        $PaymentType = PaymentType::cases();
         $transaction_types = InventoryTransactionNnum::cases();


    
            $allSubAccounts = SubAccount::all();
            $main_accounts = MainAccount::all();

                

        $items = Product::all();
        $warehouses = SubAccount::where('account_class',3)->get();
        $productionOrders = ProductionOrder::all();
        $types = InventoryTransaction::TRANSACTION_TYPES;


        return view('production_system.inventory-transactions.create', ['AllSubAccounts'=>$allSubAccounts,
                'Currency_name'=>$Currency_name,
                'main_accounts'=>$main_accounts,
                'PaymentType'=>$PaymentType,
                'transaction_types'=>$transaction_types,
        ], compact('items', 'warehouses', 'productionOrders', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:products,product_id',
            'quantity' => 'required|numeric|min:0.001',
            'transaction_type' => 'required|in:receipt,issue,return,product_in,waste_out',
            'warehouse_id' => 'required|',
            'location_id' => 'nullable|exists:sub_accounts,sub_account_id',
            'unit_cost' => 'required|numeric|min:0',
            'production_order_id' => 'nullable|exists:production_orders,id',
            'notes' => 'nullable|string|max:500'
        ]);

        // DB::beginTransaction();
        try {
            $transaction = new InventoryTransaction();
            $transaction->fill($request->all());
            $transaction->total_cost = $request->quantity * $request->unit_cost;
            $transaction->created_by = Auth::id();
            $transaction->save();

            // DB::commit();
            // return redirect()->route('inventory-transactions.index')
            //     ->with('success', 'تم تسجيل حركة المخزون بنجاح');
   return response()->json(['success' => 'تم تسجيل حركة المخزون بنجاح', 'entrie_id' => $transaction->id]);

        } catch (\Exception $e) {
            // DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaction = InventoryTransaction::with(['item', 'warehouse', 'location', 'creator', 'productionOrder'])
            ->findOrFail($id);

        return view('production_system.inventory-transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $items = Product::all();
        $warehouses = SubAccount::all();
        $productionOrders = ProductionOrder::open()->get();
        $types = InventoryTransaction::TRANSACTION_TYPES;

        return view('inventory-transactions.edit', compact('transaction', 'items', 'warehouses', 'productionOrders', 'types'));
    }

    public function update(Request $request, $id)
    {
        $transaction = InventoryTransaction::findOrFail($id);

        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.001',
            'transaction_type' => 'required|in:receipt,issue,return,product_in,waste_out',
            'warehouse_id' => 'required|exists:warehouses,id',
            'location_id' => 'nullable|exists:warehouse_locations,id',
            'unit_cost' => 'required|numeric|min:0',
            'production_order_id' => 'nullable|exists:production_orders,id',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $transaction->fill($request->all());
            $transaction->total_cost = $request->quantity * $request->unit_cost;
            $transaction->save();

            DB::commit();
            return redirect()->route('inventory-transactions.index')
                ->with('success', 'تم تحديث حركة المخزون بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);

        DB::beginTransaction();
        try {
            $transaction->delete();
            DB::commit();
            return redirect()->route('inventory-transactions.index')
                ->with('success', 'تم حذف حركة المخزون بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حذف البيانات: ' . $e->getMessage());
        }
    }
}