
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

  <form id="ajaxForm"  class="p-4 md:p-5 " method="POST"  >
    @csrf

  <div class="flex ">
    <label class="" for="">طبيعة الحساب</label>

    <div class="flex px-4" >
    <label class="labelSale">مدين</label>
      <input  type="radio" required value="مدين" name="Nature_account" class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    </div>
    <div class="flex ">
    <label  class="labelSale">دائن</label>
      <input type="radio" required  value="دائن"  name="Nature_account" class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    </div>
    </div>
  <div class="grid gap-4 mb-4 grid-cols-2">

    <div class="mb-2">
      <label class="labelSale" for="account_name">اسم الحساب</label>
      <input name="account_name" class="inputSale input-field" id="account_name" type="text" required placeholder="اسم الحساب الجديد"/>
  </div>

  <div class="mb-2">
    <label class="labelSale  "   for="typeAccount"> تصنيف الحساب</label>
      <select class=" input-field inputSale text-left " required  name="typeAccount" id="typeAccount">
        <option selected></option>
        @foreach ($TypesAccounts as $TypesAccount)
        <option value="{{$TypesAccount['id']}}" >{{$TypesAccount['TypesAccountName']}} </option>
        @endforeach


    </select>

    </div>
    <div class="mb-2">
        <label class="labelSale" for="debtor_amount">  رصيدافتتاحي مدين (علية)</label>
        <input name="debtor_amount" class="inputSale input-field english-numbers " id="debtor_amount" type="number" autocomplete="off" placeholder="0"/>
    </div>
    <div class="mb-2">
      <label class="labelSale" for="creditor_amount" >رصيدافتتاحي دائن (لة) </label>
      <input name="creditor_amount" class="inputSale input-field english-numbers" id="creditor_amount" type="number" autocomplete="off"  placeholder="0"/>
  </div>
  <div class="mb-2">
    <label class="labelSale  " required for="Type_migration"> يرحل الى </label>
      <select id="Type_migration" class=" text-left input-field inputSale" name="Type_migration">
        <option selected></option>
        @foreach ($Deportattons as $Deportatton)
        <option value="{{$Deportatton['id']}}" >{{$Deportatton['Deportatton']}} </option>
        @endforeach

      </select>
    </div>
    <div class="mb-2">
      <label for="Phone " class="labelSale" >   رقم التلفون الحساب</label>
      <input name="Phone" class="inputSale input-field english-numbers" id="Phone" type="number" autocomplete="off"  placeholder="0"/>
  </div>
  <div class="mb-2">
      <label for="name_The_known" class="labelSale">اسم/ معرف العميل</label>
        <input type="text" name="name_The_known" id="name_The_known" placeholder="" class="input-field inputSale" />
      </div>
  <div class="mb-2">
      <label for="Known_phone"  class="labelSale ">رقم تلفون/ معرف العميل</label>
        <input type="number"  autocomplete="off" name="Known_phone" id="Known_phone"  class="inputSale input-field english-numbers" />
      </div>
 </div>
 @auth
 <input type="text" name="User_id" required id="User_id" value="{{Auth::user()->id}}">
 @endauth
  <button type="submit" id="submit" class="input-field text-white inline-flex items-center bgcolor hover:bg-stone-400  font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

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
<div id="successMessage" style="display: none;">
  <p>تم الحفظ بنجاح!</p>
</div>
<div id="errorMessage" style="display: none; color: red;">
  <p>حدث خطأ أثناء الحفظ.</p>
</div>

<!-- منطقة طباعة البيانات المحفوظة -->
<div id="results" class="results"></div>
<script src={{url('node_modules/jquery/dist/')}}></script>
<style>
    .alert-success {
        color: green;
        font-weight: bold;
    }
</style>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('ajaxForm');
      const successMessage = document.getElementById('successMessage');
      const errorMessage = document.getElementById('errorMessage');
      const inputs = document.querySelectorAll('.input-field'); // تحديد جميع الحقول
      let account_name = document.getElementById('account_name');
      
      // تركيز على حقل الاسم عند بدء التشغيل
      account_name.focus();
  
      // منع السلوك الافتراضي لزر Enter
      form.addEventListener('keydown', function (event) {
          if (event.key === 'Enter') {
              event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
          }
      });
  
      // التنقل بين الحقول باستخدام السهم الأيمن أو الأيسر
      document.addEventListener('keydown', function(event) {
          if (event.key === "ArrowRight" || event.key === "ArrowLeft") {
              let currentIndex = -1;
  
              // العثور على الحقل النشط حاليًا
              for (let i = 0; i < inputs.length; i++) {
                  if (inputs[i] === document.activeElement) {
                      currentIndex = i;
                      break;
                  }
              }
  
              if (currentIndex !== -1) {
                  if (event.key === "ArrowRight") {
                      if (currentIndex < inputs.length - 1) {
                          inputs[currentIndex + 1].focus(); // نقل التركيز إلى الحقل التالي
                      }
                  } else if (event.key === "ArrowLeft") {
                      if (currentIndex > 0) {
                          inputs[currentIndex - 1].focus(); // نقل التركيز إلى الحقل السابق
                      }
                  }
              }
          }
      });
  
      // التعامل مع إرسال النموذج وحفظ البيانات باستخدام AJAX
      form.addEventListener('submit', function (event) {
          event.preventDefault(); // منع تحديث الصفحة
  
          // تجميع بيانات النموذج
          const formData = new FormData(form);
  
          // إرسال الطلب باستخدام Fetch API
          fetch('{{ route("Main_Account.store") }}', {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}' // إرسال التوكن الخاص بـ Laravel
              },
              body: formData // إرسال البيانات
          })
          .then(response => response.json()) // تحليل استجابة السيرفر
          .then(data => {
              if (data.success) {
                  // إظهار رسالة النجاح
                  successMessage.style.display = 'block';
                  successMessage.textContent = 'تم الحفظ بنجاح!';
                  account_name.focus(); // إعادة التركيز على حقل الاسم بعد الحفظ
  
                  // إخفاء الرسالة بعد 3 ثوانٍ
                  setTimeout(() => {
                      successMessage.style.display = 'none';
                  }, 3000);
  
                  // إضافة البيانات المحفوظة إلى الجدول
                  addToTable(data.DataSubAccount);
  
                  // تفريغ النموذج بعد الحفظ
                  form.reset();
              } else {
                  // إظهار رسالة عند وجود نفس الاسم
                  successMessage.style.display = 'block';
                  successMessage.textContent = 'يوجد نفس هذا الاسم من قبل';
                  account_name.focus();
  
                  setTimeout(() => {
                      successMessage.style.display = 'none';
                  }, 1000);
              }
          })
          .catch(error => {
              errorMessage.style.display = 'block';
              errorMessage.textContent = 'حدث خطأ أثناء الحفظ.';
          });
      });
  
      // وظيفة لإضافة البيانات إلى الجدول
      function addToTable(account) {
          const tableBody = document.querySelector('#invoiceItemsTable tbody');
          const newRow = `
              <tr>
                  <td class="text-right tagTd">${account.Main_id}</td>
                  <td class="text-right tagTd">${account.sub_name}</td>
                  <td class="text-right tagTd">${account.debtor_amount || 0}</td>
                  <td class="text-right tagTd">${account.creditor_amount || 0}</td>
                  <td class="text-right tagTd">${account.Phone || ''}</td>
              </tr>
          `;
          tableBody.insertAdjacentHTML('beforeend', newRow);
      }
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
