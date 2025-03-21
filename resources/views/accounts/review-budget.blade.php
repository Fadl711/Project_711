@extends('layout')

@section('conm')
    <x-navbar_accounts/>
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
    <h1 class="text font-bold text-center "> الميزانية المومية</h1>
    <div class=" bg-white shadow-md sm:rounded-lg w-full px-1 py-2 max-h-full flex ">

    <div class="overflow-x-auto w-full ">
        <table class="text-sm font-semibold w-full overflow-y-auto max-h-[80vh] border-collapse">
            <thead class="bg-gray-100 sticky top-0 uppercase dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="text-left  border-0 bg-white" colspan="4">الاصول</th>
                </tr>
                  
                        <tr>
                            <th colspan="3"></th>
                            @if($debit_YER!=0||$credit_YER!=0)
                            <th class="text-center" colspan="2">المبالغ بالعملة المحلية</th>
                            @endif
                            @if($debits_SAD!=0||$credits_SAD!=0)
                            <th class="text-center" colspan="2">المبالغ بالعملة السعودية</th>
                            @endif
        
                            @if($debitd_USD!=0||$credits_USD!=0)
                            <th class="text-center" colspan="2">المبالغ بالعملة الدولار</th>
                            @endif
                        </tr>
                        <tr class="bg-blue-100 ">
        
                            <th class=" text-right">#</th>
                            <th class=" text-right">اسم الحساب</th>
                            <th class=" text-right">رقم الحساب</th>
                            @if($debit_YER!=0||$credit_YER!=0)
                            <th class=" text-center">المدين</th>
                            <th class=" text-center">الدائن</th>
                            @endif
        
                             @if($debits_SAD!=0||$credits_SAD!=0)
                            <th class=" text-center">المدين</th>
                            <th class=" text-center">الدائن</th>
                            @endif
                            @if($debitd_USD!=0||$credits_USD!=0)
                            <th class=" text-center">المدين</th>
                            <th class=" text-center">الدائن</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @if(isset($balances))
                        @php
                            $total_debits_SAD=0;
                            $total_credits_SAD=0;
                            $total_debit_YER=0;
                            $total_credit_YER=0;
                            $total_debitd_USD=0;
                            $total_credits_USD=0;
                        @endphp
                        @foreach ($balances as $index => $balance)
                        @if (in_array($balance['type_account'], [1,2,5]))
                            {{-- @dd($balance['typeAccount']) --}}
                   
                            <tr class="hover:bg-gray-50">
                                <td class="text-right">{{ $index + 1 }}</td>
                                <td class=" text-right">{{ $balance['sub_name'] }}</td>
                                <td class=" text-center">{{ $balance['sub_account_id'] }}</td>
                                @php
                                    // Local Currency (ريال.يمني)
                                    $totalDebitYER = 0;
                                    $totalCreditYER = 0;
                                    $sumAmount = $balance->total_debit - abs($balance->total_credit);
                                    if($sumAmount >= 0) {
                                        $totalDebitYER = $sumAmount;
                                        $totalCreditYER = 0;
                                        $total_debit_YER+=$sumAmount;
                                    }
                                    if($sumAmount < 0)
                                     {
                                        $totalCreditYER = $sumAmount;
                                        $totalDebitYER = 0;
                                        $total_credit_YER+=$sumAmount;
                                    }
                                @endphp
                                {{-- Local Currency Debit --}}
                                <td class="text-center">
                                    @php
                                        $wholeNumber = floor($totalDebitYER);
                                        $decimal = $totalDebitYER - $wholeNumber;
                                     
                                    @endphp
        
        {{ $wholeNumber != 0 ? number_format($wholeNumber, 0, '.', ',') : '' }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''??''), 2) }}</span>@endif
                                </td>
                                {{-- Local Currency Credit --}}
                                <td class="text-center">
                                    @php
                                        $wholeNumber = floor(abs($totalCreditYER));
                                        $decimal = abs($totalCreditYER) - $wholeNumber;
                                    @endphp
        {{ $wholeNumber != 0 ? number_format($wholeNumber, 0, '.', ',') : '' }}
        @if(!is_null($decimal) && $decimal > 0)
            <span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>
        @endif
                                </td>
        
                                {{-- Saudi Currency (ريال سعودي) --}}
                                @php
                                    $totalDebitsSAD = 0;
                                    $totalCreditsSAD = 0;
                                    $sumAmounts = $balance->total_debits - abs($balance->total_credits);
                                    if($sumAmounts >= 0) {
                                        $totalDebitsSAD = $sumAmounts;
                                        $totalCreditsSAD = 0;
                                        $total_debits_SAD+=$totalDebitsSAD;
                                    }
                                    if($sumAmounts < 0) {
                                        $totalCreditsSAD  = $sumAmounts;
                                        $totalDebitsSAD = 0;
                                            $total_credits_SAD+=$totalCreditsSAD;
        
                                    }
                                @endphp
                                {{-- Saudi Currency Debit --}}
                                <td class="text-center">
                                    @php
                                        $wholeNumber = floor($totalDebitsSAD);
                                        $decimal = $totalDebitsSAD - $wholeNumber;
                                    @endphp
                                   {{ $wholeNumber != 0 ? number_format($wholeNumber, 0, '.', ',') : '' }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                                </td>
                                {{-- Saudi Currency Credit --}}
                                <td class="text-center">
                                    @php
                                        $wholeNumber = floor(abs($totalCreditsSAD));
                                        $decimal = abs($totalCreditsSAD) - $wholeNumber;
                                    @endphp
                                    {{ $wholeNumber != 0 ? number_format($wholeNumber, 0, '.', ',') : '' }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                                </td>
                                @if($debitd_USD!=0||$credits_USD!=0)
                                @php
                                    $totalDebitUSD=0;
                                    $totalCredistUSD=0;
                                    $sumAmountUSD=$balance->total_debitd-abs($balance->total_creditd);
                                    if($sumAmountUSD>=0)
                                    {
                                        $totalDebitUSD=$sumAmountUSD;
                                        $total_debitd_USD+=$sumAmountUSD;
                                        $totalCredistUSD=0;
                                    }
                                    if($sumAmountUSD<0)
                                    {
                                        $totalCredistUSD=$sumAmountUSD;
                                        $totalDebitUSD=0;
                                        $total_credits_USD+=$sumAmountUSD;
                                    }
                                    $wholeNumberUS = floor($totalDebitUSD);
                                    $decimalUS = $totalDebitUSD - $wholeNumberUS;
                                @endphp
        
                                <td class=" text-center">
                                {{ $wholeNumberUS != 0 ? number_format($wholeNumberUS, 0, '.', ',') : '' }}@if($decimalUS > 0)<span class="text-green-600">.{{ substr(number_format($decimalUS, 2, '.', ''), 2) }}</span>@endif
                                </td>
                                <td class=" text-center">
                                @php
                                    $wholeNumberUS = floor(abs($totalCredistUSD));
                                    $decimalUS = abs($totalCredistUSD) - $wholeNumberUS;
                                @endphp
                                 {{ $wholeNumberUS != 0 ? number_format($wholeNumberUS, 0, '.', ',') : '' }}@if($decimalUS > 0)<span class="text-green-600">.{{ substr(number_format($decimalUS, 2, '.', ''), 2) }}</span>@endif
                                </td>
                                @endif
        
        
                            </tr>
                            @endif
                        @endforeach
                        <tr class="bg-blue-100">
                            
                            <th colspan="3" class="text-right">اجمالي الرصيد</th>
                            @if($debit_YER!=0||$credit_YER!=0)
                            @php
                            $balance_YER= abs($total_debit_YER)-abs($total_credit_YER);
                            $wholeNumber = floor(abs($balance_YER));
                            $decimal = abs($balance_YER) - $wholeNumber;

                        @endphp
                            <td class=" text-center">
                                @if ($balance_YER>0)
                                {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                                @endif
                        
                            </td>
                            <td class=" text-center">
                                @if ($balance_YER<0)
                                {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                                @endif
                            </td>  
                            @endif 
        
                            @if($debits_SAD!=0||$credits_SAD!=0)
                            
                            <td class=" text-center">
                            @php
                               $balance_SAD= abs($total_debits_SAD)-abs($total_credits_SAD);
                            $wholeNumber = floor(abs($balance_SAD));
                            $decimal = abs($balance_SAD) - $wholeNumber;
                            @endphp
                              @if ($balance_SAD>0)
                                {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                                @endif
                            </td>
                            <td class=" text-center">
                                @if ($balance_SAD<0)
                                {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                                @endif                            </td>   
                            @endif
                            @if($debitd_USD!=0||$credits_USD!=0)
        
                            <td class=" text-center">
                            @php
                               $balance_USD= abs($total_debitd_USD)-abs($total_credits_USD);
                            $wholeNumber = floor(abs($balance_USD));
                            $decimal = abs($balance_USD) - $wholeNumber;

                                // $wholeNumberusd = floor($total_debitd_USD);
                                // $decimal = $total_debitd_USD - $wholeNumberusd;
                            @endphp
                             @if ($balance_USD>0)
                             {{ number_format($wholeNumberusd, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                             @endif 
                            </td>
                            <td class=" text-center">
                          
                                @if ($balance_USD<0)
                                {{ number_format($wholeNumberusd, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                                @endif                             </td>   
                            @endif
                                     </tr>
                        @endif
                    </tbody>
                </table>
</div>

<div class="overflow-x-auto w-full">
    <table class="text-sm font-semibold w-full overflow-y-auto max-h-[80vh] border-collapse">
        <thead class="bg-gray-100 sticky top-0 uppercase dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="text-left  border-0 bg-white" colspan="4">حقوق الملكية + الإتيزامات</th>
            </tr>
              
                    <tr>
                        <th colspan="3"></th>
                        @if($debit_YER!=0||$credit_YER!=0)
                        <th class="text-center" colspan="2">المبالغ بالعملة المحلية</th>
                        @endif
                        @if($debits_SAD!=0||$credits_SAD!=0)
                        <th class="text-center" colspan="2">المبالغ بالعملة السعودية</th>
                        @endif
    
                        @if($debitd_USD!=0||$credits_USD!=0)
                        <th class="text-center" colspan="2">المبالغ بالعملة الدولار</th>
                        @endif
                    </tr>
                    <tr class="bg-blue-100 ">
    
                        <th class=" text-right">#</th>
                        <th class=" text-right">اسم الحساب</th>
                        <th class=" text-right">رقم الحساب</th>
                        @if($debit_YER!=0||$credit_YER!=0)
                        <th class=" text-center">المدين</th>
                        <th class=" text-center">الدائن</th>
                        @endif
    
                         @if($debits_SAD!=0||$credits_SAD!=0)
                        <th class=" text-center">المدين</th>
                        <th class=" text-center">الدائن</th>
                        @endif
                        @if($debitd_USD!=0||$credits_USD!=0)
                        <th class=" text-center">المدين</th>
                        <th class=" text-center">الدائن</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if(isset($balances))
                    @php
                        $total_debits_SAD=0;
                        $total_credits_SAD=0;
                        $total_debit_YER=0;
                        $total_credit_YER=0;
                        $total_debitd_USD=0;
                        $total_credits_USD=0;
                    @endphp
                    @foreach ($balances as $index => $balance)
                    @if (in_array($balance['type_account'], [3]))
               
                        <tr class="hover:bg-gray-50">
                            <td class="text-right">{{ $index + 1 }}</td>
                            <td class=" text-right">{{ $balance['sub_name'] }}</td>
                            <td class=" text-center">{{ $balance['sub_account_id'] }}</td>
                            @php
                                // Local Currency (ريال.يمني)
                                $totalDebitYER = 0;
                                $totalCreditYER = 0;
                                $sumAmount = $balance->total_debit - abs($balance->total_credit);
                                if($sumAmount >= 0) {
                                    $totalDebitYER = $sumAmount;
                                    $totalCreditYER = 0;
                                    $total_debit_YER+=$sumAmount;
                                }
                                if($sumAmount < 0)
                                 {
                                    $totalCreditYER = $sumAmount;
                                    $totalDebitYER = 0;
                                    $total_credit_YER+=$sumAmount;
                                }
                            @endphp
                            {{-- Local Currency Debit --}}
                            <td class="text-center">
                                @php
                                    $wholeNumber = floor($totalDebitYER);
                                    $decimal = $totalDebitYER - $wholeNumber;
                                 
                                @endphp
    
    {{ $wholeNumber != 0 ? number_format($wholeNumber, 0, '.', ',') : '' }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''??''), 2) }}</span>@endif
                            </td>
                            {{-- Local Currency Credit --}}
                            <td class="text-center">
                                @php
                                    $wholeNumber = floor(abs($totalCreditYER));
                                    $decimal = abs($totalCreditYER) - $wholeNumber;
                                @endphp
    {{ $wholeNumber != 0 ? number_format($wholeNumber, 0, '.', ',') : '' }}
    @if(!is_null($decimal) && $decimal > 0)
        <span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>
    @endif
                            </td>
    
                            {{-- Saudi Currency (ريال سعودي) --}}
                            @php
                                $totalDebitsSAD = 0;
                                $totalCreditsSAD = 0;
                                $sumAmounts = $balance->total_debits - abs($balance->total_credits);
                                if($sumAmounts >= 0) {
                                    $totalDebitsSAD = $sumAmounts;
                                    $totalCreditsSAD = 0;
                                    $total_debits_SAD+=$totalDebitsSAD;
                                }
                                if($sumAmounts < 0) {
                                    $totalCreditsSAD  = $sumAmounts;
                                    $totalDebitsSAD = 0;
                                        $total_credits_SAD+=$totalCreditsSAD;
    
                                }
                            @endphp
                            {{-- Saudi Currency Debit --}}
                            <td class="text-center">
                                @php
                                    $wholeNumber = floor($totalDebitsSAD);
                                    $decimal = $totalDebitsSAD - $wholeNumber;
                                @endphp
                               {{ $wholeNumber != 0 ? number_format($wholeNumber, 0, '.', ',') : '' }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                            </td>
                            {{-- Saudi Currency Credit --}}
                            <td class="text-center">
                                @php
                                    $wholeNumber = floor(abs($totalCreditsSAD));
                                    $decimal = abs($totalCreditsSAD) - $wholeNumber;
                                @endphp
                                {{ $wholeNumber != 0 ? number_format($wholeNumber, 0, '.', ',') : '' }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                            </td>
                            @if($debitd_USD!=0||$credits_USD!=0)
                            @php
                                $totalDebitUSD=0;
                                $totalCredistUSD=0;
                                $sumAmountUSD=$balance->total_debitd-abs($balance->total_creditd);
                                if($sumAmountUSD>=0)
                                {
                                    $totalDebitUSD=$sumAmountUSD;
                                    $total_debitd_USD+=$sumAmountUSD;
                                    $totalCredistUSD=0;
                                }
                                if($sumAmountUSD<0)
                                {
                                    $totalCredistUSD=$sumAmountUSD;
                                    $totalDebitUSD=0;
                                    $total_credits_USD+=$sumAmountUSD;
                                }
                                $wholeNumberUS = floor($totalDebitUSD);
                                $decimalUS = $totalDebitUSD - $wholeNumberUS;
                            @endphp
    
                            <td class=" text-center">
                            {{ $wholeNumberUS != 0 ? number_format($wholeNumberUS, 0, '.', ',') : '' }}@if($decimalUS > 0)<span class="text-green-600">.{{ substr(number_format($decimalUS, 2, '.', ''), 2) }}</span>@endif
                            </td>
                            <td class=" text-center">
                            @php
                                $wholeNumberUS = floor(abs($totalCredistUSD));
                                $decimalUS = abs($totalCredistUSD) - $wholeNumberUS;
                            @endphp
                             {{ $wholeNumberUS != 0 ? number_format($wholeNumberUS, 0, '.', ',') : '' }}@if($decimalUS > 0)<span class="text-green-600">.{{ substr(number_format($decimalUS, 2, '.', ''), 2) }}</span>@endif
                            </td>
                            @endif
    
    
                        </tr>
                        @endif
                    @endforeach
                    <tr class="bg-blue-100">
                        
                        <th colspan="3" class="text-right">اجمالي الرصيد</th>
                        @if($debit_YER!=0||$credit_YER!=0)
                        @php
                        $balance_YER= abs($total_debit_YER)-abs($total_credit_YER);
                        $wholeNumber = floor(abs($balance_YER));
                        $decimal = abs($balance_YER) - $wholeNumber;

                    @endphp
                        <td class=" text-center">
                            @if ($balance_YER>0)
                            {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                            @endif
                    
                        </td>
                        <td class=" text-center">
                            @if ($balance_YER<0)
                            {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                            @endif
                        </td>  
                        @endif 
    
                        @if($debits_SAD!=0||$credits_SAD!=0)
                        
                        <td class=" text-center">
                        @php
                           $balance_SAD= abs($total_debits_SAD)-abs($total_credits_SAD);
                        $wholeNumber = floor(abs($balance_SAD));
                        $decimal = abs($balance_SAD) - $wholeNumber;
                        @endphp
                          @if ($balance_SAD>0)
                            {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                            @endif
                        </td>
                        <td class=" text-center">
                            @if ($balance_SAD<0)
                            {{ number_format($wholeNumber, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                            @endif                            </td>   
                        @endif
                        @if($debitd_USD!=0||$credits_USD!=0)
    
                        <td class=" text-center">
                        @php
                           $balance_USD= abs($total_debitd_USD)-abs($total_credits_USD);
                        $wholeNumber = floor(abs($balance_USD));
                        $decimal = abs($balance_USD) - $wholeNumber;
                        @endphp
                         @if ($balance_USD>0)
                         {{ number_format($wholeNumberusd, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                         @endif 
                        </td>
                        <td class=" text-center">
                      
                            @if ($balance_USD<0)
                            {{ number_format($wholeNumberusd, 0, '.', ',') }}@if($decimal > 0)<span class="text-green-600">.{{ substr(number_format($decimal, 2, '.', ''), 2) }}</span>@endif
                            @endif                             </td>   
                        @endif
                                 </tr>
                    @endif
                </tbody>
            </table>
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

