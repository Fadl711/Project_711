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
        <p > تقارير المخازن  من 2024/5/10 الى 2023/4/5</p>
    </div>

    <table class="min-w-full  text-[7px] text-nowrap bg-white border border-gray-200">
        <thead class="bg-gray-200 text-center">
            <th class="py-2 px-4 border-b">المخزن</th>
            <th class="py-2 px-4 border-b">اسم الصنف</th>
            <th class="py-2 px-4 border-b">كمية واردة</th>
            <th class="py-2 px-4 border-b">كمية منصرف</th>
            <th class="py-2 px-4 border-b">قيمة الوارد</th>
            <th class="py-2 px-4 border-b">قيمة المنصرف</th>
        </tr>
    </thead>
    <tbody >
        <tr class="text-[7px] ">
            <td class="py-2 px-4 border-b text-center">المخزن الرئيسي</td>
            <td class="py-2 px-4 border-b text-center">لوج شمسي</td>
            <td class="py-2 px-4 border-b text-center">0</td>
            <td class="py-2 px-4 border-b text-center">2</td>
            <td class="py-2 px-4 border-b text-center">0</td>
            <td class="py-2 px-4 border-b text-center">20000</td>
        </tbody>
    </table>
</div>


<Script>
    window.print()
</Script>

</body>

</html>
