@extends('layout')
@section('conm')

<x-navbar_accounts/>
<br>
<style>
  /* تثبيت الأرقام بالإنجليزية */
  .english-numbers {
      font-feature-settings: 'tnum';
      direction: ltr;
      unicode-bidi: plaintext;
  }
  td{
    text-align: right;
  }
</style>
<div id="successMessage" class="alert-success" style="display: none;"></div>

<div class="grid mb-4 min-w-[100%]" id="mainaccount">
  <div class="w-[80%]">
    <form id="ajaxForm" method="POST" action="{{ route('accounts.Main_Account.update', $mainAccount->main_account_id) }}">
      @csrf
      @method('PUT')
      <div class="flex border-b-2 border-blue-700 pb-2">
        <div class="flex">
          <label class="font-bold" for="">طبيعة الحساب :</label>
          <div class="flex px-4">
            <label class="labelSale">مدين</label>
            <input type="radio" required value="مدين" name="Nature_account" class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $mainAccount->Nature_account == 'مدين' ? 'checked' : '' }}>
          </div>
          <div class="flex">
            <label class="labelSale">دائن</label>
            <input type="radio" required value="دائن" name="Nature_account" class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $mainAccount->Nature_account == 'دائن' ? 'checked' : '' }}>
          </div>
        </div>

        <div class="flex px-4">
          <label class="font-bold" for="">نوع الحساب :</label>
          @isset($accountClasses)
            @foreach($accountClasses as $accountClass)
              <div class="flex px-4">
                <label class="labelSale">{{ $accountClass->label() }}</label>
                <input type="radio" required value="{{ $accountClass->value }}" id="AccountClass" name="AccountClass" class="input-field w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $mainAccount->AccountClass == $accountClass->value ? 'checked' : '' }}>
              </div>
            @endforeach
          @endisset
        </div>
      </div>

      <br>
      <div class="grid gap-4 mb-4 lg:grid-cols-6 max-sm:grid-cols-2">
        <div class="mb-2">
          <label class="labelSale" for="account_name">اسم الحساب</label>
          <input name="account_name" class="inputSale h-7 input-field" id="account_name" type="text" required value="{{ $mainAccount->account_name }}" placeholder="اسم الحساب"/>
        </div>
        <div class="mb-2">
          <label class="labelSale" for="typeAccount">تصنيف الحساب</label>
          <select class="input-field inputSale text-left select2" required name="typeAccount" id="typeAccount">
            <option></option>
            @foreach ($AccountTypes as $AccountType)
              <option value="{{$AccountType->value}}" {{ $mainAccount->typeAccount == $AccountType->value ? 'selected' : '' }}>
                {{$AccountType->label()}}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-2">
          <label class="labelSale" for="Type_migration">يرحل الى</label>
          <select id="Type_migration" class="text-left select2 input-field inputSale" name="Type_migration">
            <option></option>
            @foreach ($Deportattons as $Deportatton)
            
              <option value="{{$Deportatton['id']}}" {{ $mainAccount->Type_migration == $Deportatton['id']->value ? 'selected' : '' }}>
                {{$Deportatton['Deportatton']}}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-2">
          <button type="submit" id="updateButton" class="inline-flex items-center bg-gradient-to-t text-white from-indigo-900 to-indigo-600 font-medium rounded-lg text-sm px-5 text-nowrap py-2.5 m-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            حفظ التعديلات
          </button>
        </div>
      </div>

      @auth
        <input type="hidden" name="User_id" required id="User_id" value="{{Auth::user()->id}}">
      @endauth
    </form>
  </div>
</div>

<script>
$(document).ready(function () {
  $('.select2').select2();

  const successMessage = $('#successMessage');
  
  $('#ajaxForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      data: $(this).serialize(),
      success: function(response) {
        successMessage.text('تم تحديث الحساب بنجاح').show();
        setTimeout(function() {
          successMessage.fadeOut();
          window.location.href = '{{ route("accounts.index") }}';
        }, 2000);
      },
      error: function(xhr) {
        alert('حدث خطأ أثناء تحديث الحساب');
      }
    });
  });
});
</script>
@endsection
