@extends('layout')
@section('conm')
<x-navbar_accounts/>

<h1 class="text-2xl font-bold text-center py-4">مراجعة الميزانية</h1>
@if($accountingPeriod)
    <h2 class="text-lg font-semibold text-center text-gray-600 mb-4">
        الفترة المحاسبية: {{ $accountingPeriod->Year }} - {{ $accountingPeriod->Month }}
    </h2>
<div dir="rtl" class=" grid grid-cols-2 w-full border ">




    <div class="overflow-x-auto mx-auto ">
        <div class="shadow-lg rounded-lg overflow-hidden">
            <table id="myTable" class="min-w-full leading-normal border-collapse">
                <thead >
                    <tr>
                        <th class="text-center" colspan="5">الأصول</th>
                    </tr>
                    <tr class="bg-gray-200">
                        
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            اسم الحساب الرئيسي
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            حركة المبالغ المدين
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            حركة المبالغ  الدائن
                        </th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            عرض الحسابات الفرعية
                        </th>
                        <th></th>
                        
                    </tr>
                    
                </thead>
                <tbody class="bg-white"> 
                    @isset($mainAccountsTotals)
                    @forelse($mainAccountsTotals as $mainAccount)
                    @if (in_array($mainAccount->typeAccount, [1, 2]))                    
                        <tr class="hover:bg-gray-100 transition duration-300">
                            <td class="px-6 py-4 border-b border-gray-200"><a href="#" class="text-blue-600 font-semibold" onclick="toggleSubAccounts({{ $mainAccount->main_account_id }})">{{ $mainAccount->account_name }}</a></td>
                            <td class="px-6 py-4 border-b border-gray-200"> {{ number_format($mainAccount->subAccounts->sum('total_debit')) }} د.إ </td>
                            <td class="px-6 py-4 border-b border-gray-200"> {{ number_format($mainAccount->subAccounts->sum('total_credit')) }} د.إ </td>
                            <td class="px-6 py-4 border-b border-gray-200
                                @if(($mainAccount->subAccounts->sum('total_debit') ?? 0) - ($mainAccount->subAccounts->sum('total_credit')?? 0) < 0)
                                 text-green-500  
                                @else  text-red-500 
                                @endif"> {{-- حساب الفرق بين المدين والدائن --}}
                                @php
                                    $balance = ($mainAccount->subAccounts->sum('total_debit') ?? 0) - ($mainAccount->subAccounts->sum('total_credit')?? 0) ;
                                @endphp
                                 @if($balance < 0)دائن/له
                                @else مدين/عليه
                                @endif
                                 {{ number_format(abs($balance)) }}       {{-- عرض القيمة مع النص المناسب (مدين/دائن) --}} 
                           </td>
                            <td class="px-6 py-4 border-b border-gray-200"><button onclick="toggleSubAccounts({{ $mainAccount->main_account_id }})" class="text-blue-500 underline hover:text-blue-700">عرض</button></td>
                        </tr>
                     @endisset
                        <!-- الحسابات الفرعية -->
                        <tr id="subAccounts-{{ $mainAccount->main_account_id }}" class="hidden">
                            <td colspan="4">
                                <div class="bg-gray-50 p-4">
                                    <table class="min-w-full bg-white rounded-lg shadow-md">
                                        <thead>
                                            <tr>
                                                <th>رقم الحساب الفرعي</th>

                                                <th class="tagHt">اسم الحساب الفرعي</th>
                                                <th class="tagHt">حركة المبالغ المدين</th>
                                                <th class="tagHt">حركة المبالغ  الدائن</th>
                                                <th class="tagHt">الفرق</th> <!-- إضافة عنوان العمود الجديد -->
                                                <th class="tagHt"></th> <!-- ��ضافة عنوان العمود الجديد -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mainAccount->subAccounts as $subAccount)
                                            
                                                <tr class="hover:bg-gray-100">
                                                    <td class=" tagTd px-6 py-4 border-b border-gray-200">
                                                        {{ $subAccount->sub_account_id }}
                                                    </td>
                                                    <td class="px-6 tagTd py-4 border-b border-gray-200">
                                                        {{ $subAccount->sub_name }}
                                                    </td>
                                                    <td class="px-6 py-4 tagTd border-b border-gray-200">
                                                        {{ number_format($subAccount->total_debit ?? 0) }} د.إ
                                                    </td>
                                                    <td class="px-6 py-4 tagTd border-b border-gray-200">
                                                        {{ number_format($subAccount->total_credit ?? 0) }} د.إ 
                                                    </td>
                                                    <td class="px-6 py-4 tagTd border-b border-gray-200
    @if(($subAccount->total_debit ?? 0) - ($subAccount->total_credit ?? 0) < 0)
     text-green-500  
    @else 
           text-red-500 
    @endif">
    
    {{-- حساب الفرق بين المدين والدائن --}}
    @php
        $balance = ($subAccount->total_debit ?? 0) - ($subAccount->total_credit ?? 0);
    @endphp
 @if($balance < 0)
 دائن/له
@else
 مدين/عليه
@endif
    {{-- عرض القيمة مع النص المناسب (مدين/دائن) --}}
    {{ number_format(abs($balance)) }} 
   
</td>

                                                
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-red-500">لا توجد حسابات رئيسية متاحة.</td>
                        </tr>
                        @endif

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@else
    <p class="text-red-500 text-center py-4">الفترة المحاسبية غير موجودة.</p>
@endif

<div class="overflow-x-auto mx-auto ">
    <div class="shadow-lg rounded-lg overflow-hidden">  
        <table id="myTable" class="min-w-full leading-normal border-collapse">
            <thead >
                <tr>
                    <th class="text-center" colspan="5"> الإتزامات/الخصوم</th>
                </tr>
                <tr class="bg-gray-200">
                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        اسم الحساب الرئيسي
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        حركة المبالغ المدين
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        حركة المبالغ  الدائن
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        عرض الحسابات الفرعية
                    </th>
                    <th></th>
                    
                </tr>
                
            </thead>
            <tbody class="bg-white"> 
                @isset($mainAccountsTotals)
                @forelse($mainAccountsTotals as $mainAccount)
                @if ($mainAccount->typeAccount==3)                    
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="px-6 py-4 border-b border-gray-200"><a href="#" class="text-blue-600 font-semibold" onclick="toggleSubAccounts({{ $mainAccount->main_account_id }})">{{ $mainAccount->account_name }}</a></td>
                        <td class="px-6 py-4 border-b border-gray-200"> {{ number_format($mainAccount->subAccounts->sum('total_debit')) }} د.إ </td>
                        <td class="px-6 py-4 border-b border-gray-200"> {{ number_format($mainAccount->subAccounts->sum('total_credit')) }} د.إ </td>
                        <td class="px-6 py-4 border-b border-gray-200
                            @if(($mainAccount->subAccounts->sum('total_debit') ?? 0) - ($mainAccount->subAccounts->sum('total_credit')?? 0) < 0)
                             text-green-500  
                            @else  text-red-500 
                            @endif"> {{-- حساب الفرق بين المدين والدائن --}}
                            @php
                                $balance = ($mainAccount->subAccounts->sum('total_debit') ?? 0) - ($mainAccount->subAccounts->sum('total_credit')?? 0) ;
                            @endphp
                             @if($balance < 0)دائن/له
                            @else مدين/عليه
                            @endif
                             {{ number_format(abs($balance)) }}       {{-- عرض القيمة مع النص المناسب (مدين/دائن) --}} 
                       </td>
                        <td class="px-6 py-4 border-b border-gray-200"><button onclick="toggleSubAccounts({{ $mainAccount->main_account_id }})" class="text-blue-500 underline hover:text-blue-700">عرض</button></td>
                    </tr>
                 @endisset
                    <!-- الحسابات الفرعية -->
                    <tr id="subAccounts-{{ $mainAccount->main_account_id }}" class="hidden">
                        <td colspan="4">
                            <div class="bg-gray-50 p-4">
                                <table class="min-w-full bg-white rounded-lg shadow-md">
                                    <thead>
                                        <tr>
                                            <th>رقم الحساب </th>

                                            <th class="tagHt">اسم الحساب </th>
                                            <th class="tagHt">حركة المبالغ المدين</th>
                                            <th class="tagHt">حركة المبالغ  الدائن</th>
                                            <th class="tagHt">الفرق</th> <!-- إضافة عنوان العمود الجديد -->
                                            <th class="tagHt"></th> <!-- ��ضافة عنوان العمود الجديد -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mainAccount->subAccounts as $subAccount)
                                        
                                            <tr class="hover:bg-gray-100">
                                                <td class=" tagTd px-6 py-4 border-b border-gray-200">
                                                    {{ $subAccount->sub_account_id }}
                                                </td>
                                                <td class="px-6 tagTd py-4 border-b border-gray-200">
                                                    {{ $subAccount->sub_name }}
                                                </td>
                                                <td class="px-6 py-4 tagTd border-b border-gray-200">
                                                    {{ number_format($subAccount->total_debit ?? 0) }} د.إ
                                                </td>
                                                <td class="px-6 py-4 tagTd border-b border-gray-200">
                                                    {{ number_format($subAccount->total_credit ?? 0) }} د.إ 
                                                </td>
                                                <td class="px-6 py-4 tagTd border-b border-gray-200
@if(($subAccount->total_debit ?? 0) - ($subAccount->total_credit ?? 0) < 0)
 text-green-500  
@else 
       text-red-500 
@endif">

{{-- حساب الفرق بين المدين والدائن --}}
@php
    $balance = ($subAccount->total_debit ?? 0) - ($subAccount->total_credit ?? 0);
@endphp
@if($balance < 0)
دائن/له
@else
مدين/عليه
@endif
{{-- عرض القيمة مع النص المناسب (مدين/دائن) --}}
{{ number_format(abs($balance)) }} 

</td>

                                            
                                        @endforeach
                                    </tbody>
                                </table>
                                
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-red-500">لا توجد حسابات رئيسية متاحة.</td>
                    </tr>
                    @endif

                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>


<script>
    function toggleSubAccounts(mainAccountId) {
        const subAccountsRow = document.getElementById(`subAccounts-${mainAccountId}`);
        subAccountsRow.classList.toggle('hidden');
    }
</script>

@endsection
