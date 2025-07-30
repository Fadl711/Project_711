    <table class="text-sm overflow-y-autow-full  font-semibold w-full  border-collapse  print:overflow-y-hidden ">
        <thead class="bg-gray-100 sticky top-0 uppercase dark:bg-gray-700 dark:text-gray-400">
        <tr class="">
            <th class="text-center" colspan="{{ 2 + ($showYER ? 2 : 0) + ($showSAR ? 2 : 0) + ($showUSD ? 2 : 0) }}" class="text-left">{{ $title }}</th>
        </tr>
        <tr class="">
            <th colspan="2"></th>
            @if($showYER)
                <th colspan="2" class="text-center">المبالغ بالعملة المحلية</th>
            @endif
            @if($showSAR)
                <th colspan="2" class="text-center">المبالغ بالريال السعودي</th>
            @endif
            @if($showUSD)
                <th colspan="2" class="text-center">المبالغ بالدولار الأمريكي</th>
            @endif
        </tr>
        <tr class="">
                <th class="">اسم الحساب</th>
            <th class="">رقم الحساب</th>
            
            @if($showYER)
                <th class="">مدين</th>
                <th class="">دائن</th>
            @endif
            
            @if($showSAR)
                <th class="">مدين</th>
                <th class="">دائن</th>
            @endif
            
            @if($showUSD)
                <th class="">مدين</th>
                <th class="">دائن</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($balances as $balance)
            <tr class="hover:bg-gray-50">
                <td>{{ $balance->sub_name }}</td>
                <td>{{ $balance->sub_account_id }}</td>
                
                @if($showYER)
                    @php
                        $yerAmount = $balance->total_debit - $balance->total_credit;
                    @endphp
                    <td class="{{ $yerAmount > 0 ? 'debit-amount' : '' }}">
                        @if($yerAmount > 0)
                            <span class="money-format" data-amount="{{ abs($yerAmount) }}"></span>
                        @endif
                    </td>
                    <td class="{{ $yerAmount < 0 ? 'credit-amount' : '' }}">
                        @if($yerAmount < 0)
                            <span class="money-format" data-amount="{{ abs($yerAmount) }}"></span>
                        @endif
                    </td>
                @endif
                
                @if($showSAR)
                    @php
                        $sarAmount = $balance->total_debits - $balance->total_credits;
                    @endphp
                    <td class="{{ $sarAmount > 0 ? 'debit-amount' : '' }}">
                        @if($sarAmount > 0)
                            <span class="money-format" data-amount="{{ abs($sarAmount) }}"></span>
                        @endif
                    </td>
                    <td class="{{ $sarAmount < 0 ? 'credit-amount' : '' }}">
                        @if($sarAmount < 0)
                            <span class="money-format" data-amount="{{ abs($sarAmount) }}"></span>
                        @endif
                    </td>
                @endif
                
                @if($showUSD)
                    @php
                        $usdAmount = $balance->total_debitd - $balance->total_creditd;
                    @endphp
                    <td class="{{ $usdAmount > 0 ? 'debit-amount' : '' }}">
                        @if($usdAmount > 0)
                            <span class="money-format" data-amount="{{ abs($usdAmount) }}"></span>
                        @endif
                    </td>
                    <td class="{{ $usdAmount < 0 ? 'credit-amount' : '' }}">
                        @if($usdAmount < 0)
                            <span class="money-format" data-amount="{{ abs($usdAmount) }}"></span>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ 2 + ($showYER ? 2 : 0) + ($showSAR ? 2 : 0) + ($showUSD ? 2 : 0) }}" class="text-center py-4">لا توجد بيانات متاحة</td>
            </tr>
        @endforelse
        
        <!-- صف المجموع -->
        <tr class=" bg-gray-200 font-bold">
            <td class="" colspan="2">إجمالي {{ $title }}</td>
            
            @if($showYER)
                @php
                    $yerTotal = $balances->sum('total_debit') - $balances->sum('total_credit');
                @endphp
                <td class="{{ $yerTotal > 0 ? 'debit-amount' : '' }}">
                    @if($yerTotal > 0)
                        <span class="money-format" data-amount="{{ abs($yerTotal) }}"></span>
                    @endif
                </td>
                <td class="{{ $yerTotal < 0 ? 'credit-amount' : '' }}">
                    @if($yerTotal < 0)
                        <span class="money-format" data-amount="{{ abs($yerTotal) }}"></span>
                    @endif
                </td>
            @endif
            
            @if($showSAR)
                @php
                    $sarTotal = $balances->sum('total_debits') - $balances->sum('total_credits');
                @endphp
                <td class="{{ $sarTotal > 0 ? 'debit-amount' : '' }}">
                    @if($sarTotal > 0)
                        <span class="money-format" data-amount="{{ abs($sarTotal) }}"></span>
                    @endif
                </td>
                <td class="{{ $sarTotal < 0 ? 'credit-amount' : '' }}">
                    @if($sarTotal < 0)
                        <span class="money-format" data-amount="{{ abs($sarTotal) }}"></span>
                    @endif
                </td>
            @endif
            
            @if($showUSD)
                @php
                    $usdTotal = $balances->sum('total_debitd') - $balances->sum('total_creditd');
                @endphp
                <td class="{{ $usdTotal > 0 ? 'debit-amount' : '' }}">
                    @if($usdTotal > 0)
                        <span class="money-format" data-amount="{{ abs($usdTotal) }}"></span>
                    @endif
                </td>
                <td class="{{ $usdTotal < 0 ? 'credit-amount' : '' }}">
                    @if($usdTotal < 0)
                        <span class="money-format" data-amount="{{ abs($usdTotal) }}"></span>
                    @endif
                </td>
            @endif
        </tr>
    </tbody>
</table>

