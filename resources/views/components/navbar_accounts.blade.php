<nav class=" shadow-md bg-white print:hidden ">
    <div
        class="flex justify-evenly  text-sm  2xl:w-full items-center px-4 p-2  bg-gradient-to-t text-white from-indigo-900 to-indigo-600 rounded-md shadow-sm font-medium capitalize hover:text-blue-600">
        <a href="{{ route('Main_Account.create') }}"
            class="py-2 px-4 {{ Request::is('accounts/Main_Account/create') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">اضافة
            حساب رئيسي</a>
        <a href="{{ route('Main_Account.create-sub-account') }}"
            class="py-2 px-4 {{ Request::is('accounts/Main_Account/create-sub-account') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
            اضافة حساب فرعي</a>
        <a href="{{ route('subAccounts.allShow') }}"
            class="py-2 px-4 {{ Request::is('accounts/subAccount/allShow') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
            الحسابات الفرعية</a>
        <a href=" {{ route('index_account_tree') }}"
            class="py-2 px-4 {{ Request::is('accounts/account_tree/index_account_tree') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">شجرة
            الحسابات</a>
        <a href="{{ route('accounts.review_budget', ['year' => now()->year, 'month' => now()->month]) }}"
            class="py-2 px-4 {{ Request::is('accounts/review-budget') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
            مراجعة الحسابات
        </a>
        {{--                       <button onclick="AccountBalancing()"  id="Accountbalancing"  type="submit" class="py-2  px-4 {{ Request::is('accounts/Main_Account/create') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50"  > ترصيد الحسابات</button>
 --}}
    </div>
</nav>
