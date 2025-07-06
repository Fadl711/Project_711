<!-- Blade View -->
@extends('layout')
@section('conm')
    <div class="container mx-auto">
        <!-- Search and Filter Section -->
        <div class="bg-white p-1 shadow-lg rounded-lg flex flex-col sm:flex-row items-center gap-4 justify-between mb-2">
            <div class="w-full sm:w-auto flex gap-4 items-center">
                <select name="searchType"
                    class="sel border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500">
                    <option selected>كل الفواتير</option>
                    <option value="أول فاتورة">أول فاتورة</option>
                    <option value="آخر فاتورة">آخر فاتورة</option>
                </select>
                <input type="search" name="search"
                    class="border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500"
                    placeholder="بحث">
            </div>
            {{--             <button
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">تحديث
                البيانات</button> --}}
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
                    <input type="radio" name="list-radio" value="5" class="mr-2"> حسب التاريخ
                </li>
                <li class="w-full flex items-center justify-center">
                    <label class="text-sm font-medium">من:</label>
                    <input type="date" name="from-date"
                        class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
                </li>
                <li class="w-full flex items-center justify-center">
                    <label class="text-sm font-medium">إلى:</label>
                    <input type="date" name="to-date"
                        class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
                </li>
            </ul>
        </form>

        <div id="displayContainer" class="overflow-y-auto max-h-[65vh] bg-white px-4 py-1 rounded-lg shadow-md"></div>
        <div id="displayContainer2" class="overflow-y-auto max-h-[65vh] bg-white px-4 py-1 rounded-lg shadow-md hidden">
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const searchTypeSelect = $('select[name="searchType"]');
            const searchInput = $('input[name="search"]');
            const radioInput = $('input[name="list-radio"]');
            const fromDate = $('input[name="from-date"]');
            const toDate = $('input[name="to-date"]');
            const displayContainer = $('#displayContainer');
            const displayContainer2 = $('#displayContainer2');
            let debounceTimeout;
            let currentPage = 1;
            let currentUrl = '';

            // دالة جلب الفواتير مع الترحيم
            function fetchInvoices(url, container, page = 1) {
                currentUrl = url;
                currentPage = page;

                $.ajax({
                    url: `${url}&page=${page}`,
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(data) {
                        container.empty();
                        if (data.purchaseInvoices && data.purchaseInvoices.length > 0) {
                            data.purchaseInvoices.forEach((purchase) => {
                                container.append(renderInvoiceCard(purchase));
                            });
                            renderPagination(data.pagination, container);
                        } else {
                            container.append(
                                '<p class="text-center text-gray-500">لا توجد فواتير لعرضها.</p>');
                        }
                        bindDynamicEvents();
                    },
                    error: function(error) {
                        console.error('Error fetching invoices:', error.responseText);
                    }
                });
            }

            // دالة إنشاء كارت الفاتورة
            function renderInvoiceCard(purchase) {
                return `
        <div id="invoice-${purchase.invoice_number}" class="mb-3 border border-gray-300 rounded-lg px-4 py-2 shadow-sm">
            <div class="flex justify-between items-center">
                <div class="text-right">
                    <div class="text-gray-700 text-right">تاريخ الفاتورة: <span class="text-sm">${purchase.formatted_date}</span></div>
                    <div class="text-gray-700 text-right">اسم ${purchase.main_account_class}: <span class="text-sm">${purchase.supplier_name}</span></div>
                </div>
                <div class="text-gray-700">فاتورة ${purchase.transaction_type}: <span class="text-sm">${purchase.Invoice_type}</span></div>
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
                            <div class="flex gap-8 space-x-2">
                                <a href="#" class="text-red-600 hover:underline show-payment" data-id="${purchase.invoice_number}" data-url="${purchase.view_url}">عرض</a>
                                <a href="#" class="text-red-600 hover:underline delete-payment" data-id="${purchase.invoice_number}" data-url="${purchase.destroy_url}">حذف</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="grid grid-cols-2">
                <div class="text-gray-700 text-right text-sm">تاريخ التحديث: <span>${purchase.updated_at}</span></div>
                <div class="text-gray-700 text-sm text-left">المستخدم: <span>${purchase.user_name}</span></div>
            </div>
        </div>`;
            }

            // دالة ربط الأحداث للعناصر الديناميكية
            function bindDynamicEvents() {
                // حدث الزر عرض/طباعة
                $(document).off('click', '.show-payment').on('click', '.show-payment', function(e) {
                    e.preventDefault();
                    let invoiceField = $(this).data('id');
                    const url = $(this).data('url');
                    window.open(url, '_blank', 'width=800,height=800');
                });

                // حدث الزر حذف
                $(document).off('click', '.delete-payment').on('click', '.delete-payment', async function(e) {
                    e.preventDefault();
                    let paymentId = $(this).data('id');
                    let url = $(this).data('url');

                    const result = await Swal.fire({
                        title: 'هل أنت متأكد أنك تريد حذف هذه الفاتورة؟',
                        text: "لن تتمكن من التراجع عن هذا الإجراء!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'نعم، احذف',
                        cancelButtonText: 'إلغاء',
                        reverseButtons: true
                    });

                    if (result.isConfirmed) {
                        try {
                            const response = await $.ajax({
                                url: url,
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content'),
                                }
                            });

                            Swal.fire({
                                title: 'تم الحذف!',
                                text: 'تم حذف الفاتورة بنجاح',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // إعادة تحميل البيانات بعد الحذف
                            if (currentUrl) {
                                fetchInvoices(currentUrl, displayContainer, currentPage);
                            }
                        } catch (xhr) {
                            Swal.fire({
                                title: 'خطأ!',
                                text: xhr.responseJSON?.message || 'حدث خطأ أثناء محاولة الحذف',
                                icon: 'error'
                            });
                        }
                    }
                });
            }

            // دالة إنشاء عناصر الترحيم
            function renderPagination(pagination, container) {
                container.next('.pagination').remove();

                const paginationContainer = $(`
            <div class="pagination flex justify-center items-center gap-2 mt-6 mb-4">
                <div class="flex items-center gap-1 bg-white p-2 rounded-lg shadow-sm border border-gray-200">
                    <!-- سيتم إضافة أزرار الصفحات هنا -->
                </div>
            </div>
        `);

                const paginationInner = paginationContainer.find('div');

                // زر السابق
                if (pagination.current_page > 1) {
                    paginationInner.append(`
                        <button class="page-btn w-8 h-8 flex items-center justify-center rounded-md bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition"
                            data-page="${pagination.current_page - 1}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        </button>
                    `);
                }

                // أزرار الصفحات
                const startPage = Math.max(1, pagination.current_page - 2);
                const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

                if (startPage > 1) {
                    paginationInner.append(`
                        <button class="page-btn w-8 h-8 flex items-center justify-center rounded-md" data-page="1">1</button>
                        ${startPage > 2 ? '<span class="px-1">...</span>' : ''}
                    `);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const active = i === pagination.current_page ? 'bg-indigo-600 text-white' :
                        'text-gray-700 hover:bg-indigo-50';
                    paginationInner.append(`
                            <button class="page-btn w-8 h-8 flex items-center justify-center rounded-md ${active}" data-page="${i}">${i}</button>
                    `);
                }

                if (endPage < pagination.last_page) {
                    paginationInner.append(`
                        ${endPage < pagination.last_page - 1 ? '<span class="px-1">...</span>' : ''}
                        <button class="page-btn w-8 h-8 flex items-center justify-center rounded-md" data-page="${pagination.last_page}">${pagination.last_page}</button>
                    `);
                }

                // زر التالي
                if (pagination.current_page < pagination.last_page) {
                    paginationInner.append(`
                        <button class="page-btn w-8 h-8 flex items-center justify-center rounded-md bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition"
                            data-page="${pagination.current_page + 1}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        </button>
                    `);
                }

                container.after(paginationContainer);
            }

            // التعامل مع تغيير الصفحات
            $(document).on('click', '.page-btn', function() {
                const page = $(this).data('page');
                fetchInvoices(currentUrl, displayContainer, page);
            });

            // البحث بالمدخل
            searchInput.on('input', function() {
                const searchQuery = searchInput.val().trim();
                clearTimeout(debounceTimeout);

                if (searchQuery !== "") {
                    displayContainer.addClass("hidden");
                    displayContainer2.removeClass("hidden");
                    debounceTimeout = setTimeout(() => {
                        const searchType = searchTypeSelect.val();
                        const FromDate = fromDate.val();
                        const ToDate = toDate.val();
                        const baseUrl = "{{ url('/api/purchase-invoices') }}";
                        const url =
                            `${baseUrl}?searchType=${searchType}&searchQuery=${searchQuery}&fromDate=${FromDate}&toDate=${ToDate}`;
                        fetchInvoices(url, displayContainer2, 1);
                    }, 500);
                } else {
                    displayContainer.removeClass("hidden");
                    displayContainer2.addClass("hidden");
                    displayContainer2.empty();
                }
            });

            // البحث بالاختيار
            radioInput.on('click', function() {
                const value = $(this).val();
                const From_Date = fromDate.val();
                const To_Date = toDate.val();
                const baseUrl = "{{ url('/api/purchase-invoices') }}";
                const url = `${baseUrl}/${value}?fromDate=${From_Date}&toDate=${To_Date}`;

                displayContainer.removeClass("hidden");
                displayContainer2.addClass("hidden");
                fetchInvoices(url, displayContainer, 1);
            });

            /*             // التحميل الأولي للبيانات
                        fetchInvoices("{{ url('/api/purchase-invoices/1') }}", displayContainer); */
        });
    </script>
@endsection
