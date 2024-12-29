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

        <div class="flex-grow bg-white dark:bg-gray-900 overflow-y-auto">
          <div class="sm:px-7 sm:pt-7  pt-4 flex flex-col sm:w-full border-b border-gray-200 bg-white dark:bg-gray-900 dark:text-white dark:border-gray-800 sticky top-0">
            <div class="flex items-center space-x-3 sm:mt-7 mt-4  sm:text-lg">
              <a href="{{route('report.create')}}" class="sm:px-3 border-b-2 {{ Request::is('report/create') ? 'dark:alert("gamal")  text-blue-700 border-blue-700 ' : 'text-gray-600' }}   border-transparent  dark:text-white dark:border-white pb-1.5 ">كشف حساب</a>
              <a href="{{route('report.inventoryReport')}}" class="sm:px-3 border-b-2 border-transparent {{ Request::is('inventoryReport') ? 'text-blue-700 border-blue-700' : 'text-gray-600' }} dark:text-gray-400 pb-1.5"> تقارير المخازن </a>
              <a href="{{route('report.earningsReports')}}" class="sm:px-3 border-b-2 {{ Request::is('earningsReports') ? 'text-blue-700 border-blue-700' : 'text-gray-600' }} border-transparent   dark:text-gray-400 pb-1.5">تقارير ارباح وخسائر الاصناف</a>
              <a href="{{route('report.salesReport')}}" class="sm:px-3 border-b-2 {{ Request::is('salesReport') ? 'text-blue-700 border-blue-700' : 'text-gray-600' }} border-transparent dark:text-gray-400 pb-1.5">تقارير المبيعات</a>
              <a href="#" class="sm:px-3 border-b-2 border-transparent text-gray-600 dark:text-gray-400 pb-1.5 ">تقارير</a>
           
            </div>
          </div>
        </div>
        <h1 class="text-center text-2xl font-bold mb-6">تقرير المخزني</h1>
        <form id="Account" method="POST" class="mb-2">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-4 rounded-lg shadow-md ">
                <div>
                    <label for="warehouse_id" class="block text-sm font-semibold mb-1">المخزن</label>
                    <select name="warehouse_id" id="warehouse_id" class="input-field select2 w-full border border-gray-300 rounded-lg p-2" required>
                        <option value="" selected>اختر المخزن</option>
                        @isset($Warehouse)
                        @foreach($Warehouse as $mainAccount)
                            <option value="{{ $mainAccount->sub_account_id }}">
                                {{ $mainAccount->sub_name }} - {{ $mainAccount->sub_account_id }}
                            </option>
                        @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <label for="product_name" class="block text-sm font-semibold mb-1">اسم المنتج</label>
                    <select name="product_name" id="product_name" class="input-field select2 w-full border border-gray-300 rounded-lg p-2" required>
                        <option value="" selected>اختر المنتج</option>
                    </select>
                </div>
                <div>
                    <label for="Quantit" class="block text-sm font-semibold mb-1">نوع التقرير</label>
                    <select name="Quantit"  id="Quantit" class="input-field select2 w-full border border-gray-300 rounded-lg p-2 text-right" required>
                        <option value="" selected>اختر النوع</option>
                        @foreach([
                                            'inventoryList' => 'امر جرد',

                            'Quantityonly' => 'الكمية',
                                  'QuantityCosts' => 'الكمية والتكاليف',
                                  'QuantityCostsSupplier' => 'الكمية والتكاليف حسب حركة الموردين',
                                  'QuantitySupplier' => 'الكمية حسب حركة الموردين'] as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach(['ShowAllProducts' => 'عرض كل المنتجات', 'SelectedProduct' => 'المنتج المحدد'] as $key => $label)
                        <div class="flex items-center mb-2">
                            <input 
                                type="radio" 
                                name="DisplayMethod" 
                                value="{{ $key }}" 
                                class="mr-2" 
                                {{ (old('DisplayMethod', $selectedAccountListRadio ?? 'SelectedProduct') === $key) ? 'checked' : '' }}>
                            <label class="text-sm font-semibold">{{ $label }}</label>
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

            url: "{{url('/all-products/')}}/"+mainAccountId+"/show", // استخدام القيم الديناميكية
            
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
              const productname = $('#product_name').val();
              const DisplayMethod = $('input[name="DisplayMethod"]:checked').val(); // الخيار المحدد لعرض القائمة
              const Quantit = $('#Quantit').val(); // الخيار المحدد لعرض القائمة
              const dateList = $('input[name="dateList"]:checked').val(); // الخيار المحدد لعرض القائمة
              const Report_Type = $('#Report_Type').val(); // الحساب الرئيسي أو الفرعي
            //   const viewType = $('input[name="list"]:checked').val(); // كشف كلي أو تحليلي
        
          
          if(DisplayMethod=="SelectedProduct")
          {
              if (productname) {
                  invoiceField = productname;
              }
              
          
               else {
                  displayMessage('يرجى تحديد المنتج', 'error'); // عرض رسالة خطأ
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
          
                      window.open(url, '_blank', 'width=1000,height=800');
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
          
                      window.open(url, '_blank', 'width=100,height=100');
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

