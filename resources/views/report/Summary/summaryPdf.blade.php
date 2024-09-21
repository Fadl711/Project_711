<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class=" container  min-w-full ">

    @include('includes.header')
    <div class="w-full p-3 bg-gray-100 border-black border-[1px] rounded-lg text-[10px]  my-2 text-center font-bold">
        <p > تقارير كشف حساب  من 2024/5/10 الى 2023/4/5</p>
    </div>

    <table class="min-w-full  text-[7px] text-nowrap bg-white border border-gray-200">
        <thead class="bg-gray-200 text-center">
            <tr>
                <th class="py-2 px-4 border-b">رقم الحساب</th>
                <th class="py-2 px-4 border-b">اسم الحساب</th>
                <th class="py-2 px-4 border-b">مدين/عليه</th>
                <th class="py-2 px-4 border-b">دائن/له</th>
                <th class="py-2 px-4 border-b">الرصيد</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-[7px]">
                <td class="py-2 px-4 border-b text-center">1</td>
                <td class="py-2 px-4 border-b text-center">الصندوق الرئيسي </td>
                <td class="py-2 px-4 border-b text-center">1000</td>
                <td class="py-2 px-4 border-b text-center">500</td>
                <td class="py-2 px-4 border-b text-center">500</td>
            </tr>
            <tr class="text-[7px]">
                <td class="py-2 px-4 border-b text-center">2</td>
                <td class="py-2 px-4 border-b text-center">حساب المخزون</td>
                <td class="py-2 px-4 border-b text-center">2000</td>
                <td class="py-2 px-4 border-b text-center">1500</td>
                <td class="py-2 px-4 border-b text-center">500</td>
            </tr>
            <tr class="text-[7px]">
                <td class="py-2 px-4 border-b text-center">2</td>
                <td class="py-2 px-4 border-b text-center">حساب المبيعات</td>
                <td class="py-2 px-4 border-b text-center">2000</td>
                <td class="py-2 px-4 border-b text-center">1500</td>
                <td class="py-2 px-4 border-b text-center">500</td>
            </tr>
            <tr class="text-[7px]">
                <td class="py-2 px-4 border-b text-center">2</td>
                <td class="py-2 px-4 border-b text-center">حساب المشتريات</td>
                <td class="py-2 px-4 border-b text-center">2000</td>
                <td class="py-2 px-4 border-b text-center">1500</td>
                <td class="py-2 px-4 border-b text-center">500</td>
            </tr>
            <tr class="text-[7px]">
                <td class="py-2 px-4 border-b text-center">2</td>
                <td class="py-2 px-4 border-b text-center">حساب راس المال</td>
                <td class="py-2 px-4 border-b text-center">2000</td>
                <td class="py-2 px-4 border-b text-center">1500</td>
                <td class="py-2 px-4 border-b text-center">500</td>
            </tr>
            <tr class="text-[7px]">
                <td class="py-2 px-4 border-b text-center">2</td>
                <td class="py-2 px-4 border-b text-center">حساب الأرباح والخسائر </td>
                <td class="py-2 px-4 border-b text-center">2000</td>
                <td class="py-2 px-4 border-b text-center">1500</td>
                <td class="py-2 px-4 border-b text-center">500</td>
            </tr>
        </tbody>
    </table>
</div>


<Script>
    window.print()
</Script>

</body>

</html>
