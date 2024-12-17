@extends('inventory.index')

@section('inventory')
<div id="successMessage" class=" bg-green-500 text-right"  style="display:none; "></div>
<div id="errorMessage" class=" bg-red-300 text-right"  style="display:none;"></div>
<div class="min-w-[20%] px-1  bg-white rounded-xl ">
    <div class=" flex items-center">
        {{-- <div class="mb-4">
            <a href="{{ route('inventory.export') }}" class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600">
                تحميل بيانات الجرد بتنسيق CSV
            </a>
        </div> --}}
        <div class="w-full min-w-full  py-1">
            <form id="inventory"  class="">
                @csrf           
                     <div class=" px-2 md:flex max-md:flex   max-sm:grid grid-cols-2 gap-1 border shadow-md rounded-md"  style="display: ">
                    <div>
                        <label for="StoreId" class="labelSale"> المخزن</label>
                        <select name="StoreId" id="StoreId" class="input-field select2 inputSale" required>
                            <option value="" selected>اختر المخزن</option>
                            @isset($Warehouse)
                            @foreach($Warehouse as $mainAccount)
                                <option value="{{ $mainAccount->sub_account_id }}">
                                    {{ $mainAccount->sub_name }}-{{ $mainAccount->sub_account_id }}
                                </option>
                            @endforeach
                            @endisset
                        </select>
                    </div>
                    <div id="namecus"class="text-right" >
                        <label class="labelSale" for="InventoryOfficerId">مسؤول الجرد</label>
                        <select name="InventoryOfficerId" id="InventoryOfficerId" class="input-field select2 inputSale" >
                            <option value="" selected> </option>
                            @isset($users)
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}-{{ $user->id }}
                                </option>
                            @endforeach
                            @endisset
                        </select>             
                   </div>
                   <div id="namecus"class="text-right" >
                    <label class="labelSale" for="InventoryTitle">عنوان الجرد</label>
                    <textarea name="InventoryTitle" id="InventoryTitle" class="inputSale" cols="50" rows="1"></textarea>
                </div>
                <div>
                    <label for="TotalCost" class="labelSale"> اجمالي التلكلفة الاصناف</label>
                    <input type="text" name="TotalCost" id="TotalCost" placeholder="0" class="inputSale  english-numbers" required />
                </div>
                </div>

            </form>
            </div>
        </div>
    </div>

<div class="gap-2 grid grid-cols-2  px-1">
    <div class="min-w-full border-x bg-white rounded-xl">
            <form id="ajaxForm" >
                @csrf
                <div class="grid grid-cols-1 gap-1 px-1">
                    <div>
                        <label for="product_id" class="block labelSale">بحث عن المنتج</label>
                        <select name="product_id" id="product_id" dir="ltr" class="input-field select2 inputSale" required>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-1 px-1">
                    <div>
                        <label for="Categorie_name" class="block labelSale">الوحدة </label>
                        <select name="Categorie_name" id="Categorie_name" dir="ltr" class="input-field select2 inputSale" required>
                            <option selected value=""></option>
                        </select>
                    </div>
                    <div>
                        <label for="QuantityCategorie" class="labelSale">الكمية الوحدة</label>
                        <input type="text" name="QuantityCategorie" id="QuantityCategorie" placeholder="0" class="inputSale  english-numbers" required />
                    </div>
                    <div>
                        <label for="Quantity" class="labelSale">الكمية</label>
                        <input type="text" name="Quantity" id="Quantity" placeholder="0" class="inputSale quantity-field english-numbers" required />
                    </div>
                    <div>
                        <label for="Quantityprice" class="labelSale">الجمالي الكميه</label>
                        <input type="text" name="Quantityprice" id="Quantityprice" placeholder="0" class="inputSale english-numbers" required />
                    </div>
                </div>      
                <div class="grid grid-cols-3 gap-1 px-1">
                    <div>
                        <label for="Selling_price" class="labelSale">سعر البيع</label>
                        <input type="text" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" />
                    </div>
                    <div class="">
                        <label for="Purchase_price" class="labelSale">سعر الشراء</label>
                        <input type="text" name="Purchase_price" id="Purchase_price" placeholder="0" class="inputSale"  required/>
                    </div>
                    <div>
                        <label for="QuantityPurchase" class="labelSale">الكمية المتوفرة</label>
                        <input type="text" name="QuantityPurchase"  id="QuantityPurchase" placeholder="0" class="inputSale english-numbers"   />
                    </div>
                  
                </div>
                <div class="grid grid-cols-3 gap-1 px-1">
                    <div>
                        <label for="TotalPurchase" class="labelSale total-field">الاجمالي</label>
                        <input type="text" name="TotalPurchase" id="TotalPurchase" placeholder="0" class="inputSale total-field" required />
                    </div>
                    <div>
                        <label for="sales_invoice_id" class="labelSale">رقم الفاتورة</label>
                        <input type="text" name="sales_invoice_id" id="sales_invoice_id" 
               class="inputSale" required />
                    </div>
                    <div>
                        <label for="InventoryId" class="labelSale">رقم القيد</label>
                        <input type="text" name="InventoryId" id="InventoryId" class="inputSale"  />
                    </div>
                    <div class="" >
                        <button class="flex inputSale mt-2 " type="button" id="destroy_invoice">
                                <svg class="w-6 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z"/>
                                </svg>
                                <span class="textNav mr-1"> حذف</span>
                        </button>
                        </div>
                        <div class="col-span-6 sm:col-span-3" >
                            <button onclick="openInvoiceWindow(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح الفاتورة</button>
                        </div>
                </div>
            </form>
        </div>
        <div class="container mx-auto" id="mainAccountsTable">
            <div class="w-full overflow-y-auto max-h-[80vh] bg-white">
                <table id="mainAccountsTable" class="w-full mb-4 text-sm">
                <thead >
                    <tr class="bg-blue-100">
                        <th class=" px-2 py-1  tagTd">رقم الصنف  </th>
                        <th class=" px-2 py-1  tagTd">اسم الصنف</th>
                        <th class=" px-2 py-1  tagTd"> الوحدة</th>
                        <th class=" px-2 py-1  tagTd"> الكمية المجرودة</th>
                        <th class=" px-2 py-1  tagTd">المخزن</th>
                        <th class=" px-2 py-1  tagTd">التكلفة </th>
                        <th class=" px-2 py-1  tagTd">اجمالي تكلفة  </th>
                        <th class=" px-2 py-1  tagTd"></th>
                        <th class=" px-2 py-1  tagTd "></th>
                    </tr>
                </thead>
                <tbody>       
                </tbody>
            </table>
        </div>
        </div>  
</div>
<script src="{{ url('sales.js') }}"></script>
<script src="{{ url('inventorys/inventory.js') }}"></script>
<script src="{{ url('purchases.js') }}"></script>
<script>
  
    $(document).ready(function() {

        $(document).on('keydown', function (e) {
    if (e.key === '+') {
        e.preventDefault();
        ajaxForm(); // استدعاء دالة الحفظ
    }
});
         // الإرسال باستخدام Ctrl + Shift
    $(document).keydown(function (event) {
        if (event.ctrlKey && event.shiftKey) {
            event.preventDefault(); // منع الإرسال الافتراضي للنموذج
            saveInventorIyInvoce(); // استدعاء دالة الحفظ
        }
    }); 

function ajaxForm()
{
    const formData = new FormData($('#ajaxForm')[0]);
    $.ajax({
        url: "{{ route('inventory.storeProduct') }}",
        type: 'POST',
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: formData,
    processData: false,
    contentType: false, // استخدام this للإشارة إلى النموذج
        success: function(response) {
            if(response.success)
        {
            $('#TotalCost').val(response.TotalCost);
            addToTableInventory(response.inventoryData);
            emptyData();
              $('#successMessage').show().text(response.message).fadeOut(1000);
                console.log(response);
        }
        else
        {
            
            if(response.Quantity)
        {
            $('#Quantity').val(response.Quantity);
        }
        if(response.InventoryId)
        {
            $('#InventoryId').val(response.InventoryId);
        }
        if(response.Quantityprice)
        {
            $('#Quantityprice').val(response.Quantityprice);
        }
        $('#errorMessage').show().text(response.message).fadeOut(2000);
        }
        },
        error: function(xhr) {
            console.log(xhr.responseJSON.errors);
            // عرض الأخطاء في واجهة المستخدم
            let errorMessage = 'حدث خطأ:\n';
            $.each(xhr.responseJSON.errors, function(key, value) {
                errorMessage += value[0] + '\n'; // عرض الرسالة الأولى لكل خطأ
            });
            alert(errorMessage);
        },
        complete: function() {
            // إعادة تمكين الزر بعد انتهاء الطلب
            submitButton.prop('disabled', false);
        }
    });
}
function saveInventorIyInvoce()
{
    const formData = new FormData($('#inventory')[0]);
    $.ajax({
        url: "{{ route('inventory.store') }}",
        type: 'POST',
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: formData,
    processData: false,
    contentType: false, // استخدام this للإشارة إلى النموذج
        success: function(response) {
            if(response.success)
        {
            $('#sales_invoice_id').val(response.InventoryInvoice);
              $('#successMessage').show().text(response.message).fadeOut(3000);
                console.log(response);
        }
        else
        {
            $('#errorMessage').show().text(response.message).fadeOut(8000);
        }
        },
        error: function(xhr) {
            console.log(xhr.responseJSON.errors);
            // عرض الأخطاء في واجهة المستخدم
            let errorMessage = 'حدث خطأ:\n';
            $.each(xhr.responseJSON.errors, function(key, value) {
                errorMessage += value[0] + '\n'; // عرض الرسالة الأولى لكل خطأ
            });
            alert(errorMessage);
        },
        complete: function() {
            // إعادة تمكين الزر بعد انتهاء الطلب
            submitButton.prop('disabled', false);
        }
    });
}
$('#StoreId').on('change', function() {
const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي
showProductName(mainAccountId);
setTimeout(() => {
$('#StoreId').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
}, 1000);

});
function showProductName(mainAccountId)
{
var  productname= $('#product_id');

if (mainAccountId!==null) {
$.ajax({
  url: `/all-products/${mainAccountId}/show`, // استخدام القيم الديناميكية
  type: 'GET',
  dataType: 'json',
  success: function(data) {
      productname.empty();
      const productnameOptions = data.map(uniqueProduct =>
      `
      <option value="${uniqueProduct.product_id}">${uniqueProduct.product_id}</option>`
  ).join('');
// إضافة الخيارات الجديدة إلى القائمة الفرعية
productname.append( `<option value=""></option>`);
productname.append(productnameOptions);
productname.select2('destroy').select2();
// إعادة تهيئة Select2 بعد إضافة الخيارات
},
error: function(xhr) {
  console.error('حدث خطأ في الحصول على  المنتاج.', xhr.responseText);
}
});
};
}    
$('.select2').select2();



});
</script>

@endsection
