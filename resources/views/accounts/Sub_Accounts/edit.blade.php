@extends('layout')
@section('conm')

<x-navbar_accounts/>
<h1>تعديل حساب فرعي</h1>
{{-- @dd($subAccountId); --}}
{{-- @isset($subAccountId)
@dd($subAccountId)

@endisset --}}


<br>
<div id="SubAccount">
    <form action="{{route('subAccounts.update')}}" id="ajaxForm" class="p-4 md:p-5" method="POST">
        @csrf

        <input type="hidden" value="{{$SubAccount->sub_account_id}}" name="sub_id" class="inputSale input-field" id="sub_name" type="text" placeholder="اسم الحساب الجديد"/>

        <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="mb-2">
                <label class="labelSale" for="sub_name">اسم الحساب</label>
                <input value="{{$SubAccount->sub_name}}" name="sub_name" class="inputSale input-field" id="sub_name" type="text" placeholder="اسم الحساب الجديد"/>
            </div>
            <div class="mb-2">
                <label class="labelSale" for="Main_id">الحساب الرئيسي</label>
                <select dir="ltr" class="input-field select2 inputSale" id="Main_id" name="Main_id">
                    @forelse($MainAccounts as $MainAccount)
                    <option @selected($SubAccount->Main_id==$MainAccount['main_account_id']) value="{{$MainAccount['main_account_id']}}">{{$MainAccount['account_name']}}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="mb-2">
                <label class="labelSale" for="debtor_amount">رصيد افتتاحي مدين (اخذ)</label>
                <input value="{{$SubAccount->sub_name}}" name="debtor_amount" class="inputSale input-field" id="debtor_amount" type="number" placeholder="0"/>
            </div>
            <div class="mb-2">
                <label class="labelSale" for="creditor_amount">رصيد افتتاحي دائن (عاطي)</label>
                <input value="{{$SubAccount->sub_name}}" name="creditor_amount" class="inputSale input-field" id="creditor_amount" type="number" placeholder="0"/>
            </div>
        </div>
        @auth
        <input type="hidden" name="User_id" required id="User_id" value="{{Auth::user()->id}}">
        @endauth
        <button type="submit" id="submit" class="text-white inline-flex items-center bgcolor hover:bg-stone-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            حفظ البيانات
        </button>
    </form>



@endsection
