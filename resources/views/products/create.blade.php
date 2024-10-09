@extends('layout')
@section('conm')
<x-nav-products/>

<div id="errorMessage" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
    <p class="font-bold">تم بنجاح!</p>
    <p>تمت إضافة المنتج بنجاح.</p>
  </div>
  
<div id="successMessage" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
  <p class="font-bold">تم بنجاح!</p>
  <p>تمت إضافة المنتج بنجاح.</p>
</div>
<form action="{{route('products.store')}}" method="POST" class="border-b text-sm  ">
    @csrf
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
        <div class="flex flex-col">
          <label for="Barcode" class="btn">الباركود</label>
          <input type="number" name="Barcode" placeholder="0" class="inputSale"  />
        </div>

        <div class="flex flex-col">
          <label for="product_name" class="btn">اسم الصنف</label>
          <input type="text" name="product_name" id="product_name" placeholder="name" class="inputSale" required />
        </div>

        <div class="flex flex-col bg-gray-200 ">
          <label for="Categorie_id" class="btn">وحدة الصنف</label>
          <select style="background-image: none ;" name="Categorie_id" id="Categorie_id" class="inputSale appearance-auto" required>

            <option selected value=""></option>
            @auth
            @foreach ($cate as $cat)


            <option value="{{$cat->categorie_id}}">{{$cat->Categorie_name}}</option>

            @endforeach
            @endauth

          </select>
        </div>

        <div class="flex flex-col">
          <label for="Selling_price" class="btn">سعر المنتج </label>
          <input type="number" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" required/>
        </div>

        <div class="flex flex-col">
          <label for="Quantity" class="btn">الكمية</label>
          <input type="number" name="Quantity" id="Quantity" placeholder="0" class="inputSale"required />
        </div>

        <div class="flex flex-col">
          <label for="Purchase_price" class="btn">سعر الشراء</label>
          <input type="number" name="Purchase_price" id="Purchase_price" placeholder="0" class="inputSale"required />
        </div>
        <div class="flex flex-col">
          <label for="Selling_price" class="btn"> سعر البيع</label>
          <input type="number" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" required/>
        </div>

        <div class="flex flex-col">
          <label for="Regular_discount" class="btn">خصم عادي</label>
          <input type="number" name="Regular_discount" id="Regular_discount" placeholder="0" class="inputSale" required/>
        </div>

        <div class="flex flex-col">
          <label for="Special_discount" class="btn">خصم خاص </label>
          <input type="number" name="Special_discount" id="Special_discount" placeholder="0" class="inputSale" required/>
        </div>
        <div class="flex flex-col">
          <label for="note" class="btn"> ملاحظه  </label>
          <input type="text" name="note" id="note"  class="inputSale" required/>
        </div>

        <div class="flex flex-col">
          <label for="Total" class="btn">أجمالي الشراء </label>
          <input type="number" name="Total" id="Total" placeholder="0" class="inputSale" required/>
        </div>
        <div class="flex flex-col">
          <label for="Cost" class="btn"> التكلفة </label>
          <input type="number" name="Cost" id="Cost" placeholder="0" class="inputSale" required/>
        </div>
        <div class="flex flex-col">
          <label for="Profit" class="btn"> الربح </label>
          <input type="number" name="Profit" id="Profit" placeholder="0" class="inputSale" required/>
        </div>
        <div class="flex flex-col bg-gray-200">
          <label for="warehouse_id" class="btn">اسم المخزن</label>
          <select style="background-image: none ;" name="warehouse_id" id="warehouse_id" class="inputSale appearance-auto" required>
             <option selected value=""></option>
            @forelse ($Warehouses as $Warehouse)
              <option value="{{$Warehouse->warehouse_id}}">{{$Warehouse->Store_name}}</option>
              @empty
                  <div>لايوجد بيانات حالية</div>
              @endforelse

          </select>
      </div>


        <div class="flex flex-col bg-gray-200">
            <label for="cr" class="btn">العملة</label>
            <select style="background-image: none ;" name="currency_id" id="currency_id" class="inputSale appearance-auto" required>
                @forelse ($curr as $cur)
                <option value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
                @empty
                    <div>لايوجد بيانات حالية</div>
                @endforelse

            </select>
        </div>
    </div>

    @auth
    <div class="flex flex-col">
      <input type="hidden" name="User_id" value="{{Auth::user()->id}}"/>
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
                  اضافة صنف
                </button>
              </div>

</form>


<script>
   $(document).ready(function() {
    $('#newProduc button').click(function(e) {
        e.preventDefault();
        var formData = new FormData($('form')[0]);
        $.ajax({
            type: 'POST',
            url: "{{ route('products.store') }}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                // إعادة تعيين الحقول
                $('input').val('');
                $('select').val('');

                // إظهار التنبيه
                $('#successAlert').removeClass('hidden');

                // إخفاء التنبيه بعد 3 ثوانٍ
                setTimeout(function() {
                    $('#successAlert').addClass('hidden');
                }, 3000);

                console.log('تمت الإضافة بنجاح');
            },
            error: function(xhr, status, error) {
                console.log('حدث خطأ');
            }
        });
    });
});
</script>
@endsection

