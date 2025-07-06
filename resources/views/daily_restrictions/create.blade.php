@extends('daily_restrictions.index')

@section('restrictions')
    <style>
        /* تحسينات التصميم */
        .select2-container--default .select2-selection--single {
            height: 40px;
            line-height: 45px;
        }

        .select2-container--default .select2-selection__rendered {
            padding-top: 5px;
        }

        /* إصلاح مشكلة الـ scroll */
        .scrollable-container {
            max-height: 65vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #c5c5c5 #f1f1f1;
        }

        .scrollable-container::-webkit-scrollbar {
            width: 8px;
        }

        .scrollable-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .scrollable-container::-webkit-scrollbar-thumb {
            background-color: #c5c5c5;
            border-radius: 10px;
        }

        .scrollable-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* تحسينات التصميم العامة */
        .bg-gray-50 {
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .input-field {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            width: 100%;
        }

        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        button {
            transition: all 0.2s ease-in-out;
        }
    </style>

    <!-- إضافة مكتبة SweetAlert -->

    <form id="dailyRestrictionsForm" method="POST" class="space-y-6">
        @csrf
        <div class="container mx-auto px-4">
            <div class="flex gap-4">
                @foreach ($PaymentType as $index => $item)
                    <div class="flex">
                        <label for="" class="labelSale">{{ $item->label() }}</label>
                        <input type="radio" name="payment_type" class=" " value="{{ $item->value }}"
                            {{ isset($DailyEntrie->invoice_type) && $DailyEntrie->invoice_type == $item->value ? 'checked' : ($index === 0 ? 'checked' : '') }}
                            required>
                    </div>
                @endforeach
            </div>
            <!-- Form Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-4 grid grid-cols-2 md:grid-cols-2 gap-4">
                    <div class="mt-3">
                        <label for="Invoice_type" class="block text-gray-700 font-medium ">نوع المستند</label>
                        <select id="Invoice_type" name="Invoice_type" dir="rtl"
                            class="select2 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sel">
                            <option value="" selected>اختر نوع المستند</option>
                            @foreach ($transactionTypes as $transactionType)
                                <option value="{{ $transactionType->value }}"
                                    @isset($DailyEntrie->daily_entries_type)
                                    @if ($DailyEntrie->daily_entries_type == $transactionType->label()) selected @endif
                                @endisset>
                                    {{ $transactionType->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="Invoice_id" class="block text-gray-700 font-medium ">رقم المستند</label>
                        <select id="Invoice_id" name="Invoice_id" dir="rtl"
                            class="select2 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sel">
                            <option value="" selected>اختر رقم المستند</option>
                            @isset($DailyEntrie->invoice_id)
                                <option value="{{ $DailyEntrie->invoice_id }}" selected>{{ $DailyEntrie->invoice_id }}</option>
                            @endisset
                        </select>
                    </div>
                </div>

                <!-- حساب المدين -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 scrollable-container">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">المدين</h3>
                    <div class="space-y-3">
                        <div>
                            <label for="account_debit_id" class="block font-medium mb-1">حساب المدين/الرئيسي</label>
                            <select name="account_debit_id" id="account_debit_id" dir="ltr" class="input-field select2"
                                required>
                                <option value="" selected>اختر الحساب</option>
                                @isset($main)
                                    @foreach ($main as $mainAccount)
                                        <option @selected($mainAccount->main_account_id == $sub_account_debit->main_id) value="{{ $mainAccount['main_account_id'] }}">
                                            {{ $mainAccount->account_name }}-{{ $mainAccount->main_account_id }}</option>
                                    @endforeach
                                @endisset
                                @isset($mainAccounts)
                                    @foreach ($mainAccounts as $mainAccount)
                                        <option value="{{ $mainAccount['main_account_id'] }}">
                                            {{ $mainAccount->account_name }}-{{ $mainAccount->main_account_id }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label for="sub_account_debit_id" class="block font-medium mb-1">حساب المدين/الفرعي</label>
                            <select name="sub_account_debit_id" id="sub_account_debit_id" dir="ltr"
                                class="input-field select2">
                                <option value="" selected>اختر الحساب الفرعي</option>
                                @isset($DailyEntrie->account_debit_id)
                                    <option value="{{ $DailyEntrie->account_debit_id }}" selected>
                                        {{ $sub_account_debit->sub_name }} </option>
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>

                <!-- حساب الدائن -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 scrollable-container">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">الدائن</h3>
                    <div class="space-y-3">
                        <div>
                            <label for="account_Credit_id" class="block font-medium mb-1">حساب الدائن/الرئيسي</label>
                            <select name="account_Credit_id" id="account_Credit_id" class="input-field select2" required>
                                <option value="" selected>اختر الحساب</option>
                                @isset($main)
                                    @foreach ($main as $mainAccount)
                                        <option @selected($mainAccount->main_account_id == $sub_account_Credit->main_id) value="{{ $mainAccount['main_account_id'] }}">
                                            {{ $mainAccount->account_name }}-{{ $mainAccount->main_account_id }}</option>
                                    @endforeach
                                @endisset
                                @isset($mainAccounts)
                                    @foreach ($mainAccounts as $mainAccount)
                                        <option value="{{ $mainAccount->main_account_id }}">
                                            {{ $mainAccount->account_name }}-{{ $mainAccount->main_account_id }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label for="sub_account_Credit_id" class="block font-medium mb-1">حساب الدائن/الفرعي</label>
                            <select name="sub_account_Credit_id" id="sub_account_Credit_id" class="input-field select2">
                                <option value="" selected>اختر الحساب الفرعي</option>
                                @isset($DailyEntrie->account_credit_id)
                                    <option value="{{ $DailyEntrie->account_credit_id }}" selected>
                                        {{ $sub_account_Credit->sub_name }} </option>
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تفاصيل إضافية -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
                <h3 class="text-lg font-semibold text-center mb-4">تفاصيل إضافية</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="Amount_debit" class="block font-medium mb-2">المبلغ المدين</label>
                        <input name="Amount_debit" id="Amount_debit" type="text" class="input-field"
                            placeholder="أدخل المبلغ"
                            value="{{ !empty($DailyEntrie->amount_debit) ? number_format($DailyEntrie->amount_debit, 0, '.', ',') : (!empty($DailyEntrie->amount_credit) ? number_format($DailyEntrie->amount_credit, 0, '.', ',') : '') }}"
                            required>
                    </div>

                    <div>
                        <label for="Currency_name" class="block font-medium mb-2">العملة</label>
                        <select dir="ltr" id="Currency_name" class="input-field" name="Currency_name">
                            @isset($currs)
                                <option selected value="{{ $currs->currency_name }} ">{{ $currs->currency_name }}</option>
                            @endisset
                            @isset($curr)
                                @foreach ($curr as $cur)
                                    <option value="{{ $cur->currency_name }}"
                                        @isset($cu)
                                            @selected($cur->currency_id == $cu->Currency_id)
                                        @endisset>
                                        {{ $cur->currency_name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div>
                        <label for="exchange_rate" class="block font-medium mb-2">سعر الصرف</label>
                        <input id="exchange_rate" class="input-field" name="exchange_rate" type="number"
                            value="{{ isset($DailyEntrie->exchange_rate) ? number_format($DailyEntrie->exchange_rate, 2, '.', ',') : 1 }}">
                    </div>
                </div>

                <div class="mt-4">
                    <label for="Statement" class="block font-medium mb-2">البيان</label>
                    <textarea name="Statement" id="Statement" class="block w-full border rounded-md p-3" rows="3"
                        placeholder="أدخل البيان هنا..." onblur="this.value = this.value.trim();">
                        @isset($DailyEntrie->statement)
{{ $DailyEntrie->statement }}
@endisset
                    </textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="flex justify-center">
                        <button type="submit" id="submitButton"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            {{ $submitButton ?? ' حفظ القيد' }}
                        </button>
                    </div>

                    <div>
                        <label for="entrie_id" class="block font-medium mb-2">رقم القيد</label>
                        <input name="entrie_id" id="entrie_id" type="number" class="input-field"
                            @isset($DailyEntrie->entrie_id)
                                value="{{ $DailyEntrie->entrie_id }}"
                            @endisset>
                    </div>

                    <div>
                        <form action="{{ route('daily_restrictions.stor') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div>
                                <label for="page_id" class="block font-medium mb-2">رقم الصفحة</label>
                                @auth
                                    @isset($dailyPage->page_id)
                                        <input type="text" name="page_id" id="page_id" class="input-field"
                                            value="{{ $dailyPage['page_id'] }}">
                                    @endisset
                                @endauth
                            </div>
                            <button type="submit"
                                class="mt-2 px-4 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                                إنشاء صفحة جديدة
                            </button>
                        </form>
                    </div>
                </div>

                @auth
                    <input type="hidden" name="User_id" value="{{ Auth::user()->id }}">
                @endauth
            </div>
        </div>
    </form>

    <script src="{{ url('payments.js') }}"></script>

    <script>
        $(document).ready(function() {
            // تفعيل Select2
            $('.select2').select2();

            // تنسيق المبالغ المالية
            $('#Amount_debit').on('input', function() {
                let value = $(this).val();
                value = value.replace(/[^0-9.]/g, '');
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                if (value) {
                    let [integer, decimal] = value.split('.');
                    integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    value = decimal ? integer + '.' + decimal : integer;
                }
                $(this).val(value);
            });

            // فتح الحقل الفرعي للمدين عند التحميل
            $('#sub_account_debit_id').select2('open');

            // منع إرسال النموذج عند الضغط على Enter
            $('#dailyRestrictionsForm').on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });

            // معالجة إرسال النموذج
            $('#submitButton').click(function(event) {
                event.preventDefault();
                const entrie_id = $('#entrie_id').val();

                // إظهار مؤشر التحميل
                Swal.fire({
                    title: 'جارٍ المعالجة',
                    html: 'يرجى الانتظار أثناء حفظ البيانات...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                var formData = $('#dailyRestrictionsForm').serialize();

                $.ajax({
                    url: '{{ route('daily_restrictions.store') }}',
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        Swal.close();

                        if (data.success) {
                            Swal.fire({
                                title: 'نجاح!',
                                text: data.success,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });

                            if (entrie_id) {
                                var invoiceField = data.entrie_id;
                                const url =
                                    `{{ route('restrictions.print', ':invoiceField') }}`
                                    .replace(':invoiceField', invoiceField);
                                window.open(url, '_blank', 'width=600,height=800');

                                setTimeout(() => {
                                    window.location.href =
                                        '{{ route('restrictions.create') }}';
                                }, 1000);
                            }

                            $('#Amount_debit').val("");
                            $('#sub_account_debit_id').select2('open');
                        } else {
                            Swal.fire({
                                title: 'خطأ!',
                                text: data.errorMessage || 'حدث خطأ غير متوقع',
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessages = '';

                            for (const field in errors) {
                                errorMessages += `${errors[field][0]}<br>`;
                                const inputField = $(`#${field}`);
                                const parentDiv = inputField.closest('div');
                                parentDiv.find('.error-message').remove();
                                inputField.addClass('border-red-500');
                                parentDiv.append(
                                    `<div class="error-message text-red-500 text-sm mt-1">${errors[field][0]}</div>`
                                );
                            }

                            Swal.fire({
                                title: 'خطأ في التحقق',
                                html: errorMessages,
                                icon: 'error',
                                timer: 5000,
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                title: 'خطأ!',
                                text: 'حدث خطأ أثناء الحفظ. يرجى المحاولة لاحقًا.',
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            });

            // إزالة رسائل الخطأ عند التعديل
            $('select, input').on('input change', function() {
                const parentDiv = $(this).closest('div');
                $(this).removeClass('border-red-500');
                parentDiv.find('.error-message').remove();
            });

            // عند اختيار الحساب الرئيسي (المدين)
            $('#account_debit_id').on('change', function() {
                $(this).select2('close');
                const mainAccountId = $(this).val();
                $('#sub_account_debit_id').empty();

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

                            $('#sub_account_debit_id').append(subAccountOptions);
                            $('#sub_account_debit_id').select2('destroy').select2();
                            $('#sub_account_debit_id').select2('open');
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

            // عند اختيار الحساب الرئيسي (الدائن)
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

            // عند تغيير نوع المستند
            $('#Invoice_type').on('change', function() {
                const Invoice_typeId = $(this).val();
                $(this).select2('close');

                if (!Invoice_typeId) {
                    return;
                }

                // إظهار مؤشر التحميل
                Swal.fire({
                    title: 'جارٍ التحميل',
                    text: 'جلب أرقام الفواتير...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                GetInvoiceNumber(Invoice_typeId);
            });
        });

        // جلب أرقام الفواتير
        function GetInvoiceNumber(Invoice_typeId) {
            const Invoice_number = $('#Invoice_id');

            if (!Invoice_typeId) {
                Swal.fire({
                    title: 'تحذير!',
                    text: 'يرجى اختيار نوع المستند أولاً.',
                    icon: 'warning',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            $.ajax({
                url: "{{ url('/invoice_purchases/') }}/" + Invoice_typeId + "/GetInvoiceNumber",
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    Swal.close();
                    Invoice_number.empty();

                    const purchase_invoice = data.map(invoice =>
                        `<option value="${invoice.purchase_invoice_id ?? invoice.sales_invoice_id}">
                            ${invoice.purchase_invoice_id ?? invoice.sales_invoice_id}
                        </option>`
                    ).join('');

                    Invoice_number.append(purchase_invoice);
                    Invoice_number.select2('open');
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء جلب أرقام الفواتير. يرجى المحاولة لاحقًا.',
                        icon: 'error',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
        }
    </script>
@endsection
