@extends('layout')

@section('conm')
    <x-nav-transfer-restriction />

    <div id="successMessage" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg">
    </div>

    <div id="errorMessage" class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg">
    </div>
    <!-- منطقة طباعة البيانات المحفوظة -->
    <div id="results" class="results"></div>
    <!-- عرض الرسائل إذا كانت موجودة -->
    @if (session('error'))
        <div class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-info">{{ session('success') }}</div>
    @endif
    @isset($massg)
        <div id="errorMessage">{{ $massg }}</div>
    @endisset

    <form id="dailyRestrictionsForm" method="POST" action="{{ route('transfer_restrictions.optional') }}" class="space-y">
        @csrf
        <div class="container space-y-2">
            <div class="shadow-lg grid grid-cols-3 md:grid-cols-6 rounded-lg bg-white px-1">
                <!-- طريقة الترحيل -->
                <div id="the_way_of">
                    <label for="the_way_of_deportation" class="block font-medium text-center">طريقة الترحيل</label>
                    <select name="the_way_of_deportation" id="the_way_of_deportation"
                        class="block select2 inputSale p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
                        <option value="optional">اختياري</option>
                        <option selected value="all">الكل</option>
                    </select>
                </div>

                <!-- اختيار الحسابات الرئيسية -->
                <div class="px-1" id="main_account">
                    <label for="main_account_id" class="block font-medium text-center">ترحيل</label>
                    <select name="main_account_id" id="main_account_id"
                        class="block select2 inputSale p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
                        <option value="null" selected>اختار</option>
                        @isset($mainAccounts)
                            @foreach ($mainAccounts as $mainAccount)
                                <option value="{{ $mainAccount->main_account_id }}">{{ $mainAccount->account_name }} -
                                    {{ $mainAccount->main_account_id }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <!-- اختيار الحسابات الفرعية -->
                <div id="subAccountDiv" class="mb-4 px-1">
                    <label for="sub_account_id" class="block font-medium text-center">اختار حسابات</label>
                    <select name="sub_account_id" id="sub_account_id" class="select2 inputSale" required>
                        <option value="null" selected>اختار</option>
                    </select>
                </div>

                <!-- نوع الترحيل -->
                <div class="mb-4">
                    <label for="TypeRestrictions" class="block font-medium text-center">نوع الترحيل</label>
                    <select name="TypeRestrictions" id="TypeRestrictions"
                        class="block select2 inputSale p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
                        <option selected value="1">تلقائي</option>
                        <option value="2">يوم</option>
                    </select>
                </div>

                <!-- التاريخ -->
                <div class="px-1" id="date">
                    <label for="date" class="block font-medium text-center">التاريخ</label>
                    <input type="date" name="date" id="date" class="inputSale"
                        value="{{ now()->format('Y-m-d') }}">
                </div>
                <div>
                    <button id="submitShow" type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">عرض</button>
                    <input type="button" id="saveButton" name="saveButton" value="ترحيل الكل"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{-- <button id="saveButton" name="saveButton" type="button" class="bg-yellow-500 text-white px-4 py-1 rounded">ترحيل الكل</button> --}}

                </div>
                {{-- <div>
                <button id="submitShow" type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">عرض</button>

            </div> --}}

            </div>

            <div class="shadow-lg rounded-lg py-1 bg-white border">
                <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                    <div class="flex gap-4">


                        <div>

                        </div>
                        <div>

                        </div>
                    </div>
                    @auth
                        <input type="hidden" name="User_id" value="{{ Auth::user()->id }}">
                    @endauth
                </div>
                @if (session('entries'))
                    @php
                        $entries = session('entries');
                        $mainAccount = session('mainAccount');
                        $subAccount = session('subAccount');
                        $debitAccounts = session('debitAccounts', []);
                        $creditAccounts = session('creditAccounts', []);
                    @endphp
            </div>
            <input type="hidden" name="allDailyEntrie" id="allDailyEntrie" value="1">

    </form>
    {{-- <form id="ajaxForm" >
        @csrf --}}
    {{-- </form> --}}


    <div class="w-full overflow-y-auto max-h-[80vh]   bg-white">

        <table class="w-full text-sm overflow-y-auto max-h-[80vh]">
            <thead class="bg-[#2430d3] text-white">
                <tr>
                    <th class="py-1 text-right bg-white"></th>
                    <th colspan="2" class="py-1 border text-center"> المدين /عليه</th>
                    <th colspan="2" class="py-1 border text-center"> الدائن /لة</th>
                    <th colspan="5" class="py-1 border text-center  font-bold">
                        @if ($mainAccount)
                            حساب {{ $mainAccount->account_name }}
                        @else
                            كل الحسابات
                        @endif
                        @isset($subAccount)
                            @if ($subAccount && isset($subAccount->sub_name))
                                <p> الحساب : {{ $subAccount->sub_name }}</p>
                            @else
                                <p>لا يوجد حساب فرعي محدد</p>
                            @endif
                        @endisset
                    </th>
                </tr>
                <tr>
                    <th class="py-1 border text-right">رقم القيد</th>
                    <th class="py-1 border text-right">من حساب/</th>
                    <th class="py-1 border text-right">مدين</th>
                    <th class="py-1 border text-right">الحالة</th>
                    <th class="py-1 border text-right">الى حساب/</th>
                    <th class="py-1 border text-right">دائن</th>
                    <th class="py-1 border text-right">الحالة</th>
                    <th class="py-1 border text-right">بيان الحساب</th>
                    <th class="py-1 border text-right">تاريخ القيد</th>
                    <th class="py-1 border text-right">تاريخ الانشا</th>
                    <th class="py-1 border text-right">المستخدم</th>
                    <th class="py-1 border text-right">عرض - تحرير</th>
                </tr>
            </thead>
            <tbody id="products-table">
                @if ($entries->isEmpty())
                    <tr>
                        <td colspan="10" class="py-1 border text-center">لا توجد قيود يومية</td>
                    </tr>
                @else
                    @foreach ($entries as $entry)
                        <tr>
                        <tr id="row-{{ $entry->entrie_id }}">

                            <td class="py-1 border text-right">{{ $entry->entrie_id }}</td>
                            <td class="py-1 border text-right">
                                {{ $debitAccounts[$entry->account_debit_id] ?? 'غير موجود' }}</td>
                            <td class="py-1 border text-right">{{ $entry->amount_debit }}</td>
                            <td class="py-1 border text-right">
                                <form id="ajaxForm" method="POST">
                                    @csrf
                                    <input type="hidden" name="entrie_id" id="entrie_id"
                                        value="{{ $entry->entrie_id }}">
                                    <input type="hidden" name="account_debit_id" id="account_debit_id"
                                        value="{{ $entry->account_debit_id }}">
                                    <input type="hidden" name="status_debit" id="status_debit"
                                        value="{{ $entry->status_debit }}">

                                    <button id="saveButton" name="saveButton" type="button"
                                        class="bg-yellow-500 text-white px-4 py-1 rounded"
                                        value="{{ $entry->entrie_id }}">{{ $entry->status_debit }}</button>
                                </form>
                            </td>
                            <td class="py-1 border text-right">
                                {{ $creditAccounts[$entry->account_credit_id] ?? 'غير موجود' }}</td>
                            <td class="py-1 border text-right">{{ $entry->amount_credit }}</td>
                            <td class="py-1 border text-right">
                                <form id="ajaxForm" method="POST">
                                    @csrf
                                    <input type="hidden" name="entrie_id" id="entrie_id"
                                        value="{{ $entry->entrie_id }}">
                                    <input type="hidden" name="account_Credit_id" id="account_Credit_id"
                                        value="{{ $entry->account_credit_id }}">
                                    <input type="hidden" name="status" id="status" value="{{ $entry->status }}">

                                    <button id="saveButton" name="saveButton" type="button"
                                        class="bg-yellow-500 text-white px-4 py-1 rounded"
                                        value="{{ $entry->entrie_id }}">{{ $entry->status }}</button>
                                </form>
                            </td>
                            <td class="py-1 border text-right">{{ $entry->statement }}</td>
                            <td class="py-1 border text-right">{{ $entry->daily_page_id }}</td>
                            <td class="py-1 border text-right">{{ $entry->created_at }}</td>
                            <td class="py-1 border text-right">{{ $entry->user_id }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            @endif

        </table>
        @auth
            <input type="hidden" name="User_id" required id="User_id" value="{{ Auth::user()->id }}">
        @endauth
    </div>

    <script src="{{ url('transfer_restrictions/transfer_restrictions.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2(); // تفعيل Select2
            $('#the_way_of_deportation').focus();

            $('#the_way_of_deportation').on('change', function() {
                if ($(this).val() == "optional") {
                    $('#main_account_id').addClass('focus:ring focus:ring-blue-300').focus();
                }
            });

            setTimeout(function() {
                $('#error-alert').fadeOut(300); // إخفاء الرسالة مع تأثير تلاشي خلال 300ms
            }, 4000); // الانتظار لمدة 4 ثوانٍ قبل التلاشي
        });
    </script>



    <script src="{{ url('payments.js') }}"></script>

    <style>
        .alert-success {
            color: green;
            font-weight: bold;
        }
    </style>
    <script>
        $(document).ready(function() {
            const successMessage = $('#successMessage');
            const errorMessage = $('#errorMessage');

            // التعامل مع إرسال النموذج وحفظ البيانات باستخدام AJAX
            function saveData(event) {
                event.preventDefault(); // منع تحديث الصفحة
                const form = $(event.target).closest('form'); // جلب النموذج من الزر الذي تم النقر عليه
                const formData = new FormData(form[0]); // إنشاء FormData من النموذج

                // إرسال الطلب باستخدام AJAX
                $.ajax({
                    url: '{{ route('transfer_restrictions.store') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success) {
                            // successMessage.show().text('تم الترحيل');
                            successMessage.show().text(data.success).fadeOut(3000);
                            // form[0].reset(); // تفريغ النموذج
                        } else
                        if (data.error) {
                            errorMessage.show().text(data.error).fadeOut(3000);
                        }
                    },
                    error: function(xhr) {
                        let message = 'حدث خطأ أثناء الترحيل.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage.show().text(xhr.responseJSON.error).fadeOut(3000);

                            // message += `<br>${xhr.responseJSON.error}`;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            message +=
                            `<br>${xhr.responseJSON.message}`; // إضافة المزيد من التفاصيل في حال وجودها
                        }
                        errorMessage.show().html(message);
                        setTimeout(() => {
                            errorMessage.hide();
                        }, 8000);
                    },
                    complete: function() {
                        // إعادة تفعيل الزر بعد انتهاء الطلب
                        $('#saveButton').prop('disabled', false).val(
                        'ترحيل الكل'); // إعادة النص إلى "حفظ"
                    }
                });
            }

            // التعامل مع النقر على زر الحفظ
            $(document).on('click', '#saveButton', function(event) {
                const saveButton = $(this);
                saveButton.val('جاري الترحيل');
                saveButton.prop('disabled', true);
                saveData(event); // استدعاء دالة الحفظ
            });

            // عند اختيار الحساب الرئيسي (المدين)
            $('#main_account_id').on('change', function() {
                const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (المدين)

                if (mainAccountId) {
                    // طلب AJAX لجلب الحسابات الفرعية بناءً على الحساب الرئيسي
                    SubAccount(mainAccountId);
                }
            });

            // دالة لجلب الحسابات الفرعية
            function SubAccount(mainAccountId) {
                const subAccountDiv = $('#subAccountDiv'); // الحصول على الحقل المخفي
                $('#sub_account_id').empty(); // تفريغ القائمة الفرعية

                $.ajax({
                    url: "{{ url('/main-accounts/') }}/" + mainAccountId +
                    "/sub-accounts", // استخدام القيم الديناميكية
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // تعبئة الحسابات الفرعية الجديدة
                        const subAccountOptions = data.map(subAccount =>
                            `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
                        ).join('');
                        const subAccountOption = '<option selected value="all">الكل</option>';
                        // إضافة الخيارات الجديدة إلى القائمة الفرعية
                        $('#sub_account_id').append(subAccountOption).append(subAccountOptions);
                        // إعادة تهيئة Select2 بعد إضافة الخيارات
                        $('#sub_account_id').select2('destroy').select2();
                    },
                    error: function() {
                        console.error('Error fetching sub-accounts.');
                    }
                });
            }
        });
    </script>

@endsection
