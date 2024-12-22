@extends('layout')
@section('conm')
<style>
 
.select2-container--default .select2-selection--single {
    height: 40px; /* ارتفاع العنصر الأساسي */
    line-height: 45px; لتوسيط النص عموديًا
}
.select2-container--default .select2-selection__rendered {
    padding-top: 5px; /* تحسين النصوص */
}
</style>
<div class="bg-gray-100 dark:bg-gray-900 dark:text-white text-gray-600 h-screen  overflow-hidden text-sm">
<x-nav-inventory/>
{{-- <br> --}}
<h1>تقرير  المخزني</h1>
<form id="Account" method="POST" class="mb-2">
    @csrf
    <div class="gap-2 grid grid-cols-4 bg-white p-1 rounded-lg shadow-md mb-2">
        <div>
            <label for="accountingPeriod" class="labelSale"> السنة</label>
            
            <select name="accountingPeriod" id="accountingPeriod" class="input-field select2 inputSale" required>
                @isset($accountingPeriodOpen)
                <option  @isset($accountingPeriodOpen)
                value="{{$accountingPeriodOpen->accounting_period_id}}" 
                    
                @endisset > {{$accountingPeriodOpen['created_at']->format('Y') }}</option>
                @endisset 
             
            </select>
        </div>
        <div>
            <label for="warehouse_id" class="labelSale"> المخزن</label>
            
            <select name="warehouse_id" id="warehouse_id" class="input-field select2 inputSale" required>
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
        <div>
            <label for="product_name" class="labelSale"> اسم المنتج</label>
            <select data-mdb-select-init multiple data-mdb-filter="true" name="product_name" id="product_name" class="input-field select2 
                block w-auto min--[200px] max-w-full p-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:border-blue-500">   <option value="" selected>اختر  المنتج</option>
            </select>
        </div>
        <div class="">
          <label for="Quantit" class="labelSale"> نوع التقرير</label>
          
          <select name="Quantit" id="Quantit" class="input-field select2 inputSale" required>
              <option value="" selected>اختر المخزن</option>
              @foreach([
                'inventoryList' => 'امر جرد',
                'AllAbstractQuantities' => 'كل الكميات المجرودة',
                'AllAbstractQuantitiesWithCosts' => 'كل الكميات المجرودة مع التكاليف',
                'MissingQuantitiesInventoryTeams' => 'فارق الجرد للكميات الناقصة',
                'InventoryDifferenceMissingQuantitiesWithCosts' => ' فارق الجرد للكميات الناقصة مع التكاليف',
                'appendix' => 'فارق الجرد للكميات الزائدة',
                'Costappendix' => 'فارق الجرد للكميات الزائدة مع التكاليف',
              
               ] 
              as $key => $label)
              <option value="{{ $key }}" > {{ $label }}</option>
              @endforeach
          </select>


      </div>
        <div class="gap-2 grid grid-cols-2">
            @foreach(['ShowAllProducts' => ' عرض كل المنتجات', 'SelectedProduct' => ' المنتج المحدد'] as $key => $label)
            <div class="flex">
                <input 
                    type="radio" 
                    name="DisplayMethod" 
                    value="{{ $key }}" 
                    class="mr-2"
                    {{ (old('DisplayMethod', $selectedAccountListRadio ?? 'SelectedProduct') === $key) ? 'checked' : '' }}>
                <label class="labelSale">{{ $label }}</label>
            </div>
        @endforeach
        
        </div>
        
    </div>
</form>
<div id="errorMessage" class="text-red-500 text-xs mt-2 hidden"></div>
<button onclick="openInvoiceWindow(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح الفاتورة</button>
<button onclick="openAndPrintInvoice2(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح وطباعة الفاتورة</button>
<div id="successMessage" style="display:none;" class="text-red-500 font-semibold mt-2"></div>
</div>
<script></script>

<script>
      $(document).ready(function() {
$('.select2').select2();

$('#warehouse_id').on('change', function() {
const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي
showProductName(mainAccountId);
setTimeout(() => {
$('#warehouse_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
}, 1000);

});

function showProductName(mainAccountId)
{
var  productname= $('#product_name');

if (mainAccountId!==null) {
$.ajax({
  url: `/all-products/${mainAccountId}/show`, // استخدام القيم الديناميكية
  type: 'GET',
  dataType: 'json',
  success: function(data) {
      productname.empty();
      const productnameOptions = data.map(uniqueProduct =>
      `
      <option value="${uniqueProduct.product_id}">${uniqueProduct.Product_name}</option>`
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
});
   function openInvoiceWindow(event) {
      event.preventDefault(); // منع تحديث الصفحة
    let invoiceField = 0; // تعريف المتغير بـ let لتجنب الأخطاء
    const warehouseid = $('#warehouse_id').val();
    const accountingPeriodData = $('#accountingPeriod').val();
    const productname = $('#product_name').val();
    const DisplayMethod = $('input[name="DisplayMethod"]:checked').val(); // الخيار المحدد لعرض القائمة
    const Quantit = $('#Quantit').val(); // الخيار المحدد لعرض القائمة
    const dateList = $('input[name="dateList"]:checked').val(); // الخيار المحدد لعرض القائمة
    const Report_Type = $('#Report_Type').val(); // الحساب الرئيسي أو الفرعي


if(DisplayMethod=="SelectedProduct")
{
    if (productname) {
        invoiceField = productname;
    }
    else 
    {
       $('#errorMessage').val('قم بتحديد الاصناف');
       displayMessage('قم بتحديد الاصناف', 'error');

    }
}
if(DisplayMethod=="ShowAllProducts")
{
    if (warehouseid) {
        invoiceField = warehouseid;
    }
}

    


    if (invoiceField) {
        const url = `{{ route('inventory.print', ':invoiceField') }}`
            .replace(':invoiceField', invoiceField)
            + `?warehouseid=${warehouseid}&productname=${productname}&DisplayMethod=${DisplayMethod}&Quantit=${Quantit}&accountingPeriodData=${accountingPeriodData}`;

            window.open(url, '_blank', 'width=1000,height=800');
          } else {
        displayMessage('يرجى تحديد  المخزن', 'error'); // عرض رسالة خطأ
    }
}

    
    function openAndPrintInvoice2(event) {
      event.preventDefault(); // منع تحديث الصفحة
    
if(DisplayMethod=="SelectedProduct")
{
    if (productname) {
        invoiceField = productname;
    }
    else 
    {
       $('#errorMessage').val('قم بتحديد الاصناف');
       displayMessage('قم بتحديد الاصناف', 'error');

    }
}
if(DisplayMethod=="ShowAllProducts")
{
    if (warehouseid) {
        invoiceField = warehouseid;
    }
}
        const invoiceField = $('#product_name').val();
        if (invoiceField) {
        const url = `{{ route('inventory.print', ':invoiceField') }}`
            .replace(':invoiceField', invoiceField)
            + `?warehouseid=${warehouseid}&productname=${productname}&DisplayMethod=${DisplayMethod}&Quantit=${Quantit}&accountingPeriodData=${accountingPeriodData}`;

            window.open(url, '_blank', 'width=1000,height=800');
            if (newWindow) {
                newWindow.onload = function() {
                    setTimeout(() => {
                        newWindow.print();
                        newWindow.close();
                    }, 1000);
                };
            } else {
                displayMessage('تعذر فتح النافذة. يرجى التحقق من إعدادات المتصفح.', 'error');
            }
        } else {
            displayMessage('يرجى تحديد الحساب الفرعي', 'error');
        }
    }
    
    function displayMessage(message, type) {
        const successMessage = $('#successMessage');
        successMessage
            .text(message)
            .removeClass()
            .addClass(type === 'error' ? 'text-red-500 font-semibold' : 'text-green-500 font-semibold')
            .fadeIn();
    
        setTimeout(() => {
            successMessage.fadeOut();
        }, 3000);
    }
    </script>
{{-- <script>
function myfun(){

if (window.location.href.includes('/summary')){
  window.open("summaryPdf", "_blank", "download");

}
else if(window.location.href.includes('/inventoryReport')){
  window.open("inventoryReportPdf", "_blank", "download");
}
else if(window.location.href.includes('/earningsReports')){
  window.open("earningsReportsPdf", "_blank", "download");
}
else if(window.location.href.includes('/salesReport')){
  window.open("salesReportPdf", "_blank", "download");
}

}
</script> --}}
 
@endsection
