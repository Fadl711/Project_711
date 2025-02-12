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

    <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg w-full px-4 py-2 max-h-full">
        <table class="text-sm font-semibold w-full overflow-y-auto max-h-[80vh] border-collapse">
            <thead class="bg-gray-100 sticky top-0 uppercase dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-2 text-center " colspan="5">الاصول</th>
                </tr>
            <tr class="bg-blue-100">
                <th class="px-2 text-center">#</th>
                <th class="px-2 text-right">اسم الحساب</th>
                <th class="px-2 text-center">رقم الحساب</th>
                <th class="px-2 text-center">المدين</th>
                <th class="px-2 text-center">الدائن</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @if(isset($balances))
            @foreach ($balances as $ind => $balance)
                <tr class="hover:bg-gray-50">
                    <td class="px-2 text-center">{{ $ind + 1 }}</td>
                    <td class="px-2 text-right">{{ $balance['sub_name'] }}</td>
                    <td class="px-2 text-center">{{ $balance['sub_account_id'] }}</td>
                    @php
                        $totalDebit=0;
                        $totalCredit=0;
                        $sumAmount=$balance->total_debit-abs($balance->total_credit);
                        if($sumAmount>=0)
                        {
                            $totalDebit=$sumAmount;
                            $totalCredit=0;
                        }
                        if($sumAmount<0)
                        {
                            $totalCredit=$sumAmount;
                            $totalDebit=0;
                        }


                    @endphp
                    <td class="px-2 text-center">{{ number_format($totalDebit, 2) ?? 0 }}</td>
                    <td class="px-2 text-center">{{ number_format(abs($totalCredit), 2) ?? 0 }}</td>
                </tr>
            @endforeach
            <tr class="bg-blue-100">
                <th colspan="3" class="text-right">اجمالي الرصيد</th>
                <td class="px-2 text-center">{{ number_format($SumDebtor_amount ?? 0, 2) }}</td>
                <td class="px-2 text-center">{{ number_format (abs($SumCredit_amount) ?? 0, 2) }}</td>   
                         </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="overflow-x-auto bg-white shadow-md sm:rounded-lg w-full px-4 py-2 max-h-full">
    <table class="text-sm font-semibold w-full overflow-y-auto max-h-[80vh] border-collapse">
        <thead class="bg-gray-100 sticky top-0 uppercase dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-2 text-center " colspan="5">الاتزامات</th>
            </tr>
        <tr class="bg-blue-100">
            <th class="px-2 text-center">#</th>
            <th class="px-2 text-right">اسم الحساب</th>
            <th class="px-2 text-center">رقم الحساب</th>
            <th class="px-2 text-center">المدين</th>
            <th class="px-2 text-center">الدائن</th>
        </tr>
    </thead>
    <tbody class="bg-white">
        @if(isset($balances2))
        @foreach ($balances2 as $index => $balance2)
            <tr class="hover:bg-gray-50">
                <td class="px-2 text-center">{{ $index + 1 }}</td>
                <td class="px-2 text-right">{{ $balance2['sub_name'] }}</td>
                <td class="px-2 text-center">{{ $balance2['sub_account_id'] }}</td>
                @php
                    $totalDebit2=0;
                    $totalCredit2=0;
                    $sumAmount=$balance2->total_debit2-abs($balance2->total_credit2);
                    if($sumAmount>=0)
                    {
                        $totalDebit2=$sumAmount;
                        $totalCredit2=0;
                    }
                    if($sumAmount<0)
                    {
                        $totalCredit2=$sumAmount;
                        $totalDebit2=0;
                    }


                @endphp
                <td class="px-2 text-center">{{ number_format($totalDebit2, 2) ?? 0 }}</td>
                <td class="px-2 text-center">{{ number_format(abs($totalCredit2), 2) ?? 0 }}</td>
            </tr>
        @endforeach
        <tr class="bg-blue-100">
            <th colspan="3" class="text-right">اجمالي الرصيد</th>
            <td class="px-4 text-center">{{ number_format($SumDebtor_amount2?? 0, 2) }}</td>
            <td class="px-4 text-center">{{ number_format (abs($SumCredit_amount2) ?? 0, 2) }}</td>   
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

