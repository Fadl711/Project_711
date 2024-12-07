<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$PaymentBond->transaction_type}} ({{$payment_type}})</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Tajawal', sans-serif;
        }
        .header-section {
            background-color: #f3f4f6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        table th, table td {
            text-align: center;
        }
        .no-print button {
            transition: background-color 0.3s ease;
        }
        .no-print button:hover {
            transform: scale(1.05);
        }
        @media print {
        .no-print {
            display: none;
        }
    }
    </style>
</head>
<body>
<div class="container mx-auto px-4 py-6">
    @include('includes.header2')


    <div class="bg-white border border-gray-300 rounded-lg shadow-md p-4 mb-4">
        <div class="grid grid-cols-3 gap-4">
            <div class="text-right">
                <p>رقم السند: <span class="font-bold text-gray-800">{{$PaymentBond->payment_bond_id}}</span></p>
                <p>تاريخ السند: <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($PaymentBond->created_at)->format('Y/m/d') }}</span></p>
                <p>رقم المرجع: <span class="font-bold text-gray-800"></span></p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-700">{{$PaymentBond->transaction_type}} ({{$payment_type}})</p>
            </div>
            <div class="text-right">
                <p  class="text-center">المبلغ:</p>
                <p class="text-lg font-bold bg-gray-100 p-2 rounded-md">{{number_format($PaymentBond->Amount_debit)}} 
                    <span class="text-sm font-normal">{{ $Currencies->where('currency_id', $PaymentBond->Currency_id)->first()->currency_name }}</span>
                </p>
                <p>إيداع في حساب: <span class="font-bold text-gray-800">
                    {{ $SubAccounts->where('sub_account_id', $PaymentBond->Debit_sub_account_id)->first()->sub_name ?? '' }}
                    </span></p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-300 rounded-lg shadow-md p-4">
        <p>  اسم المستلم: <span class="font-bold text-gray-800">
            @if ($PaymentBond->transaction_type==="سند صرف")
                
            {{ $SubAccounts->where('sub_account_id', $PaymentBond->Debit_sub_account_id)->first()->sub_name ?? '' }}
            @endif
        </span></p>
        <p>مبلغ وقدره: <span class="text-lg font-bold">

            {{number_format($PaymentBond->Amount_debit)}}
        </span> {{$result}}</p>
 
        <p>تقيد المبلغ لحساب /الدائن</p>
        
        <table class="w-full text-sm bg-gray-100 border border-gray-300 rounded-lg">
            <thead class="bg-gray-300">
                <tr>
                    <th class="px-4 py-2 border">م</th>
                    <th class="px-4 py-2 border">اسم الحساب</th>
                    <th class="px-4 py-2 border">البيان</th>
                    <th class="px-4 py-2 border">المبلغ</th>
                    <th class="px-4 py-2 border">العملة</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-2 border">1</td>
                    <td class="px-4 py-2 border">{{ $SubAccounts->where('sub_account_id', $PaymentBond->Credit_sub_account_id)->first()->sub_name }}</td>
                    <td class="px-4 py-2 border">{{$PaymentBond->Statement}}</td>
                    <td class="px-4 py-2 border">{{number_format($PaymentBond->Amount_debit)}}</td>
                    <td class="px-4 py-2 border">{{ $Currencies->where('currency_id', $PaymentBond->Currency_id)->first()->currency_name }}</td>
                </tr>
            </tbody>
        </table>
        <div class="totals-section py-5">
            <div class="flex justify-between">
                <div>
                   
                       
                        <div>
                            <p class="text-sm" dir="ltr">................ التوقيع المستلم </p>
    
                        </div>
                    </div> 

                    <div>
                        <p class="text-sm" dir="ltr">المسؤول : {{ $PaymentBond->UserName ?? 0 }}</p>
                    </div>
                </div>
            </div>
    </div>

    <!-- أزرار الطباعة -->
    <div class="mt-4 no-print flex space-x-2">
        <button onclick="printAndClose()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-800">طباعة</button>
        <button onclick="closeWindow()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-800">إلغاء الطباعة</button>
    </div>
</div>

<script>
    function printAndClose() {
        window.print();
        setTimeout(() => window.close(), 500);
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
