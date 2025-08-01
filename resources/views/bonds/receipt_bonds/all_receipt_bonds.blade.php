@extends('bonds.index')
@section('tital')
    {{ 'gamal' }}
@endsection
@section('bonds')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* تثبيت الأرقام بالإنجليزية */
        .english-numbers {
            font-feature-settings: 'tnum';
            direction: ltr;
            unicode-bidi: plaintext;
        }

        td {
            text-align: right;
        }

        .select2-container--default .select2-dropdown {
            max-height: 200px;
            /* ارتفاع القائمة */
            overflow-y: auto;
            /* تمكين التمرير إذا تجاوز المحتوى الارتفاع */
        }

        .select2-container--default .select2-selection--single {
            height: 40px;
            /* ارتفاع العنصر الأساسي */
            line-height: 45px;
            /* لتوسيط النص عموديًا */
        }

        .select2-container--default .select2-selection__rendered {
            padding-top: 5px;
            /* تحسين النصوص */
        }
    </style>

    <div class="container mx-auto ">
        <!-- Search and Filter Section -->
        <div class="bg-white p-1 shadow-lg rounded-lg flex flex-wrap gap-2 justify-between items-center mb-1">
            <div class="flex flex-wrap gap-4 items-center w-full sm:w-auto">
                <select name="searchType"
                    class="border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500 sel">
                    <option selected value="كل السندات">كل السندات</option>
                    <option value="أول سند">أول سند</option>
                    <option value="آخر سند">آخر سند</option>
                </select>
                <input type="search" name="search"
                    class="border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500"
                    placeholder="بحث">
            </div>

            <select name="transactionType"
                class="border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500  sel">
                <option selected value="سند قبض">سند قبض</option>
                <option value="سند صرف">سند صرف</option>
            </select>

            {{-- <button
                class="response-payment bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                استرجع سندات الصرف البيانات
            </button> --}}
        </div>

        <!-- Date Filter Section -->
        <form class="bg-white p-1 rounded-lg shadow-md">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-center">
                <label class="text-sm font-medium text-center">عرض حسب</label>

                <div class="flex items-center justify-center">
                    <input type="radio" name="list-radio" value="1" class="mr-2"> تلقائي
                </div>
                <div class="flex items-center justify-center">
                    <input type="radio" name="list-radio" value="2" class="mr-2"> اليوم
                </div>
                <div class="flex items-center justify-center">
                    <input type="radio" name="list-radio" value="3" class="mr-2"> هذا الأسبوع
                </div>
                <div class="flex items-center justify-center">
                    <input type="radio" name="list-radio" value="4" class="mr-2"> هذا الشهر
                </div>
                <div class="flex items-center justify-center">
                    <input type="radio" name="list-radio" value="5" class="mr-2"> حسب التاريخ
                </div>

                <div class="flex items-center justify-center">
                    <label class="text-sm font-medium">من:</label>
                    <input type="date" name="from-Date"
                        class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="flex items-center justify-center">
                    <label class="text-sm font-medium">إلى:</label>
                    <input type="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="to-Date"
                        class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </form>
    </div>

    <div id="displayContainer" class="overflow-y-auto max-h-[80vh] bg-white px-4 py-1  ">
    </div>
    <div id="displayContainer2" class="overflow-y-auto max-h-[80vh] bg-white px-4 py-1  ">

    </div>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        $(document).ready(function() {
            const searchTypeSelect = $('select[name="searchType"]');
            const transactiontypeeSelect = $('select[name="transactionType"]');
            const searchInput = $('input[name="search"]');
            const radioInput = $('input[name="list-radio"]');
            const toDate = $('input[name="to-Date"]');
            const fromDate = $('input[name="from-Date"]');
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
                    success: function(data) {
                        container.empty();
                        if (data.PaymentInvoices.length > 0) {
                            data.PaymentInvoices.forEach((PaymentBond) => {
                                container.append(renderInvoiceCard(PaymentBond));
                            });
                        } else {
                            container.append(
                                '<p class="text-center text-gray-500">لا توجد فواتير لعرضها.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching invoices:', error.responseText);
                    }
                });
            }

            function fetchInvoice(url, container) {
                $.ajax({
                    url: url,
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(data) {
                        container.empty();
                        if (data.PaymentInvoices.length > 0) {
                            data.PaymentInvoices.forEach((PaymentBond) => {
                                container.append(renderInvoiceCard(PaymentBond));
                            });
                        } else {
                            container.append(
                                '<p class="text-center text-gray-500">لا توجد فواتير لعرضها.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching invoices:', error.responseText);
                    }
                });
            }

            // إنشاء كارت عرض الفاتورة
            function renderInvoiceCard(invoice) {
                return `
        <div class="mb-2 border border-black rounded-lg px-2 py-2  max-w-full" id="invoice-${invoice.payment_bond_id}">
            <div class="bg-white border border-gray-300 rounded-lg  p-2 mb-1">
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
            $(document).on('click', '.show-payment', function(e) {
                e.preventDefault();

                let invoiceField = $(this).data('id');
                const url = `{{ route('receip.print', ':invoiceField') }}`.replace(':invoiceField',
                    invoiceField);
                window.open(url, '_blank', 'width=600,height=800'); // فتح الرابط في نافذة جديدة
            });

            // دالة لحذف السند باستخدام AJAX بعد التأكيد
            $(document).on('click', '.delete-payment', async function(e) {
                e.preventDefault();

                let paymentId = $(this).data('id');
                let url = $(this).data('url');
                const result = await Swal.fire({
                    title: 'هل أنت متأكد من الحذف؟',
                    text: "لن تتمكن من التراجع!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء'
                });
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'تم الحذف!',
                                    text: response.data ??
                                        'تمت عملية الحذف بنجاح',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                // إخفاء السند من الواجهة
                                $('#invoice-' + paymentId)
                                    .fadeOut(); // يمكن استخدام fadeOut لإخفاء العنصر مع تأثير } else {
                            }
                        },
                        error: function(error) {
                            console.error('Error deleting payment bond:', error
                                .responseText);
                            Swal.fire('خطأ', error, 'error');
                        }
                    });
                }
            });





            // البحث بالنص
            searchInput.on('input', function() {
                const searchQuery = searchInput.val().trim();
                clearTimeout(debounceTimeout);


                if (searchQuery !== "") {
                    displayContainer.addClass("hidden");
                    displayContainer2.removeClass("hidden");

                    debounceTimeout = setTimeout(() => {
                        const searchType = searchTypeSelect.val();
                        const From_Date = fromDate.val();
                        const To_Date = toDate.val();
                        const transactionType = transactiontypeeSelect.val();
                        const baseUrl = "{{ url('/api/Receip-invoices') }}";
                        const url =
                            `${baseUrl}?searchType=${searchType}&transactionType=${transactionType}&searchQuery=${searchQuery}&fromDate=${From_Date}&toDate=${To_Date}`;

                        fetchInvoices(url, displayContainer2);
                    }, 500);
                } else {
                    displayContainer.removeClass("hidden");
                    displayContainer2.addClass("hidden");
                    displayContainer2.empty();
                }
            });


            // البحث بالإعدادات الأخرى
            radioInput.on('click', function() {
                const filterType = $(this).val();
                const transactionType = transactiontypeeSelect.val();
                const FromDate = fromDate.val();
                const ToDate = toDate.val();
                const baseUrl = "{{ url('/api/Receip-invoices') }}";
                const url =
                    `${baseUrl}/${filterType}?transactionType=${transactionType}&fromDate=${FromDate}&toDate=${ToDate}`;

                // استخدام url في طلب AJAX
                // $.ajax({
                //     url: url,
                //     method: 'GET',
                //     // المعاملات الأخرى...
                // });
                displayContainer.removeClass("hidden");

                fetchInvoices(url, displayContainer);
            });
            $(document).on('click', '.response-payment', function() {
                const url = "{{ url('/store/storeUp') }}";

                //   $.ajax({
                //     url: url,
                //     method: 'post',
                //     // المعاملات الأخرى...
                // });
                displayContainer.removeClass("hidden");

                fetchInvoice(url, displayContainer);
                // });

            });



        });
    </script>
@endsection
