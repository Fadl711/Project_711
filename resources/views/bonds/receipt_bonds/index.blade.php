@extends('bonds.index')
@section('bonds')

  <br>
  {{-- <button onclick="window.history.back()">رجوع</button> --}}

  <form method="POST" id="Receip">
    @csrf

  <div class="flex container  shadow-md py-4 px-2 bg-white">
        <div class="w-[40%] max-sm:w-[30%]">
            <div class=" text-center">
                  <label for="date" class=" text-center ">التاريخ </label>
                <input name="date" type="date" class="inputSale" placeholder="505,550">
            </div>

       </div>
        <div class="text-gray-700  px-2">

        </div>
        <div class="text-gray-700 w-[50%] max-sm:w-[70%] ">
            <div class="  text-center  ">
                <div class="flex  " role="">
                    <div  class=" text-center  ">
                        <label for="" class=" text-center" >العمله </label>
                        <select   dir="ltr" id="Currency" class="inputSale input-field " name="Currency"  >
                            @auth
                          @foreach ($curr as $cur)
                          <option @isset($cu)
                          @selected($cur->currency_id==$cu->Currency_id)
                          @endisset
                          value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
                           @endforeach
                           @endauth
                          </select>
                       </div>
                       <div  class=" text-center  ">
                        <label for="" class=" text-center" >الصرف </label>
                        <input id="maont" type="number" class="inputSale px-1" placeholder="505,550" required>
                       </div>

                <div class="">
                <label for="" class=" text-center " >المبلغ </label>
                <input name="Amount_debit" id="maont" type="number" class="inputSale px-1" placeholder="505,550" required>
                </div>

                </div>
            </div>
            <div class=" text-center ">
                <label for="b" class="text-center ">  حساب القبض </label>
                <select name="AccountReceivable" id="AccountReceivable" dir="ltr" class="input-field  select2 inputSale" required>
                    <!-- إضافة خيارات الحسابات -->
                    @isset($mainAccounts)
                  <option value="" selected>اختر الحساب</option>
                   @foreach ($mainAccounts as $mainAccount)
                        <option value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                   @endforeach
                   @endisset
                 </select>
            </div>
            <div class="text-center ">
                 <label for="b" class="text-center ">  إيداع في حساب </label>
                 <select name="DepositAccount" id="DepositAccount" dir="ltr" class="input-field select2 inputSale" >
                    <!-- سيتم تعبئة الخيارات بناءً على الحساب الرئيسي المحدد -->
                    <option value="" selected>اختر الحساب الفرعي</option>
                    </select>
            </div>
        </div>
    </div>

<br>
<div class="shadow-md p-4 bg-white">
    <ul class="space-y-2  ">
        <li>
            <ul class="grid grid-cols-3 w-full ">
                <li class=" text-center px-1"> جهة الدفع </li>
                <li class=" text-center px-1 ">تقيد المبلغ لحساب /الدائن </li>
                <li class=" text-center px-1">البيان</li>
            </ul>
            <ul class="grid grid-cols-3  w-full py-1">
                <li class="text-center">
                    <select name="PaymentParty" id="PaymentParty" class=" select2 inputSale" required>
                    <option value="" selected>اختر الحساب</option>
                        @isset($mainAccounts)
                            @foreach ($mainAccounts as $mainAccount)
                                <option value="{{$mainAccount->main_account_id}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                            @endforeach
                        @endisset
                    </select>
                    </li>
                <li class=" text-center px-1">
                    <select name="CreditAmount"  step="0.01" id="CreditAmount" class="block w-full select2 p-2 border rounded-md inputSale">
                    </select>
                </li>
                <li class=" text-center">
                       <textarea class="inputSale" name="Statement" id="Statement" cols="30" rows="1"></textarea>
                </li>
            </ul>
        </li>
     </ul>
    </div>

<div class="flex place-content-center py-4 ">
    <div class="mx-10"  >
        <button type="submit" id="submitButton" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            حفظ القيد
        </button>
        </div>
        <div class="mx-10" id="newInvoice" >
            <button type="button"  class="text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">
                             الغاء الحساب
                  </button>
            </div>
    </div>

    @auth

    <input type="hidden" name="User_id" value="{{ Auth::user()->id }}">
    @endauth
</form>

<script>

  $(document).ready(function() {
    $('#PaymentParty').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (الدائن)

    // تفريغ القائمة الفرعية وإضافة الخيار الافتراضي
    $('#CreditAmount').empty();

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
                $('#CreditAmount').append(subAccountOptions);

                // إعادة تهيئة Select2 بعد إضافة الخيارات
                $('#CreditAmount').select2('destroy').select2();
            },
            error: function() {
                console.error('Error fetching sub-accounts.');
            }
        });
    }
});
      // تفعيل Select2
      $('.select2').select2();
      $('#AccountReceivable').focus();

          $('#AccountReceivable').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (المدين)

    // تفريغ القائمة الفرعية
    $('#DepositAccount').empty();

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
                $('#DepositAccount').append(subAccountOptions);


                // إعادة تهيئة Select2 بعد إضافة الخيارات
                $('#DepositAccount').select2('destroy').select2();
            },
            error: function() {
                console.error('Error fetching sub-accounts.');
            }
        });
    }
});
});




$(document).ready(function() {
            $('#submitButton').click(function(event) {
                event.preventDefault();
                // جمع بيانات النموذج
                var formData = $('#Receip').serialize();

            // إرسال الطلب باستخدام AJAX
            $.ajax({
                url: '{{ route("Receip.store") }}',
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

    function datainvoices(){
        var maont = document.getElementById('crud-modal').value;
    }

    function invoices() {




         return {
             printInvoice() {
                // var modal = document.getElementById('crud-modal');
                //  modal.classList.remove('hidden');
                 var printContents = document.getElementById('js-print-template').innerHTML;
                 var originalContents = document.body.innerHTML;
                 document.body.innerHTML = printContents;
                 window.onafterprint = function() {
                     document.body.innerHTML = originalContents;
                     window.focus();
                     window.location.reload(); // reload the page after printing

                     // Add this line to close the modal window
                     document.getElementById('crud-modal').classList.add('hidden');
                 };
                 window.print();
             }
         }
     }
    </script>
    @endsection
