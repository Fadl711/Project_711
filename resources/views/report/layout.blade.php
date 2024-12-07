@extends('layout')
@section('conm')
<style>
    .select2-container--default .select2-dropdown {
    max-height: 200px; /* ارتفاع القائمة */
    overflow-y: auto; /* تمكين التمرير إذا تجاوز المحتوى الارتفاع */
}
.select2-container--default .select2-selection--single {
    height: 40px; /* ارتفاع العنصر الأساسي */
    line-height: 45px; لتوسيط النص عموديًا
}
.select2-container--default .select2-selection__rendered {
    padding-top: 5px; /* تحسين النصوص */
}
</style>

<div class="bg-gray-100 dark:bg-gray-900 dark:text-white text-gray-600 h-screen  overflow-hidden text-sm">

        <div class="flex-grow bg-white dark:bg-gray-900 overflow-y-auto">
          <div class="sm:px-7 sm:pt-7  pt-4 flex flex-col sm:w-full border-b border-gray-200 bg-white dark:bg-gray-900 dark:text-white dark:border-gray-800 sticky top-0">
            <div class="flex items-center space-x-3 sm:mt-7 mt-4  sm:text-lg">
              <a href="{{route('report.summary')}}" class="sm:px-3 border-b-2 {{ Request::is('summary') ? 'dark:alert("gamal")  text-blue-700 border-blue-700 ' : 'text-gray-600' }}   border-transparent  dark:text-white dark:border-white pb-1.5 ">كشف حساب</a>
              <a href="{{route('report.create')}}" class="sm:px-3 border-b-2 {{ Request::is('report/create') ? 'dark:alert("gamal")  text-blue-700 border-blue-700 ' : 'text-gray-600' }}   border-transparent  dark:text-white dark:border-white pb-1.5 ">كشف حساب</a>
              <a href="{{route('report.inventoryReport')}}" class="sm:px-3 border-b-2 border-transparent {{ Request::is('inventoryReport') ? 'text-blue-700 border-blue-700' : 'text-gray-600' }} dark:text-gray-400 pb-1.5"> تقارير المخازن </a>
              <a href="{{route('report.earningsReports')}}" class="sm:px-3 border-b-2 {{ Request::is('earningsReports') ? 'text-blue-700 border-blue-700' : 'text-gray-600' }} border-transparent   dark:text-gray-400 pb-1.5">تقارير ارباح وخسائر الاصناف</a>
              <a href="{{route('report.salesReport')}}" class="sm:px-3 border-b-2 {{ Request::is('salesReport') ? 'text-blue-700 border-blue-700' : 'text-gray-600' }} border-transparent dark:text-gray-400 pb-1.5">تقارير المبيعات</a>
              <a href="#" class="sm:px-3 border-b-2 border-transparent text-gray-600 dark:text-gray-400 pb-1.5 ">تقارير</a>
              <a href="" onclick="myfun()"  class="sm:px-3 border-b-2 border-transparent text-gray-600 dark:text-gray-400 pb-1.5 flex">
               <svg class="w-6 h-5 text-gray-800 dark:text-white"
                   aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linejoin="round"
                       stroke-width="2" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z"/>
                    </svg>
                       <span class=" mr-1">طباعة التقرير</span>

                </a>
            </div>
          </div>
        </div>
    
 
          <h1>تقرير كشف حساب</h1>
          
          <form id="Account" method="POST" class="mb-2">
              @csrf
              <ul class="flex flex-col sm:flex-row gap-4 items-center bg-white p-1 rounded-lg shadow-md mb-2">
                  <li class="w-full text-center">
                      <label for="horizontal-list-radio-license" class="labelSale">إعدادات العرض</label>
                  </li>
                  @foreach(['1' => 'تلقائي', '2' => 'اليوم', '3' => 'هذا الأسبوع', '4' => 'هذا الشهر'] as $key => $label)
                      <li class="w-full text-center">
                          <input type="radio" name="dateList" value="{{ $key }}" {{ $key == 1 ? 'checked' : '' }} class="mr-2"> {{ $label }}
                      </li>
                  @endforeach
                  <li class="w-full flex items-center justify-center">
                      <input type="checkbox" name="list-radio" value="5" class="mr-2">
                      <label class="text-sm font-medium">من:</label>
                      <input type="date" name="from-date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
                  </li>
                  <li class="w-full flex items-center justify-center">
                      <label class="text-sm font-medium">إلى:</label>
                      <input type="date" name="to-date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
                  </li>
              </ul>
          
              <div class="gap-2 grid grid-cols-4 bg-white p-1 rounded-lg shadow-md mb-2">
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
                      <select name="product_name" id="product_name" class="input-field select2 inputSale" required>
                          <option value="" selected>اختر  المنتج</option>
                      </select>
                  </div>
                  <div class="">
                    <label for="Report_Type" class="labelSale"> نوع التقرير</label>
                    <select name="Report_Type" id="Report_Type" class="input-field select2 inputSale" required>
                        <option value="" selected>اختر المخزن</option>
                        @foreach(['storeData' => 'بيانات المخزن', 'stordeData' => ' المنتج المحدد'] as $key => $label)

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
                  
                      <div class="flex ">
                          <input type="radio" name="Quantit" value="Quantityonly" class="mr-2">  
                          <label for="" class="labelSale">  الكمية فقط</label>
                      </div>
                      <div class="flex ">
                          <input type="radio" name="Quantit" value="QuantityCosts" checked class="mr-2">  
                          <label for="" class="labelSale  ">   الكمية والتكاليف</label>
                      </div>
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
        // $('#Supplier_id').select2('open');
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
                `<option value="${uniqueProduct.product_id}">${uniqueProduct.Product_name}</option>`
            ).join('');

        // إضافة الخيارات الجديدة إلى القائمة الفرعية
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
              const productname = $('#product_name').val();
              const DisplayMethod = $('input[name="DisplayMethod"]:checked').val(); // الخيار المحدد لعرض القائمة
              const Quantit = $('input[name="Quantit"]:checked').val(); // الخيار المحدد لعرض القائمة
              const dateList = $('input[name="dateList"]:checked').val(); // الخيار المحدد لعرض القائمة
              const Report_Type = $('#Report_Type').val(); // الحساب الرئيسي أو الفرعي
            //   const viewType = $('input[name="list"]:checked').val(); // كشف كلي أو تحليلي
        
          
          if(DisplayMethod=="SelectedProduct")
          {
              if (productname) {
                  invoiceField = productname;
              }
          }
          if(DisplayMethod=="ShowAllProducts")
          {
              if (warehouseid) {
                  invoiceField = warehouseid;
              }
          }
         
              
          
         
              if (invoiceField) {
                  const url = `{{ route('report.print', ':invoiceField') }}`
                      .replace(':invoiceField', invoiceField)
                      + `?warehouseid=${warehouseid}&productname=${productname}&DisplayMethod=${DisplayMethod}&Quantit=${Quantit}`;
          
                  window.open(url, '_blank', 'width=800,height=800');
              } else {
                  displayMessage('يرجى تحديد الحساب الفرعي', 'error'); // عرض رسالة خطأ
              }
          }
          
              
              function openAndPrintInvoice2(event) {
                event.preventDefault(); // منع تحديث الصفحة
              
                  const invoiceField = $('#product_name').val();
              
                  if (invoiceField) {
                  const url = `{{ route('report.print', ':invoiceField') }}`
                      .replace(':invoiceField', invoiceField)
                      + `?warehouseid=${warehouseid}&productname=${productname}&DisplayMethod=${DisplayMethod}`;
          
                  window.open(url, '_blank', 'width=800,height=800');
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
              
        

<script>
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


</script>
@endsection

