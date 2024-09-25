
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
        ajax: {
            url: '//accounts/Sub_Account/create',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return { text: item.account_name, id: item.main_account_id };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 1 // بدء البحث بعد كتابة حرف واحد
    });
  });
</script> 
<br>
<div id="SubAccount" style="d">
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
              
              
        <option value=" {{$posts['main_account_id']}}">  {{$posts['account_name']}}</option>
        

        
      
        </select>
      
        {{-- <option value="DE">الصندوق الرئيسي</option>
        <option value="DE">البنك  </option> --}}
      </div>
  
      <div class="mb-2">
          <label class="labelSale" for="debtor">  رصيدافتتاحي مدين (اخذ)</label>
          <input name="debtor" class="inputSale " id="debtor" type="number" placeholder="0"/>
      </div>
      <div class="mb-2">
        <label class="labelSale" for="creditor" >رصيدافتتاحي دائن (عاطي) </label>
        <input name="creditor" class="inputSale " id="creditor" type="number"  placeholder="0"/>
    </div>
      <div class="mb-2">
          <label for="Phone" class="labelSale">رقم التلفون</label>
          <input type="number" name="Phone" id="Phone" placeholder=" Phone Number" class="inputSale" />
      </div>
      <div class="mb-2">
          <label for="email" class="labelSale">البريد الإلكتروني</label>
          <input type="email" name="email" id="email" placeholder="Email" class="inputSale" />
      </div>
      <div class="mb-2">
          <label for="address" class="labelSale">العنوان</label>
            <input type="text" name="address" id="address" placeholder="Address" class="inputSale" />
          </div>
      <div class="mb-2">
          <label for="name_The_known" class="labelSale">اسم/ معرف العميل</label>
            <input type="text" name="name_The_known" id="name_The_known" placeholder="Address" class="inputSale" />
          </div>
      <div class="mb-2">
          <label for="Known_phone" class="labelSale">رقم تلفون/ معرف العميل</label>
            <input type="text" name="Known_phone" id="Known_phone" placeholder="" class="inputSale" />
          </div>
    </div>
    <button type="submit" id="submit" class="text-white inline-flex items-center bgcolor hover:bg-stone-400  font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
      
      حفظ البيانات
    </button>
  </form>
  <table class=" min-w-[85%]  " id="invoiceItemsTable">
    <thead>
        <tr class="bgcolor">
            <th scope="col" class="leading-2 tagHt "> رقم الحساب</th>
            <th scope="col" class="leading-2 tagHt ">اسم الحساب</th>
            <th scope="col" class="leading-2 tagHt">الرصيد مدين</th>
            <th scope="col" class="leading-2 tagHt">الرصيد دائن</th>
            <th scope="col" class="leading-2 tagHt"> التلفون</th>
            {{-- <th scope="col" class="leading-2 tagHt"> تعديل</th> --}}
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-300 ">
    <!-- الأصناف سيتم إضافتها هنا ديناميكياً -->
    <td id="sub_name1"></td>
    <td id="debtor1"></td>
    <td id="creditor1"></td>

</tbody>
</table>
  </div>




<script src={{url('node_modules/jquery/dist/')}}></script>
<style>
    .alert-success {
        color: green;
        font-weight: bold;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
 
<script type="text/javascript">
  // تحميل الأصناف الحالية عند تحميل الصفحة
  $(document).ready(function() {
    $('#addItemForm').on('submit', function(event) {
                event.preventDefault();

                let formData = {
                  Main_id: $('#Main_id').val(),
                  // Nature_account: $('input[name="Nature_account"]:checked').val(),               
                  account_name: $('#account_name').val(),
                  // Type_migration: $('#Type_migration').val(),
                  // typeAccount: $('#typeAccount').val(),

                  // name_The_known: $('#name_The_known').val(),
                  // Known_phone: $('#Known_phone').val(),
                  // Phone: $('#Phone').val(),
                  debtor: $('#debtor').val(),
                  creditor: $('#creditor').val(),
                  // Type_account: $('input[name="Type_account"]').val(),               


                  User_id: $('#User_id').val(),
                    _token: '{{ csrf_token() }}' // CSRF token
                };

                $.ajax({
                    url:'/accounts/Sub_Account/store',
                    type:'POST',
                    data: formData,
                    success: function(response) {
                        // عرض رسالة النجاح
                        $('#successMessage').text(response.message).show();
                        $('#sub_name1').text(response.posts.sub_name).show();
                        // $('#debtor1').text(response.posts.debtor).show();
                        // $('#creditor1').text(response.posts.creditor).show();
                        // $('#successMessage').text(response.posts.message).show();
                          
                         
                        // إخفاء الرسالة بعد 3 ثوانٍ
                        setTimeout(function() {
                            $('#successMessage').hide();
                        }, 3000);

                        // تفريغ الحقول بعد الإضافة
                        $('#sub_name').val('');
                        $('#debtor').val('');
                        $('#creditor').val('');
                    },
                    error: function(response) {
                        alert('Error adding customer');
                    }
                });
            });
        });
</script>

@endsection