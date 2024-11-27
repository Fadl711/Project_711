<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body >
<div class="container mx-10">
    
    @include('includes.header')

    <div class="container p-3 flex justify-between bg-gray-100 border-black border-2 rounded-lg h-40 my-2 text-center font-bold ">
        <div class="p-3  bg-gray-100 border-black  h-16 my-1 text-right font-bold space-y-2 ">

            <p >رقم السند : {{$PaymentBond->payment_bond_id}}</p>
            <p>تاريخ السند : {{ \Carbon\Carbon::parse($PaymentBond->created_at)->format('Y/m/d') }}</p>
            <p >رقم المرجع : 21</p>
        </div>
        <div class="p-3 w-52 bg-gray-100 border-black   h-16 my-1 text-center text-2xl font-bold underline underline-offset-8">

            <p > سند قبض /نقد</p>
        </div>
        <div class=" w-52 bg-gray-100 h-16 my-1 text-center font-bold space-y-2">
            <div>

                <p class="border-b-2 border-black font-bold text-base">المبلغ </p>
                <p id="maont2" class="bg-white h-10 font-bold text-lg pt-2">{{number_format($PaymentBond->Amount_debit)}} <span class="pb-1  font-normal">{{ $Currencies->where('currency_id', $PaymentBond->Currency_id)->first()->currency_name }} </span></p>
            </div>
            <p class="text-right text-base">إيداع في حساب :{{ $MainAccounts->where('main_account_id', $PaymentBond->Main_debit_account_id)->first()->account_name }} </p>
        </div>
    </div>
    {{-- end header --}}
{{--
@php
    function convertNumberToWords($number)
{
    $formatter = new \NumberFormatter('ar', \NumberFormatter::SPELLOUT);
    return $formatter->format($number);
}

$number = 505550 ;
$oo = convertNumberToWords($number) . ' ريال  يمني';
@endphp --}}


    {{-- body srart --}}
    <div class="container p-3 relative space-y-2  text-base bg-gray-200 border-black  rounded-lg  my-2 text-right font-bold">
        <div class="flex justify-between">
            {{-- {{"(".$oo.")"}}  --}}
            <p class="">أستلمنا من الأخ / {{ $SubAccounts->where('sub_account_id', $PaymentBond->Credit_sub_account_id)->first()->sub_name }}</p>
            <p class="">مبلغ وقدره <span class="text-lg">:</span> <span class="font-bold text-base">{{number_format($PaymentBond->Amount_debit)}}</span> ر.ي  </p>
        </div>
        <br>
        <p>تقيد المبلغ لحساب /الدائن</p>
        <div class="container">
            <table class=" bg-gray-100 container text-center  border-black border-2 ">
                <thead>
                    <tr class="bg-gray-300 ">
                        <th class="px-2 py-2  border-black border-2 " >م</th>
                        <th class="  max-w-72 border-black border-2">اسم الحساب </th>
                        <th class="px-2 py-2 min-w-40 max-w-40  border-black border-2">البيان</th>
                        <th class="px-2 py-2 min-w-16 max-w-24  border-black border-2">المبلغ </th>
                        <th class="px-2 py-2 min-w-10 max-w-14  border-black border-2">العملة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-right">
                        <td class="py-2 px-2 border-black border-2">1</td>
                        <td class="py-2 px-2 border-black border-2">{{ $SubAccounts->where('sub_account_id', $PaymentBond->Credit_sub_account_id)->first()->sub_name }}</td>
                        <td class="py-2 px-2 border-black border-2">{{$PaymentBond->Statement}}</td>
                        <td class="py-2 px-2 border-black border-2">{{number_format($PaymentBond->Amount_debit)}}</td>
                        <td class="py-2 px-2 border-black border-2">{{ $Currencies->where('currency_id', $PaymentBond->Currency_id)->first()->currency_name }}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <hr class="border-t-2 bg-black">
            <br>
            <br>
            <div class="flex justify-between mx-10">


                <div class="totals- bg-gray-100 p-4">
                    <div class="flex justify-">
                        <div>
                            <p class=" text-sm" dir="ltr">................ توقيع المستلم</p>
                        </div>
                        <div>
                            <p class=" text-sm" dir="ltr">  المسؤول :{{ $users->where('id', $PaymentBond->User_id)->first()->name }}</p>
                        </div>
                        
                    </div>
                </div>            </div>
        </div>
    </div>
</div>
<Script>
            window.print();
</Script>

</body>

</html>
