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

    <div class=" border-[1px] border-black rounded-b-lg my-2  ">
        <div class="bg-gray-200 p-2 rounded-b-lg  flex justify-between w-full ">
            <div class="text-right text-[10px] items-center">
                <h2 class=" font-bold ">الريعاني للمواد البناء</h2>
                <p>مواد بناء <strong>-</strong> ادوات كهربائية <strong>-</strong> دهانات</p>
                <p> الصباحة السوق الاعلئ بعد سوق القات </p>
                <p> 772020232-77774633-123456789</p>
            </div>
            <div class="flex items-center justify-center">
                <div class="w-16 h-16 bg-gray-300 flex items-center justify-center translate-x-8 ">
                    <img class="" src="{{url('img/bnaa.png')}}" alt="">
                </div>
            </div>
            <div class="text-left text-[10px] items-center">
                <h2 class=" font-bold ">Company Name</h2>
                <p>To constract - Elcetric - Funtret</p>
                <p>Address: 123 Example St</p>
                <p>Phone: 123456789</p>
            </div>
        </div>
    </div>
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
