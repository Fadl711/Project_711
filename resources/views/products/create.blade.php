@extends('products.layout')
@section('prod')    
<form action="{{route('products.store')}}" method="POST" class="border-b flex justify-between text-sm  ">
    @csrf
    <div class=" flex items-center">
      <div class="max-w-full bg-white ">
        <div class="md:flex md:justify-around text-right">
          <div class="flex max-md:block p-1">
            <div class="min-w-[30%] border-x border-y border-orange-950 rounded-xl">
              <div class="flex">
                <div class="mb-1 p-1">
                  <label for="barcod" class="btn">الباركود</label>
                  <input type="number" name="barcod" placeholder="0" class="inputSale" />
                </div>
                <div class="mb-1 p-1">
                  <label for="name" class="btn">اسم الصنف</label>
                  <input type="text" name="name" id="_name" placeholder="name" class="inputSale" />
                </div>
                <div class="mb-1 p-1">
                  <label for="name" class="btn">وحدة الصنف</label>
                  <select name="Catog" id="" class="inputSale">
                    @foreach ($cate as $cat)
                    <option value="{{$cat->categorie_id}}">{{$cat->Categorie_name}}</option>

                    @endforeach
                  </select>
                </div>
              </div>
              <div class="flex">
                <div class="px-1">
                  <label for="pricep" class="btn">سعر المنتج </label>
                  <input type="number" name="pricep" id="pricep" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                  <label for="pricep" class="btn">الكمية</label>
                  <input type="number" name="quni" id="pricep" placeholder="0" class="inputSale" />
                </div>
              </div>
              <div class="flex">
                <div class="px-1">
                  <label for="pricesa" class="btn">خصم عادي</label>
                  <input type="number" name="pricesa" id="pricesa" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                  <label for="pricep" class="btn">خصم خاص </label>
                  <input type="number" name="pricesp" id="pricep" placeholder="0" class="inputSale" />
                </div>
                </div>
                <div class="flex">
                <div class="px-1">
                  <label for="pricesa" class="btn">أجمالي الشراء </label>
                  <input type="number" name="allpri" id="pricesa" placeholder="0" class="inputSale" />
                  @auth

                  <input type="hidden" name="user_id" value="{{Auth::user()->id}}"/>
                  @endauth
                </div>
                <div class="px-1">
                  <label for="pricesa" class="btn">العملة</label>
                  <select name="cr" id="" class="inputSale">
                    <option value="1">يمني</option>
                    <option value="2">دولار</option>
                    <option value="3">سعودي</option>
                  </select>
                </div>
              </div>
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
                  اضافة صنف
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</form>
@error('record')

@enderror



@endsection

