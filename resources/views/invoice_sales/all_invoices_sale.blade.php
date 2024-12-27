@extends('layout')
@section('conm')
<div class="container mx-auto ">
    <!-- Search and Filter Section -->
    <div class="bg-white p-1 shadow-lg rounded-lg flex flex-col sm:flex-row items-center gap-4 justify-between mb-2">
        <div class="w-full sm:w-auto flex gap-4 items-center">
            <select name="searchType" class="border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500">
                <option selected>كل الفواتير</option>
                <option value="أول فاتورة">أول فاتورة</option>
                <option value="آخر فاتورة">آخر فاتورة</option>
            </select>
            <input type="search" name="search" class="border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500" placeholder="بحث" name="search">
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
    <div id="displayContainer" class="overflow-y-auto max-h-[80vh] bg-white px-4 py-1 rounded-lg shadow-md">
        </div>
    <div id="displayContainer2" class="overflow-y-auto max-h-[80vh]  bg-white px-4 py-1 rounded-lg shadow-md">
        </div>
</div>
<script>
    $(document).ready(function () {
    const searchTypeSelect = $('select[name="searchType"]');
    const searchInput = $('input[name="search"]');
    const radioInput = $('input[name="list-radio"]');
    const displayContainer = $('#displayContainer');
    const displayContainer2 = $('#displayContainer2');
    let debounceTimeout;
    function fetchInvoices(url, container) {
    console.log(`Fetching invoices from ${url}`);
    $.ajax({
        url: url,
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (data) {
            container.empty();
            if (data.saleInvoice && data.saleInvoice.length > 0) {
                data.saleInvoice.forEach((sale) => {
                    container.append(renderInvoiceCard(sale));
                });
            } else {
                container.append('<p class="text-center text-gray-500">لا توجد فواتير لعرضها.</p>');
            }
        },
        error: function (error) {
            console.error('Error fetching invoices:', error.responseText);
        },
    });
}

    // إنشاء كارت الفاتورة
    function renderInvoiceCard(sale) {
        return `
            <div  class="mb-3 border border-gray-300 rounded-lg px-4 py-2 shadow-sm" id="invoice-${sale.invoice_number}">
                <div class="flex justify-between items-center ">
                    <div class="text-right">
                        <div class="text-gray-700 text-right">تاريخ الفاتورة: <span class="text-sm">${sale.formatted_date}</span></div>
                        <div class="text-gray-700 text-right">اسم .${" "}.${sale.main_account_class}: <span class="text-sm">${sale.Customer_name}</span></div>
                    </div>
                    <div class="text-gray-700">فاتورة ${sale.transaction_type}: <span class="text-sm">${sale.payment_type}
</span></div>
                    <div class="text-right">
                        <div>رقم الفاتورة: <span class="text-sm">${sale.invoice_number}</span></div>
                        <div>رقم الإيصال: <span class="text-sm">${sale.receipt_number??0}</span></div>
                    </div>
                </div>
                <table class="w-full text-center border-collapse text-sm">
                    <thead class="bg-indigo-600 text-white text-sm">
                         <tr >
                            <td class="py-1 px-4"> إجمالي سعر البيع </td>
                            <td class="py-1 px-4"> قيمة الخصم الممنوح</td>
                            <td class="py-1 px-4">مبلغ الشحن </td>
                            <td class="py-1 px-4"> المسؤول عن الشحن </td>
                            <td class="py-1 px-4"> الإجمالي الصافي   </td>
                            <td class="py-1 px-4">المدفوع</td>
                            <td class="py-1 px-4">عرض الفاتورة</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-gray-50">
                            <td class="py-1 px-4">${sale.total_price_sale.toLocaleString()}</td>
                            <td class="py-1 px-4">${sale.discount.toLocaleString()}</td>
                            <td class="py-1 px-4">${sale.shipping_amount.toLocaleString()}</td>
                            <td class="py-1 px-4">${sale.shipping_bearer === 'customer' ? 'العميل' : sale.shipping_bearer === 'customer' ? 'التاجر':'غير محدد'}</td>
                            <td class="py-1 px-4">${sale.net_total_after_discount.toLocaleString()}</td>
                            <td class="py-1 px-4">${sale.paid_amount.toLocaleString()}</td>
                            <td class="py-1 px-4">
                                  <div class="flex gap-8 space-x-2">
                       <a href="#" class="text-red-600 hover:underline show-payment" data-id="${sale.invoice_number}" data-url="${sale.view_url}">عرض</a>
                            <a href="${sale.edit_url}" class="text-green-600 hover:underline">تعديل</a>
                           <a href="#" class="text-red-600 hover:underline delete-payment" data-id="${sale.invoice_number}" data-url="${sale.destroy_url}">حذف</a>
                                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="grid grid-cols-2">
                    <div class="text-gray-700 text-right text-sm">تاريخ التحديث: <span>${sale.updated_at}</span></div>
                    <div class="text-gray-700 text-sm  text-left">المستخدم: <span>${sale.user_name}</span></div>
                </div>
            </div>`;
    }
    $(document).on('click', '.show-payment', function (e) {
    e.preventDefault();

    let invoiceField = $(this).data('id');
    const url = `{{ route('invoiceSales.print', ':invoiceField') }}`.replace(':invoiceField', invoiceField);

window.open(url, '_blank', 'width=600,height=800');// فتح الرابط في نافذة جديدة
  });
  $(document).on('click', '.delete-payment', function (e) {
    e.preventDefault();

    let paymentId = $(this).data('id');
    let url = $(this).data('url');

    if (confirm('هل أنت متأكد أنك تريد حذف هذا فاتورة?')) {
        $.ajax({
            url: url,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.success) {
                
                    // إخفاء السند من الواجهة
                    $('#invoice-' + paymentId).fadeOut(); // يمكن استخدام fadeOut لإخفاء العنصر مع تأثير } else {
                }
            },
            error: function (error) {
                console.error('Error deleting payment bond:', message.responseText);
                alert('حدث خطأ أثناء الحذف. يرجى المحاولة لاحقًا.');
            }
        });
    }
});

    // البحث بالمدخل
    searchInput.on('input', function () {
        const searchQuery = searchInput.val().trim();
        clearTimeout(debounceTimeout);
        

        if (searchQuery !== "") {
            displayContainer.addClass("hidden");
            displayContainer2.removeClass("hidden");
            debounceTimeout = setTimeout(() => {
                const searchType = searchTypeSelect.val();
                const baseUrl = "{{ url('/api/sale-invoices') }}"; 
              const url = `${baseUrl}?searchType=${searchType}&searchQuery=${searchQuery}`;
 
                fetchInvoices(url, displayContainer2);
            }, 500);
        } else {
            displayContainer.removeClass("hidden");
            displayContainer2.addClass("hidden");
            displayContainer2.empty();
        }
    });

    // البحث بالاختيار
    radioInput.on('click', function () {
        const value = $(this).val();
        const url = "{{ url('/api/sale-invoices/') }}/"+value; 

        displayContainer.removeClass("hidden");

        fetchInvoices(url, displayContainer);
    });
});

</script>

<script>
    function openInvoiceWindow(e) {
        // استخدم e.target للحصول على الزر الذي تم النقر عليه
        let invoiceField = $(e.target).val(); // استخدام e.target بدلاً من this

        const successMessage = $('#successMessage');
        
        if (invoiceField) {
            e.preventDefault(); // منع تحديث الصفحة

            // استبدال القيمة في الرابط
            const url = `{{ route('invoiceSales.print', ':invoiceField') }}`.replace(':invoiceField', invoiceField);

            window.open(url, '_blank', 'width=600,height=800'); // فتح الرابط مع استبدال القيمة
        } else {
            successMessage.text('لا توجد فاتورة').show();
            setTimeout(() => {
                successMessage.hide();
            }, 3000);
        }
    }
</script>

@endsection 

