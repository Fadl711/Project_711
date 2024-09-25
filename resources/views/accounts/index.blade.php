@extends('layout')
@section('conm')

<div class=" container  shadow p-2 mt-2" id="new_account" style="">

<nav class="navIndex ">
    <div class="flex items-center">
 <a href="{{route('Main_Account.create')}}"  class="text-sm px-4 py-2 leading-none rounded hover:bg-gray-50" >اضافة حساب رئيسي</a> 
        <a href="{{route('Sub_Account.create')}}"  class="text-sm px-4 py-2 leading-none rounded hover:bg-gray-50" > اضافة حساب فرعي</a>

        <button onclick="AccountTree()" type="button" class="text-sm px-4 py-2 leading-none rounded hover:bg-gray-50" >شجرة الحسابات</button>
        <button onclick="FinancialAccounts()" type="button" class="text-sm px-4 py-2 leading-none rounded hover:bg-gray-50" >  مراجعة الحسابات </button>
       {{-- <form action="{{route('accounts.balancing')}}" method="GET"> --}}
            <button onclick="AccountBalancing()"  id="Accountbalancing"  type="submit"  class="text-sm px-4 py-2 leading-none rounded hover:bg-gray-50" > ترصيد الحسابات</button>
        {{-- </form>  --}}
        {{-- <a href="{{route('accounts.balancing')}}" onclick="AccountBalancing()"  class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" > ترصيد الحسابات</a> --}}
    </div>
</nav>
        @yield('accounts')
    </div>
    {{-- <div class="  border-x border-y border-orange-950 rounded-xl p-2 mt-2 " id="account_tree" style="display:none">
            @include('accounts.account_tree')
    </div>
    <div class="  border-x border-y border-orange-950 rounded-xl p-2 mt-2 " id="financial_account" style="display:none">
        <h1>ميزان المراجعة بالمجاميع والأرصدة</h1>
        @include('accounts.financial_accounts')
</div>
<div class="  border-x border-y border-orange-950 rounded-xl p-2 mt-2 " id="chart" style="display:block">
    @include('accounts.chart')
</div> --}}
{{-- <div id="account_balancing" style="display:"></div> --}}










   
{{--     
<h1 class="text-center  font-bold py-2">ترصيد الحسابات</h1>
<div class="-mx-4 sm:-mx-8 px-4 sm:px-8 overflow-x-auto   p-1">
    <h1> ترصيد حساب: ب  </h1>
    <div class="inline-block min-w-full shadow rounded-lg  max-h-[500px] ">
        <table id="myTable" class="min-w-full leading-normal ">
            <thead class="tracking-tight ">
                <tr class="bgcolor">
                    <th scope="col" class="leading-2 tagHt"> القم القيد</th>
                    <th scope="col" class="leading-2 tagHt ">اسم الحساب</th>
                    <th scope="col" class="leading-2 tagHt ">رقم الحساب</th>
                    <th scope="col" class="leading-2 tagHt ">  المبالغ المقبوض</th>
                    <th scope="col" class="leading-2 tagHt ">  اسم العميل</th>
                    <th scope="col" class="leading-2 tagHt"> القم القيد</th>
                    <th scope="col" class="leading-2 tagHt ">اسم الحساب</th>
                    <th scope="col" class="leading-2 tagHt ">رقم الحساب</th>
                    <th scope="col" class="leading-2 tagHt ">  المبالغ المدفوع</th>
                    <th scope="col" class="leading-2 tagHt ">  اسم العميل</th>
                    <th scope="col" class="leading-2 tagHt "> الرصيد الحالي </th>
                    <th scope="col" class="leading-2 tagHt "> المزيد التفاصيل</th>
                </tr>
            </thead>
            <tbody class="">
                @foreach($posts as $mnn)
               <tr class="bg-white transition-all duration-500 hover:bg-gray-50"> 
                    <td class="tagTd  border-r border-r-orange-950">{{$mnn['id']}}</td>
                    <td class="tagTd  border-r border-r-orange-950">{{$mnn['sec']}}</td>
                    <td class="tagTd  border-r border-r-orange-950">{{$mnn['idsec']}}</td>
                    <td class="tagTd   items-center font-bold ">{{$mnn['pric']}}</td>
                    <td class="tagTd   items-center font-bold ">{{$mnn['name']}}</td> 
                    <td class="tagTd  border-r border-r-orange-950">1</td>
                    <td  id="financial_accoun" class="tagTd  border-r border-r-orange-950">الصندوق</td>
                    <td class="tagTd   items-center font-bold ">$41225</td>
                    <td class="tagTd   items-center font-bold ">جمال</td>
                    <td class="tagTd ">$1500</td>
                            <td class="tagTd  "><a href="{{route('users.details')}}"
                            class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                            <span aria-hidden
                            class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                            <span class="relative">المزيد</span>
                        </a>
                    </td>
                </tr>
             @endforeach 
            </tbody>
        </table>
    </div>
</div>
</div>   --}}


<script> 

// $(document).ready(function(){
//   $("#Accountbalancing").click(function(){  
//      $.ajax({
    
//     url:'{{route('accounts.balancing')}}',
//     type:'GET',
//     success:function(data){
//         let html='';
//         data.forEach($item=>{
//             html+='<br>'+'<p>'+$item.name+'</P>';

            
//         });
        
//         $('#account_balancing').html(html)


//     },
// });


//   });
// });

// ----------------------------

function AccountTree(){
    
    
    var accounttree= document.getElementById('account_tree');
    var Chart= document.getElementById('chart');

    var newaccount= document.getElementById('new_account');
    var financialaccount= document.getElementById('financial_account');
    var accountbalancing= document.getElementById('account_balancing');


    if(accounttree.style.display=="none"){
        accounttree.style.display="block";
        Chart.style.display="none";

        newaccount.style.display="none";
        accountbalancing.style.display="none";

        financialaccount.style.display="none";

    }else  if(accounttree.style.display=="block")
     {
        Chart.style.display="block";
        accounttree.style.display="none";
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
    var Chart= document.getElementById('chart');



    if(newaccount.style.display=="none"){
        newaccount.style.display="block";
        accounttree.style.display="none";
        financialaccount.style.display="none";
        accountbalancing.style.display="none";
        Chart.style.display="none";



    }else  if(newaccount.style.display=="block")
     {
        newaccount.style.display="none";
        Chart.style.display="block";

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
    var Chart= document.getElementById('chart');



    if(financialaccount.style.display=="none"){
        financialaccount.style.display="block";
        newaccount.style.display="none";
        accounttree.style.display="none";
        accountbalancing.style.display="none";
        Chart.style.display="none";



    }else  if(financialaccount.style.display=="block")
     {
        financialaccount.style.display="none";
        Chart.style.display="block";

        newaccount.style.display="none";
        accounttree.style.display="none";
        accountbalancing.style.display="none";

    }
};
 
// ____________________________________

function AccountBalancing(){
    
    
    var accounttree= document.getElementById('account_tree');
    var financialaccount= document.getElementById('financial_account');
    var newaccount= document.getElementById('new_account');
    var accountbalancing= document.getElementById('account_balancing');
    var Chart= document.getElementById('chart');



    if(accountbalancing.style.display=="none"){
        accountbalancing.style.display="block";
        Chart.style.display="none";

        newaccount.style.display="none";
        accounttree.style.display="none";
        financialaccount.style.display="none";


    }else  if(accountbalancing.style.display=="block")
     {
        accountbalancing.style.display="none";
        Chart.style.display="block";

        newaccount.style.display="none";
        accounttree.style.display="none";
        financialaccount.style.display="none";

    }
};

</script>
@endsection
