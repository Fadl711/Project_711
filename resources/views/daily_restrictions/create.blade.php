@extends('daily_restrictions.index')

@section('restrictions')

<form id="dailyRestrictionsForm" action="{{ route('daily_restrictions.store') }}" method="POST" class="space-y-6">
    @csrf
    <div class="container mx-auto py-8 px-4">
        <!-- Title -->
        <h2 class="text-2xl font-bold text-center mb-6">إضافة قيد يومي</h2>

        <!-- Form Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- حساب المدين -->
            <div class="shadow-lg rounded-lg p-4 bg-white border">
                <h3 class="text-lg font-semibold mb-4">المدين</h3>
                <div class="mb-4">
                    <label for="account_debit_id" class="block font-medium mb-2">حساب المدين/الرئيسي</label>

                    <select name="account_debit_id" id="account_debit_id" dir="ltr" class="block w-full p-2 border select2 rounded-md inputSale" required>
                       <!-- إضافة خيارات الحسابات -->
                       
                      <option value="" selected>اختر الحساب</option>
                      @foreach ($mainAccounts as $mainAccount)
                           <option value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                      @endforeach
                      
                    </select>
                </div>
                <div class="mb-4">

                    <label for="sub_account_debit_id" class="block font-medium mb-2 ">حساب المدين/الفرعي</label>
                    @auth

                    <select name="sub_account_debit_id" dir="ltr" class="input-field select2 inputSale" id="sub_account_debit_id">
                        <!-- سيتم تعبئة الخيارات بناءً على الحساب الرئيسي المحدد -->
                     
                 
                      

                    </select>
                    @endauth
                </div>
                <div>
                    <label for="Amount_debit" class="block font-medium mb-2">المبلغ المدين</label>
                    <input name="Amount_debit" type="number" step="0.01" class="block w-full p-2 border rounded-md inputSale " placeholder="أدخل المبلغ" required>
                </div>
            </div>

            <!-- حساب الدائن -->
            <div class="shadow-lg rounded-lg p-4 bg-white border">
                <h3 class="text-lg font-semibold mb-4">الدائن</h3>
                <div class="mb-4">
                    <label for="account_Credit_id" class="block font-medium mb-2">حساب الدائن/الرئيسي</label>
                    <select name="account_Credit_id" id="account_Credit_id" class="block w-full p-2 select2 border rounded-md inputSale" required>
                        <option value="" selected>اختر الحساب</option>
                        <option value="" selected>اختر الحساب</option>
                        @foreach ($mainAccounts as $mainAccount)
                             <option value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                        @endforeach
                                            </select>
                </div>
                <div class="mb-4">
                    <label for="sub_account_Credit_id" class="block font-medium mb-2">حساب الدائن/الفرعي</label>
                    <select name="sub_account_Credit_id" id="sub_account_Credit_id" class="block w-full select2 p-2 border rounded-md inputSale">
                        <option value="" selected>اختر الحساب الفرعي</option>
                        <!-- سيتم تعبئة الخيارات بناءً على الحساب الرئيسي المحدد -->
                    </select>
                </div>
                <div>
                    <label for="Amount_Credit" class="block font-medium mb-2">المبلغ الدائن</label>
                    <input name="Amount_Credit" type="number" step="0.01" class="block w-full p-2 border rounded-md inputSale" placeholder="أدخل المبلغ" required>
                </div>
            </div>
        </div>

        <!-- تفاصيل إضافية -->
        <div class="shadow-lg rounded-lg p-4 bg-white border">
            <h3 class="text-lg font-semibold mb-4">تفاصيل إضافية</h3>

            <div class="mb-4">
                <label for="Statement" class="block font-medium mb-2">البيان</label>
                <textarea name="Statement" class="block w-full p-2 border rounded-md inputSale" placeholder="أدخل البيان" rows="4" required></textarea>
            </div>

            <div class="mb-4">
                <label for="Currency_id" class="block font-medium mb-2">العملة</label>
                <select name="Currency_id" id="Currency_id" class="block w-full p-2 border rounded-md inputSale" required>
                    <option value="" selected>اختر العملة</option>
                    <!-- إضافة خيارات العملات -->
                </select>
            </div>

            <input type="hidden" name="Daily_page_id" value="1">
            <input type="hidden" name="User_id" value="{{ Auth::user()->id }}">
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit" id="submitButton" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                حفظ القيد
            </button>
        </div>
    </div>
</form>

{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script>
  $(document).ready(function() {
      // تفعيل Select2
      $('.select2').select2();
  
      // التركيز على الحقل الأول عند التحميل
      $('#account_debit_id').focus();
  
      // التنقل بين الحقول باستخدام أزرار الأسهم
      $('.inputSale').on('keydown', function(e) {
          var inputs = $('.inputSale');
          var currentIndex = inputs.index(this);
  
          // السهم السفلي (Down Arrow)
          if (e.which === 40) {
              e.preventDefault();
              if (currentIndex + 1 < inputs.length) {
                  inputs.eq(currentIndex + 1).focus();
              }
          }
  
          // السهم العلوي (Up Arrow)
          if (e.which === 38) {
              e.preventDefault();
              if (currentIndex - 1 >= 0) {
                  inputs.eq(currentIndex - 1).focus();
              }
          }
      });
  
      // إضافة مؤشر تحميل
      function toggleLoading(state) {
          if (state) {
              $('#submitButton').prop('disabled', true).text('جارٍ الحفظ...');
          } else {
              $('#submitButton').prop('disabled', false).text('حفظ القيد');
          }
      }
  
      // إرسال النموذج باستخدام AJAX بدون تحديث الصفحة
      $('#dailyRestrictionsForm').on('submit', async function(e) {
          e.preventDefault(); // منع التحديث الافتراضي للصفحة
  
          try {
              toggleLoading(true); // تشغيل مؤشر التحميل
  
              let formData = $(this).serialize(); // تحويل البيانات لإرسالها
              let response = await fetch($(this).attr('action'), {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/x-www-form-urlencoded',
                      'X-CSRF-TOKEN': $('input[name="_token"]').val() // إضافة التوكن إذا كنت تستخدم Laravel
                  },
                  body: formData
              });
  
              if (!response.ok) throw new Error('فشل في حفظ البيانات');
  
              let result = await response.json();
  
              // هنا يتم التعامل مع الرد بعد النجاح
              alert('تم حفظ القيد بنجاح');
              $('#dailyRestrictionsForm')[0].reset(); // تصفية الحقول
          } catch (error) {
              console.error(error);
              alert('حدث خطأ أثناء حفظ القيد. حاول مرة أخرى.');
          } finally {
              toggleLoading(false); // إيقاف مؤشر التحميل
          }
      });
  
      // عند اختيار الحساب الرئيسي (المدين)
      $('#account_debit_id').on('change', function() {
          const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (المدين)
  
          // تفريغ القائمة الفرعية إذا لم يتم اختيار حساب رئيسي
          $('#sub_account_debit_id').empty().append('<option value="">اختر الحساب الفرعي</option>');
  
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
  
          // تفريغ القائمة الفرعية إذا لم يتم اختيار حساب رئيسي
          $('#sub_account_Credit_id').empty().append('<option value="">اختر الحساب الفرعي</option>');
  
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
