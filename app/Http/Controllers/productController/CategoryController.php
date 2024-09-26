<?php

namespace App\Http\Controllers\productController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(){
        return view('products.category.categorie');
    }
    public function store(Request $request){
    $Post = new Category;
    $Post->Categorie_name=$request->cate;
    $Post->user_id=1;
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
}
