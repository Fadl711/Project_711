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
    $Post = new Category;
    $Post->Categorie_name=$request->cate;
    $Post->product_id=$request->product_id;
    $Post->Purchase_price=$request->Purchase_price;
    $Post->Selling_price=$request->Selling_price ;
    $Post->Quantityprice=$request->Quantityprice ??1 ;
    $Post->user_id=$request->user_id;
    $Post->save();
/*         Category::createOrFirst([
            'Categorie_name' => ,
            'created_at' => now(),
            'updated_at' => now(),
        ]);*/

        return back();
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
