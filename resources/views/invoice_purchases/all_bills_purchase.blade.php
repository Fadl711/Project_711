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
    const searchTypeSelect = $('select[name="searchType"]'); // قائمة البحث
    const searchInput = $('input[name="search"]'); // مدخل البحث
    let debounceTimeout; // لتخزين التوقيت بين عمليات البحث
    const displayContainer = $('#displayContainer'); // الحاوية الأولى
    const displayContainer2 = $('#displayContainer2'); // الحاوية الثانية

    // استماع لتغيير قيمة مدخل البحث (input)
    searchInput.on('input', function () {
        const searchQuery = searchInput.val().trim(); // الحصول على قيمة البحث بعد التصفية

        clearTimeout(debounceTimeout); // إلغاء البحث السابق إذا كان هناك تأخير

        if (searchQuery !== "") {
            // إذا كان المدخل يحتوي على نص
            displayContainer.addClass("hidden"); // إظهار الحاوية الأولى
            displayContainer2.removeClass("hidden"); // إظهار الحاوية الثانية
            debounceTimeout = setTimeout(performSearch, 500); // تأخير البحث لمدة 500 مللي ثانية
        } else {
            // إذا كان المدخل فارغًا
            displayContainer.removeClass("hidden"); // إظهار الحاوية الأولى
            displayContainer2.addClass("hidden"); // إخفاء الحاوية الثانية
            resetSearchResults(); // إعادة تهيئة الحاويات أو البيانات عند إفراغ المدخل
        }
    });

    // وظيفة performSearch لتنفيذ البحث بناءً على نص المدخل
    function performSearch() {
        const searchType = searchTypeSelect.val(); // قيمة البحث (كل الفواتير، أول فاتورة، آخر فاتورة)
        const searchQuery = searchInput.val().trim(); // الحصول على قيمة البحث بعد التصفية

        // بناء رابط API مع القيم
        let url = `/api/purchase-invoices?searchType=${searchType}&searchQuery=${searchQuery}`;

        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                displayContainer2.empty(); // تفريغ الحاوية قبل عرض البيانات الجديدة

                if (data.purchaseInvoices.length > 0) {
                    // عرض الفواتير في الحاوية الثانية
                    data.purchaseInvoices.forEach((purchase) => {
                        displayContainer2.append(`
                            <div class="mb-3 border border-gray-300 rounded-lg px-4 py-2 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div class="text-right">
                                        <div class="text-gray-700 text-right">
                                            تاريخ الفاتورة: <span class="text-sm">${purchase.formatted_date}</span>
                                        </div>
                                        <div class="text-gray-700 text-right">
                                            اسم ${purchase.main_account_class}: <span class="text-sm">${purchase.supplier_name}</span>
                                        </div>
                                    </div>
                                    <div class="text-gray-700">
                                        فاتورة ${purchase.transaction_type}: <span class="text-sm">${purchase.Invoice_type}</span>
                                    </div>
                                    <div class="text-right">
                                        <div>رقم الفاتورة: <span class="text-sm">${purchase.invoice_number}</span></div>
                                        <div>رقم الإيصال: <span class="text-sm">${purchase.receipt_number}</span></div>
                                    </div>
                                </div>
                                <table class="w-full text-center border-collapse text-sm">
                                    <thead class="bg-indigo-600 text-white text-sm">
                                        <tr>
                                            <th class="py-1 px-4">إجمالي تكلفة الفاتورة</th>
                                            <th class="py-1 px-4">إجمالي المصاريف</th>
                                            <th class="py-1 px-4">المدفوع</th>
                                            <th class="py-1 px-4">عرض الفاتورة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-gray-50">
                                            <td class="py-1 px-4">${purchase.total_invoice.toLocaleString()}</td>
                                            <td class="py-1 px-4">${purchase.total_cost.toLocaleString()}</td>
                                            <td class="py-1 px-4">${purchase.paid.toLocaleString()}</td>
                                            <td class="py-1 px-4">
                                                <button value="${purchase.invoice_number}" onclick="openInvoiceWindow(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح الفاتورة</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="grid grid-cols-2">
                                    <div class="text-gray-700 text-sm text-right">
                                        المستخدم: <span>${purchase.user_name}</span>
                                    </div>
                                    <div class="text-gray-700 text-left text-sm">
                                        تاريخ التحديث: <span>${purchase.updated_at}</span>
                                    </div>
                                </div>
                            </div>
                        `);
                    });

                } else {
                    // إذا لم توجد فواتير
                    displayContainer2.append('<p class="text-center text-gray-500">لا توجد فواتير لعرضها.</p>');
                }
            },
            error: function (error) {
                console.error('Error fetching invoices:', error.responseText);
            }
        });
    }

    // وظيفة لإعادة تهيئة الحاويات أو البيانات عند إفراغ المدخل
    function resetSearchResults() {
        displayContainer2.empty(); // تفريغ المحتوى في الحاوية الثانية
    }
});

</script>

<script>
   $(document).ready(function () {
    const displayContainer = $('.overflow-y-auto'); // العنصر الذي سيعرض البيانات

    // الاستماع لأي تغييرات في الاختيار بين الأزرار الراديوية
    $('input[name="list-radio"]').on('click', function () {
        const value = $(this).val(); // القيمة المختارة
        const url = `/api/purchase-invoices/${value}`; // بناء الرابط بناءً على القيمة

        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                displayContainer.empty(); // تفريغ الحاوية قبل عرض البيانات الجديدة

                if (data.purchaseInvoices.length > 0) {
                    data.purchaseInvoices.forEach((purchase) => {
                        displayContainer.append(`
                            <div class="mb-3 border border-gray-300 rounded-lg px-4 py-2 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div class="text-right">
                                        <div class="text-gray-700 text-right">
                                            تاريخ الفاتورة: <span class="text-sm">${purchase.formatted_date}</span>
                                        </div>
                                        <div class="text-gray-700 text-right">
                                            اسم ${purchase.main_account_class}: <span class="text-sm">${purchase.supplier_name}</span>
                                        </div>
                                    </div>
                                    <div class="text-gray-700">
                                        فاتورة ${purchase.transaction_type}: <span class="text-sm">${purchase.Invoice_type}</span>
                                    </div>
                                    <div class="text-right">
                                        <div>رقم الفاتورة: <span class="text-sm">${purchase.invoice_number}</span></div>
                                        <div>رقم الإيصال: <span class="text-sm">${purchase.receipt_number}</span></div>
                                    </div>
                                </div>
                                <table class="w-full text-center border-collapse text-sm">
                                    <thead class="bg-indigo-600 text-white text-sm">
                                        <tr>
                                            <th class="py-1 px-4">إجمالي تكلفة الفاتورة</th>
                                            <th class="py-1 px-4">إجمالي المصاريف</th>
                                            <th class="py-1 px-4">المدفوع</th>
                                            <th class="py-1 px-4">عرض الفاتورة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-gray-50">
                                            <td class="py-1 px-4">${purchase.total_invoice.toLocaleString()}</td>
                                            <td class="py-1 px-4">${purchase.total_cost.toLocaleString()}</td>
                                            <td class="py-1 px-4">${purchase.paid.toLocaleString()}</td>
                                            <td class="py-1 px-4">
                                                <button value="${purchase.invoice_number}" onclick="openInvoiceWindow(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح الفاتورة</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="grid grid-cols-2">
                                    <div class="text-gray-700 text-sm text-right">
                                        المستخدم: <span>${purchase.user_name}</span>
                                    </div>
                                    <div class="text-gray-700 text-left text-sm">
                                        تاريخ التحديث: <span>${purchase.updated_at}</span>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                } else {
                    displayContainer.html('<p class="text-center text-gray-500">لا توجد فواتير لعرضها.</p>');
                }
            },
            error: function (error) {
                console.error('Error fetching invoices:', error.responseText);
            }
        });
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
            const url = `{{ route('invoicePurchases.print', ':invoiceField') }}`.replace(':invoiceField', invoiceField);

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

