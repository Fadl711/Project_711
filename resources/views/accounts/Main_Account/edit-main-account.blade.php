@extends('layout')
@section('conm')

<x-navbar_accounts/>
<h1 > انشأ حساب رئيسي  </h1>
<form id="ajaxForm" class="p-4 md:p-5" method="POST" action="{{ route('accounts.Main_Account.update', $account->main_account_id) }}">

    @csrf
    @method('PUT') <!-- استخدام PUT لتحديث البيانات -->
  <input type="number" name="main_account_id" value="{{$account->main_account_id}}" id="accountId">
    <div class="flex">
        <label for="">طبيعة الحساب</label>
        <div class="flex px-4">
            <label class="labelSale">مدين</label>
            <input type="radio" required value="مدين" name="Nature_account" class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $account->Nature_account == 'مدين' ? 'checked' : '' }}>
        </div>
        <div class="flex">
            <label class="labelSale">دائن</label>
            <input type="radio" required value="دائن" name="Nature_account" class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $account->Nature_account == 'دائن' ? 'checked' : '' }}>
        </div>
    </div>

    <div class="grid gap-4 mb-4 grid-cols-2">
        <div class="mb-2">
            <label class="labelSale" for="account_name">اسم الحساب</label>
            <input name="account_name" class="inputSale input-field" id="account_name" type="text" required placeholder="اسم الحساب الجديد" value="{{ $account->account_name }}"/>
        </div>

        <div class="mb-2">
            <label class="labelSale" for="typeAccount">تصنيف الحساب</label>
            <select class="input-field inputSale text-left" required name="typeAccount" id="typeAccount">
                <option selected></option>
                @foreach ($TypesAccounts as $TypesAccount)
                <option  value="{{$TypesAccount['id']}}" {{ $TypesAccount['id']->value == $account->typeAccount ? 'selected' : '' }}>{{$TypesAccount['TypesAccountName']}} </option>
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <label class="labelSale" for="debtor_amount">رصيدافتتاحي مدين (علية)</label>
            <input name="debtor_amount" class="inputSale input-field english-numbers" id="debtor_amount" type="number" autocomplete="off" placeholder="0" value="{{ $account->debtor_amount }}"/>
        </div>

        <div class="mb-2">
            <label class="labelSale" for="creditor_amount">رصيدافتتاحي دائن (لة)</label>
            <input name="creditor_amount" class="inputSale input-field english-numbers" id="creditor_amount" type="number" autocomplete="off" placeholder="0" value="{{ $account->creditor_amount }}"/>
        </div>

        <div class="mb-2">
            <label class="labelSale" required for="Type_migration">يرحل الى</label>
            <select id="Type_migration" class="text-left input-field inputSale" name="Type_migration">
                <option selected></option>
                @foreach ($Deportattons as $Deportatton)
                <option  value="{{$Deportatton['id']}}" {{ $Deportatton['id']->value == $account->Type_migration ? 'selected' : '' }}> {{ $Deportatton['Deportatton'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <label for="Phone" class="labelSale">رقم التلفون الحساب</label>
            <input name="Phone" class="inputSale input-field english-numbers" id="Phone" type="number" autocomplete="off" placeholder="0" value="{{ $account->phone }}"/>
        </div>

        <div class="mb-2">
            <label for="name_The_known" class="labelSale">اسم/ معرف العميل</label>
            <input type="text" name="name_The_known" id="name_The_known" placeholder="" class="input-field inputSale" value="{{ $account->name_the_known }}"/>
        </div>

        <div class="mb-2">
            <label for="Known_phone" class="labelSale">رقم تلفون/ معرف العميل</label>
            <input type="number" autocomplete="off" name="Known_phone" id="Known_phone" class="inputSale input-field english-numbers" value="{{ $account->known_phone }}"/>
        </div>
    </div>
{{--
    @auth
    <input type="hidden" name="User_id" required id="User_id" value="{{ Auth::user()->id }}">
    @endauth --}}

    <button type="submit" id="submit" class="input-field text-white inline-flex items-center bgcolor hover:bg-stone-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        تحديث البيانات
    </button>
</form>
<div id="responseMessage" class="mt-4"></div>


  @endsection
