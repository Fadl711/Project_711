<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
</head>
<body>

<div class=" container  min-w-full ">

    @include('includes.header')
    <div class="w-full p-3 bg-gray-100 border-black border-[1px] rounded-lg text-[10px]  my-2 text-center font-bold">
        <p > تقارير كشف حساب  من 2024/5/10 الى 2023/4/5</p>
    </div>

    <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-200 text-center">
            <tr>
                <th class="py-2 px-4 border-b">رقم الحساب</th>
                <th class="py-2 px-4 border-b">اسم الحساب</th>
                <th class="py-2 px-4 border-b">مدين/عليه</th>
                <th class="py-2 px-4 border-b">دائن/له</th>

            </tr>
        </thead>
        <tbody >
            @foreach ($MainAccounts as $MainAccount)

            <tr>
                <td class="py-2 px-4 border-b text-center">{{$MainAccount->main_account_id}}</td>
                <td class="py-2 px-4 border-b text-center"> {{$MainAccount->account_name}} </td>
                @foreach ($SubAccounts as $SubAccount)
                @if ($SubAccount->where('main_id',$MainAccount->main_account_id)->get())
                <td class="py-2 px-4 border-b text-center">{{$SubAccount->where('main_id',$MainAccount->main_account_id)->sum('debtor_amount')}}</td>
                <td class="py-2 px-4 border-b text-center">{{$SubAccount->where('main_id',$MainAccount->main_account_id)->sum('creditor_amount')}}</</td>
                @break
                @endif
                @endforeach

            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<Script>
    window.print()
</Script>

</body>

</html>
