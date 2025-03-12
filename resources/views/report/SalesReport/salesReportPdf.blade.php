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
        <p > تقارير المبيعات من 2024/5/10 الى 2023/4/5</p>
    </div>
    <table class="min-w-full  text-[7px] text-nowrap bg-white border border-gray-200">
        <thead class="bg-gray-200 text-center">
            <tr>
                <th class="py-2 px-4 border-b">رقم الصنف</th>
                <th class="py-2 px-4 border-b">اسم الصنف</th>
                <th class="py-2 px-4 border-b">الوحدة</th>
                <th class="py-2 px-4 border-b">سعر الوحدة</th>
                <th class="py-2 px-4 border-b">الكمية</th>
                <th class="py-2 px-4 border-b">الأجمالي</th>
            </tr>
        </thead>
        <tbody >
            <tr class="text-[7px] ">
                <td class="py-2 px-4 border-b text-center">1</td>
                <td class="py-2 px-4 border-b text-center">سلك كهرباء</td>
                <td class="py-2 px-4 border-b text-center">متر</td>
                <td class="py-2 px-4 border-b text-center">1000</td>
                <td class="py-2 px-4 border-b text-center">3</td>
                <td class="py-2 px-4 border-b text-center">3000</td>
            </tr>
            <tr class="text-[7px] ">
                <td class="py-2 px-4 border-b text-center">1</td>
                <td class="py-2 px-4 border-b text-center">سلك كهرباء</td>
                <td class="py-2 px-4 border-b text-center">متر</td>
                <td class="py-2 px-4 border-b text-center">1000</td>
                <td class="py-2 px-4 border-b text-center">3</td>
                <td class="py-2 px-4 border-b text-center">3000</td>
            </tr>
        </tbody>
    </table>
</div>


<Script>
    window.print()
</Script>

</body>

</html>
