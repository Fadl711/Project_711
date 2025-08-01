<?php

namespace App\Http\Controllers\productController;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create($id){
        $product=Product::all();
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        // $productName=Product::where('product_id',$id)->pluok('product_name');
        $cate=Category::where('product_id',$id)->get();

        $uniquePurchase = Purchase::where('product_id',$id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
         ->whereIn('transaction_type', [6,7])->get();

       
        //  dd($uniquePurchase);

        return view('products.category.categorie',['products'=>$product,'cates'=>$cate
        ,'uniquePurchase'=> $uniquePurchase
    ]);
    
}
    public function store(Request $request){
        // dd(55);
        if (Category::where('Categorie_name', $request->input('cate'))
        ->where('Categorie_name', $request->input('product_id'))
    ->exists()) 
    {
            return response()->json(['error' => 'الاسم موجود مسبقاً'], 422);
        }
        // dd($request->Categorieid);
        Category::updateOrCreate(
            [
                'categorie_id' => $request->Categorieid??$request->Categorie_id,
                'product_id' => $request->product_id,
            ],
            [
            'Categorie_name' => $request->cate,
            'Purchase_price' => $request->Purchase_price,
            'Selling_price' => $request->Selling_price,
            'Quantityprice' => $request->Quantityprice,
            'User_id' => auth()->id(),
        ]);
        if($request->Categorieid|| $request->Categorie_id)
        {
                return response()->json([
                    'success' => true,
                    'message' => ' تم تعديل الوحدة بنجاح  .',
                ]);
            }
/*         Category::createOrFirst([
            'Categorie_name' => ,
            'created_at' => now(),
            'updated_at' => now(),
        ]);*/

        return response()->json([
            'success' => true,
            'message' => ' تم حفظ الوحدة بنجاح  .',
        ]);
        // return back();
    }
    public function edit($id)
    {

        $product=Product::all();
        $prod=Category::where('categorie_id',$id)->first();
        $cate=Category::where('product_id',$prod->product_id)->get();
      
        // $productName=Product::where('product_id',$prod->product_id)->plouk('product_name');
        return view('products.category.categorie',['category'=>$prod,'products'=>$product,'cates'=>$cate 
    ]);
    }
    public function update(Request $request,$id){
 dd(55);
  if (Category::where('Categorie_name', $request->cate)
    ->where('product_id',$prod->product_id)
        ->where('categorie_id','!=', $id)
    ->first())
    {
            return response()->json(['error' => 'الاسم موجود مسبقاً'], 422);
        }
        
        $product=Product::all();
        $prod=Category::where('categorie_id',$id)->first();
        $cate=Category::where('product_id',$prod->product_id)->get();
        // $productName=Product::where('product_id',$prod->product_id)->plouk('product_name');
$product_id=$prod->product_id;

        Category::where('categorie_id',$id)
        ->update([
            'Categorie_name'=>$request->cate,
            'Purchase_price' => $request->Purchase_price,
            'Selling_price' => $request->Selling_price,
            'Quantityprice' => $request->Quantityprice,
        ]);
        return response()->json([

            'category'=>$prod,
            'product_id'=>$product_id,
            'products'=>$product,'cates'=>$cate
        ]);
        return redirect('products.category.categorie',['category'=>$prod,'products'=>$product,'cates'=>$cate]);

        return back();


        return redirect()->route('products.category.categorie');
    }
    public function destroy($id){
        Category::where('categorie_id',$id)->delete();
        return back();
    }
   public function getUnitPrice(Request $request)
{
    $categoryId = $request->Categoriename;
    $productId = $request->mainAccountId;

    $product = Category::where('categorie_id', $categoryId)
        ->where('product_id', $productId)
        ->first();
         
    if ($product) {
        return response()->json(['product'=>$product]);
    }

    return response()->json([
        'error' => 'لم يتم العثور على المنتج.'
    ], 404);
}
  
    
    
}
