@extends('bonds.index')
@section('bonds')
<div id="successAlert" style="display: none" class=" fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
    <p></p>
  </div>
<div id="errorMessage" style="display: none" class=" fixed top-4 right-4 bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
    <p></p>
  </div>


  <form method="POST" id="Receip">
    @csrf
    <div class="flex gap-4">
        <div class="flex gap-4">
            @foreach ($PaymentType as $index => $item)
                <div class="flex items-center gap-2">
                    <label for="payment_type_{{ $index }}" class="labelSale">
                        {{ $item->label() }}
                    </label>
                    <input
                        type="radio"
                        name="payment_type"
                        id="payment_type_{{ $index }}"
                        value="{{ $item->value }}"
                        {{ isset($ExchangeBond->payment_type) && $ExchangeBond->payment_type == $item->value ? 'checked' : ($index === 0 ? 'checked' : '') }}
                        required
                    >
                </div>
            @endforeach
        </div>

        </div>
    </div>
    <div class="overflow-y-auto max-h-[80vh] bg-white px-4 py-1 rounded-lg shadow-md">
  <div class="flex container  shadow-md py-4 px-2 bg-white"  >
        <div class="w-[40%] max-sm:w-[30%]">
            <div>
                <label for="transaction_type" class="labelSale">نوع العملية</label>
                <select dir="ltr" id="transaction_type" class="inputSale select2 input-field" name="transaction_type">
                    @isset($ExchangeBond->transaction_type)
                    <option value="{{$ExchangeBond->transaction_type}}" > {{$ExchangeBond->transaction_type}} </option>
                      @endisset
                 <option value="سند قبض"> سند قبض</option>
                 <option value="سند صرف">سند صرف</option>
                </select>
            </div>
            <div class="text-center">
                <label for="date" class="text-center">التاريخ</label>
                <input
                    name="date"
                    type="date"
                    class="inputSale"
                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                    @isset($ExchangeBond->created_at)
                        value="{{ \Carbon\Carbon::parse($ExchangeBond->created_at)->format('Y-m-d') }}"
                    @endisset
                >
            </div>

       </div>
        <div class="text-gray-700  px-2">

        </div>
        <div class="text-gray-700 w-[50%] max-sm:w-[70%] ">
            <div class="  text-center  ">
                <div class="flex  " role="">
                    <div  class=" text-center  ">
                        <label for="Currency" class=" text-center" >العمله </label>
                        <select   dir="ltr" id="Currency" class="inputSale select2 input-field " name="Currency"  >
                            @isset($currs)
                            <option selected value="{{$currs->currency_id}}">{{$currs->currency_name}}</option>
                            @endisset
                            @isset($curr)

                          @foreach ($curr as $cur)
                          <option @isset($cu)
                          @selected($cur->currency_id==$cu->Currency_id)
                          @endisset
                          value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
                           @endforeach
                           @endisset
                        </select>
                       </div>


                       <div class="">
                        <label for="Amount_debit" class="text-center">المبلغ</label>
                        <input 
                            name="Amount_debit" 
                            id="Amount_debit" 
                            type="text" 
                            @isset($ExchangeBond->Amount_debit)
                                value="{{ number_format($ExchangeBond->Amount_debit, 2, '.', ',') }}"  
                            @endisset 
                            class="inputSale px-1" 
                            placeholder="0" 
                            required 
                            onblur="formatCurrency(this)">
                    </div>
                </div>
            </div>
            <div class=" text-center ">
                <label for="AccountReceivable" class="text-center ">  حساب القبض </label>
                <select name="AccountReceivable" id="AccountReceivable" dir="ltr" class="input-field  select2 inputSale" required>
                    <!-- إضافة خيارات الحسابات -->
                    @isset($mainAccounts)
                  <option value="" selected>اختر الحساب</option>
                   @foreach ($mainAccounts as $mainAccount)
                        <option @isset($ExchangeBond->Main_debit_account_id)
                             @selected($ExchangeBond->Main_debit_account_id==$mainAccount->main_account_id) value="{{$mainAccount['main_account_id']}}"

                        @endisset value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                   @endforeach
                   @endisset
                 </select>
            </div>
            <div class="text-center ">
                 <label for="DepositAccount" class="text-center ">  إيداع في حساب </label>
                 <select name="DepositAccount" id="DepositAccount" dir="ltr" class="input-field select2 inputSale" >
                    @isset($Debitsub_account_id)
                    <option  value="{{$Debitsub_account_id->sub_account_id}}">{{$Debitsub_account_id->sub_name}}</option>
                   @endisset
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
                    <option value="" selected>اختر الحساب</option>
                     @foreach ($mainAccounts as $mainAccount)
                          <option @isset($ExchangeBond->Main_Credit_account_id)
                               @selected($ExchangeBond->Main_Credit_account_id==$mainAccount->main_account_id) value="{{$mainAccount['main_account_id']}}"

                          @endisset value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                     @endforeach
                     @endisset
                    </select>
                    </li>
                <li class=" text-center px-1">
                    <select name="CreditAmount"  id="CreditAmount" class="block w-full select2 p-2 border rounded-md inputSale">
                         @isset($Creditsub_account_id)
                    <option  value="{{$Creditsub_account_id->sub_account_id}}">{{$Creditsub_account_id->sub_name}}</option>
                   @endisset
                    </select>
                </li>
                <li class="text-center">
                    <textarea
                        class="inputSale"
                        name="Statement"
                        id="Statement"
                        rows="3"
                    >@isset($ExchangeBond->Statement){{ $ExchangeBond->Statement }}@endisset</textarea>
                </li>

            </ul>
        </li>
     </ul>
    </div>

<div class="flex  py-4 ">
    <div class="mx-10"  >
        <input type="submit" id="submitButton"  @isset($submitButton) value="{{ $submitButton }}" @endisset class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"  value="حفظ" >

        </div>
        {{-- <div class="mx-10" id="newInvoice" >
            <button type="reset"  class="text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">
                             الغاء السند
                  </button>
            </div> --}}
            <div class="mx-10" id="" >
                <label for="payment_bond_id" class="text-center ">  رقم السند</label>
                <input type="text" id="payment_bond_id" name="payment_bond_id" @isset($ExchangeBond->payment_bond_id)value="{{$ExchangeBond->payment_bond_id}}"
                @endisset>
                </div>
    </div>




</div>
@auth
    <input type="hidden" name="User_id" value="{{ Auth::user()->id }}">
    @endauth

</form>
</div>

<script>

  $(document).ready(function() {
    $('#Amount_debit').on('input', function() {
        let value = $(this).val();
    // إزالة أي شيء ليس رقمًا أو فاصلة عشرية
    value = value.replace(/[^0-9.]/g, '');
    let amountValue = $('#Amount_debit').val();
    amountValue = amountValue.replace(/,/g, '');
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
            url:"{{ url('/main-accounts/') }}/" + mainAccountId + "/sub-accounts", // استخدام القيم الديناميكية
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
      const CreditAmount=  $('#CreditAmount').val(); 
      const DepositAccount=  $('#DepositAccount').val(); 
      let Amount_debit = $('#Amount_debit').val();
      Amount_debit = Amount_debit.replace(/,/g, ''); // إزالة جميع الفواصل
        $('#Amount_debit').val(Amount_debit);
      // تعيين القيمة المعدلة للحقل
        var buttonValue = $(this).val(); // الحصول على قيمة الزر الذي تم الضغط عليه
          if(Amount_debit<0)
          {
            $('#errorMessage').show().text('يجب ان يكون المبلغ موجب').fadeOut(3000);
          }
          if(Amount_debit==0)
          {
            $('#errorMessage').show().text('يجب ان يكون المبلغ اكبر من الصفر').fadeOut(5000);

          }
          if(!DepositAccount)
          {
            $('#errorMessage').show().text(' الدخل حساب  ').fadeOut(3000);
            $('#DepositAccount').select2('open');
          }
          else{
          if(!CreditAmount)
          {
            $('#errorMessage').show().text(' الدخل حساب الدائن الفرعي').fadeOut(3000);
            $('#CreditAmount').select2('open');
          }
        }
          
         
        // جمع بيانات النموذج
        var formData = $('#Receip').serialize();
        if(Amount_debit>0 && CreditAmount && DepositAccount)
        {
          
        // إرسال الطلب باستخدام AJAX
        $.ajax({
            url: '{{ route("Receip.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.error) {
                    // عرض رسالة الخطأ
                    $('#successAlert').show().text(response.error);
                    $('#Amount_debit').val(''); // إعادة تعيين الحقل
                    setTimeout(function() {
                        $('#successAlert').hide();
                    }, 3000);
                } else if (response.success) {
                    // عرض رسالة النجاح
                    $('#successAlert').show().text(response.success);
                    $('#Amount_debit').val(''); // إعادة تعيين الحقل
                    setTimeout(function() {
                        $('#successAlert').hide();
                    }, 3000);
                    $('#Receip')[0].reset();  // إفراغ النموذج

                    // تحديد الإجراء بناءً على قيمة الزر
                    var invoiceField = response.payment_bond_id;
                    const url = `{{ route('receip.print', ':invoiceField') }}`.replace(':invoiceField', invoiceField);
                            window.open(url, '_blank', 'width=600,height=800'); // فتح الرابط في نافذة جديدة

                            window.location.href = '{{ route("Receip.create") }}';  // توجيه المستخدم إلى صفحة "إنشاء"

                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // معالجة أخطاء التحقق من الإدخال
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '<br>';
                    });
                    $('#errorMessage').show().html(errorMessage);
                } else {
                    $('#errorMessage').show().text('حدث خطأ أثناء الحفظ.');
                }
            }
        });
    }
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
