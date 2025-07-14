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
                    @forelse($main_accounts as $main_account)
                    <option @selected($SubAccount->main_id==$main_account->main_account_id) value="{{$main_account->main_account_id}}">{{$main_account->account_name}}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="mb-2">
                <label class="labelSale" for="debtor_amount">رصيد افتتاحي مدين (اخذ)</label>
                <input value="{{$Getentrie_id->amount_debit ??0 }}" name="debtor_amount" class="inputSale input-field" id="debtor_amount" type="number" placeholder="0"/>
            </div>
            <div class="mb-2">
                <label class="labelSale" for="creditor_amount">رصيد افتتاحي دائن (عاطي)</label>
                <input value="{{$Getentrie_id->amount_credit  ??0}}" name="creditor_amount" class="inputSale input-field" id="creditor_amount" type="number" placeholder="0"/>
            </div>
            <div class="mb-2">
                <label for="Phone" class="labelSale">رقم التلفون</label>
                <input type="number" value="{{$SubAccount->phone ?? null}}" name="Phone" id="Phone" class="input-field inputSale" />
            </div>
            <div class="mb-2">
                <label for="name_The_known" class="labelSale">العنوان</label>
                <input type="text" name="name_The_known" id="name_The_known" value="{{$SubAccount->name_the_known ?? null}}" class="input-field inputSale" />
            </div>
            <div class="text-center">
                <label for="date" class="text-center">التاريخ</label>
                <input
                    name="date"
                    id="date"
                    type="date"
                    class="inputSale"
                    @isset($Getentrie_id->created_at)
                        value="{{ \Carbon\Carbon::parse($Getentrie_id->created_at)->format('Y-m-d') }}"
                    @else
                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                    @endisset
                >
            </div>

       </div>
        <div class="text-gray-700  px-2">
        </div>
        <div class="text-gray-700 w-[50%] max-sm:w-[70%] ">
            <div class="  text-center  ">
                <div class="flex  " role="">
                    <div  class=" text-center  ">
                        <label for="Currency_name" class=" text-center" >العمله </label>
                        <label for="Currency_name" class="block font-medium mb-2">العملة</label>
            <select dir="ltr" id="Currency_name" class="inputSale input-field" name="Currency_name">
                {{-- @isset($currs)
                    <option selected value="{{ $currs->currency_name }}">{{ $currs->currency_name }}</option>
                @endisset --}}

                @isset($currs)
                    @foreach ($currs as $cur)
                        <option 
                            @isset($Getentrie_id)
                                @selected($cur->currency_name == $Getentrie_id->currency_name)
                                value="{{ $cur->currency_name }}"
                            @endisset
                            value="{{ $cur->currency_name }}">
                            {{ $cur->currency_name }}
                        </option>
                    @endforeach
                @endisset
            </select>
                       </div>
                       <div class="text-center">
    <label for="exchange_rate" class="text-center">سعر الصرف</label>
    <input 
        id="exchange_rate" 
        class="inputSale" 
        type="number"
        name="exchange_rate"
        value="{{ isset($Getentrie_id->exchange_rate) ? $Getentrie_id->exchange_rate : 1.00 }}">
</div>
            <div class="mb-2">
                <label for="name_The_known" class="labelSale">بيان رصيد الافتتاحي</label>
                <textarea
                        class="inputSale"
                        name="Statement"
                        id="Statement"
                        rows="3"
                    >
                    {{$Getentrie_id->statement ??'' }}
                </textarea>
                           </div>
            <div class="mb-2">
                <label for="entrie_id"   class="labelSale">رقم الحساب</label>
                <input type="text" value="{{$Getentrie_id->entrie_id ?? null}}" disabled name="entrie_id" id="entrie_id" class="input-field inputSale" />
            </div>
        </div>
        @auth
        <input type="hidden" name="User_id" required id="User_id" value="{{Auth::user()->id}}">
        @endauth
        <button type="submit" id="submit" class="text-white inline-flex items-center bgcolor hover:bg-stone-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            حفظ البيانات
        </button>
    </form>


    <script src="{{url('payments.js')}}">   </script>

@endsection
