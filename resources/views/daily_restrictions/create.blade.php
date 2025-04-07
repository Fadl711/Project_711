@extends('daily_restrictions.index')

@section('restrictions')
    <style>
        .select2-container--default .select2-selection--single {
            height: 40px;
            /* ارتفاع العنصر الأساسي */
            line-height: 45px;
            لتوسيط النص عموديًا
        }

        .select2-container--default .select2-selection__rendered {
            padding-top: 5px;
            /* تحسين النصوص */
        }
    </style>


    <div id="successMessage" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg"
        role="alert"></div>
    <div id="errorMessage" class="hidden fixed top-4 right-4 bg-red-300 text-white px-6 py-4 rounded-lg shadow-lg"
        role="alert"></div>
    <form id="dailyRestrictionsForm" method="POST" class="space-y-6 ">
        @csrf
        <div class="container mx-auto  px-4">
            <div class="flex gap-4">
                @foreach ($PaymentType as $index => $item)
                    <div class="flex">
                        <label for="" class="labelSale">{{ $item->label() }}</label>
                        <input type="radio" name="payment_type" class="input-field " value="{{ $item->value }}"
                            {{isset($DailyEntrie->invoice_type) && $DailyEntrie->invoice_type == $item->value ? 'checked' : ($index === 0 ? 'checked' : '') }}
                            required>
                    </div>
                @endforeach
            </div>
            <!-- Form Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-4 grid grid-cols-2 md:grid-cols-2 gap-4">
                <div>
                    <label for="Invoice_type" class="block text-gray-700 font-medium mb-3">نوع المستند</label>
                    <select id="Invoice_type" name="Invoice_type" dir="rtl"
                        class="select2 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sel">
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
                        class="select2 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sel">
                        <option value="" selected>اختر رقم المستند</option>
                        @isset($DailyEntrie->invoice_id)
                            <option value="{{$DailyEntrie->invoice_id}}" selected>{{$DailyEntrie->invoice_id}}</option>
                        @endisset
                    </select>
                </div>
            </div>
                <!-- حساب المدين -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">المدين</h3>
                <div class="space-y-4">
                        <label for="account_debit_id" class="block font-medium ">حساب المدين/الرئيسي</label>
                        <select name="account_debit_id" id="account_debit_id" dir="ltr"
                            class="input-field   select2 inputSale" required>
                            <!-- إضافة خيارات الحسابات -->

                            @isset($main)
                                <option value="" selected>اختر الحساب</option>

                                @foreach ($main as $mainAccount)
                                    <option @selected($mainAccount->main_account_id == $sub_account_debit->main_id) value="{{ $mainAccount['main_account_id'] }}">
                                        {{ $mainAccount->account_name }}-{{ $mainAccount->main_account_id }}</option>
                                @endforeach
                            @endisset
                            @isset($mainAccounts)
                                <option value="" selected>اختر الحساب</option>

                                @foreach ($mainAccounts as $mainAccount)
                                    <option value="{{ $mainAccount['main_account_id'] }}">
                                        {{ $mainAccount->account_name }}-{{ $mainAccount->main_account_id }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="">
                        <label for="sub_account_debit_id" class="block font-medium  ">حساب المدين/الفرعي</label>
                        <select name="sub_account_debit_id" id="sub_account_debit_id" dir="ltr"
                            class="input-field select2 inputSale">
                            <option value="" selected>اختر الحساب الفرعي</option>
                            <!-- سيتم تعبئة الخيارات بناءً على الحساب الرئيسي المحدد -->
                            @isset($DailyEntrie->account_debit_id)
                                <option value="{{ $DailyEntrie->account_debit_id }}" selected>
                                    {{ $sub_account_debit->sub_name }} </option>
                            @endisset
                        </select>
                    </div>
                </div>
                <!-- حساب الدائن -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">الدائن</h3>
                <div class="space-y-4">
                        <label for="account_Credit_id" class="block font-medium ">حساب الدائن/الرئيسي</label>
                        <select name="account_Credit_id" id="account_Credit_id" class=" select2 inputSale" required>
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
                    <div class=" ">
                        <label for="sub_account_Credit_id" class="block font-medium ">حساب الدائن/الفرعي</label>
                        <select name="sub_account_Credit_id" step="0.01" id="sub_account_Credit_id"
                            class="block w-full select2 p-2 border rounded-md inputSale">
                                                        <option value="" selected>اختر الحساب الفرعي</option>
                            @isset($DailyEntrie->account_credit_id)
                                <option value="{{ $DailyEntrie->account_credit_id }}" selected>
                                    {{ $sub_account_Credit->sub_name }} </option>
                            @endisset
                        </select>
                    </div>

                </div>
            </div>
            <!-- تفاصيل إضافية -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-center ">تفاصيل إضافية</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label for="Amount_debit" class="block font-medium mb-2">المبلغ المدين</label>
                        <input name="Amount_debit" id="Amount_debit" type="text" class="inputSale input-field"
                            placeholder="أدخل المبلغ"
                            value="{{ !empty($DailyEntrie->amount_debit) ? number_format($DailyEntrie->amount_debit, 0, '.', ',') : (!empty($DailyEntrie->amount_credit) ? number_format($DailyEntrie->amount_credit, 0, '.', ',') : '') }}"
                            required>


                    </div>

                    <div class="">
                        <label for="Currency_name" class="block font-medium mb-2">العملة</label>
                        <select dir="ltr" id="Currency_name" class="inputSale input-field" name="Currency_name">
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
                    <div class="text-center">
                        <label for="exchange_rate" class="text-center">سعر الصرف</label>

                        <input id="exchange_rate" class="inputSale input-field" name="exchange_rate" type="number"
                            value="{{ isset($DailyEntrie->exchange_rate) ? number_format($DailyEntrie->exchange_rate, 2, '.', ',') : 1 }}">

                    </div>
                </div>
                <div class="">
                    <label for="Statement" class="block font-medium mb-2">البيان</label>
                    <textarea name="Statement" id="Statement" class="block w-full border rounded-md p-2" rows="4"
                        placeholder="أدخل البيان هنا..." onblur="this.value = this.value.trim();">
                @isset($DailyEntrie->statement)
{{ $DailyEntrie->statement }}
@endisset
            </textarea>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                    <div class=" justify-">
                        <button type="submit" id="submitButton"
                            class="px-6  m-2 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">

                            {{ $submitButton ?? ' حفظ القيد' }}
                        </button>
                    </div>

                    <div class="">
                        <label for="entrie_id" class="block font-medium mb-2">رقم القيد</label>
                        <input name="entrie_id" id="entrie_id" type="number" class=" inputSale input-field"
                            @isset($DailyEntrie->entrie_id)
                value="{{ $DailyEntrie->entrie_id }}"
                @endisset>
                    </div>
                    <div class="">

                        <form action="{{ route('daily_restrictions.stor') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="">
                                <label for="page_id" class="block font-medium ">رقم الصفحة</label>
                                @auth
                                    @isset($dailyPage->page_id)
                                        <input type="text" name="page_id" id="page_id" class=" rounded-md "
                                            value="{{ $dailyPage['page_id'] }}">
                                    @endisset


                                @endauth

                            </div>
                            <button type="submit">إنشاء صفحة جديدة</button>
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
            $('#Amount_debit').on('input', function() {
                let value = $(this).val();
                // إزالة أي شيء ليس رقماً أو فاصلة عشرية
                value = value.replace(/[^0-9.]/g, '');
                // التأكد من أن الفاصلة العشرية تظهر مرة واحدة فقط
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                // إضافة الفاصلة بعد كل ثلاثة أرقام (فصل الآلاف)
                if (value) {
                    let [integer, decimal] = value.split('.');
                    integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ","); // إضافة الفواصل بين الآلاف
                    value = decimal ? integer + '.' + decimal : integer; // إعادة تركيب الرقم
                }
                // تعيين القيمة المعدلة للحقل
                $(this).val(value);
            });
            $('#sub_account_debit_id').select2('open'); // التركيز على الحقل الأول عند التحميل
            $(document).ready(function() {
                const inputs = $('.input-field'); // تحديد جميع الحقول
                const form = $('#dailyRestrictionsForm');
                form.on('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
                    }
                });
                $('#submitButton').click(function(event) {
                    const entrie_id = $('#entrie_id')
                .val(); // الحصول على ID الحساب الرئيسي (المدين)


                    event.preventDefault();
                    // جمع بيانات النموذج
                    var formData = $('#dailyRestrictionsForm').serialize();

                    // إرسال الطلب باستخدام AJAX
                    $.ajax({
                        url: '{{ route('daily_restrictions.store') }}',
                        method: 'POST',
                        data: formData,
                        success: function(data) {
                            if (data.success) {
                                // إظهار رسالة النجاح
                                $('#successMessage').show().text(data.success).fadeOut(
                                    3000);
                                if (entrie_id) {
                                    var invoiceField = data.entrie_id;
                                    const url =
                                        `{{ route('restrictions.print', ':invoiceField') }}`
                                        .replace(':invoiceField', invoiceField);
                                    window.open(url, '_blank',
                                    'width=600,height=800'); // فتح الرابط في نافذة جديدة
                                    window.location.href =
                                        '{{ route('restrictions.create') }}'; // توجيه المستخدم إلى صفحة "إنشاء"
                                }
                                $('#Amount_debit').val(""); // إعادة تعيين النموذج
                                $('#sub_account_debit_id').select2('open');

                                // إخفاء الرسالة بعد 3 ثوانٍ
                                setTimeout(function() {


                                    $('#successMessage').hide();
                                }, 3000);
                            } else

                            {

                                // إخفاء التنبيه بعد 3 ثوانٍ
                                $('#errorMessage').show().text(data.errorMessage)
                                    .fadeOut(3000);


                            }
                        },


                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    const inputField = $(`#${field}`);
                                    const parentDiv = inputField.closest('div');
                                    parentDiv.find('.error-message').remove();
                                    inputField.addClass('is-invalid');
                                    parentDiv.append(
                                        `<div class="error-message text-danger text-sm">${errors[field][0]}</div>`
                                        );
                                }
                            } else {
                                $('#errorMessage').show().text('حدث خطأ أثناء الحفظ.')
                                    .fadeOut(3000);
                            }
                        }
                    });
                });
                $('select, input').on('input change', function() {
                    const parentDiv = $(this).closest('div');
                    $(this).removeClass('is-invalid');
                    parentDiv.find('.error-message').remove();
                });
            });
            // عند اختيار الحساب الرئيسي (المدين)
            $('#account_debit_id').on('change', function() {
                $(this).select2('close');
                const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (المدين)
                // تفريغ القائمة الفرعية
                $('#sub_account_debit_id').empty();
                // التحقق من وجود قيمة
                if (mainAccountId) {
                    // طلب AJAX لجلب الحسابات الفرعية بناءً على الحساب الرئيسي
                    $.ajax({
                        url: '{{ route('sub-accounts', ':mainAccountId') }}'.replace(
                            ':mainAccountId', mainAccountId), // استخدام القيم الديناميكية
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            // تعبئة الحسابات الفرعية الجديدة
                            const subAccountOptions = data.map(subAccount =>
                                `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
                            ).join('');
                            // إضافة الخيارات الجديدة إلى القائمة الفرعية
                            $('#sub_account_debit_id').append(subAccountOptions);
                            // إعادة تهيئة Select2 بعد إضافة الخيارات
                            $('#sub_account_debit_id').select2('destroy').select2();
                            $('#sub_account_debit_id').select2('open');

                        },
                        error: function() {
                            console.error('Error fetching sub-accounts.');
                        }
                    });
                }
            });

            $('#account_Credit_id').on('change', function() {
                const mainAccountId = $(this).val();
                $('#sub_account_Credit_id').empty();
                $('#account_Credit_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
                // الحصول على ID الحساب الرئيسي
                showAccounts(mainAccountId);

            });
            $('#sub_account_debit_id').on('change', function() {
                $('#account_Credit_id').select2('open'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
            });
            $('#sub_account_Credit_id').on('change', function() {
                // $('#Amount_debit').input('focus');
                let Amount_debit = $('#Amount_debit');
                $('#Amount_debit').focus();
                Amount_debit.focus();
                Amount_debit.addClass('focus');
            });
            // عند اختيار الحساب الرئيسي (الدائن)
            function showAccounts(mainAccountId) {
                if (mainAccountId) {
                    // طلب AJAX لجلب الحسابات الفرعية بناءً على الحساب الرئيسي
                    $.ajax({
                        url: '{{ route('sub-accounts', ':mainAccountId') }}'.replace(':mainAccountId',
                            mainAccountId), // استخدام القيم الديناميكية
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            const subAccountOptions = data.map(subAccount =>
                                `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
                            ).join(''); // تعبئة الحسابات الفرعية الجديدة
                            // إضافة الخيارات الجديدة إلى القائمة الفرعية
                            $('#sub_account_Credit_id').append(subAccountOptions);
                            $('#sub_account_Credit_id').select2('destroy')
                        .select2(); // إعادة تهيئة Select2 بعد إضافة الخيارات
                            $('#sub_account_Credit_id').select2('open');

                        },
                        error: function() {
                            console.error('Error fetching sub-accounts.');
                        }
                    });
                }
            }
        });

        function toggleLoading(state) {
            if (state) {
                $('#submitButton').prop('disabled', true).text('جارٍ الحفظ...');
            } else {
                $('#submitButton').prop('disabled', false).text('حفظ القيد');
            }
        }
        $(document).ready(function() {
            $('.select2').select2(); // تفعيل Select2
        });
        $('#Invoice_type').on('change', function() {
            const Invoice_typeId = $(this).val(); // الحصول على معرف التصنيف المحدد
            $(this).select2('close');
            if (!Invoice_typeId) {
                console.warn('لم يتم اختيار تصنيف.');
                return; // إنهاء التنفيذ إذا لم يتم اختيار تصنيف
            }
            // استدعاء الدالة لجلب المنتج بناءً على التصنيف
            GetInvoiceNumber(Invoice_typeId);
            setTimeout(() => {
                $('#Invoice_type').select2('close'); // إغلاق القائمة المنسدلة بعد التأخير
            }, 1000);
            setTimeout(function() {
                console.log('Focused on Quantity'); // للتأكد من التركيز
            }, 100); // تأخير 100 مللي ثانية
        });

        function GetInvoiceNumber(Invoice_typeId) {
            const Invoice_number = $('#Invoice_id'); // حقل سعر البيع
            if (!Invoice_typeId) {
                alert('يرجى اختيار التصنيف.');
                return;
            }
            // إرسال طلب AJAX إذا كان التصنيف صالحًا
            $.ajax({
                url: "{{ url('/invoice_purchases/') }}/" + Invoice_typeId + "/GetInvoiceNumber",
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // التحقق إذا كانت البيانات تحتوي على سعر البيع
                    Invoice_number.empty();
                    const purchase_invoice = data.map(invoice =>
                        `<option value="${invoice.purchase_invoice_id ??invoice.sales_invoice_id}">${invoice.purchase_invoice_id??invoice.sales_invoice_id}</option>`
                    ).join('');

                    // إضافة الخيارات الجديدة إلى القائمة الفرعية
                    Invoice_number.append(purchase_invoice);
                    //   Invoice_number.select2('destroy').select2();

                },
                error: function(xhr) {
                    // التعامل مع الأخطاء
                    console.error('حدث خطأ :', xhr.responseText);
                    // تنبيه المستخدم بخطأ واضح
                    alert('حدث خطأ. يرجى المحاولة لاحقًا.');
                }
            });
        }
    </script>


@endsection
