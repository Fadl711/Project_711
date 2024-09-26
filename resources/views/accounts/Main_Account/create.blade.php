
@extends('accounts.index')
@section('accounts')
<h1 > انشأ حساب رئيسي  </h1>

{{-- <div class="container mt-5">
  <h2>بحث داخل القائمة المنسدلة باستخدام Select2</h2>

  <!-- القائمة المنسدلة مع البحث -->
  <select class="select2" multiple="multiple" style="width: 100%">
      <option value="1">One</option>
      <option value="2">Two</option>
      <option value="3">Three</option>
      <option value="4">Four</option>
      <option value="5">Five</option>
      <option value="6">Six</option>
  </select>


<script>
    $(document).ready(function() {
        // تهيئة Select2 مع تحديد عدد العناصر المسموح اختيارها إلى 1
        $('.select2').select2({
            maximumSelectionLength: 1

        });



    $('.select2').select2({
            ajax: {
                url: '/get-options',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.map(function(item) {
                            return { id: item.id, text: item.name };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 1 // بدء البحث بعد كتابة حرف واحد
        });
      });
</script> --}}
<style>
  /* تثبيت الأرقام بالإنجليزية */
  .english-numbers {
      font-feature-settings: 'tnum';
      direction: ltr;
      unicode-bidi: plaintext;
  }
  td{
    text-align: right;
  }
</style>
<div class="" id="mainaccount" >
  <div id="successMessage" class="alert-success" style="display: none;"></div>

  <form id="addItemForm"  class="p-4 md:p-5 "  >
    @csrf

  <div class="flex ">
    <label class="" for="">طبيعة الحساب</label>

    <div class="flex px-4" >
    <label class="labelSale">مدين</label>
      <input  type="radio" required value="مدين" name="Nature_account" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    </div>
    <div class="flex ">
    <label  class="labelSale">دائن</label>
      <input type="radio" required  value="دائن"  name="Nature_account" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    </div>
    </div>
  <div class="grid gap-4 mb-4 grid-cols-2">

    <div class="mb-2">
      <label class="labelSale" for="account_name">اسم الحساب</label>
      <input name="account_name" class="inputSale" id="account_name" type="text" required placeholder="اسم الحساب الجديد"/>
  </div>

  <div class="mb-2">
    <label class="labelSale  "   for="typeAccount"> تصنيف الحساب</label>
      <select class=" inputSale text-left " required  name="typeAccount" id="typeAccount">
        <option selected></option>
        @foreach ($IntOrderStatus as $Deportatton)
        <option value="{{$Deportatton['id']}}" >{{$Deportatton['Deportatton']}} </option>
        @endforeach


    </select>

    </div>
    <div class="mb-2">
        <label class="labelSale" for="debtor">  رصيدافتتاحي مدين (علية)</label>
        <input name="debtor" class="inputSale english-numbers " id="debtor" type="number" autocomplete="off" placeholder="0"/>
    </div>
    <div class="mb-2">
      <label class="labelSale" for="creditor" >رصيدافتتاحي دائن (لة) </label>
      <input name="creditor" class="inputSale english-numbers" id="creditor" type="number" autocomplete="off"  placeholder="0"/>
  </div>
  <div class="mb-2">
    <label class="labelSale  " required for="Type_migration"> يرحل الى </label>
      <select id="Type_migration" class=" text-left inputSale" name="Type_migration">
        <option selected></option>
        @foreach ($Deportattons as $Deportatton)
        <option value="{{$Deportatton['id']}}" >{{$Deportatton['Deportatton']}} </option>
        @endforeach

      </select>
    </div>
    <div class="mb-2">
      <label for="Phone " class="labelSale" >   رقم التلفون الحساب</label>
      <input name="Phone" class="inputSale english-numbers" id="Phone" type="number" autocomplete="off"  placeholder="0"/>

  </div>
  {{-- <div class="mb-2">
      <label for="email" class="labelSale">البريد الإلكتروني</label>
      <input type="email" name="email" id="email" placeholder="Email" class="inputSale" />
  </div>
  <div class="mb-2">
      <label for="address" class="labelSale">العنوان</label>
        <input type="text" name="address" id="address" placeholder="العنوان العميل" class="inputSale" />
      </div> --}}
  <div class="mb-2">
      <label for="name_The_known" class="labelSale">اسم/ معرف العميل</label>
        <input type="text" name="name_The_known" id="name_The_known" placeholder="" class="inputSale" />
      </div>
  <div class="mb-2">
      <label for="Known_phone"  class="labelSale ">رقم تلفون/ معرف العميل</label>
        <input type="number"  autocomplete="off" name="Known_phone" id="Known_phone"  class="inputSale  english-numbers" />

      </div>

 </div>

 @auth
 <input type="text" name="User_id" required id="User_id" value="{{Auth::user()->id}}">

 @endauth




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
  <td id="Phone1"></td>


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
<div id="results" class="results"></div>

{{-- <script src="jquery-3.7.1.min.js"></script> --}}

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script> --}}

<script type="text/javascript">
  // تحميل الأصناف الحالية عند تحميل الصفحة
  $(document).ready(function() {
    $('#addItemForm').on('submit', function(event) {
                event.preventDefault();

                let formData = {

                //
                  Nature_account: $('input[name="Nature_account"]:checked').val(),
                  account_name: $('#account_name').val(),
                  Type_migration: $('#Type_migration').val(),
                  typeAccount: $('#typeAccount').val(),

                  name_The_known: $('#name_The_known').val(),
                  Known_phone: $('#Known_phone').val(),
                  debtor: $('#debtor').val(),
                  creditor: $('#creditor').val(),
                  Phone: $('#Phone').val(),
                  // Type_account: $('input[name="Type_account"]').val(),


                  User_id: $('#User_id').val(),

                 //name_The_known: $('#name_The_known').val(),
                 //Known_phone: $('#Known_phone').val(),
                 //Phone:   $('#Phone').val(),
                 //email:   $('#email').val(),
                 //address: $('#address').val(),
                 //supplier: $('#supplier').val(),
                 //customer: $('#customer').val(),
                 //debtor:   $('#debtor').val(),
                 //creditor: $('#creditor').val(),
                    _token: '{{ csrf_token() }}' // CSRF token
                };

                $.ajax({
                    url: '/accounts/Main_Account/store',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // عرض رسالة النجاح
                        $('#successMessage').text(response.message).show();
                         $('#sub_name1').text(response.DataSubAccount.sub_name).show();
                        $('#debtor1').text(response.DataSubAccount.debtor).show();
                        $('#creditor1').text(response.DataSubAccount.creditor).show();
                        $('#Phone1').text(response.DataSubAccount.Phone).show();
                        // $('#successMessage').text(response.post.message).show();



                        // إخفاء الرسالة بعد 3 ثوانٍ
                        setTimeout(function() {
                            $('#successMessage').fadeOut('slow');
                        }, 100);

                        // تفريغ الحقول بعد الإضافة
                        // $('#account_name').val('');
                        // $('#Nature_account').val('');
                        // $('#creditor').val('');

                        // ��ضافة الحساب ��لى الجدول
                        $('#invoiceItemsTable tbody').append('<tr><td>' + response.post.main_account_id + '</td><td>' + response.post.account_name + '</td><td>' + response.post.debtor + '</td><td>' + response.post.creditor + '</td><td>' + response.post.Phone + '</td></tr>');
                    },
                    // error: function(response) {
                    //     alert('Error adding account');
                    // }


                });
            });
        });
</script>

    {{-- <script>
        $(document).ready(function() {
            // عند الكتابة في حقل البحث
            $('#account_name').on('keyup', function() {
                let account_name = $(this).val(); // النص المدخل في حقل البحث

                // تحقق إذا كان هناك نص في الحقل
                if (account_name.length > 0) {
                    $.ajax({
                        url: '/search', // مسار البحث
                        type: 'GET',
                        data: { account_name: account_name }, // إرسال النص المدخل إلى السيرفر
                        success: function(data) {
                            // تنظيف النتائج السابقة
                            $('#results').empty();

                            // عرض النتائج الجديدة
                            data.forEach(function(accountname) {
                                $('#results').append('<div class="product"><strong>' + accountname.account_name + '</strong> - $' + accountname.id + '</div>');
                            });
                        }
                    });
                } else {
                    // تنظيف النتائج إذا كان الحقل فارغًا
                    $('#results').empty();
                }
            });
        });
    </script> --}}
@endsection
