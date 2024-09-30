
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
  <form  id="ajaxForm" class="p-4 md:p-5 " method="POST" >
    {{-- method="POST" action="{{route('add_account.store')}}" --}}
    @csrf
    <div id="successMessage" class="alert-success" style="display: none;"></div>

    <div class="grid gap-4 mb-4 grid-cols-2">
      <div class="mb-2">
        <label class="labelSale" for="sub_name">اسم الحساب</label>
        <input name="sub_name" class="inputSale input-field " id="sub_name" type="text" placeholder="اسم الحساب الجديد"/>
    </div>
    <div class="mb-2">

      <label class="labelSale  " for="Main_id">  الحساب الرئيسي</label>
        <select dir="ltr" class=" input-field select2 inputSale" id="Main_id" name="Main_id" >
             @forelse($MainAccounts as $MainAccount)
             <option value=" {{$MainAccount['main_account_id']}}">  {{$MainAccount['account_name']}}</option> 
             @empty
             @endforelse
           </select>
      </div>
  
      <div class="mb-2">
          <label class="labelSale" for="debtor_amount">  رصيدافتتاحي مدين (اخذ)</label>
          <input name="debtor_amount" class="inputSale input-field" id="debtor_amount" type="number" placeholder="0"/>
      </div>
      <div class="mb-2">
        <label class="labelSale" for="creditor_amount" >رصيدافتتاحي دائن (عاطي) </label>
        <input name="creditor_amount" class="inputSale input-field" id="creditor_amount" type="number"  placeholder="0"/>
    </div>
      <div class="mb-2">
          <label for="Phone" class="labelSale">رقم التلفون</label>
          <input type="number" name="Phone" id="Phone" placeholder="" class="input-field inputSale" />
      </div>
      <div class="mb-2">
          <label for="name_The_known" class="labelSale">اسم/ معرف العميل</label>
            <input type="text" name="name_The_known" id="name_The_known" placeholder="Address" class="input-field inputSale" />
          </div>
      <div class="mb-2">
          <label for="Known_phone" class="labelSale">رقم تلفون/ معرف العميل</label>
            <input type="text" name="Known_phone" id="Known_phone" placeholder="" class="input-field inputSale" />
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
<div id="successMessage" style="display: none;">
  <p>تم الحفظ بنجاح!</p>
</div>
<div id="errorMessage" style="display: none; color: red;">
</div>

<script src={{url('node_modules/jquery/dist')}}></script>
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
      let account_name = document.getElementById('sub_name');
      
      // تركيز على حقل الاسم عند بدء التشغيل
      sub_name.focus();
  
      // منع السلوك الافتراضي لزر Enter
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
          fetch('{{ route("Main_Account.storc") }}', {
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
                  sub_name.focus(); // إعادة التركيز على حقل الاسم بعد الحفظ
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
                  sub_name.focus();
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

@endsection