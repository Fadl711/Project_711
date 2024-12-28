@extends('bonds.index')
@section('tital'){{'gamal'}} @endsection
@section('bonds')
<style>
  /* تثبيت الأرقام بالإنجليزية */
  .english-numbers {
      font-feature-settings: 'tnum';
      direction: ltr;
      unicode-bidi: plaintext;
  }
  td{
    text-align: right;
  }
 
.select2-container--default .select2-dropdown {
  max-height: 200px; /* ارتفاع القائمة */
  overflow-y: auto; /* تمكين التمرير إذا تجاوز المحتوى الارتفاع */
}
.select2-container--default .select2-selection--single {
  height: 40px; /* ارتفاع العنصر الأساسي */
  line-height: 45px; /* لتوسيط النص عموديًا */
}
.select2-container--default .select2-selection__rendered {
  padding-top: 5px; /* تحسين النصوص */
}
</style>

<div class="container mx-auto ">
  <!-- Search and Filter Section -->
  <div class="bg-white p-1 shadow-lg rounded-lg flex flex-col sm:flex-row items-center gap-4 justify-between mb-2">
      <div class="w-full sm:w-auto flex gap-4 items-center">
          <select name="searchType" class="border select2 border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500">
              <option selected value="كل السندات">كل السندات</option>
              <option value="أول سند">أول سند</option>
              <option value="آخر سند">آخر سند</option>
          </select>
          <input type="search" name="search" class="border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500" placeholder="بحث">
      </div>
      <div>
        <select name="transactionType" class="border select2 border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500">
          <option selected value="سند قبض"> سند قبض</option>
         <option value="سند صرف">سند صرف</option>
        </select>
    </div>
    
      <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">تحديث البيانات</button>
  </div>
  <!-- Date Filter Section -->
  <form class="bg-gray-50 p-1 rounded-lg shadow-md mb-2">
      <ul class="flex flex-col sm:flex-row gap-4 items-center">
          <li class="w-full text-center">
              <label class="text-sm font-medium">عرض حسب</label>
          </li>
                    <li class="w-full text-center">
              <input type="radio" name="list-radio" value="1" class="mr-2"> تلقائي
          </li>
          <li class="w-full text-center">
              <input type="radio" name="list-radio" value="2" class="mr-2"> اليوم
          </li>
          <li class="w-full text-center">
              <input type="radio" name="list-radio" value="3" class="mr-2"> هذا الأسبوع
          </li>
          <li class="w-full text-center">
              <input type="radio" name="list-radio" value="4" class="mr-2"> هذا الشهر
          </li>
          <li class="w-full flex items-center justify-center">
              <label class="text-sm font-medium">من:</label>
              <input type="date" name="from-date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
          </li>
          <li class="w-full flex items-center justify-center">
              <label class="text-sm font-medium">إلى:</label>
              <input type="date" name="to-date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
          </li>
      </ul>
  </form>
  
  </div>
  <div id="displayContainer" class="overflow-y-auto max-h-[80vh] bg-white px-4 py-1 rounded-lg shadow-md">
  </div>
  <div id="displayContainer2" class="overflow-y-auto max-h-[80vh] bg-white px-4 py-1 rounded-lg shadow-md">
    
  </div>


<script>
  $(document).ready(function () {
    $('.select2').select2();
});

$(document).ready(function () {
    const searchTypeSelect = $('select[name="searchType"]');
    const transactiontypeeSelect = $('select[name="transactionType"]');
    const searchInput = $('input[name="search"]');
    const radioInput = $('input[name="list-radio"]');
    const displayContainer = $('#displayContainer');
    const displayContainer2 = $('#displayContainer2');
    let debounceTimeout;

    // استدعاء البيانات من API
    function fetchInvoices(url, container) {
        $.ajax({
            url: url,
            method: 'GET',
            
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                container.empty();
                if (data.PaymentInvoices.length > 0) {
                    data.PaymentInvoices.forEach((PaymentBond) => {
                        container.append(renderInvoiceCard(PaymentBond));
                    });
                } else {
                    container.append('<p class="text-center text-gray-500">لا توجد فواتير لعرضها.</p>');
                }
            },
            error: function (error) {
                console.error('Error fetching invoices:', error.responseText);
            }
        });
    }

    // إنشاء كارت عرض الفاتورة
    function renderInvoiceCard(invoice) {
    return `
        <div class="mb-2 border border-gray-300 rounded-lg px-2 py-2 shadow-lg max-w-full" id="invoice-${invoice.payment_bond_id}">
            <div class="bg-white border border-gray-300 rounded-lg shadow-md p-2 mb-1">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <div class="text-right">
                        <p>رقم السند: <span class="font-bold text-gray-800">${invoice.payment_bond_id}</span></p>
                        <p>تاريخ السند: <span class="font-bold text-gray-800">${invoice.formatted_date}</span></p>
                    </div>
                    <div class="text-center sm:text-left">
                        <p class="text-xl sm:text-2xl font-bold text-blue-700">
                            ${invoice.transaction_type} (${invoice.payment_type})
                        </p>
                    </div>
                    <div class="text-right sm:text-left">
                        <p>المبلغ: <span class="text-lg font-bold bg-gray-100 rounded-md">${invoice.amount_debit} ريال</span></p>
                        <p>إيداع في حساب: <span class="font-bold text-gray-800">${invoice.sub_name_debit}</span></p>
                    </div>
                </div>
            </div>
            <div class="bg-white border border-gray-300 rounded-lg shadow-md p-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div class="text-right">
                        <p>مبلغ وقدره: <span class="text-lg font-bold">${invoice.amount_debit}</span> ${invoice.result}</p>
                        <p>تقيد المبلغ لحساب الدائن: <span class="font-bold text-gray-800">${invoice.sub_name_credit}</span></p>
                    </div>
                    <div class="text-left">
                        <p>المسؤول: <span class="text-lg">${invoice.user_name}</span></p>
                        <p>تاريخ التحديث: <span class="text-lg">${invoice.updated_at}</span></p>
                        <div class="flex gap-8 space-x-2">
                       <a href="#" class="text-red-600 hover:underline show-payment" data-id="${invoice.payment_bond_id}" data-url="${invoice.destroy_url}">عرض</a>
                            <a href="${invoice.edit_url}" class="text-green-600 hover:underline">تعديل</a>
                           <a href="#" class="text-red-600 hover:underline delete-payment" data-id="${invoice.payment_bond_id}" data-url="${invoice.destroy_url}">حذف</a>
                                                </div>
                    </div>
                </div>
            </div>
        </div>
        
    `;

}
$(document).on('click', '.show-payment', function (e) {
    e.preventDefault();

    let invoiceField = $(this).data('id');
    const url = `{{ route('receip.print', ':invoiceField') }}`.replace(':invoiceField', invoiceField);
    window.open(url, '_blank', 'width=600,height=800'); // فتح الرابط في نافذة جديدة
  });

// دالة لحذف السند باستخدام AJAX بعد التأكيد
$(document).on('click', '.delete-payment', function (e) {
    e.preventDefault();

    let paymentId = $(this).data('id');
    let url = $(this).data('url');

    if (confirm('هل أنت متأكد أنك تريد حذف هذا السند؟')) {
        $.ajax({
            url: url,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.status === 'success') {
                
                    // إخفاء السند من الواجهة
                    $('#invoice-' + paymentId).fadeOut(); // يمكن استخدام fadeOut لإخفاء العنصر مع تأثير } else {
                }
            },
            error: function (error) {
                console.error('Error deleting payment bond:', error.responseText);
                alert('حدث خطأ أثناء الحذف. يرجى المحاولة لاحقًا.');
            }
        });
    }
});





    // البحث بالنص
    searchInput.on('input', function () {
    const searchQuery = searchInput.val().trim();
    clearTimeout(debounceTimeout);


    if (searchQuery !== "") {
            displayContainer.addClass("hidden");
            displayContainer2.removeClass("hidden");
 
            debounceTimeout = setTimeout(() => {
              const searchType = searchTypeSelect.val();
              const transactionType = transactiontypeeSelect.val();
              const baseUrl = "{{ url('/api/Receip-invoices') }}"; 
              const url = `${baseUrl}?searchType=${searchType}&searchQuery=${searchQuery}&transactionType=${transactionType}`;
 
fetchInvoices(url, displayContainer2);
}, 500);
    } else {
        displayContainer.removeClass("hidden");
        displayContainer2.addClass("hidden");
        displayContainer2.empty();
    }
});


    // البحث بالإعدادات الأخرى
    radioInput.on('click', function () {
        const filterType = $(this).val();
        const transactionType = transactiontypeeSelect.val();
        const baseUrl = "{{ url('/api/Receip-invoices')}}";  
              const url = `${baseUrl}/${filterType}?transactionType=${transactionType}`;

// استخدام url في طلب AJAX
$.ajax({
    url: url,
    method: 'GET',
    // المعاملات الأخرى...
});        displayContainer.removeClass("hidden");

        fetchInvoices(url, displayContainer);
    });
});

</script>

@endsection
