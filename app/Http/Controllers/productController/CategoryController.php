<?php

namespace App\Http\Controllers\productController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(){
        $product=Product::all();

        return view('products.category.categorie',['products'=>$product]);
    }
    public function store(Request $request){
        if (Category::where('Categorie_name', $request->input('cate'))
        ->where('Categorie_name', $request->input('product_id'))
    ->exists()) {
            // Record already exists
            return response()->json(['error' => 'الاسم موجود مسبقاً'], 422);
        }
        Category::updateOrCreate(
            [
                'product_id' => $request->product_id,
                'categorie_id' => $request->Categorie_id,
            ],
            [
            'Categorie_name' => $request->cate,
            'Purchase_price' => $request->Purchase_price,
            'Selling_price' => $request->Selling_price,
            'User_id' => auth()->id(),
           
        ]);
        if($request->Categorie_id)
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
            'message' => ' تم تعديل الصنف بنجاح  .',
        ]);
        // return back();
    }
    public function edit($id){
        $prod=Category::where('categorie_id',$id)->first();


        return view('products.category.edit',['prod'=>$prod]);
    }
    public function update(Request $request,$id){
        if (Category::where('Categorie_name', $request->input('cate'))->exists()) {
            // Record already exists
            return response()->json(['error' => 'الاسم موجود مسبقاً'], 422);
        }

        Category::where('categorie_id',$id)
        ->update([
            'Categorie_name'=>$request->cate,
        ]);


        return redirect()->route('Category.create');
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
