@extends('layout')

@section('conm')
    <x-navbar_accounts/>

    <h1 class="text font-bold text-center ">مراجعة الميزانية</h1>

    @if($accountingPeriod)
        <h2 class="text- font-semibold text-center text-gray-600 ">
            الفترة المحاسبية: {{ $accountingPeriod->Year }} - {{ $accountingPeriod->Month }}
        </h2>
        <div class="grid gap-4 grid-cols-2">
             <div>       
               <h3 class=" bg-[#2430d3] font-semibold text-center text-gray-50 ">الأصول</h3>
           </div>
            <div>    
                <h3 class=" bg-[#2430d3] font-semibold text-center text-gray-50 ">الإتزامات/الخصوم</h3>
            </div>
        </div>
        <div class="grid gap-4  grid-cols-2">

        <!-- جدول الأصول -->
        <div class="overflow-x-auto mx-auto px-1">
            <div class="max-w-7xl mx-auto h-screen overflow-auto">

                <table class="min-w-full border-collapse border border-gray-200">
                    <thead class="bg-[#2430d3] text-white">
                        <tr class="bg-gray-200">
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                اسم الحساب الرئيسي
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                حركة المبالغ المدين
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                حركة المبالغ الدائن
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                عرض الحسابات الفرعية
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @php
                            $totalDebit = $totalDebit ?? 0;
                            $totalCredit = $totalCredit ?? 0;
                        @endphp

                        @isset($mainAccountsTotals)
                            @forelse($mainAccountsTotals as $mainAccount)
                            
                                @if (in_array($mainAccount->typeAccount, [1, 2])) <!-- فحص إذا كان نوع الحساب من الأصول -->
                                @php
                                $mainAccountBalance = $mainAccount->subAccounts->sum('balance');
                            @endphp
                                <tr class="hover:bg-gray-100 transition duration-300">
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <a href="#" class="text-blue-600 font-semibold" onclick="toggleSubAccounts({{ $mainAccount->main_account_id }})">
                                                {{ $mainAccount->account_name }}
                                            </a>
                                        </td>
                                       
                                        <td class="px-6 py-4 border-b border-gray-200">{{ number_format($mainAccount->subAccounts->sum('total_debit')) }} </td>
                                        <td class="px-6 py-4 border-b border-gray-200">{{ number_format($mainAccount->subAccounts->sum('total_credit')) }} </td>
                                        <td class="tagTd @if($mainAccountBalance < 0) text-green-500 @else text-red-500 @endif">
                                            @if($mainAccountBalance < 0) دائن/له @else مدين/عليه @endif
                                           
                                            {{ number_format(abs($mainAccountBalance)) }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <button onclick="toggleSubAccounts({{ $mainAccount->main_account_id }})" class="text-blue-500 underline hover:text-blue-700">
                                                عرض
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- الحسابات الفرعية -->
                                    <tr id="subAccounts-{{ $mainAccount->main_account_id }}" class="hidden">
                                        <td colspan="5">
                                            <div class="bg-gray-50 p-4">
                                                <table class="min-w-full bg-white rounded-lg shadow-md">
                                                    <thead>
                                                        <tr>
                                                            <th class="tagHt">رقم الحساب الفرعي</th>
                                                            <th class="tagHt">اسم الحساب الفرعي</th>
                                                            <th class="tagHt">حركة المبالغ المدين</th>
                                                            <th class="tagHt">حركة المبالغ الدائن</th>
                                                            <th class="tagHt">الفرق</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($mainAccount->subAccounts as $subAccount)
                                                            @php
                                                                $subAccountBalance =   ($subAccount->total_debit ?? 0)-($subAccount->total_credit?? 0);
                                                            @endphp
                                                            <tr class="hover:bg-gray-100">
                                                                <td class="tagTd">{{ $subAccount->sub_account_id }}</td>
                                                                <td class="tagTd">{{ $subAccount->sub_name }}</td>
                                                                <td class="tagTd">{{ number_format($subAccount->total_debit) }} د.إ</td>
                                                                <td class="tagTd">{{ number_format($subAccount->total_credit ?? 0) }} د.إ</td>
                                                                <td class="tagTd @if($subAccountBalance < 0) text-green-500 @else text-red-500 @endif">
                                                                    @if($subAccountBalance < 0) دائن/له @else مدين/عليه @endif
                                                                    {{ number_format(abs($subAccountBalance)) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-red-500">لا توجد حسابات رئيسية متاحة.</td>
                                </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>

               
            </div>
             <!-- عرض مجموع الفروقات -->
             <div class="grid gap-4 mb-4 grid-cols-2">
                @if ($totalDebit >0)
                <div> 
                    <label for="totaldebit2" class="labelSale"> 
                        اجمالي المبالغ المدينة 
                    </label>
                    <input id="totaldebit2" name="totaldebit2" class="text-red-500 border-2 border-red-500 rounded w-full" type="text" value=" {{ number_format(abs($totalDebit)) }}">
                </div>
                <div>
                    <label for="totalcredit2" class="labelSale"> 
                        اجمالي المبالغ الدائنة 
                    </label>
                    <input id="totalcredit2" name="totalcredit2" class="text-green-500 border-2 border-green-500 rounded w-full" type="text" value="0 ">
                </div>
                @endif
                @if ($totalDebit < 0)
                <div> 
                    <label for="totaldebit2" class="labelSale"> 
                        اجمالي المبالغ المدينة 
                    </label>
                    <input id="totaldebit2" name="totaldebit2" class="text-red-500 border-2 border-red-500 rounded w-full" type="text" value=" 0">
                </div>
                <div>
                    <label for="totalcredit2" class="labelSale"> 
                        اجمالي المبالغ الدائنة 
                    </label>
                    <input id="totalcredit2" name="totalcredit2" class="text-green-500 border-2 border-green-500 rounded w-full" type="text" value="{{ number_format(abs($totalDebit )) }} ">
                </div>
                @endif
            </div>
        </div>

        <!-- جدول الخصوم -->
        <div class="overflow-x-auto mx-auto px-1">
            <div class=" min-w-full shadow rounded-lg  overflow-x-auto overflow-y-auto text-sm ">

                <table class="min-w-full bg-white text-sm">
                    <thead class="bg-[#2430d3] text-white">
                        
                        <tr class="bg-gray-200">
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                اسم الحساب الرئيسي
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                حركة المبالغ المدين
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                حركة المبالغ الدائن
                            </th>
                           
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                الرصيد النهائي للحساب
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                عرض الحسابات الفرعية
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @php
                            $totalDebit2 = $totalDebit2 ?? 0;
                            $totalCredit2 = $totalCredit2 ?? 0;
                        @endphp

                        @isset($mainAccountsTotals)
                            @forelse($mainAccountsTotals as $mainAccount)
                                @if ($mainAccount->typeAccount == 3) 
                                <!-- فحص إذا كان نوع الحساب من الخصوم -->
                                @php
                                $mainAccountBalance = $mainAccount->subAccounts->sum('balance');
                            @endphp

                                    <tr class="hover:bg-gray-100 transition duration-300">
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <a href="#" class="text-blue-600 font-semibold" onclick="toggleSubAccounts({{$mainAccount->main_account_id }})">
                                                {{ $mainAccount->account_name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200">{{ number_format($mainAccount->subAccounts->sum('total_debit')) }} </td>
                                        <td class="px-6 py-4 border-b border-gray-200">{{ number_format($mainAccount->subAccounts->sum('total_credit')) }} </td>
                                          
                                              <td class="tagTd @if($mainAccountBalance < 0) text-green-500 @else text-red-500 @endif">
                                            @if($mainAccountBalance < 0) دائن/له @else مدين/عليه @endif
                                           
                                            {{ number_format(abs($mainAccountBalance)) }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <button onclick="toggleSubAccounts({{ $mainAccount->main_account_id }})" class="text-blue-500 underline hover:text-blue-700">
                                                عرض
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- الحسابات الفرعية -->
                                    <tr id="subAccounts-{{ $mainAccount->main_account_id }}" class="hidden">
                                        <td colspan="5">
                                            <div class="bg-gray-50 p-4">
                                                <table class="min-w-full bg-white rounded-lg shadow-md">
                                                    <thead>
                                                        <tr>
                                                            <th class="tagHt">رقم الحساب الفرعي</th>
                                                            <th class="tagHt">اسم الحساب الفرعي</th>
                                                            <th class="tagHt">حركة المبالغ المدين</th>
                                                            <th class="tagHt">حركة المبالغ الدائن</th>
                                                            <th class="tagHt">الفرق</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($mainAccount->subAccounts as $subAccount)
                                                            @php
                                                                $subAccountBalance = ($subAccount->total_debit ?? 0) - ($subAccount->total_credit ?? 0);
                                                            @endphp
                                                            <tr class="hover:bg-gray-100">
                                                                <td class="tagTd">{{ $subAccount->sub_account_id }}</td>
                                                                <td class="tagTd">{{ $subAccount->sub_name }}</td>
                                                                <td class="tagTd">{{ number_format($subAccount->total_debit ?? 0) }} د.إ</td>
                                                                <td class="tagTd">{{ number_format($subAccount->total_credit ?? 0) }} د.إ</td>
                                                                <td class="tagTd @if($subAccountBalance < 0) text-green-500 @else text-red-500 @endif">
                                                                    @if($subAccountBalance < 0) دائن/له @else مدين/عليه @endif
                                                                    {{ number_format(($subAccountBalance)) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-red-500">لا توجد حسابات رئيسية متاحة.</td>
                                </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
                @endif

                <!-- عرض مجموع الفروقات -->
                 <div class="grid gap-4 mb-4 grid-cols-2">
                    @if ($totalCredit2 >0)
                    <div> 
                        <label for="totaldebit2" class="labelSale"> 
                            اجمالي المبالغ المدينة 
                        </label>
                        <input id="totaldebit2" name="totaldebit2" class="text-red-500 border-2 border-red-500 rounded w-full" type="text" value=" {{ number_format(abs($totalCredit2)) }}">
                    </div>
                    <div>
                        <label for="totalcredit2" class="labelSale"> 
                            اجمالي المبالغ الدائنة 
                        </label>
                        <input id="totalcredit2" name="totalcredit2" class="text-green-500 border-2 border-green-500 rounded w-full" type="text" value="0 ">
                    </div>
                    @endif
                    @if ($totalCredit2 < 0)
                    <div> 
                        <label for="totaldebit2" class="labelSale"> 
                            اجمالي المبالغ المدينة 
                        </label>
                        <input id="totaldebit2" name="totaldebit2" class="text-red-500 border-2 border-red-500 rounded w-full" type="text" value=" 0">
                    </div>
                    <div>
                        <label for="totalcredit2" class="labelSale"> 
                            اجمالي المبالغ الدائنة 
                        </label>
                        <input id="totalcredit2" name="totalcredit2" class="text-green-500 border-2 border-green-500 rounded w-full" type="text" value="{{ number_format(abs($totalCredit2 )) }} ">
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
<script>
    function toggleSubAccounts(accountId) {
    const subAccountRow = document.getElementById(`subAccounts-${accountId}`);
    if (subAccountRow.classList.contains('hidden')) {
        subAccountRow.classList.remove('hidden');
    } else {
        subAccountRow.classList.add('hidden');
    }
}

</script>
@endsection

