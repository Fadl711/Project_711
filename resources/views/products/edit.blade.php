@extends('layout')
@section('conm')

<x-nav-products/>

<form action="{{route('products.store')}}" method="POST" class="border-b text-sm  ">
    @csrf
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div class="flex flex-col">
          <label for="barcod" class="btn">الباركود</label>
          <input type="number" name="barcod" value="{{$prod->barcod}}" class="inputSale" />
        </div>

        <div class="flex flex-col">
          <label for="name" class="btn">اسم الصنف</label>
          <input type="text" name="name" id="_name" value="{{$prod->product_name}}" class="inputSale" />
        </div>

        <div class="flex flex-col">
          <label for="name" class="btn">وحدة الصنف</label>
          <select name="Catog" id="" class="inputSale">
            @foreach ($cate as $cat)
            <option value="{{$cat->categorie_id}}">{{$cat->Categorie_name}}</option>
            @endforeach
          </select>
        </div>

        <div class="flex flex-col">
          <label for="pricep" class="btn">سعر المنتج </label>
          <input type="number" name="pricep" id="pricep" value="{{$prod->Product_price}}" class="inputSale" />
        </div>

        <div class="flex flex-col">
          <label for="pricep" class="btn">الكمية</label>
          <input type="number" name="quni" id="pricep" value="{{$prod->quantity}}" class="inputSale" />
        </div>

        <div class="flex flex-col">
          <label for="pricesa" class="btn">خصم عادي</label>
          <input type="number" name="pricesa" id="pricesa" value="{{$prod->Regular_discount}}" class="inputSale" />
        </div>

        <div class="flex flex-col">
          <label for="pricep" class="btn">خصم خاص </label>
          <input type="number" name="pricesp" id="pricep" value="{{$prod->Special_discount}}" class="inputSale" />
        </div>

        <div class="flex flex-col">
          <label for="pricesa" class="btn">أجمالي الشراء </label>
          <input type="number" name="allpri" id="pricesa" value="{{$prod->Total_price}}" class="inputSale" />
        </div>


        <div class="flex flex-col">
            <label for="pricesa" class="btn">العملة</label>
            <select name="cr" id="" class="inputSale">
                <option value="1">يمني</option>
                <option value="2">دولار</option>
                <option value="3">سعودي</option>
            </select>
        </div>
    </div>

    @auth
    <div class="flex flex-col">
      <input type="hidden" name="user_id" value="{{Auth::user()->id}}"/>
    </div>
    @endauth
              <div id="newProduc" class="py-2">
                <button class="flex bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                  <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                      <g id="Edit / Add_Plus_Circle">
                        <path id="Vector" d="M8 12H12M12 12H16M12 12V16M12 12V8M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      </g>
                    </g>
                  </svg>
                  تعدبل الصنف
                </button>
              </div>
</form>

@endsection
