@extends('bonds.index')
@section('bonds')
<div id="successAlert" style="display: none" class=" fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
    <p></p>
  </div>
  <br>
  {{-- <button onclick="window.history.back()">رجوع</button> --}}
  <form action="{{route('exchange.stor')}}" method="POST"  enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="page_id" class="block font-medium mb-2">رقم الصفحة</label>
@auth
@isset($dailyPage->page_id)
<input type="text" name="page_id" id="page_id" class=" rounded-md w-[10%]"  value="{{$dailyPage['page_id']}}">
@endisset


@endauth

    </div>
    <button type="submit">إنشاء صفحة جديدة</button>
</form>
  <form method="POST" id="Receip">
    @csrf
    <div class="flex gap-4">
        <div class="flex gap-4">
            @foreach ($PaymentType as $index => $item)
<div class="flex">
<label for="" class="labelSale">{{$item->label()}}</label>
<input type="radio" name="payment_type" value="{{$item->value}}"
    {{ $index === 0 ? 'checked' : '' }} required>
</div>
@endforeach


        </div>
    </div>
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
                <label for="b" class="text-center ">   جهة الدفع </label>
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
                 <label for="b" class="text-center ">  تقيد المبلغ لحساب/الدائن </label>
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
                <li class=" text-center px-1"> جهة المستفيد </li>
                <li class=" text-center px-1 ">إيداع في حساب </li>
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

<div class="flex  py-4 ">
    <div class="mx-10"  >
        <button type="submit" id="submitButton" class="px-6 py-2  bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            حفظ
        </button>
        </div>
        <div class="mx-10" id="" >
            <label for="payment_bond_id" class="text-center ">  رقم السند</label>

            <input type="text" id="payment_bond_id" name="payment_bond_id" value="">
            </div>
    </div>

    @auth
    <input type="hidden" name="daily_entries_type" value="سند صرف">
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
            url: "{{ url('/main-accounts/') }}/" + mainAccountId + "/sub-accounts", // استخدام القيم الديناميكية
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
            url: "{{ url('/main-accounts/') }}/" + mainAccountId + "/sub-accounts", // استخدام القيم الديناميكية
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
                url: '{{ route("exchange.store") }}',
                method: 'POST',
                data: formData,
                success: function(data) {
                        if(data.error){
                            $('#successAlert').show().text(data.error);
                        $('#Amount_debit').val(""); // إعادة تعيين النموذج
                        // إخفاء الرسالة بعد 3 ثوانٍ
                        setTimeout(function() {
                            $('#successAlert').hide();
                        }, 3000);

                        }else if(data.success){
                            $('#successAlert').show().show().text(data.success);
                            $('#Amount_debit').val(""); // إعادة تعيين النموذج
                            // إخفاء الرسالة بعد 3 ثوانٍ
                            setTimeout(function() {
                                $('#successAlert').hide();
                            }, 3000);
                            setTimeout(function() {
                                location.reload ();
                            }, 3000);

                        }
                        // إظهار رسالة النجاح

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
