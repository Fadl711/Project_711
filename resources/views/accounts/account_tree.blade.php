@extends('accounts.index')
@section('accounts')
<h1 class="font-bold">شجرة الحسابات</h1>
<br>


<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var query = $(this).val();

            if (query === '') {
                // إخفاء الجدول عندما يكون حقل البحث فارغًا
                $('#results-table').addClass('hidden');
                return;
            }

            $.ajax({
                url: '{{ route("search.sub.accounts") }}',
                type: 'GET',
                data: { query: query },
                success: function(data) {
                    $('#results-body').empty();

                    if (data.length > 0) {
                        $('#results-table').removeClass('hidden'); // إظهار الجدول عند وجود نتائج
                        $.each(data, function(index, account) {
                            $('#results-body').append(
                                '<tr>' +
                                    '<td class="border border-gray-300 p-2">' + (index + 1) + '</td>' +
                                        '<td class="border border-gray-300 p-2">' + account.sub_name + '</td>' +
                                        '<td class="border border-gray-300 p-2">' + account.Main_id + '</td>' +
                                        '<td class="border border-gray-300 p-2">' + account.sub_account_id + '</td>' +
                                        '<td class="border border-gray-300 p-2">' + account.debtor_amount + '</td>' + // عرض المبلغ المدين
                                        '<td class="border border-gray-300 p-2">' + account.creditor_amount + '</td>' + // عرض المبلغ الدائن
                                   '</tr>'
                            );
                        });
                    } else {
                        $('#results-table').removeClass('hidden'); // إظهار الجدول حتى لو لم تكن هناك نتائج
                        $('#results-body').append(
                            '<tr>' +
                                '<td colspan="4" class="border border-gray-300 p-2 text-center">No sub-accounts found</td>' +
                            '</tr>'
                        );
                    }
                }
            });
        });
    });
</script>
    <br>
<div dir="rtl" class="grid gap-4 mb-4 grid-cols-3 max-sm:grid-cols-2 min-w-full ">
  <div dir="ltr" id="largeMainAccounts" class="w-[25%] shadow-md rounded-md">
  <ul>
    @auth
      @foreach ($TypesAccounts as $largeAccount)
          <li class="py-2 px-2 ">
              <a href="#" 
                 class= "{{ Request::is(' ') ? 'border-b-2 text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6] leading-none rounded hover:bg-gray-50 large-main-account link-item"
                 data-id="{{ $largeAccount['id'] }}">
                  {{ $largeAccount['TypesAccountName'] }}
              </a>
          </li>
      @endforeach
      @endauth
  </ul>
</div>



<div class="w-[100%] ">
<!-- عرض الحسابات الرئيسية -->
<div id="mainAccountsTable" style="display: none;">
    <h3>الحسابات الرئيسية</h3>
    <table class="table ">
        <thead>
            <tr>
                <th class="tagHt">رقم الحساب</th>
                <th class="tagHt">اسم الحساب</th>
                <th class="tagHt">الرصيد مدين</th>
                <th class="tagHt">الرصيد دائن</th>
                <th class="tagHt">الهاتف</th>
            </tr>
        </thead>
        <tbody id="mainAccountsTableBody">
            <!-- سيتم تعبئة الحسابات الرئيسية هنا عبر JavaScript -->
        </tbody>
    </table>
</div>
</div>
<!-- عرض الحسابات الفرعية -->
<div id="subAccountsTable" style="display: none;">
  <h3>الحسابات الفرعية</h3>
  <table class="table">
      <thead>
          <tr>
              <th class="tagHt">رقم الحساب الفرعي</th>
              <th class="tagHt">اسم الحساب الفرعي</th>
              <th class="tagHt">الرصيد مدين</th>
              <th class="tagHt">الرصيد دائن</th>
          </tr>
      </thead>
      <tbody id="subAccountsTableBody">
          <!-- سيتم تعبئة الحسابات الفرعية هنا عبر JavaScript -->
      </tbody>
  </table>
</div>

</div>
<input type="text" id="search" name="search" placeholder="Search for sub-accounts" class="border border-gray-300 p-2 rounded w-full mb-4">

<table id="results-table" class="min-w-full border-collapse border border-gray-300 hidden">
    <thead class="bg-gray-200">
        <tr>
            <th class="border border-gray-300 p-2">#</th>
            <th class="border border-gray-300 p-2">Account Name</th>
            <th class="border border-gray-300 p-2">Account Number</th>
            <th class="border border-gray-300 p-2">Parent ID</th>
            <th class="border border-gray-300 p-2">المدين</th> <!-- المبلغ المدين لعام 2024 -->
            <th class="border border-gray-300 p-2">الدائن</th> <!-- المبلغ الدائن لعام 2024 -->
        </tr>
    </thead>
    <tbody id="results-body">
        <!-- سيتم عرض النتائج هنا -->
    </tbody>
</table>
<input type="text" value="" name="mainAccountInput" id="mainAccountInput">
<script>
  // الحصول على جميع العناصر التي تحمل الكلاس "link-item"
  const links = document.querySelectorAll('.link-item');

  links.forEach(link => {
      link.addEventListener('click', function(event) {
      
          event.preventDefault(); // لمنع الانتقال

          // إزالة الكلاس "colored" من جميع الروابط
          links.forEach(l => l.classList.remove('colored'));

          // إضافة الكلاس "colored" للرابط الذي تم الضغط عليه
          this.classList.add('colored');
      });
  });
</script>

<style>
  .colored {
    
border-bottom:2px solid rgb(43, 12, 244);      color: #1b1bfdc6; /* يمكنك تغيير اللون كما تريد */
  }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const largeMainAccountLinks = document.querySelectorAll('.large-main-account');

        largeMainAccountLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // منع الانتقال للرابط

                const largeMainAccountId = this.getAttribute('data-id');
                document.getElementById('mainAccountInput').value = largeMainAccountId;

                // جلب الحسابات الرئيسية باستخدام AJAX
                fetch(`/accounts/large-main-accounts/${largeMainAccountId}`)
                    .then(response => response.json())
                    .then(data => {
                        const mainAccountsTableBody = document.getElementById('mainAccountsTableBody');
                        const mainAccountsTable = document.getElementById('mainAccountsTable');
                        mainAccountsTableBody.innerHTML = ''; // تفريغ القائمة القديمة
                        const subAccountsTable = document.getElementById('subAccountsTable');
                        const subAccountsTableBody = document.getElementById('subAccountsTableBody');
                        subAccountsTableBody.innerHTML = ''; // 

                        // عرض الحسابات الرئيسية في الجدول
                        if (data.length > 0) {
                            data.forEach(account => {
                                const row = `
                                    <tr>
                                        <td>
                                          <a href="#" class="main-account link-item text-blue-500 hover:underline"
                                             data-id="${account.main_account_id}">${account.main_account_id}</a>
                                        </td>
                                        <td class="text-right tagTd">${account.account_name}</td>
                                        <td class="text-right tagTd">${account.debit_balance || 0}</td>
                                        <td class="text-right tagTd">${account.credit_balance || 0}</td>
                                        <td class="text-right tagTd">${account.phone || 'غير متوفر'}</td>
                                    </tr>
                                `;
                                mainAccountsTableBody.insertAdjacentHTML('beforeend', row);
                            });
                            mainAccountsTable.style.display = 'block'; // إظهار الجدول
                        } else {
                            mainAccountsTableBody.innerHTML = '<tr><td colspan="5">لا توجد حسابات رئيسية.</td></tr>';
                        }

                        // إضافة حدث النقر للحسابات الرئيسية لجلب الحسابات الفرعية
                        const mainAccountLinks = document.querySelectorAll('.main-account');
                        let currentIndex = 0; // المؤشر الحالي

                        // تمييز الرابط الأول عند التحميل
                        if (mainAccountLinks.length > 0) {
                            highlightLink(mainAccountLinks[currentIndex]);
                        }

                        mainAccountLinks.forEach((link, index) => {
                            link.addEventListener('click', function(event) {
                                event.preventDefault();
                                currentIndex = index; // تحديث المؤشر الحالي عند الضغط على الرابط

                                // إزالة التنسيق من جميع الروابط
                                mainAccountLinks.forEach(l => l.classList.remove('text-red-500', 'font-bold'));

                                // إضافة الفئة "text-red-500" للرابط الذي تم النقر عليه لتغيير لونه
                                this.classList.add('text-red-500', 'font-bold');
                                const mainAccountId = this.getAttribute('data-id');
                                document.getElementById('mainAccountInput').value = mainAccountId;

                                // جلب الحسابات الفرعية باستخدام AJAX
                                fetch(`/accounts/main-accounts/${mainAccountId}/sub-accounts`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const subAccountsTableBody = document.getElementById('subAccountsTableBody');
                                        subAccountsTableBody.innerHTML = ''; // تفريغ القائمة القديمة

                                        // عرض الحسابات الفرعية في الجدول
                                        if (data.length > 0) {
                                            data.forEach(subAccount => {
                                                const row = `
                                                    <tr>
                                                        <td class="text-right tagTd">${subAccount.sub_account_id}</td>
                                                        <td class="text-right tagTd">${subAccount.sub_name}</td>
                                                        <td class="text-right tagTd">${subAccount.debit_balance || 0}</td>
                                                        <td class="text-right tagTd">${subAccount.credit_balance || 0}</td>
                                                    </tr>
                                                `;
                                                subAccountsTableBody.insertAdjacentHTML('beforeend', row);
                                            });
                                            subAccountsTable.style.display = 'block'; // إظهار الجدول
                                        } else {
                                            subAccountsTableBody.innerHTML = '<tr><td colspan="4">لا توجد حسابات فرعية.</td></tr>';
                                        }
                                    })
                                    .catch(error => console.error('Error fetching sub accounts:', error));
                            });
                        });

                        // مستمع لأحداث لوحة المفاتيح
                        document.addEventListener('keydown', function(event) {
                            // زر السهم الأيمن (Right Arrow)
                            if (event.key === 'ArrowRight' && currentIndex < mainAccountLinks.length - 1) {
                                removeHighlight(mainAccountLinks[currentIndex]); // إزالة التمييز الحالي
                                currentIndex++; // الانتقال إلى الرابط التالي
                                highlightLink(mainAccountLinks[currentIndex]); // تمييز الرابط الجديد
                                updateLinkId(mainAccountLinks[currentIndex]); // تحديث الـ data-id للرابط الجديد
                            }

                            // زر السهم الأيسر (Left Arrow)
                            if (event.key === 'ArrowLeft' && currentIndex > 0) {
                                removeHighlight(mainAccountLinks[currentIndex]); // إزالة التمييز الحالي
                                currentIndex--; // العودة إلى الرابط السابق
                                highlightLink(mainAccountLinks[currentIndex]); // تمييز الرابط الجديد
                                updateLinkId(mainAccountLinks[currentIndex]); // تحديث الـ data-id للرابط السابق
                            }
                        });

                        // دالة لتلوين الرابط
                        function highlightLink(link) {
                            link.classList.add('text-red-500', 'font-bold'); // إضافة تنسيق
                            link.focus(); // التركيز على الرابط الحالي
                        }

                        // دالة لإزالة تلوين الرابط
                        function removeHighlight(link) {
                            link.classList.remove('text-red-500', 'font-bold'); // إزالة التنسيق
                        }

                        // دالة لتحديث قيمة الـ data-id
                        function updateLinkId(link) {
                            const actualId = link.getAttribute('data-id'); // الحصول على القيمة الحقيقية
                            console.log(`Current data-id: ${actualId}`); // طباعة القيمة الحالية في الكونسول
                        }

                    })
                    .catch(error => console.error('Error fetching main accounts:', error));
            });
        });
    });
</script>
@endsection
