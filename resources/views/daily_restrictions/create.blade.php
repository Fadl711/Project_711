@extends('daily_restrictions.index')

@section('restrictions')
<style>
    .select2-container--default .select2-dropdown {
    max-height: 200px; /* ارتفاع القائمة */
    overflow-y: auto; /* تمكين التمرير إذا تجاوز المحتوى الارتفاع */
}
.select2-container--default .select2-selection--single {
    height: 40px; /* ارتفاع العنصر الأساسي */
    line-height: 45px; لتوسيط النص عموديًا
}
.select2-container--default .select2-selection__rendered {
    padding-top: 5px; /* تحسين النصوص */
}
</style>

<form action="{{route('daily_restrictions.stor')}}" method="POST"  enctype="multipart/form-data">
    @csrf
    <div class="">
        <label for="page_id" class="block font-medium ">رقم الصفحة</label>
@auth
@isset($dailyPage->page_id)
<input type="text" name="page_id" id="page_id" class=" rounded-md w-[10%]"  value="{{$dailyPage['page_id']}}">
@endisset


@endauth

    </div>
    <button type="submit">إنشاء صفحة جديدة</button>
</form>
<div id="successMessage" style="display: none;"></div>
<div id="errorMessage" style="display: none;"></div>

<form id="dailyRestrictionsForm" method="POST" class="space-y-6">
    @csrf
    <div class="container mx-auto  px-4">
        <!-- Title -->
        <div class="flex gap-4">
            @foreach ($PaymentType as $index => $item)
<div class="flex">
<label for="" class="labelSale">{{$item->label()}}</label>
<input type="radio" name="payment_type" value="{{$item->value}}" 
    {{ $index === 0 ? 'checked' : '' }} required>
</div>
@endforeach
        </div>
        <!-- Form Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div class="">
                <label for="Invoice_type" class="block font-medium  ">  نوع المستند</label>
                <select name="Invoice_type" dir="ltr" class=" select2 inputSale" id="Invoice_type">
                    <option value="" selected>اختر  نوع المستند</option>
                    @foreach ($transactionTypes as $transactionType)
                    <option value="{{ $transactionType->value }}">{{ $transactionType->label() }}</option>
                @endforeach
            </select>
            </div>
            <div class="">
                <label for="Invoice_id" class="block font-medium  ">  رقم المستند</label>
                <select name="Invoice_id" dir="ltr" class=" select2 inputSale" id="Invoice_id">
                    <option value="" selected>اختر  رقم المستند</option>
            </select>
                    </select>
            </div>
            </div>
            <!-- حساب المدين -->
            <div class="shadow-lg rounded-lg p-1 bg-white border">
                <h3 class=" font-semibold">المدين</h3>
                <div class="">
                    <label for="account_debit_id" class="block font-medium ">حساب المدين/الرئيسي</label>
                    <select name="account_debit_id" id="account_debit_id" dir="ltr" class="input-field   select2 inputSale" required>
                       <!-- إضافة خيارات الحسابات -->
                       @isset($mainAccounts)
                     <option value="" selected>اختر الحساب</option>
                      @foreach ($mainAccounts as $mainAccount)
                           <option value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                      @endforeach
                      @endisset
                    </select>
                </div>
                <div class="">
                    <label for="sub_account_debit_id" class="block font-medium  ">حساب المدين/الفرعي</label>
                    <select name="sub_account_debit_id" id="sub_account_debit_id" dir="ltr" class="input-field select2 inputSale" >
                        <!-- سيتم تعبئة الخيارات بناءً على الحساب الرئيسي المحدد -->
                        <option value="" selected>اختر الحساب الفرعي</option>
                        </select>
                </div>
            </div>
            <!-- حساب الدائن -->
            <div class="shadow-lg rounded-lg p-1 bg-white border">
                <h3 class=" font-semibold ">الدائن</h3>
                <div class="">
                    <label for="account_Credit_id" class="block font-medium ">حساب الدائن/الرئيسي</label>
                    <select name="account_Credit_id" id="account_Credit_id" class=" select2 inputSale" required>
                        <option value="" selected>اختر الحساب</option>

                        @isset($mainAccounts)
                       @foreach ($mainAccounts as $mainAccount)
                             <option value="{{$mainAccount->main_account_id}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                        @endforeach
                        @endisset                                             </select>
                </div>
                <div class=" ">
                    <label for="sub_account_Credit_id" class="block font-medium ">حساب الدائن/الفرعي</label>
                    <select name="sub_account_Credit_id"  step="0.01" id="sub_account_Credit_id" class="block w-full select2 p-2 border rounded-md inputSale">
                       </select>
                </div>

            </div>
        </div>
        <!-- تفاصيل إضافية -->
        <div class="shadow-lg rounded-lg p-1 bg-white border">
          <h3 class="text-lg font-semibold mb-">تفاصيل إضافية</h3>
          <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
          <div>
            <label for="Amount_debit" class="block font-medium mb-2">المبلغ المدين</label>
            <input name="Amount_debit" id="Amount_debit" type="text"  class=" inputSale input-field" placeholder="أدخل المبلغ" required>
        </div>
            <div class="">
                <label for="Currency_name" class="block font-medium mb-2">العملة</label>
                <select   dir="ltr" id="Currency_name" class="inputSale input-field " name="Currency_name"  >
                    @auth
                  @foreach ($curr as $cur)
                  <option @isset($cu)
                  @selected($cur->currency_id==$cu->Currency_id)
                  @endisset
                  value="{{$cur->currency_name}}">{{$cur->currency_name}}</option>
                   @endforeach
                   @endauth
                  </select>
            </div>
        </div>
            <div class="">
                <label for="Statement" class="block font-medium mb-2">البيان</label>
                <textarea name="Statement" id="Statement" class="block w-full p-2 border rounded-md inputSale" placeholder="أدخل البيان" rows="4" ></textarea>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">

            <div class=" justify-">
              <button type="submit" id="submitButton" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  حفظ القيد
              </button>
          </div>
          <div class=" justify-">
            <button type="submit" id="savaAndPrint" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                حفظ وطباعه
            </button>
        </div>
            </div>
            @auth

            <input type="hidden" name="User_id" value="{{ Auth::user()->id }}">
            @endauth

        </div>

    </div>
</form>
<script src="{{url('payments.js')}}">   </script>

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
            integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ",");  // إضافة الفواصل بين الآلاف
            value = decimal ? integer + '.' + decimal : integer;  // إعادة تركيب الرقم
        }
        // تعيين القيمة المعدلة للحقل
        $(this).val(value);
    });
      // التركيز على الحقل الأول عند التحميل
      $('#account_debit_id').focus();
      // إضافة مؤشر تحميل
     // إرسال النموذج باستخدام AJAX بدون تحديث الصفحة
      $(document).ready(function() {
            $('#savaAndPrint').click(function(event) {
                event.preventDefault();
                // جمع بيانات النموذج
                var formData = $('#dailyRestrictionsForm').serialize();
            // إرسال الطلب باستخدام AJAX
            $.ajax({
                url: '{{ route("daily_restrictions.saveAndPrint") }}',
                method: 'POST',
                data: formData,
                success: function(data) {
                    if (data.success) {
        // إظهار رسالة النجاح
        $('#successMessage').show().text(data.success);

        // فتح صفحة الطباعة مع البيانات
        const printUrl = '{{ route("restrictions.print", ":id") }}'.replace(':id', data.dailyEntrie.entrie_id);
        window.open(printUrl , "_blank", "width=600,height=800,left=700,top=100");
        location.reload ();
        // إخفاء الرسالة بعد 3 ثوانٍ
        setTimeout(function() {
            $('#successMessage').hide();
        }, 1000);
    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // إظهار الأخطاء عند وجود أخطاء في التحقق
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>'; // إضافة الأخطاء
                        });
                        $('#errorMessage').show().html(errorMessage);
                    } else {
                        $('#errorMessage').show().text('حدث خطأ أثناء الحفظ.');
                    }
                }
            });
        });
    });
      $(document).ready(function() {
            $('#submitButton').click(function(event) {
                event.preventDefault();
                // جمع بيانات النموذج
                var formData = $('#dailyRestrictionsForm').serialize();

            // إرسال الطلب باستخدام AJAX
            $.ajax({
                url: '{{ route("daily_restrictions.store") }}',
                method: 'POST',
                data: formData,
                success: function(data) {
                    if (data.success) {
                        // إظهار رسالة النجاح
                        $('#successMessage').show().text(data.success);
                        $('#Amount_debit').val(""); // إعادة تعيين النموذج
                        // إخفاء الرسالة بعد 3 ثوانٍ
                        setTimeout(function() {
                            $('#successMessage').hide();
                        }, 3000);
                    }else
                     {
                        $('#successMessage').show().text(data.success);
                        setTimeout(function() {
                            $('#successMessage').hide();
                        }, 3000);

                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // إظهار الأخطاء عند وجود أخطاء في التحقق
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>'; // إضافة الأخطاء
                        });
                        $('#errorMessage').show().html(errorMessage);
                    } else {
                        $('#errorMessage').show().text('حدث خطأ أثناء الحفظ.');
                    }
                }
            });
        });
    });
      // عند اختيار الحساب الرئيسي (المدين)
      $('#account_debit_id').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (المدين)

    // تفريغ القائمة الفرعية
    $('#sub_account_debit_id').empty();

    // التحقق من وجود قيمة
    if (mainAccountId) {
        // طلب AJAX لجلب الحسابات الفرعية بناءً على الحساب الرئيسي
        $.ajax({
            url: `/main-accounts/${mainAccountId}/sub-accounts`, // استخدام القيم الديناميكية
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // تعبئة الحسابات الفرعية الجديدة
                const subAccountOptions = data.map(subAccount =>
                    `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
                ).join('');

                // إضافة الخيارات الجديدة إلى القائمة الفرعية
                $('#sub_account_debit_id').append(subAccountOptions);


                // إعادة تهيئة Select2 بعد إضافة الخيارات
                $('#sub_account_debit_id').select2('destroy').select2();
            },
            error: function() {
                console.error('Error fetching sub-accounts.');
            }
        });
    }
});

      // عند اختيار الحساب الرئيسي (الدائن)
      $('#account_Credit_id').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (الدائن)

    // تفريغ القائمة الفرعية وإضافة الخيار الافتراضي
    $('#sub_account_Credit_id').empty();

    // التحقق من وجود قيمة في الحساب الرئيسي
    if (mainAccountId) {
        // طلب AJAX لجلب الحسابات الفرعية بناءً على الحساب الرئيسي
        $.ajax({
            url: `/main-accounts/${mainAccountId}/sub-accounts`, // استخدام القيم الديناميكية
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // تعبئة الحسابات الفرعية الجديدة
                const subAccountOptions = data.map(subAccount =>
                    `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
                ).join('');

                // إضافة الخيارات الجديدة إلى القائمة الفرعية
                $('#sub_account_Credit_id').append(subAccountOptions);

                // إعادة تهيئة Select2 بعد إضافة الخيارات
                $('#sub_account_Credit_id').select2('destroy').select2();
            },
            error: function() {
                console.error('Error fetching sub-accounts.');
            }
        });
    }
});




  });
  </script>


@endsection
