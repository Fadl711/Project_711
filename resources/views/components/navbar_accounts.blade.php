<nav class=" ">
    <div class="flex ">
 <a href="{{route('Main_Account.create')}}"   class="py-2 px-4 {{ Request::is('accounts/Main_Account/create') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50" >اضافة حساب رئيسي</a>
        <a href="{{route('Main_Account.create-sub-account')}}"  class="py-2 px-4 {{ Request::is('accounts/Main_Account/create-sub-account') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50" > اضافة حساب فرعي</a>
        <a href=" {{route('index_account_tree')}}" class="py-2 px-4 {{ Request::is('accounts/account_tree/index_account_tree') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50"  >شجرة الحسابات</a>
        <a href=" {{route('accounts.review_budget')}}" class="py-2  px-4{{ Request::is('accounts/review-budget') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50"  >  مراجعة الحسابات </a>
            <button onclick="AccountBalancing()"  id="Accountbalancing"  type="submit" class="py-2  px-4 {{ Request::is('accounts/Main_Account/create') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50"  > ترصيد الحسابات</button>
      </div>
</nav>
