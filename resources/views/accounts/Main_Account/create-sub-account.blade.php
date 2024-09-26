
@extends('accounts.index')
@section('accounts')
<h1 > انشأ حساب فرعي  </h1>

<!-- القائمة المنسدلة مع البحث -->



<script>
$(document).ready(function() {
    // تهيئة Select2 مع تحديد عدد العناصر المسموح اختيارها إلى 1
    $('.select2').select2({
        maximumSelectionLength: 1

    });
$('.select2').select2({
        // ajax: {
        //     url: '/get-options',
        //     dataType: 'json',
        //     delay: 250,
        //     processResults: function (data) {
        //         return {
        //             results: data.map(function(item) {
        //                 return { text: item.idsec, id: item.id };
        //             })
        //         };
        //     },
        //     cache: true
        // },
        minimumInputLength: 1 // بدء البحث بعد كتابة حرف واحد
    });
  });
</script> 
<br>
<div id="SubAccount" >
  {{-- id="addItemForm"  --}}
  <form  id="addItemForm" class="p-4 md:p-5 " >
    {{-- method="POST" action="{{route('add_account.store')}}" --}}
    @csrf
    <div id="successMessage" class="alert-success" style="display: none;"></div>

    <div class="grid gap-4 mb-4 grid-cols-2">
      <div class="mb-2">
        <label class="labelSale" for="sub_name">اسم الحساب</label>
        <input name="sub_name" class="inputSale" id="sub_name" type="text" placeholder="اسم الحساب الجديد"/>
    </div>
    <div class="mb-2">

      <label class="labelSale  " for="Main_id">  الحساب الرئيسي</label>
        <select dir="ltr" class="select2 inputSale" id="Main_id" name="Main_id" >
             @forelse($MainAccounts as $MainAccount)
             <option value=" {{$MainAccount['main_account_id']}}">  {{$MainAccount['account_name']}}</option> 

                 
             @empty
             @endforelse
                  
                  
              
        


        
      
        </select>
      
        {{-- <option value="DE">الصندوق الرئيسي</option>
        <option value="DE">البنك  </option> --}}
      </div>
  
      <div class="mb-2">
          <label class="labelSale" for="debtor_amount">  رصيدافتتاحي مدين (اخذ)</label>
          <input name="debtor_amount" class="inputSale " id="debtor_amount" type="number" placeholder="0"/>
      </div>
      <div class="mb-2">
        <label class="labelSale" for="creditor_amount" >رصيدافتتاحي دائن (عاطي) </label>
        <input name="creditor_amount" class="inputSale " id="creditor_amount" type="number"  placeholder="0"/>
    </div>
      <div class="mb-2">
          <label for="Phone" class="labelSale">رقم التلفون</label>
          <input type="number" name="Phone" id="Phone" placeholder=" Phone Number" class="inputSale" />
      </div>
      {{-- <div class="mb-2">
          <label for="email" class="labelSale">البريد الإلكتروني</label>
          <input type="email" name="email" id="email" placeholder="Email" class="inputSale" />
      </div> --}}
      {{-- <div class="mb-2">
          <label for="address" class="labelSale">العنوان</label>
            <input type="text" name="address" id="address" placeholder="Address" class="inputSale" />
          </div> --}}
      <div class="mb-2">
          <label for="name_The_known" class="labelSale">اسم/ معرف العميل</label>
            <input type="text" name="name_The_known" id="name_The_known" placeholder="Address" class="inputSale" />
          </div>
      <div class="mb-2">
          <label for="Known_phone" class="labelSale">رقم تلفون/ معرف العميل</label>
            <input type="text" name="Known_phone" id="Known_phone" placeholder="" class="inputSale" />
          </div>
    </div>
    @auth
    <input type="text" name="User_id" required id="User_id" value="{{Auth::user()->id}}">
   
    @endauth
   
   
    <button type="submit" id="submit" class="text-white inline-flex items-center bgcolor hover:bg-stone-400  font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
      
      حفظ البيانات
    </button>
  </form>
  
  <table class="min-w-[85%]" id="invoiceItemsTable">
    <thead>
        <tr class="bgcolor">
            <th class="text-right">رقم الحساب</th>
            <th class="text-right">اسم الحساب</th>
            <th class="text-right">الرصيد مدين</th>
            <th class="text-right">الرصيد دائن</th>
            <th class="text-right">التلفون</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>



  <script src="jquery-3.7.1.min.js"></script>

<script src={{url('node_modules/jquery/dist')}}></script>
<style>
    .alert-success {
        color: green;
        font-weight: bold;
    }
</style>

 
<script type="text/javascript">
  // تحميل الأصناف الحالية عند تحميل الصفحة
  $(document).ready(function() {
    $('#addItemForm').on('submit', function(event) {
                event.preventDefault();
             
                let formData = {
               
                
                Main_id: $('#Main_id').val(),
                sub_name: $('#sub_name').val(),
                name_The_known: $('#name_The_known').val(),
                Known_phone: $('#Known_phone').val(),
                debtor_amount: $('#debtor_amount').val(),
                creditor_amount: $('#creditor_amount').val(),
                Phone: $('#Phone').val(),
                  User_id: $('#User_id').val(),
                
                    _token: '{{ csrf_token() }}' // CSRF token
                };

                $.ajax({
                    url: '/accounts/Main_Account/storc',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // عرض رسالة النجاح
                        $('#successMessage').text(response.message).show();
                        $('#invoiceItemsTable tbody').append(
        '<tr>' +
        '<td class="text-right">' + response.DataSubAccount.Main_id + '</td>' +
        '<td class="text-right">' + response.DataSubAccount.sub_name + '</td>' +
        '<td class="text-right">' + response.DataSubAccount.debtor_amount + '</td>' +
        '<td class="text-right">' + response.DataSubAccount.creditor_amount + '</td>' +
        '<td class="text-right">' + response.DataSubAccount.Phone + '</td>' +
        '</tr>'
    );

                      
              
                         
                        // إخفاء الرسالة بعد 3 ثوانٍ
                        setTimeout(function() {
                        $('#successMessage').fadeOut('slow');
                    }, 1000);
                         // تفريغ الحقول بعد الإضافة
    $('#addItemForm')[0].reset();

                        // تفريغ الحقول بعد الإضافة
                        // $('#account_name').val('');
                        // $('#Nature_account').val('');
                        // $('#creditor').val('');
                  
                        // ��ضافة الحساب ��لى الجدول
                        $('#invoiceItemsTable tbody').append('<tr><td>' + response.post.main_account_id + '</td><td>' + response.post.account_name + '</td><td>' + response.post.debtor + '</td><td>' + response.post.creditor + '</td><td>' + response.post.Phone + '</td></tr>');
                    },
                    error: function(response) {
                        alert('Error adding account');
                    }
                   
                   
                });
            });
        });
</script>

@endsection