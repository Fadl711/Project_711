@extends('layout')
@section('conm')
<nav class="bg-gray-800 text-white py-1 border-x border-y border-gray-900 rounded-xl  flex items-center justify-between ">
    <a class="font-bold  tracking-tight px-2" href="#" >محلاتي</a>
    <div class="flex items-center">
        <button onclick="NewAccount()" type="button" class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" >اضافة حساب</button>
        <button onclick="AccountTree()" type="button" class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" >شجرة الحسابات</button>
        <button onclick="FinancialAccounts()" type="button" class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" >  مراجعة الحسابات </button>
        <form action="{{route('accounts.balancing')}}" method="GET">
            <button  onclick="AccountBalancing()" type="submit"  class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" > ترصيد الحسابات</button>

        </form>
        {{-- <a href="{{route('accounts.balancing')}}" onclick="AccountBalancing()"  class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" > ترصيد الحسابات</a> --}}
    </div>
</nav>
    <div class="  border-x border-y border-orange-950 rounded-xl p-2 mt-2" id="new_account" style="display:block">
        @include('accounts.add_account')
    </div>
    <div class="  border-x border-y border-orange-950 rounded-xl p-2 mt-2 " id="account_tree" style="display:none">
            @include('accounts.account_tree')
    </div>
    <div class="  border-x border-y border-orange-950 rounded-xl p-2 mt-2 " id="financial_account" style="display:none">
        <h1>ميزان المراجعة بالمجاميع والأرصدة</h1>
        @include('accounts.financial_accounts')
</div>
{{-- <div class="  border-x border-y border-orange-950 rounded-xl p-2 mt-2 " id="account_balancing" style="display:none">
   
    @include('accounts.account_balancing')
</div> --}}


<script> 



// ----------------------------
function AccountTree(){
    
    var accounttree= document.getElementById('account_tree');
    var newaccount= document.getElementById('new_account');
    var financialaccount= document.getElementById('financial_account');
    var accountbalancing= document.getElementById('account_balancing');


    if(accounttree.style.display=="none"){
        accounttree.style.display="block";
        newaccount.style.display="none";
        accountbalancing.style.display="none";

        financialaccount.style.display="none";

    }else  if(accounttree.style.display=="block")
     {
        
        accounttree.style.display="block";
        newaccount.style.display="none";
        financialaccount.style.display="none";
        accountbalancing.style.display="none";


    }
};
function NewAccount()
{
    var accounttree= document.getElementById('account_tree');
    var newaccount= document.getElementById('new_account');
    var financialaccount= document.getElementById('financial_account');
    var accountbalancing= document.getElementById('account_balancing');


    if(newaccount.style.display=="none"){
        newaccount.style.display="block";
        accounttree.style.display="none";
        financialaccount.style.display="none";
        accountbalancing.style.display="none";


    }else  if(newaccount.style.display=="block")
     {
        newaccount.style.display="block";
        accounttree.style.display="none";
        financialaccount.style.display="none";
        accountbalancing.style.display="none";

    }
};

function FinancialAccounts(){
    var accounttree= document.getElementById('account_tree');
    var financialaccount= document.getElementById('financial_account');
    var newaccount= document.getElementById('new_account');
    var accountbalancing= document.getElementById('account_balancing');


    if(financialaccount.style.display=="none"){
        financialaccount.style.display="block";
        newaccount.style.display="none";
        accounttree.style.display="none";
        accountbalancing.style.display="none";


    }else  if(financialaccount.style.display=="block")
     {
        financialaccount.style.display="block";
        newaccount.style.display="none";
        accounttree.style.display="none";
        accountbalancing.style.display="none";

    }
};

function AccountBalancing(){
    var accounttree= document.getElementById('account_tree');
    var financialaccount= document.getElementById('financial_account');
    var newaccount= document.getElementById('new_account');
    var accountbalancing= document.getElementById('account_balancing');


    if(accountbalancing.style.display=="none"){
        accountbalancing.style.display="block";
        newaccount.style.display="none";
        accounttree.style.display="none";
        financialaccount.style.display="none";


    }else  if(accountbalancing.style.display=="block")
     {
        accountbalancing.style.display="block";
        newaccount.style.display="none";
        accounttree.style.display="none";
        financialaccount.style.display="none";

    }
};

</script>
@endsection
