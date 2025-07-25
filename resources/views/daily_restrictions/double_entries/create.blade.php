@extends('daily_restrictions.index')

@section('restrictions')

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
            width: 400px;
            /* ارتفاع العنصر الأساسي */

            max-width: 400px;
            /* ارتفاع القائمة */
        }

        .select2-container--default .select2-selection--single {
            height: 40px;
            /* ارتفاع العنصر الأساسي */
            line-height: 45px;

        }

        .select2-container--default .select2-selection__rendered {
            padding-top: 5px;
            /* تحسين النصوص */
        }
    </style>
    <div id="successMessage" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg"
        role="alert">
        <p class="font-bold">تم بنجاح!</p>
    </div>
    <div id="errorMessage" style="display: none;" class="alert alert-danger"></div>
    <div id="successMessage" style="display: none;" class="alert alert-success"></div>
    <div class="px-2 bg-white rounded-xl shadow-md">
        <div class="flex">
            <div class="w-full py-2">
                <form id="invoicePurchases" action="{{ route('double_entry.storeOrUpdate') }}" method="POST">
                    @csrf
                    <input type="hidden" id="saveData_debit_id2" name="saveData_debit_id2">

                    <!-- نوع الحساب -->
                    <div class="flex flex-wrap items-center gap-4 mb-2 text-sm text-gray-700">
                        <span class="font-medium">نوع الحساب:</span>
                        <label class="flex items-center gap-1">
                            <input id="typeAcouunt" type="radio" name="typeAccount" value="دائن" required
                                class="text-blue-600 focus:ring-blue-500 h-4 w-4">
                            <span>دائن / عاطي</span>
                        </label>
                        <label class="flex items-center gap-1">
                            <input id="typeAcouunt" type="radio" name="typeAccount" value="مدين" required
                                class="text-blue-600 focus:ring-blue-500 h-4 w-4">
                            <span>مدين / آخذ</span>
                        </label>
                    </div>

                    <!-- الحقول -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 items-end text-sm">
                        <!-- الحساب الرئيسي -->
                        <div>
                            <label id="main_account_debit_id_lab" for="main_account_debit_id"
                                class="block text-gray-600 mb-1 font-medium">اختار
                                حساب</label>
                            <select name="main_account_debit_id" id="main_account_debit_id" required
                                class="select2 w-full rounded-md border-gray-300 focus:ring-blue-500 text-sm">
                                <option value="">اختر الحساب</option>
                                @foreach ($main_accounts as $mainAccount)
                                    <option value="{{ $mainAccount->main_account_id }}">
                                        {{ $mainAccount->account_name }} - {{ $mainAccount->main_account_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- الحساب الفرعي -->
                        <div>
                            <label id="sub_account_debit_id_lab" for="sub_account_debit_id"
                                class="block text-gray-600 mb-1 font-medium">تحديد
                                الحساب</label>
                            <select name="sub_account_debit_id" id="sub_account_debit_id" required
                                class="select2 w-full rounded-md border-gray-300 focus:ring-blue-500 text-sm">
                                <option value="">اختر الحساب الفرعي</option>
                            </select>
                        </div>

                        <!-- البيان -->
                        <div>
                            <label for="Statement1" class="block text-gray-600   font-medium text-center">البيان</label>
                            <textarea name="Statement" id="Statement1" rows="1"
                                class="w-full border rounded-md text-sm p-2 resize-none focus:ring-blue-500 focus:border-blue-500 translate-y-1"></textarea>
                        </div>

                        <!-- زر الإرسال -->
                        <div id="newInvoice1" class="w-full">
                            <button id="newInvoice" type="submit"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-md shadow transition duration-200 text-sm">
                                إضافة قيد مزدوج
                            </button>
                        </div>
                    </div>

                    @auth
                        <input type="hidden" name="User_id" id="User_id" value="{{ Auth::user()->id }}">
                    @endauth
                </form>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-4 p-4">
        <!-- النموذج -->

        <form id="ajaxForm" class="bg-white shadow-xl rounded-2xl p-6 space-y-3">
            @csrf

            <!-- نوع الدفع -->
            <div class="flex flex-wrap gap-4">
                @foreach ($PaymentType as $index => $item)
                    <label class="flex items-center space-x-2 text-gray-700">
                        <input type="radio" name="Payment_type" value="{{ $item->value }}"
                            {{ $index === 0 ? 'checked' : '' }} required
                            class="h-4 w-4 ml-1 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span>{{ $item->label() }}</span>
                    </label>
                @endforeach
            </div>
            <!-- حساب الدائن -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-3">
                <h3 id="h3Account" class="text-lg font-bold text-gray-800 mb-4"></h3>

                <input type="hidden" id="fadl" name="sub_account_debit_id">
                <input type="hidden" id="saveData_debit_id" name="saveData_debit_id">
                <input type="hidden" id="sub_account_type" name="sub_account_type">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- الحساب الرئيسي -->
                    <div>
                        <label id="account_Credit_id_label" for="account_Credit_id"
                            class="block text-gray-600 font-medium mb-1">الحساب الرئيسي</label>
                        <select name="account_Credit_id" id="account_Credit_id" class="select2 w-full" required>
                            <option value="" selected>اختر الحساب</option>
                            @foreach ($main_accounts as $main_account)
                                <option value="{{ $main_account->main_account_id }}">
                                    {{ $main_account->account_name }} - {{ $main_account->main_account_id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الحساب الفرعي -->
                    <div>
                        <label id="sub_account_Credit_id_label" for="sub_account_Credit_id"
                            class="block text-gray-600 font-medium mb-1">الحساب الفرعي</label>
                        <select name="sub_account_Credit_id" id="sub_account_Credit_id" class="select2 w-full">
                            <option value="" selected>اختر الحساب الفرعي</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- العملة وسعر الصرف والمبلغ -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- العملة -->
                <div>
                    <label for="Currency_name" class="block text-gray-700 font-medium mb-2">العملة</label>
                    <select dir="ltr" id="Currency_name" name="Currency_name"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @isset($Currency_name)
                            @foreach ($Currency_name as $cur)
                                <option value="{{ $cur->currency_name }}">{{ $cur->currency_name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <!-- سعر الصرف -->
                <div>
                    <label for="exchange_rate" class="block text-gray-700 font-medium mb-2">سعر الصرف</label>
                    <input id="exchange_rate" name="exchange_rate" type="number"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        value="{{ isset($DailyEntrie->exchange_rate) ? number_format($DailyEntrie->exchange_rate, 2, '.', ',') : 1 }}">
                </div>

                <!-- المبلغ -->
                <div>
                    <label for="Amount_debit" class="block text-gray-700 font-medium mb-2">المبلغ المدين</label>
                    <input name="Amount_debit" id="Amount_debit" type="number"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="أدخل المبلغ"
                        value="{{ !empty($DailyEntrie->amount_debit) ? number_format($DailyEntrie->amount_debit, 0, '.', ',') : (!empty($DailyEntrie->amount_credit) ? number_format($DailyEntrie->amount_credit, 0, '.', ',') : '') }}"
                        required>
                </div>
            </div>

            <!-- البيان -->
            <div>
                <label for="Statement" class="block text-gray-700 font-medium mb-2">البيان</label>
                <textarea name="Statement" id="Statement" rows="3"
                    class="w-full rounded-lg border border-gray-300 shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="أدخل البيان هنا..."></textarea>
            </div>

            <!-- زر الحفظ -->
            <div class="flex gap-x-2">
                <div>

                    <label class="inline" for="">رقم القيد</label>
                    <input class="w-1/2 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       name="entrie_id" id="entrie_id" type="number">
                </div>
                <button type="submit"
                    class="inline-block px-8 py-3 bg-emerald-600 hover:bg-emerald-700	 text-white text-lg font-semibold rounded-lg shadow-md transition duration-200"
                    id="saveButton">
                    إضافة القيد
                </button>

            </div>

            @auth
                <input type="hidden" name="User_id" value="{{ Auth::user()->id }}" />
            @endauth
        </form>


        <div class="container mx-auto  " id="mainAccountsTable">
            <div class="w-full overflow-y-auto max-h-[80vh]  bg-white">
                <table id="mainAccountsTable" class="w-full mb-4 text-sm">
                    <thead>
                        <tr class="bg-blue-100">
                            <th class=" px-2 py-1  tagTd">م</th>
                            <th class=" px-2 py-1  tagTd">اسم الحساب</th>
                            <th class=" px-2 py-1  tagTd">اسم الحساب</th>
                            <th class=" px-2 py-1  tagTd"> البيان</th>
                            <th class=" px-2 py-1  tagTd"> العملة</th>
                            <th class=" px-2 py-1  tagTd">المبلغ</th>
                            <th class=" px-2 py-1  tagTd"></th>
                            <th class=" px-2 py-1  tagTd "></th>
                        </tr>
                    </thead>
                    <tbody>
                </table>
            </div>
            <button onclick="openInvoiceWindow(event)"
                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح السند </button>
            <script>
                function openInvoiceWindow(e) {
                    const successMessage = $('#successMessage');
                    const sub_account_debit_id = $('#saveData_debit_id').val() // الحصول على قيمة حقل رقم الفاتورة
                    if (sub_account_debit_id) {
                        e.preventDefault(); // منع تحديث الصفحة
                        const url = `{{ route('double_entry.show', ':invoiceField') }}`.replace(':invoiceField',
                            sub_account_debit_id); // استبدال القيمة في الرابط

                        window.open(url, '_blank', 'width=600,height=800'); // فتح الرابط مع استبدال القيمة
                    } else {

                        successMessage.text('لا توجد فاتورة').show();
                        setTimeout(() => {
                            successMessage.hide();
                        }, 3000);
                    }

                }
            </script>
            {{--  <button onclick="openAndPrintInvoice2(event)"
                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح وطباعة الفاتورة</button>
            <div id="successMessage" style="display:none;" class="text-red-500 font-semibold mt-2"></div>
        </div> --}}

            {{--  <script>
            function openAndPrintInvoice2(e) {
                const successMessage = $('#successMessage');
                const sub_account_debit_id = $('#saveData_debit_id').val() // الحصول على قيمة حقل رقم الفاتورة
                if (sub_account_debit_id) {
                    e.preventDefault(); // منع تحديث الصفحة
                    const url = `{{ route('double_entry.show', ':invoiceField') }}`.replace(':invoiceField',
                        sub_account_debit_id); // استبدال القيمة في الرابط
                    // فتح الرابط في نافذة جديدة
                    const newWindow = window.open(url, '_blank', 'width=600,height=800');

                    // التأكد من أن النافذة فتحت بنجاح
                    if (newWindow) {
                        newWindow.onload = function() {
                            setTimeout(() => {
                                newWindow.print(); // طباعة المحتوى بعد تحميله
                                newWindow.close(); // إغلاق النافذة بعد الطباعة
                            }, 1000); // تأخير بسيط للسماح بتحميل المحتوى
                        };
                    } else {
                        successMessage.text('تعذر فتح النافذة. يرجى التحقق من إعدادات المتصفح.').show();
                        setTimeout(() => {
                            successMessage.hide(); // إخفاء الرسالة بعد 3 ثوانٍ
                        }, 3000);
                    }
                } else {
                    successMessage.text('لا توجد فاتورة').show(); // عرض الرسالة
                    setTimeout(() => {
                        successMessage.hide(); // إخفاء الرسالة بعد 3 ثوانٍ
                    }, 3000);
                }
            }
        </script> --}}
            <script>
                window.editData = function(id) {
                    console.log(id);
                    $.ajax({
                        url: "{{ url('/double_entry_edit/') }}/" + id,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        // استدعاء API بناءً على product_id
                        success: function(DailyEntrie) {
                            $('#entrie_id').val(DailyEntrie.entrie_id);
                            $('#sub_account_Credit_id').val(DailyEntrie.account_credit_id);
                            $('#Currency_name').val(DailyEntrie.currency_name);
                            $('#Amount_debit').val(DailyEntrie.amount_debit);
                            $('#exchange_rate').val(DailyEntrie.Selling_price);
                            $('#Statement').val(DailyEntrie.statement)
                        },
                        error: function(xhr, status, error) {
                            // console.error("خطأ في جلب بيانات التعديل:", error);
                            errorMessage.show().text(data.message);
                            setTimeout(() => {
                                errorMessage.hide();
                            }, 5000);
                        }
                    });
                }
                $(function() {
                    const form = $('#invoicePurchases'),
                        submitButton = $('#newInvoice'),
                        saveData_debit_id = $('#saveData_debit_id'),
                        fadl = $('#fadl'),
                        sub_account_type = $('#sub_account_type'),
                        successMessage = $('#successMessage'),
                        errorMessage = $('#errorMessage'),
                        transaction_type = $('.transaction_type'),
                        invoiceField = $('#purchase_invoice_id'),
                        supplierField = $('#supplier_name'),
                        main_accountdebit = $('.main_accountdebit'),
                        sub_account_debit_id = $('.sub_account_debit_id'),
                        supplier_id = $('.supplier_id'),
                        product_id = $('.product_id'),
                        csrfToken = $('input[name="_token"]').val();
                    $(document).ready(function() {
                        // نستخدم [name="typeAccount"] لأن الـ radio group يعتمد على الاسم وليس الـ id
                        $('input[name="typeAccount"]').on('change', function() {
                            const type = $(this).val(); // الحصول على قيمة الـ radio المحدد
                            const label = $('#main_account_debit_id_lab');
                            const label1 = $('#sub_account_debit_id_lab');

                            label.text(''); // مسح النص أولًا
                            label1.text(''); // مسح النص أولًا

                            if (type === "مدين") {
                                label.text('اختار حساب/المدين الرئيسي');
                                label1.text('اختار حساب/المدين الفرعي');
                            } else if (type === "دائن") {
                                label.text('اختار حساب/الدائن الرئيسي');
                                label1.text('اختار حساب/الدائن الفرعي');
                            }
                        });
                    });

                    submitButton.click(function(e) {
                        e.preventDefault();
                        submitButton.prop('disabled', true).text('جاري الإرسال...');
                        // إخفاء الرسائل السابقة
                        successMessage.hide();
                        errorMessage.hide();
                        const formData = new FormData(form[0]);

                        $.ajax({
                                url: '{{ route('double_entry.storeOrUpdate') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: formData,
                                processData: false,
                                contentType: false,
                            })

                            .done(function(Double_entry) {
                                if (Double_entry.account_id) {
                                    console.log(Double_entry);
                                    if (saveData_debit_id.val() == null || saveData_debit_id.val() == "") {
                                        var message = "تم إنشاء قيد مزدوج بنجاح";
                                        $('#account_Credit_id').select2('open');
                                    } else {
                                        var message = "تم تحديث القيد المزدوج بنجاح";
                                    }
                                    // تحديث الحقول وإظهار رسالة النجاح
                                    saveData_debit_id.val(Double_entry.id);
                                    $('#saveData_debit_id2').val(Double_entry.id);
                                    fadl.val(Double_entry.account_id);
                                    sub_account_type.val(Double_entry.account_type);
                                    $('#account_Credit_id_label').text(
                                        `حساب ${Double_entry.account_type == "دائن" ? 'مدين' : "دائن"} / الرئيسي`
                                    );
                                    $('#sub_account_Credit_id_label').text(
                                        `حساب ${Double_entry.account_type == "دائن" ? 'مدين' : "دائن"} /الفرعي`
                                    );
                                    $('#h3Account').text(Double_entry.account_type == "دائن" ? 'مدين' : "دائن");

                                    Swal.fire({
                                        position: "top-start",
                                        icon: "success",
                                        title: message,
                                        showConfirmButton: false,
                                        timer: 1000
                                    });

                                } else {
                                    errorMessage.text(response.message || 'حدث خطأ غير معروف.').show();
                                }
                            })
                            .fail(function(xhr) {
                                if (xhr.status === 422) {
                                    const errors = xhr.responseJSON.errors;
                                    const firstErrorField = Object.keys(errors)[0];
                                    const firstErrorMessage = errors[firstErrorField][0];

                                    // إظهار الرسالة مع اسم الحقل
                                    errorMessage.html(`<strong>${firstErrorMessage}</strong>`).show();

                                    // تسليط الضوء على الحقل الخاطئ
                                    const errorField = $(`[name="${firstErrorField}"]`);
                                    errorField.focus();

                                    // فتح `select2` إذا كان الحقل من نوع `select2`
                                    if (errorField.hasClass('select2')) {
                                        errorField.select2('open');
                                    }
                                } else {
                                    errorMessage.text('حدث خطأ أثناء إرسال الطلب. حاول مرة أخرى لاحقاً.')
                                        .show();
                                }
                            })
                            .always(function() {
                                submitButton.prop('disabled', false).text('تعديل القيد مزدوج');
                            });
                    });
                });
            </script>

            <script>
                $('#account_Credit_id').on('change', function() {
                    const mainAccountId = $(this).val();
                    $('#sub_account_Credit_id').empty();
                    $('#account_Credit_id').select2('close');

                    if (mainAccountId) {
                        $.ajax({
                            url: '{{ route('sub-accounts', ':mainAccountId') }}'.replace(
                                ':mainAccountId', mainAccountId),
                            type: 'GET',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                const subAccountOptions = data.map(subAccount =>
                                    `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
                                ).join('');

                                $('#sub_account_Credit_id').append(subAccountOptions);
                                $('#sub_account_Credit_id').select2('destroy').select2();
                                $('#sub_account_Credit_id').select2('open');
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'خطأ!',
                                    text: 'حدث خطأ أثناء جلب الحسابات الفرعية.',
                                    icon: 'error',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });

                // التنقل بين الحقول
                $('#sub_account_debit_id').on('change', function() {
                    $('#account_Credit_id').select2('open');
                });

                $('#sub_account_Credit_id').on('change', function() {
                    $('#Amount_debit').focus();
                });
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: 'اختر عنصر',
                        allowClear: true
                    });

                    function CsrfToken() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    }
                    $(document).on('click', '.delete-payment', async function(e) {
                        e.preventDefault();
                        var successMessage = $('#successMessage'); // الرسالة الناجحة
                        var errorMessage = $('#errorMessage'); // الرسالة الخطأ
                        const Total_invoice = $('#Total_invoice'); // إجمالي الفاتورة

                        let paymentId = $(this).data('id');
                        // let url = `/purchases/${paymentId}`; // تصحيح مسار الحذف

                        const result = await Swal.fire({
                            title: 'هل انت متاكد من حذف هذا القيد ',
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
                                url: "{{ url('/double_entry_delete/') }}/" +
                                    paymentId, // استدعاء API بناءً على product_id
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                success: function(response) {
                                    if (response.status === 'success') {
                                        Swal.fire({
                                            position: "top-start",
                                            title: 'تم الحذف!',
                                            text: response.data ??
                                                'تمت عملية الحذف بنجاح',
                                            icon: 'success',
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                        // إخفاء السند من الواجهة
                                        $('#row-' + paymentId).fadeOut(); // إخفاء الصف
                                        // تحديث إجمالي الفاتورة
                                    } else {
                                        errorMessage.text(response.message ||
                                                'حدث خطأ أثناء الحذف.')
                                            .show();
                                        setTimeout(() => errorMessage.hide(), 3000);
                                    }
                                },
                                error: function(error) {
                                    console.error('Error deleting payment bond:', error
                                        .responseText);
                                    alert('حدث خطأ أثناء الحذف. يرجى المحاولة لاحقًا.');
                                }
                            });
                        }
                    });
                    $('#account_debitid').on('change', function() {
                        $(this).select2('close');
                        $('#product_id').select2('open');
                    });
                    $('#Supplier_id').on('change', function() {
                        const receipt_number = $('#Receipt_number');
                        $('#Receipt_number').focus(); // تركيز المؤشر على الحقل
                        $('#Supplier_id').select2('close');
                    });
                    const Product_name = $('#product_name');
                    const form = $('#ajaxForm');
                    const inputs = $('.input-field'); // تحديد جميع الحقول
                    const selectedPaymentType = $('input[name="Payment_type"]');
                    selectedPaymentType.focus();
                    form.on('keydown', function(event) {
                        if (event.key === 'Enter') {
                            event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
                        }
                    });
                    // استدعاء وظيفة الحفظ عند الضغط على زر +

                    $('#saveButton').click(function() {
                        saveData(event); // استدعاء دالة الحفظ
                    });
                    $(document).on('keydown', function(event) {
                        if (event.key === '+') {
                            event.preventDefault();
                            saveData(event); // استدعاء دالة الحفظ
                        }
                    });

                    function saveData(event) {
                        event.preventDefault(); // منع تحديث الصفحة

                        const form = $('#ajaxForm'); // تخزين العنصر في متغير
                        const formData = new FormData(form[0]);

                        const selectedPaymentType = $('input[name="Payment_type"]:checked').val();
                        formData.append('Payment_type', selectedPaymentType ||
                            ''); // إضافة القيمة المختارة أو قيمة فارغة إذا لم يتم اختيار شيء
                        $.ajax({
                            url: '{{ route('double_entry.store') }}', // المسار الخاص بك
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false, // ضروري مع FormData
                            contentType: false, // ضروري مع FormData
                            success: function(data) {
                                if (data.success) {
                                    $('#errorMessage').hide(); // تأكد من وجود عنصر بهذا المعرف
                                    $('#successMessage').removeClass('hidden').text(data.success);

                                    // إخفاء التنبيه بعد 3 ثوانٍ
                                    setTimeout(() => {
                                        $('#successMessage').addClass('hidden');
                                    }, 3000);

                                    addToTable(data.dailyEntrie);
                                    emptyData();
                                } else {
                                    // إظهار رسالة عند وجود نفس الاسم
                                    $('#errorMessage').show().text(data.message);
                                    setTimeout(() => {
                                        $('#errorMessage').hide();
                                    }, 5000);
                                }
                            },
                            error: function(xhr) {
                                const errorMsg = xhr.responseJSON ? xhr.responseJSON.message :
                                    'حدث خطأ أثناء الإرسال.';
                                $('#errorMessage').show().text(errorMsg);
                                setTimeout(() => {
                                    $('#errorMessage').hide();
                                }, 8000);
                                $('#Product_name').focus(); // تأكد من وجود عنصر بهذا المعرف
                            }
                        });
                    }

                    // تأكد من إضافة حدث `keydown` على النموذج
                    $('#ajaxForm').on('keydown', function(event) {
                        if (event.key === 'Enter') {
                            event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
                        }
                    });



                    function displayPurchases(sales) {
                        let uniqueInvoices = new Set(); // Set لتخزين الفواتير الفريدة
                        let rows = ''; // متغير لتخزين الصفوف
                        $('#mainAccountsTable tbody').empty(); // تنظيف الجدول
                        sales.forEach(function(purchase) {
                            // إضافة شرط للتأكد من عدم تكرار البيانات
                            if (!uniqueInvoices.has(purchase.purchase_id)) {
                                uniqueInvoices.add(purchase.purchase_id);
                                rows += `
                <tr id="row-${purchase.purchase_id}">
                    <td class="text-right tagTd">${purchase.Barcode}</td>
                    <td class="text-right tagTd">${purchase.Product_name}</td>
                    <td class="text-right tagTd">${purchase.categorie_id}</td>
                    <td class="text-right tagTd">${purchase.quantity}</td>
                    <td class="text-right tagTd">${purchase.Purchase_price}</td>
                    <td class="text-right tagTd">${purchase.Cost}</td>
                    <td class="text-right tagTd">${purchase.warehouse_to_id}</td>
                    <td class="text-right tagTd">${purchase.Total}</td>
                    <td class="flex">
                        <button class="" onclick="editData(${purchase.purchase_id})">
                                           <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
</svg>
                        </button>
<a href="#" class="text-red-600 hover:underline delete-payment" data-id="${purchase.purchase_id}" >حذف</a>

                    </td>
                </tr>
            `;
                            }
                        });
                        $('#mainAccountsTable tbody').append(rows);
                    }

                    function addToTable(account) {
                        const rowId = `#row-${account.entrie_id}`;
                        const tableBody = $('#mainAccountsTable tbody');
                        console.log(account)
                        // التحقق مما إذا كان الصف موجودًا بالفعل
                        if ($(rowId).length) {
                            // تحديث الصف إذا كان موجود
                            $(`${rowId} td:nth-child(1)`).text(account.entrie_id);
                            $(`${rowId} td:nth-child(2)`).text(account.credit_account.sub_name || '--');
                            $(`${rowId} td:nth-child(3)`).text(account.debit_account.sub_name || '--');
                            $(`${rowId} td:nth-child(4)`).text(account.amount_credit || '0');
                            $(`${rowId} td:nth-child(5)`).text(account.statement || '');
                        } else {
                            // إنشاء صف جديد
                            const newRow = `
            <tr id="row-${account.entrie_id}">
                <td class="text-right tagTd">${account.entrie_id}</td>
                <td class="text-right tagTd">${account.credit_account?.sub_name|| '--'}</td>
                <td class="text-right tagTd">${account.debit_account?.sub_name || '--'}</td>
                <td class="text-right tagTd">${account.currency_name}</td>
                <td class="text-right tagTd">${account.amount_credit}</td>
                <td class="text-right tagTd">${account.statement}</td>
                                <td class="flex">
                    <button class="edit-btn" onclick="editData(${account.entrie_id})">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                        </svg>
                    </button>
                    <a href="#" class="text-red-600 hover:underline delete-payment" data-id="${account.entrie_id}" >حذف</a>

                </td>
            </tr>
        `;
                            tableBody.append(newRow);
                        }
                    }

                    function CsrfToken() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    }

                    $(document).on('click', '#delete_invoice', function(e) {
                        e.preventDefault();
                        const invoiceId = $('#purchase_invoice_id').val(); // الحصول على معرف الفاتورة من الحقل
                        if (!invoiceId) {
                            $('#errorMessage').show().text('لم يتم العثور على معرف الفاتورة.');
                            setTimeout(() => {
                                $('#errorMessage').hide();
                            }, 5000);
                            return;
                        }
                        // تأكيد الحذف
                        if (!confirm('هل أنت متأكد من حذف الفاتورة وجميع المشتريات المرتبطة بها؟')) {
                            return;
                        }
                        // إرسال طلب الحذف باستخدام Ajax
                        $.ajax({
                            url: "{{ url('/purchase-invoices/') }}/" + invoiceId, // مسار الحذف
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    successMessage.show().text(response.message);
                                    setTimeout(() => {
                                        successMessage.hide();
                                        window.location
                                            .reload(); // إعادة تحميل الصفحة بعد إخفاء الرسالة
                                    }, 5000);
                                } else {
                                    $('#errorMessage').show().text(response.message);
                                    setTimeout(() => {
                                        $('#errorMessage').hide();
                                    }, 5000);
                                }
                            },
                            error: function(xhr) {
                                $('#errorMessage').show().text(xhr.responseJSON.message);
                                setTimeout(() => {
                                    $('#errorMessage').hide();
                                }, 5000);
                            }
                        });
                    });
                });

                $('#account_debitid').on('change', function() {
                    $(this).select2('close');
                    $('#product_id').select2('open');
                });

                $('#main_account_debit_id').on('change', function() {
                    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي
                    showAccounts(mainAccountId);
                    setTimeout(() => {
                        $('#main_account_debit_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
                        $('#sub_account_debit_id').select2('open');
                    }, 1000);

                });

                function showAccounts(mainAccountId) {
                    if (mainAccountId) {
                        var sub_account_debit_id = $('#sub_account_debit_id');
                    }

                    if (mainAccountId !== null) {

                        $.ajax({
                            url: "{{ url('/main-accounts/') }}/" + mainAccountId +
                                "/sub-accounts", // استخدام القيم الديناميكية

                            type: 'GET',
                            dataType: 'json',

                            success: function(data) {
                                sub_account_debit_id.empty();
                                const subAccountOptions = data.map(subAccount =>
                                    `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
                                ).join('');

                                // إضافة الخيارات الجديدة إلى القائمة الفرعية
                                sub_account_debit_id.append(subAccountOptions);
                                sub_account_debit_id.select2('destroy').select2();

                                // إعادة تهيئة Select2 بعد إضافة الخيارات
                            },
                            error: function(xhr) {
                                console.error('حدث خطأ في الحصول على الحسابات الفرعية.', xhr.responseText);
                            }
                        });
                    };
                }

                function emptyData() {
                    $('#Statement').val('');
                    $('#Amount_debit').val('');
                    $('#entrie_id').val('');
                    $('#sub_account_Credit_id').select2('open');
                };
            </script>
            {{-- <script src="{{url('purchases/purchases.js')}}"></script> --}}
            {{-- <script src="{{ url('purchases.js') }}"></script> --}}

            <style>
                .alert-success {
                    color: green;
                    font-weight: bold;
                }

                .alert-errorMessage {
                    color: rgb(212, 50, 50);
                    font-weight: bold;
                }
            </style>


        @endsection
