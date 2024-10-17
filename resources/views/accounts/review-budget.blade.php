@extends('layout')
@section('conm')
    <x-navbar_accounts/>

    <h1 class="text-2xl font-bold text-center py-4">مراجعة الميزانية</h1>

    @if($accountingPeriod)
        <h2 class="text-lg font-semibold text-center text-gray-600 mb-4">
            الفترة المحاسبية: {{ $accountingPeriod->Year }} - {{ $accountingPeriod->Month }}
        </h2>
        <div class="grid gap-4 mb-4 grid-cols-2">

<!-- جدول الأصول -->

<div class="overflow-x-auto mx-auto">
    <div class="shadow-lg rounded-lg overflow-hidden">
        <table id="myTable" class="min-w-full leading-normal border-collapse">
            <thead>
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
                    $totalDebit = 0;  // مجموع الفروقات المدينة
                    $totalCredit = 0; // مجموع الفروقات الدائنة
                @endphp
        
                @isset($mainAccountsTotals)
                    @forelse($mainAccountsTotals as $mainAccount)
                        @if ($mainAccount->typeAccount == 1)  <!-- فحص إذا كان نوع الحساب من الأصول -->
                            <tr class="hover:bg-gray-100 transition duration-300">
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <a href="#" class="text-blue-600 font-semibold" onclick="toggleSubAccounts({{ $mainAccount->main_account_id }})">
                                        {{ $mainAccount->account_name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ number_format($mainAccount->subAccounts->sum('total_debit')) }} </td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ number_format($mainAccount->subAccounts->sum('total_credit')) }} </td>
                                <td class="px-6 py-4 border-b border-gray-200 @if(($mainAccount->subAccounts->sum('total_debit') ?? 0) - ($mainAccount->subAccounts->sum('total_credit') ?? 0) < 0) text-green-500 @else text-red-500 @endif">
                                    @php
                                        $mainAccountBalance = ($mainAccount->subAccounts->sum('total_debit') ?? 0) - ($mainAccount->subAccounts->sum('total_credit') ?? 0);
                                    @endphp
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
                                                @php
                                                    $subAccountDebitTotal = 0;
                                                    $subAccountCreditTotal = 0;
                                                @endphp
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
                                                            {{ number_format(abs($subAccountBalance)) }}
                                                        </td>
                                                    </tr>
                                                    @php
                                                        // جمع الفروقات المدينة والدائنة لكل حساب فرعي
                                                        $subAccountDebitTotal += $subAccountBalance > 0 ? $subAccountBalance : 0;
                                                        $subAccountCreditTotal += $subAccountBalance < 0 ? abs($subAccountBalance) : 0;
                                                    @endphp
                                                @endforeach
        
                                                @php
                                                    // إضافة الفروقات إلى المجموع الكلي
                                                    $totalDebit += $subAccountDebitTotal;
                                                    $totalCredit += $subAccountCreditTotal;
                                                @endphp
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
        <!-- عرض مجموع الفروقات -->
        <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="">
                <label for="totaldebit" class="labelSale"> 
                    اجمالي المبالغ المدينة 
                </label>
                <input id="totaldebit" name="totaldebit" class="text-red-500 border-2 border-red-500 rounded w-full" type="text" value=" {{ number_format($totalDebit) }}">
            </div>
            <div class="">
                <label for="totalcredit" class="labelSale"> 
                    اجمالي المبالغ الدائنة 
                </label>
                <input id="totalcredit" name="totalcredit" class="text-green-500 border-2 border-green-500 rounded w-full" type="text" value="{{ number_format($totalCredit) }} ">
            </div>
        </div>
    </div>
</div>


         <!-- جدول الخصوم -->
         
<div class="overflow-x-auto mx-auto">
    <div class="shadow-lg rounded-lg overflow-hidden">
        <table id="myTable" class="min-w-full leading-normal border-collapse">
            <thead>
                <tr>
                    <th class="text-center" colspan="5">الإتزامات/الخصوم</th>
                </tr>
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
                    $totalDebit = 0;  // مجموع الفروقات المدينة
                    $totalCredit = 0; // مجموع الفروقات الدائنة
                @endphp
        
                @isset($mainAccountsTotals)
                    @forelse($mainAccountsTotals as $mainAccount)
                        @if ($mainAccount->typeAccount == 3)  <!-- فحص إذا كان نوع الحساب من الخصوم -->
                            <tr class="hover:bg-gray-100 transition duration-300">
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <a href="#" class="text-blue-600 font-semibold" onclick="toggleSubAccounts({{ $mainAccount->main_account_id }})">
                                        {{ $mainAccount->account_name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ number_format($mainAccount->subAccounts->sum('total_debit')) }} </td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ number_format($mainAccount->subAccounts->sum('total_credit')) }} </td>
                                <td class="px-6 py-4 border-b border-gray-200 @if(($mainAccount->subAccounts->sum('total_debit') ?? 0) - ($mainAccount->subAccounts->sum('total_credit') ?? 0) < 0) text-green-500 @else text-red-500 @endif">
                                    @php
                                        $mainAccountBalance = ($mainAccount->subAccounts->sum('total_debit') ?? 0) - ($mainAccount->subAccounts->sum('total_credit') ?? 0);
                                    @endphp
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
                                                    <th class="tagHt" >الفرق</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $subAccountDebitTotal = 0;
                                                    $subAccountCreditTotal = 0;
                                                @endphp
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
                                                            {{ number_format(abs($subAccountBalance)) }}
                                                        </td>
                                                    </tr>
                                                    @php
                                                        // جمع الفروقات المدينة والدائنة لكل حساب فرعي
                                                        $subAccountDebitTotal += $subAccountBalance > 0 ? $subAccountBalance : 0;
                                                        $subAccountCreditTotal += $subAccountBalance < 0 ? abs($subAccountBalance) : 0;
                                                    @endphp
                                                @endforeach
        
                                                @php
                                                    // إضافة الفروقات إلى المجموع الكلي
                                                    $totalDebit += $subAccountDebitTotal;
                                                    $totalCredit += $subAccountCreditTotal;
                                                @endphp
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
        <!-- عرض مجموع الفروقات -->
        <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="">
                <label for="totaldebit" class="labelSale"> 
                    اجمالي المبالغ المدينة 
                </label>
                <input id="totaldebit" name="totaldebit" class="text-red-500 border-2 border-red-500 rounded w-full" type="text" value=" {{ number_format($totalDebit) }}">
            </div>
            <div class="">
                <label for="totalcredit" class="labelSale"> 
                    اجمالي المبالغ الدائنة 
                </label>
                <input id="totalcredit" name="totalcredit" class="text-green-500 border-2 border-green-500 rounded w-full" type="text" value="{{ number_format($totalCredit) }} ">
            </div>
        </div>
    </div>
</div>
        </div>
    @else
        <p class="text-center text-red-500">لا توجد فترة محاسبية متاحة حالياً.</p>
    @endif

    <script>
        function toggleSubAccounts(mainAccountId) {
            const subAccountRow = document.getElementById(`subAccounts-${mainAccountId}`);
            if (subAccountRow.classList.contains('hidden')) {
                subAccountRow.classList.remove('hidden');
            } else {
                subAccountRow.classList.add('hidden');
            }
        }
    </script>
@endsection
