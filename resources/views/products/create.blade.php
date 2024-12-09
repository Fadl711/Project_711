@extends('layout')
@section('conm')
<x-nav-products/>

<div id="successAlert" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
    <p class="font-bold">تم بنجاح!</p>
    <p>تمت إضافة المنتج بنجاح.</p>
  </div>
  <div id="successAlert1" class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
    <p class="font-bold">هناك خطاء</p>
    <p id="re"></p>
  </div>
<form action="{{route('products.store')}}" method="POST" class="border-b text-sm  ">
    @csrf
    <div class="grid grid-cols-2 gap-1 md:grid-cols-8 lg:grid-cols-8">
        <div class="flex flex-col">
          <label for="Barcode" class="labelSale">الباركود</label>
          <input type="number" name="Barcode" placeholder="0" class="inputSale"  />
        </div>

        <div class="flex flex-col">
          <label for="product_name" class="labelSale">اسم الصنف</label>
          <input type="text" name="product_name" id="product_name" placeholder="name" class="inputSale" required />
        </div>
        
        <div class="flex flex-col">
          <label for="Purchase_price" class="labelSale">سعر الشراء</label>
          <input type="number" name="Purchase_price" id="Purchase_price" placeholder="0" class="inputSale"required />
        </div>
        <div class="flex flex-col">
          <label for="Selling_price" class="labelSale"> سعر البيع</label>
          <input type="number" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" required/>
        </div>
        
        
        <div class="flex flex-col">
          <label for="Regular_discount" class="labelSale">خصم عادي</label>
          <input type="number" name="Regular_discount" id="Regular_discount" placeholder="0" class="inputSale" required/>
        </div>
        
        <div class="flex flex-col">
          <label for="Special_discount" class="labelSale">خصم خاص </label>
          <input type="number" name="Special_discount" id="Special_discount" placeholder="0" class="inputSale" required/>
        </div>
        <div class="flex flex-col">
          <label for="Quantity" class="labelSale">الكمية</label>
          <input type="number" name="Quantity" id="Quantity" placeholder="0" class="inputSale"required />
        </div>
        <div class="flex flex-col">
          <label for="note" class="labelSale"> ملاحظه  </label>
          <input type="text" name="note" id="note"  class="inputSale" required/>
        </div>
      
        <div>
          <label for="Supplier_id" class="labelSale">اسم المورد الصنف</label>
          <select name="Supplier_id" id="Supplier_id" dir="ltr" class="input-field w-full select2 inputSale" >
            <option selected value=""></option>
              @isset($subAccountSupplierid)
              @foreach ($subAccountSupplierid as $Supplier)
              <option value="{{$Supplier->sub_account_id}}">{{$Supplier->sub_name}}</option>
               @endforeach
               @endisset
          </select>
      </div>
        <div>
          <label for="account_debitid" class="labelSale"> توريد الكمية الى المخازن </label>
          {{-- warehouse_to_id --}}
          <select name="account_debitid" id="account_debitid"  dir="ltr" class="input-field select2 inputSale" required>
            <option selected value=""></option>
              @isset($Warehouse)
              @foreach ($Warehouse as $cur)
              <option @isset($Default_warehouse)
              @selected($cur->sub_account_id==$Default_warehouse)
              @endisset
              value="{{$cur->sub_account_id}}">{{$cur->sub_name}}</option>
               @endforeach
               @endisset
          </select>
      </div>
      <div class="">
        <label for="Purchase_invoice_id" class="labelSale">رقم الفاتورة</label>
        <input type="number" name="Purchase_invoice_id" id="Purchase_invoice_id" placeholder="0" class="inputSale"required />
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
    $('.select2').select2();
  });

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
                location.reload ();
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

