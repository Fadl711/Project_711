<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductBom;
use App\Models\Product;
use App\Models\InventoryItem;
use App\Models\SubAccount;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductBomController extends Controller
{
   

    public function index()
    {
        $boms = ProductBom::with(['product', 'material', 'unit', 'warehouse'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);        
        return view('production_system.product-boms.index', compact('boms'));
    }
    public function getProductBoms(Request $request ,$id)
    {
        
        $boms = ProductBom::with(['product', 'material', 'unit', 'warehouse'])
        ->where('product_id', $id)->get();
      
         if ($boms) {
        return response()->json(['boms'=>$boms]);
    }

    return response()->json([
        'error' => 'لم يتم العثور على المنتج.'
    ], 404);


            }

    public function create()
    {
        $products = Product::all();
        // $materials = InventoryItem::where('is_active', true)->where('is_material', true)->get();
        $units = Category::all();

        $warehouses = SubAccount::where('account_class', 3)->get();
        
        return view('production_system.product-boms.create',['materials'=>$products], compact('products', 'units', 'warehouses')
    );
    }

 public function store(Request $request)
{
    // dd($request);
    // التحقق من صحة البيانات
    // $validatedData = $request->validate([
    //     'product_id' => 'required|exists:products,product_id',
    //     'material_id' => 'required|exists:products,product_id|different:product_id',
    //     'quantity' => 'required|numeric|min:0.001|max:999999.999',
    //     'unit_id' => 'required|exists:categories,categorie_id',
    //     'waste_factor' => 'nullable|numeric|min:0|max:100',
    //     'default_warehouse_id' => 'required|exists:sub_accounts,sub_account_id',
    //     'standard_cost' => 'required|numeric|min:0|max:9999999.99999',
    //     'is_active' => 'boolean',
    // ], [
    //     'product_id.required' => 'حقل المنتج النهائي مطلوب',
    //     'material_id.required' => 'حقل المادة الخام مطلوب',
    //     'material_id.different' => 'يجب أن تكون المادة الخام مختلفة عن المنتج النهائي',
    //     'quantity.min' => 'يجب أن تكون الكمية أكبر من الصفر',
    //     'unit_id.required' => 'حقل وحدة القياس مطلوب',
    //     'waste_factor.max' => 'يجب أن تكون نسبة الهدر أقل من أو تساوي 100%',
    //     'default_warehouse_id.required' => 'حقل المخزن الافتراضي مطلوب',
    //     'standard_cost.required' => 'حقل التكلفة المعيارية مطلوب',
    // ]);

    // التحقق من عدم تكرار BOM لنفس المنتج والمادة
    // if (ProductBom::where('product_id', $validatedData['product_id'])
    //              ->where('material_id', $validatedData['material_id'])
    //              ->exists()) {
    //     return redirect()->back()
    //                    ->withInput()
    //                    ->withErrors(['material_id' => 'هذه المادة موجودة بالفعل في BOM هذا المنتج']);
    // }

    // حساب القيمة الإجمالية مع الهدر
    // $validatedData['total_with_waste'] = $validatedData['quantity'] * 
    //                                     (1 + ($validatedData['waste_factor'] / 100));

    // DB::beginTransaction();
    // try {
    // gettype($request->is_active);
    $is_active=$request->is_active==='on'?1:0;
        // إنشاء سجل BOM جديد
        $productBom = ProductBom::create([
            'product_id'=>$request->product_id,
            'material_id'=>$request->material_id,
            'quantity'=>$request->quantity,
            'unit_id'=>$request->unit_id,
            'waste_factor'=>$request->waste_factor??0,
            'default_warehouse_id'=>$request->default_warehouse_id,
            'standard_cost'=>$request->standard_cost,
            'is_active'=>$is_active,
        ]);
        
        // تسجيل العملية في سجل النشاطات إذا كان النظام يدعم ذلك
       

        // DB::commit();

         $boms = ProductBom::with(['product', 'material', 'unit', 'warehouse'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);
                        //  dd(  $boms );
        
        return view('production_system.product-boms.index', compact('boms'))  ->with('success', 'تم إضافة BOM بنجاح');
        
        // return redirect()->route('product-boms.index')
        //                ->with('success', 'تم إضافة BOM بنجاح');

    // } catch (\Exception $e) {
    //     // DB::rollBack();
        
    //     Log::error('Failed to create BOM: ' . $e->getMessage(), [
    //         'exception' => $e,
    //         'request_data' => $request->all(),
    //         'user_id' => auth()->id()
    //     ]);
        
    //     return redirect()->back()
    //                    ->withInput()
    //                    ->with('error', 'حدث خطأ أثناء حفظ البيانات. الرجاء المحاولة مرة أخرى.');
    // }
}

    public function show($id)
    {
        $bom = ProductBom::with(['product', 'material', 'unit', 'warehouse'])
                        ->findOrFail($id);
        
        return view('production_system.product-boms.show', compact('bom'));
    }

    public function edit($id)
    {
        $bom = ProductBom::findOrFail($id);
      $units = Category::where('product_id' ,$bom->product_id)->get();

        $warehouses = SubAccount::where('account_class', 3)->get();
        return view('production_system.product-boms.edit', compact('bom', 'units', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $bom = ProductBom::findOrFail($id);
        
        $request->validate([
            'quantity' => 'required|numeric|min:0.001',
            'unit_id' => 'required|exists:units,id',
            'waste_factor' => 'nullable|numeric|min:0|max:100',
            'default_warehouse_id' => 'required|exists:warehouses,id',
            'standard_cost' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $bom->update($request->all());
            
            return redirect()->route('product-boms.index')
                           ->with('success', 'تم تحديث BOM بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $bom = ProductBom::findOrFail($id);
        
        try {
            $bom->delete();
            
            return redirect()->route('product-boms.index')
                           ->with('success', 'تم حذف BOM بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'حدث خطأ أثناء حذف البيانات: ' . $e->getMessage());
        }
    }
}