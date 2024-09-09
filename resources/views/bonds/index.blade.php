@extends('layout')
@section('conm')
<div class=" bg-gray-50 ">

<nav class="bg-white text-black py-1   rounded shadow-md flex items-center justify-between ">
    <a class="font-bold  tracking-tight px-2" href="#" >محلاتي</a>
    <div class="flex items-center">
        <button onclick="NewAccount()" type="button" class="text-sm px-4 py-2 leading-none rounded-md hover:bg-gray-100" >اضافة حساب</button>
        <button onclick="AccountTree()" type="button" class="text-sm px-4 py-2 leading-none rounded-md hover:bg-gray-100" >شجرة الحسابات</button>
        <button onclick="FinancialAccounts()" type="button" class="text-sm px-4 py-2 leading-none rounded-md hover:bg-gray-100" >  مراجعة الحسابات </button>
       {{-- <form action="{{route('accounts.balancing')}}" method="GET"> --}}
            <button onclick="AccountBalancing()"  id="Accountbalancing"  type="submit"  class="text-sm px-4 py-2 leading-none rounded-md hover:bg-gray-100" > ترصيد الحسابات</button>
        {{-- </form>  --}}
        {{-- <a href="{{route('accounts.balancing')}}" onclick="AccountBalancing()"  class="text-sm px-4 py-2 leading-none rounded-full hover:bg-gray-700" > ترصيد الحسابات</a> --}}
    </div>
</nav>
    <div class="  p-2 mt-2" id="new_account" style="display:">
        @include('bonds.receipt_bonds.index')
    </div>
</div>
@endsection