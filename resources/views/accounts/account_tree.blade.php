@extends('accounts.index')
@section('accounts')
<h1 class="font-bold">شجرة الحسابات</h1>
<br>
<div dir="rtl" class="grid gap-4 mb-4 grid-cols-2">
  <div dir="ltr" id="largeMainAccounts" class="w-[20%] shadow-md rounded-md">
  <ul>
      @foreach ($TypesAccounts as $largeAccount)
          <li class="py-2 px-2 ">
              <a href="#" 
                 class= "{{ Request::is(' ') ? 'border-b-2 text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6] leading-none rounded hover:bg-gray-50 large-main-account link-item"
                 data-id="{{ $largeAccount['id'] }}">
                  {{ $largeAccount['TypesAccountName'] }}
              </a>
          </li>
      @endforeach
  </ul>
</div>



<div>
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
</div>
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

                // جلب الحسابات الرئيسية باستخدام AJAX
                fetch(`/accounts/large-main-accounts/${largeMainAccountId}`)
                    .then(response => response.json())
                    .then(data => {
                        const mainAccountsTableBody = document.getElementById('mainAccountsTableBody');
                        const mainAccountsTable = document.getElementById('mainAccountsTable');
                        mainAccountsTableBody.innerHTML = ''; // تفريغ القائمة القديمة
                        const subAccountsTable = document.getElementById('subAccountsTable');
                        subAccountsTableBody.innerHTML = ''; //

                        // عرض الحسابات الرئيسية في الجدول
                        if (data.length > 0) {
                            data.forEach(account => {
                              const row = `
                                    <tr>
                                        <td>
                                          <a href="#" class="main-account  link-item "
  data-id="${account.main_account_id}">${account.main_account_id}</a></td>
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
                        mainAccountLinks.forEach(link => {
                            link.addEventListener('click', function(event) {
                                event.preventDefault();

                                const mainAccountId = this.getAttribute('data-id');

                                // جلب الحسابات الفرعية باستخدام AJAX
                                fetch(`/accounts/main-accounts/${mainAccountId}/sub-accounts`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const subAccountsTableBody = document.getElementById('subAccountsTableBody');
                                        const subAccountsTable = document.getElementById('subAccountsTable');
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
                    })
                    .catch(error => console.error('Error fetching main accounts:', error));
            });
        });
    });
</script>@endsection
