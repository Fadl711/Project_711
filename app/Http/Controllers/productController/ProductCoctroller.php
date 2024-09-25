<?php

namespace App\Http\Controllers\productController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCoctroller extends Controller
{
    public function index(){

        $cate=Category::all();
        $prod=Product::all();
        return view('products.index',['cate'=>$cate,'prod'=>$prod]);
    }
    public function create(){
        return view('products.create');
    }
    public function store(Request $request){

        Product::create([
            'barcod'=>$request->barcod,
            'product_name'=>$request->name,
            'Categorie_id'=>$request->Catog,
            'Product_price'=>$request->pricep,
            'quantity'=>$request->quni,
            'Regular_discount'=>$request->pricesa,
            'Special_discount'=>$request->pricesp,
            'user_id'=>1,
            'Currency_id'=>$request->cr,
            'Total_price'=>$request->allpri,
        ]);
        return back();
    }
    public function edit($id){
       $prod= Product::where('product_id',$id)->first();


        return view('products.edit',['prod'=>$prod]);
    }
    public function update(Request $request,$id){
        Product::where('product_id',$id)->update([
            'barcod'=>$request->barcod,
            'product_name'=>$request->name,
            'Categorie_id'=>$request->Catog,
            'Product_price'=>$request->pricep,
            'quantity'=>$request->quni,
            'Regular_discount'=>$request->pricesa,
            'Special_discount'=>$request->pricesp,
            'user_id'=>1,
            'Currency_id'=>$request->cr,
            'Total_price'=>$request->allpri,
        ]);
         return redirect()->route('products.index');
    }

    public function destroy($id){
        Product::where('product_id',$id)->delete();
        return back();
    }


    //
}
