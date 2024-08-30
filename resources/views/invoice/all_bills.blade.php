@extends('layout')
@section('conm')

<nav class="bg-gray-800 text-white py-1 border-x border-y border-gray-900 rounded-xl  flex items-center justify-between ">
    <a class="font-bold  tracking-tight px-2" href="#" >محلاتي</a>
    <div class="flex items-center">
        <button onclick="NewAccount()" type="button" class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" >اضافة حساب</button>
        <button onclick="AccountTree()" type="button" class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" >شجرة الحسابات</button>
        <button onclick="FinancialAccounts()" type="button" class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" >  مراجعة الحسابات </button>
       {{-- <form action="{{route('accounts.balancing')}}" method="GET"> --}}
            <button onclick="AccountBalancing()"  id="Accountbalancing"  type="submit"  class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" > ترصيد الحسابات</button>
        {{-- </form>  --}}
        {{-- <a href="{{route('accounts.balancing')}}" onclick="AccountBalancing()"  class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" > ترصيد الحسابات</a> --}}
    </div>
</nav>
<div class="flex flex-col gap-4 justify-center items-center p-2">
    
    <div class="relative  border border-gray-200 rounded-lg w-full max-w-lg">
        <input type="text" class="rounded-md w-full text-left" placeholder="Search MCQ | Topic | Course">

        <button type="submit" class="absolute right-6 top-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
        </button>

    </div>
</div>
<br>
<div class="-mx-4 sm:-mx-8 px-4 sm:px-8 overflow-x-auto overflow-y-auto ">
        <div class="min-w-full leading-normal ">
        <div class="tracking-tight ">


        
   
   
@for($s=0; $s<=10;$s++)
<div class="bg-white rounded-lg shadow-lg  max-w-xl mx-auto">
  
    <table class="w-full">
        <thead>
            <tr class="bg-indigo-700 text-white w-full border-x-4 border-x-indigo-700 border-y-4 border-y-indigo-700 ">
                <td colspan="2" class=" text-right  bg-white text-black  ">
                    <div class="flex items-center">
                        <div class="text-indigo-700 ">اسم العميل : جمال </div>
                     </div>                   
                <div class="text-sm"> التلفون:7754545515455</div>
                <div class="text-sm">رقم العميل : 15</div>
           
        </td>
                <td colspan="1" class="  ">
                    <div class="text-sm">التاريخ: 01/05/2023</div>
                    <div class="text-sm">رقم الفاتورة: 12345</div>
                </td>
                <td colspan="1" >
                    <h2 class="text-1xl font-bold mb-4">فاتورة المبيعات </h2>
                </td>
            </tr>
             <tr class="border-b border-b-indigo-700 py-2 ">
                <th  class=" text-right"><div class="text-indigo-700 ">اجمالي الفاتورة</div></th>
                <th  class=" text-right"><div class="text-indigo-700 ">المبلغ المدفوع</div></th>
                <th  class=" text-right"><div class="text-indigo-700 ">المبلغ المتبقي</div></th>
            </tr>
        </thead>
        <tbody>
          <tr>
      <td class=" text-right">$442</td>
      <td class=" text-right">$442</td>
      <td class=" text-right">$442</td>
      <td class=" text-right text-indigo-700"> <a href="{{route('invoice.index')}}"> عرض مبيعات </a></td>
    </tr> 
    </tbody>
    </table>
</div>
<br>
@endfor
</div>
</div>
</div>

@endsection