<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>كشف حساب {{$Myanalysis}}</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    <style>
          body {
        font-family: Arial, sans-serif; /* الخط الافتراضي */
    }
    .english {
        font-family: 'Times New Roman', serif; /* الخط الإنجليزي */
    }
        /* تخصيص للطباعة */
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .print-container {
                @apply w-full max-w-full mx-auto p-2;
            }

            .no-print {
                display: none;
            }
        }

    table {
        table-layout: ; /* استخدم تخطيط ثابت */
        width: 100%;
    }

    th, td {
        border: 1px solid #000;
        /* padding: 8px; */
    }

   

    /* تحسين مظهر الجدول */
    .header-section, .totals-section {
        margin-top: 10px;
        border: 2px solid #000;
        border-radius: 8px;
    }
        
    </style>
</head>
<body class="bg-white">
    <div class="print-container px-1">
        @if(isset($buss))
        <div class="header border-2 border-black bg-[#1749fd15] rounded-lg">
            <div class="flex p-2 w-full">
                <div class="text-right font-bold">
                    <h2 class="font-extrabold">{{ $buss->Company_Name }}</h2>
                    <p class="text-sm text-gray-700">{{ $buss->Services }}</p>
                    <p class="text-sm text-gray-700">العنوان: {{ $buss->Company_Address }}</p>
                    <p class="text-sm text-gray-700">التلفون: {{ $buss->Phone_Number }}</p>
                </div>
                <div class="flex w-20 h-20">
                    <img class="bg-[#1749fd15] rounded-3xl" src="{{ url($buss->Company_Logo ? 'images/' . $buss->Company_Logo : '') }}" alt="">
                </div>
                <div class="text-left font-bold">
                    <h2 class="font-extrabold">{{ $buss->Company_NameE }}</h2>
                    <p class="text-sm text-gray-700">{{ $buss->ServicesE }}</p>
                    <p class="text-sm text-gray-700">Address: {{ $buss->Company_AddressE }}</p>
                    <p class="text-sm text-gray-700">Phone: {{ $buss->Phone_Number }}</p>
                </div>
            </div>
            <div class="text-center">
                <p class="font-extrabold">كشف حساب {{ $Myanalysis }} - رصيد نهاية التقرير</p>
                <div class="grid grid-cols-2 w-full gap-2 text-sm font-bold text-gray-700">
                    <div>تاريخ: {{ $startDate }}</div>
                    <div>{{ __('الى التاريخ  ') }}: {{ $endDate }}</div>
                </div>
            </div>
        </div>
        @endif

        <header class="flex justify-between items-center border-b-2 border-gray-800 pb-1 mb-1">
            <div class="flex mt-2 gap-5">
                <div class="font-extrabold">{{ __('رقم ') }}  {{ $AccountClassName ?? __(' ') }}:</div>
                <div>{{ $customerMain->sub_account_id ?? $customerMain->main_account_id ?? __(' ') }}</div>
                <div>{{ $customerMain->sub_name ?? $customerMain->account_name ?? __(' ') }}/{{ $customer->name_The_known ?? __(' ') }}</div>
            </div>
            <div class="flex mt-2">
                <div class="font-extrabold">{{ __('العملة') }} :</div>
                <div>{{ $currencysettings ?? __('YR') }}</div>
            </div>
        </header>

        <table class="text-sm font-semibold overflow-y-auto max-h-[80vh]">
            <thead>
                <tr>
                    <th colspan="3"></th>
                    <th class="text-center" colspan="2">المبالغ بالعملة المحلية</th>
                    <th class="text-center" colspan="2">المبالغ بالعملة السعودية</th>
                    @isset($Sum_debitd_USD)
                    <th class="text-center" colspan="2">المبالغ بالعملة الدولار</th>
                    @endisset
                </tr>
                <tr class="bg-blue-100 ">

                    <th class=" text-right">#</th>
                    <th class=" text-right">اسم الحساب</th>
                    <th class=" text-right">رقم الحساب</th>
                    <th class=" text-center">المدين</th>
                    <th class=" text-center">الدائن</th>
                    
                    <th class=" text-center">المدين</th>
                    <th class=" text-center">الدائن</th>
                    @isset($Sum_debitd_USD)

                    <th class=" text-center">المدين</th>
                    <th class=" text-center">الدائن</th>
                    @endisset
                </tr>
            </thead>
            <tbody class="bg-white">
                @if(isset($balances))
                @foreach ($balances as $index => $balance)
                    <tr class="hover:bg-gray-50">
                        <td class="text-right">{{ $index + 1 }}</td>
                        <td class=" text-right">{{ $balance['sub_name'] }}</td>
                        <td class=" text-center">{{ $balance['sub_account_id'] }}</td>
                        @php
                            // Local Currency (ريال.يمني)
                            $totalDebit = 0;
                            $totalCredit = 0;
                            $sumAmount = $balance->total_debit - abs($balance->total_credit);
                            if($sumAmount >= 0) {
                                $totalDebit = $sumAmount;
                                $totalCredit = 0;
                            }
                            if($sumAmount < 0) {
                                $totalCredit = $sumAmount;
                                $totalDebit = 0;
                            }
                        @endphp
                        {{-- Local Currency Debit --}}
                        <td class="text-center">
                            @php
                                $wholeNumber = floor($totalDebit);
                                $decimal = $totalDebit - $wholeNumber;
                            @endphp
                            {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                        </td>
                        {{-- Local Currency Credit --}}
                        <td class="text-center">
                            @php
                                $wholeNumber = floor(abs($totalCredit));
                                $decimal = abs($totalCredit) - $wholeNumber;
                            @endphp
                            {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                        </td>

                        {{-- Saudi Currency (ريال سعودي) --}}
                        @php
                            $totalDebits = 0;
                            $totalCredits = 0;
                            $sumAmounts = $balance->total_debits - abs($balance->total_credits);
                            if($sumAmounts >= 0) {
                                $totalDebits = $sumAmounts;
                                $totalCredits = 0;
                            }
                            if($sumAmounts < 0) {
                                $totalCredits = $sumAmounts;
                                $totalDebits = 0;
                            }
                        @endphp
                        {{-- Saudi Currency Debit --}}
                        <td class="text-center">
                            @php
                                $wholeNumber = floor($totalDebits);
                                $decimal = $totalDebits - $wholeNumber;
                            @endphp
                            {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                        </td>
                        {{-- Saudi Currency Credit --}}
                        <td class="text-center">
                            @php
                                $wholeNumber = floor(abs($totalCredits));
                                $decimal = abs($totalCredits) - $wholeNumber;
                            @endphp
                            {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                        </td>
                        @isset($Sum_debitd_USD)

                        @php
                            $totalDebitUSD=0;
                            $totalCredistUSD=0;
                            $sumAmountUSD=$balance->total_debitd-abs($balance->total_creditd);
                            if($sumAmountUSD>=0)
                            {
                                $totalDebitUSD=$sumAmountUSD;
                                $totalCredistUSD=0;
                            }
                            if($sumAmountUSD<0)
                            {
                                $totalCredistUSD=$sumAmountUSD;
                                $totalDebitUSD=0;
                            }
                            $wholeNumberUS = floor($totalDebitUSD);
                            $decimalUS = $totalDebitUSD - $wholeNumberUS;
                        @endphp
                        <td class=" text-center">
                        {{ number_format($wholeNumberUS, 0, '.', ',') }}@if($decimalUS > 0)<span class="text-green-600">.{{ substr(number_format($decimalUS, 2, '.', ''), 2) }}</span>@endif
                        </td>
                        <td class=" text-center">
                        @php
                            $wholeNumberUS = floor(abs($totalCredistUSD));
                            $decimalUS = abs($totalCredistUSD) - $wholeNumberUS;
                        @endphp
                        {{ number_format($wholeNumberUS, 0, '.', ',') }}@if($decimalUS > 0)<span class="text-green-600">.{{ substr(number_format($decimalUS, 2, '.', ''), 2) }}</span>@endif
                        </td>
                        @endisset


                    </tr>
                @endforeach
                <tr class="bg-blue-100">
                    <th colspan="3" class="text-right">اجمالي الرصيد</th>
                    <td class=" text-center">
                    @php
                        $wholeNumber = floor($SumDebtor_amount);
                        $decimal = $SumDebtor_amount - $wholeNumber;
                    @endphp
                    {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                    </td>
                    <td class=" text-center">
                    @php
                        $wholeNumber = floor(abs($SumCredit_amount));
                        $decimal = abs($SumCredit_amount) - $wholeNumber;
                    @endphp
                    {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                    </td>   
                             </tr>
                @endif
            </tbody>
        </table>

        <table class="w-[60%] text-sm">
            <thead>
                <tr class="bg-blue-100">
                    <th>
                        @php
                        $sum = $SumDebtor_amount - $SumCredit_amount;
                        $commintString = $sum >= 0 ? "عليكم/ رصيد" : "لكم/ رصيد";
                        @endphp
                        <p>{{ $commintString }}</p>
                    </th>
                    <th class="px-2 text-right">
                        {{ number_format(abs($Sale_priceSum), 2, '.', ',') ?? 0 }}
                        <p class="text-sm">{{ $priceInWords }}</p>
                    </th>
                </tr>
            </thead>
        </table>

        <div class="totals-section p-4">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm" dir="ltr">المستخدم: {{ $UserName ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="mt-4 no-print">
            <button onclick="printAndClose()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">طباعة</button>
            <button onclick="closeWindow()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">إلغاء الطباعة</button>
        </div>
    </div>

    <script>
        function printAndClose() {
            window.print();
            setTimeout(() => {
                window.close();
            }, 500);
        }

        function closeWindow() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.close();
            }
        }
    </script>
</body>
</html>