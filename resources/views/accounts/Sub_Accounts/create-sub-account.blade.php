@extends('layout')
@section('conm')
<<<<<<< HEAD
<style>
    .alert-success {
        color: green;
        font-weight: bold;
    }
</style>
 <style>
        .mb-2 {
            margin-bottom: 1rem;
        }
        .labelSale {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .inputSale {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        #startBtn {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        #startBtn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        #status {
            margin-top: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
        }
        .listening {
            background-color: #fff3cd;
        }
        .success {
            background-color: #d4edda;
        }
        .error {
            background-color: #f8d7da;
        }
    </style>
<x-navbar_accounts/>
=======
    <style>
        .alert-success {
            color: green;
            font-weight: bold;
        }
    </style>
    <x-navbar_accounts />
>>>>>>> 6221055c4340d16216740eb43b09f91961efc219

    <br>
    <div id="" class ="rounded-lg shadow-lg bg-white">
        <h1 class="text-center  font-bold ">انشأ حساب فرعي</h1>

        <form id="SubAccount" class="p-2 md:p-5" method="POST">
            @csrf
            <div id="successMessage" class="alert-success" style="display: none;"></div>

<<<<<<< HEAD
        <div class="grid gap-4 mb-4 grid-cols-2">
           <div class="mb-2">
        <label class="labelSale" for="sub_name">اسم الحساب</label>
        <input name="sub_name" class="inputSale input-field" id="sub_name" type="text" placeholder="اسم الحساب الجديد"/>
    </div>

    <div class="button-container">
        <button id="startBtn" >التحدث لملء الحقل اسم الحساب</button>
    </div>

    <div id="status"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputField = document.getElementById('sub_name');
            const startBtn = document.getElementById('startBtn');
            const statusDiv = document.getElementById('status');
            
            // التحقق من دعم API التعرف على الصوت
            const isSpeechRecognitionSupported = 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window;
            
            if (!isSpeechRecognitionSupported) {
                startBtn.disabled = true;
                statusDiv.textContent = "عذرًا، التعرف على الصوت غير مدعوم في متصفحك الحالي. يرجى استخدام متصفح حديث مثل Chrome أو Edge.";
                statusDiv.className = 'error';
                return;
            }
            
            // تهيئة التعرف على الصوت
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new SpeechRecognition();
            recognition.lang = 'ar-SA'; // اللغة العربية - السعودية
            recognition.interimResults = false;
            
            recognition.onstart = function() {
                statusDiv.textContent = "جاري الاستماع... قل اسم الحساب الآن";
                statusDiv.className = 'listening';
                startBtn.textContent = "جاري التسجيل...";
                inputField.placeholder = "جاري الاستماع...";
            };
            
            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                inputField.value = transcript;
                statusDiv.textContent = `تم تعبئة الحقل تلقائياً بـ: "${transcript}"`;
                statusDiv.className = 'success';
                inputField.placeholder = "اسم الحساب الجديد";
            };
            
            recognition.onerror = function(event) {
                statusDiv.textContent = `حدث خطأ: ${event.error}`;
                statusDiv.className = 'error';
                startBtn.textContent = "التحدث لملء الحقل";
                inputField.placeholder = "قل اسم الحساب الجديد";
            };
            
            recognition.onend = function() {
                startBtn.textContent = "التحدث لملء الحقل";
                inputField.placeholder = "قل اسم الحساب الجديد";
            };
            
         // حدث زر البدء بالصوت
startBtn.addEventListener('click', function(event) {
    event.preventDefault();
    recognition.start();
});

// حدث الضغط على مفاتيح الاختصار (Alt + S أو Alt + س)
document.addEventListener('keydown', function(event) {
    // التحقق من Alt مع أي من الحروف التالية:
    // - s (الإنجليزية)
    // - س (العربية)
    // - keyCode 83 (S) أو 1587 (س) للتوافق مع المتصفحات القديمة
    if (event.altKey && !event.ctrlKey && !event.shiftKey && (
        event.key.toLowerCase() === 's' || 
        event.key === 'س' || 
        event.keyCode === 83 || 
        event.keyCode === 1587
    )) {
        event.preventDefault();
        recognition.start();
        
        // إضافة رسالة توضيحية للمستخدم
        const statusDiv = document.getElementById('status');
        if (statusDiv) {
            statusDiv.textContent = "تم تفعيل التسجيل الصوتي باستخدام Alt+S/س";
            statusDiv.className = 'success';
            setTimeout(() => {
                if (statusDiv.textContent.includes("Alt+S/س")) {
                    statusDiv.textContent = "جاري الاستماع... قل اسم الحساب الآن";
                    statusDiv.className = 'listening';
                }
            }, 2000);
        }
    }
});
            // تركيز على حقل الإدخال عند النقر عليه مع رسالة توجيهية
            inputField.addEventListener('focus', function(event) {
                                            event.preventDefault();
                statusDiv.textContent = "اضغط على زر 'التحدث لملء الحقل' وأذكر اسم الحساب صوتياً";
                statusDiv.className = '';
            });
        });
    </script>
            <div class="mb-2">
                <label class="labelSale" for="Main_id">الحساب الرئيسي</label>
                <select dir="ltr" class="input-field select2 inputSale" id="Main_id" name="Main_id">
=======
            <div class="grid gap-4 mb-4 grid-cols-2">
                <div class="mb-2">
                    <label class="labelSale" for="sub_name">اسم الحساب</label>
                    <input name="sub_name" class="inputSale input-field" id="sub_name" type="text"
                        placeholder="اسم الحساب الجديد" />
                </div>
                <div class="mb-2">
                    <label class="labelSale" for="Main_id">الحساب الرئيسي</label>
                    <select dir="ltr" class="input-field select2 inputSale" id="Main_id" name="Main_id">
>>>>>>> 6221055c4340d16216740eb43b09f91961efc219

                        @foreach ($main_accounts as $main_account)
                            <option value="{{ $main_account->main_account_id }}">{{ $main_account->account_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label for="Phone" class="labelSale">رقم التلفون</label>
                    <input type="number" name="Phone" id="Phone" class="input-field inputSale" />
                </div>
                <div class="mb-2">
                    <label for="name_The_known" class="labelSale">العنوان</label>
                    <input type="text" name="name_The_known" id="name_The_known" class="input-field inputSale" />
                </div>
            </div>
            <div class="  text-center grid grid-cols-2 ">
                <div class="text-center">
                    <label for="date" class="text-center">التاريخ</label>
                    <input name="date" id="date" type="date" class="inputSale"
                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
                <div class="mb-2">
                    <label class="labelSale" for="debtor_amount">رصيد افتتاحي مدين (اخذ)</label>
                    <input name="debtor_amount" class="inputSale input-field" id="debtor_amount" type="text"
                        placeholder="0" />
                </div>
                <div class="mb-2">
                    <label class="labelSale" for="creditor_amount">رصيد افتتاحي دائن (عاطي)</label>
                    <input name="creditor_amount" class="inputSale input-field" id="creditor_amount" type="text"
                        placeholder="0" />
                </div>
                <div class="  grid grid-cols-2" role="">
                    <div class=" text-center  ">
                        <label for="Currency" class=" text-center">العمله </label>
                        <select dir="ltr" id="Currency" class="inputSale select2 input-field " name="Currency">
                            @isset($currs)
                                <option selected value="{{ $currs->currency_name }}">{{ $currs->currency_name }}</option>
                            @endisset
                            @isset($curr)
                                @foreach ($curr as $cur)
                                    <option
                                        @isset($cu)
                          @selected($cur->currency_id == $cu->Currency_id)
                          @endisset
                                        value="{{ $cur->currency_name }}">{{ $cur->currency_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="text-center">
                        <label for="exchange_rate" class="text-center">سعر الصرف</label>

                        <input id="exchange_rate" class="inputSale" type="number" name="exchange_rate"
                            value="{{ 1.0 }}">
                    </div>
                </div>
                <div class="mb-2">
                    <label for="name_The_known" class="labelSale">بيان رصيد الافتتاحي</label>
                    <textarea class="inputSale" name="Statement" id="Statement" rows="3"></textarea>
                </div>

<<<<<<< HEAD
         
      
        @auth
        <input type="hidden" name="User_id" required id="User_id" value="{{Auth::user()->id}}">
        @endauth
        <div class="grid gap-4 mb-4 grid-cols-2">

        <div class="mb-2">

        <button type="submit" title=" حفظ النموذج عند الضغط على Ctrl + Shift" id="submitButton" class="text-white inline-flex items-center bgcolor hover:bg-stone-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            حفظ البيانات
        </button>
        </div> 

        <div class="mb-2">
                <label for="name_The_known"   class="labelSale">رقم الحساب</label>
                <input type="text" disabled name="name_The_known" id="name_The_known" class="input-field inputSale" />
            </div> 
            </div> 
    </form>


</div>

<div id="errorMessage" style="display: none; color: red;"></div>
=======
            </div>
>>>>>>> 6221055c4340d16216740eb43b09f91961efc219



            @auth
                <input type="hidden" name="User_id" required id="User_id" value="{{ Auth::user()->id }}">
            @endauth
            <div class="grid gap-4 mb-4 grid-cols-2">

                <div class="mb-2">

                    <button type="submit" id="submit"
                        class="text-white inline-flex items-center bgcolor hover:bg-stone-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        حفظ البيانات
                    </button>
                </div>

                <div class="mb-2">
                    <label for="name_The_known" class="labelSale">رقم الحساب</label>
                    <input type="text" disabled name="name_The_known" id="name_The_known"
                        class="input-field inputSale" />
                </div>
            </div>
        </form>

<<<<<<< HEAD
     $(document).ready(function() {
    // تهيئة النموذج
    initForm();
    
    // معالجة إرسال النموذج
    $('#submitButton').on('click', handleFormSubmit);
    
    // منع إرسال النموذج عند الضغط على Enter
    $('#ajaxForm').on('keypress', function(event) {
        if (event.which === 13) {
            event.preventDefault();
        }
    });
       $(document).on('keydown', function(event) {
        if (event.ctrlKey && event.shiftKey) {
            event.preventDefault(); // منع السلوك الافتراضي
            handleFormSubmit(event); // تنفيذ الدالة
        }
    });
});

function initForm() {
    // تهيئة القيم الافتراضية
    $('#debtor_amount').val($('#debtor_amount').val() || 0);
    $('#creditor_amount').val($('#creditor_amount').val() || 0);
    
    // إضافة معالجة للأرقام (إزالة الفواصل)
    $('#debtor_amount, #creditor_amount').on('blur', function() {
        let value = $(this).val().replace(/,/g, '');
        $(this).val(value);
    });
}

function handleFormSubmit(event) {
    event.preventDefault();
    
    // إخفاء الرسائل السابقة
    $('#successMessage').hide();
    
    // تعطيل الزر وإظهار مؤشر التحميل
    $('#submitButton').prop('disabled', true).text('جارٍ الحفظ...');
    
    // تنظيف قيم المدخلات الرقمية
    cleanNumericInputs();
    
    // إرسال البيانات
    sendFormData();
}

function cleanNumericInputs() {
    // تنظيف المدخلات الرقمية من الفواصل
    const cleanInput = (selector) => {
        let value = $(selector).val().replace(/,/g, '');
        $(selector).val(value);
    };
    
    cleanInput('#debtor_amount');
    cleanInput('#creditor_amount');
}

function sendFormData() {
    const formData = $('#SubAccount').serialize();
    const successMessage = $('#successMessage');
    const submitButton = $('#submitButton');
    
    $.ajax({
        url: '{{ route("Main_Account.storc") }}',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                showSuccess(response.message);
                resetForm();
            } else {
                showError(response.message || 'يوجد نفس هذا الاسم من قبل');
            }
        },
        error: function(xhr) {
            handleAjaxError(xhr);
        }
    });
    
    function showSuccess(message) {
        successMessage.show().text(message).removeClass('error').addClass('success');
        setTimeout(() => successMessage.hide(), 3000);
    }
    
    function showError(message) {
        successMessage.show().text(message).removeClass('success').addClass('error');
        setTimeout(() => successMessage.hide(), 8000);
        $('#sub_name').focus();
    }
    
    function resetForm() {
        // تفريغ الحقول المطلوبة
        $('#sub_name, #debtor_amount, #creditor_amount, #Phone, #name_The_known').val('');
        
        // إعادة تعيين القيم الافتراضية
        $('#debtor_amount').val(0);
        $('#creditor_amount').val(0);
        
        // التركيز على حقل الاسم
        $('#sub_name').focus();
    }
    
    function handleAjaxError(xhr) {
        let errorMessage = 'حدث خطأ ما. حاول مرة أخرى.';
        if (xhr.status === 400 && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        }
        showError(errorMessage);
    }
    
    // إعادة تمكين الزر بغض النظر عن النتيجة
    submitButton.prop('disabled', false).text('حفظ');
}
=======

    </div>

    <div id="errorMessage" style="display: none; color: red;"></div>



    <script src="{{ url('payments.js') }}"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2 مع تحديد الحد الأدنى لعدد المدخلات المطلوبة

            $('#debtor_amount,#creditor_amount').on('input', function() {
                let value = $(this).val();
                // إزالة أي شيء ليس رقمًا أو فاصلة عشرية
                value = value.replace(/[^0-9.]/g, '');
                let amountValue = $('#debtor_amount').val();
                let amountValue2 = $('#creditor_amount').val();
                amountValue = amountValue.replace(/,/g, '');
                amountValue2 = amountValue2.replace(/,/g, '');
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
                $(this).val(value);

            });
            // التركيز على حقل الاسم عند بدء التشغيل
            $('#sub_name').focus();

            // التعامل مع إرسال النموذج وحفظ البيانات باستخدام jQuery
            $('#SubAccount').on('submit', function(event) {
                event.preventDefault(); // منع تحديث الصفحة

                // إخفاء أي رسائل سابقة
                $('#successMessage').hide();

                // إضافة مؤشر تحميل
                $('#submitButton').prop('disabled', true).text('جارٍ الحفظ...');
                let creditor_amount = $('#creditor_amount').val();
                let debtor_amount = $('#debtor_amount').val();
                // تجميع بيانات النموذج
                debtor_amount = debtor_amount.replace(/,/g, ''); // إزالة جميع الفواصل
                $('#debtor_amount').val(debtor_amount);
                creditor_amount = creditor_amount.replace(/,/g, ''); // إزالة جميع الفواصل
                $('#creditor_amount').val(creditor_amount);
                var formData = $(this).serialize();

                // إرسال الطلب باستخدام AJAX
                $.ajax({
                    url: '{{ route('Main_Account.storc') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // إظهار رسالة النجاح
                            Swal.fire({
                                title: 'نجاح!',
                                text: "تم اضافة الحساب بنجاح",
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // إعادة التركيز على حقل معين
                            $('#debtor_amount').val($('#debtor_amount').val() || 0);
                            $('#creditor_amount').val($('#creditor_amount').val() || 0);

                            // تفريغ الحقول المحددة فقط
                            $('#sub_name').val(''); // تفريغ حقل الاسم
                            $('#debtor_amount').val(''); // تفريغ حقل المبلغ المدين
                            $('#creditor_amount').val(''); // تفريغ حقل المبلغ الدائن
                            $('#Phone').val(''); // تفريغ حقل الهاتف
                            $('#name_The_known').val(''); // تفريغ حقل الاسم المعروف
                            $('#sub_name').focus();

                            // تفعيل الزر مرة أخرى
                            $('#submitButton').prop('disabled', false).text('حفظ');
                        } else {
                            // إظهار رسالة خطأ عند وجود اسم مكرر
                            $('#successMessage').show().text(response.message ||
                                'يوجد نفس هذا الاسم من قبل');
                            setTimeout(function() {
                                $('#successMessage').hide();
                            }, 8000);

                            // إعادة التركيز على الحقل
                            $('#sub_name').focus();

                            // تفعيل الزر مرة أخرى
                            $('#submitButton').prop('disabled', false).text('حفظ');
                        }
                    },
                    error: function(xhr) {
                        // التعامل مع الأخطاء
                        let errorMessage = 'حدث خطأ ما. حاول مرة أخرى.';
                        if (xhr.status === 400 && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('#successMessage').show().text(errorMessage);

                        // إخفاء الرسالة بعد 8 ثوانٍ
                        setTimeout(function() {
                            $('#successMessage').hide();
                        }, 8000);

                        // تفعيل الزر مرة أخرى
                        $('#submitButton').prop('disabled', false).text('حفظ');
                    }
                });
            });

>>>>>>> 6221055c4340d16216740eb43b09f91961efc219

            // وظيفة لإضافة البيانات إلى الجدول


            // منع السلوك الافتراضي لزر Enter
            $('#ajaxForm').on('keypress', function(event) {
                if (event.which === 13) { // 13 هو كود زر Enter
                    event.preventDefault(); // منع إرسال النموذج
                }
            });


        });
    </script>

@endsection
