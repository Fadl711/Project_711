
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


{{--  --}}
<div class="">
        <div class=" border-2 border-black rounded-b-lg my-2  ">
            <div class="bg-gray-200 p-8 rounded-lg  flex justify-between w-full ">
                <div class="text-right">
                    <h2 class="text-xl font-bold mb-2">الريعاني للمواد البناء</h2>
                    <p>مواد بناء <strong>-</strong> ادوات كهربائية <strong>-</strong> دهانات</p>
                    <p> الصباحة السوق الاعلئ بعد سوق القات </p>
                    <p> 772020232-77774633-123456789</p>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-24 h-20 bg-gray-300 flex items-center justify-center translate-x-10 ">
                        <img class="" src="{{url('img/bnaa.png')}}" alt="">
                    </div>
                </div>
                <div class="text-left">
                    <h2 class="text-xl font-bold mb-2">Company Name</h2>
                    <p>To constract - Elcetric - Funtret</p>
                    <p>Address: 123 Example St</p>
                    <p>Phone: 123456789</p>
                </div>
            </div>
        </div>


        <div class="w-full p-3 bg-gray-100 border-black border-2 rounded-lg  my-2 text-center font-bold">
            <p >فاتورة مبيعات</p>
        </div>

        <div class=" ">
            <table class=" bg-gray-100 w-full text-center  border-black border-2">
                <tbody class="">
                    <tr>
                        <td class="border-black border-2 p-2">التاريخ</td>
                        <td class="border-black border-2 p-2">{{ \Carbon\Carbon::now()->format('Y/m/d') }}</td>
                        <td class="border-black border-2 p-2">رقم الفاتورة</td>
                        <td class="border-black border-2 p-2">2000</td>
                        <td class="border-black border-2 p-2">رقم المرجع</td>
                        <td class="border-black border-2 p-2">2000</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-200 border-black border-2 p-2"> اسم العميل</td>
                        <td class="bg-gray-200 border-black border-2 p-2 " colspan="6">فضل عبده حسين المطري</td>
                    </tr>
                </tbody>
            </table>
        </div>
        {{--  --}}
        {{-- body --}}

        <div class="">
            <table class=" bg-gray-100 w-full text-center  border-black border-2 my-2">
                <thead>
                    <tr class="bg-gray-300 ">
                        <th class="px-2 py-2  border-black border-2 " >م</th>
                        <th class=" min-w-52 max-w-72 border-black border-2">الصنف </th>
                        <th class="px-2 py-2 min-w-16 max-w-20  border-black border-2">الوحدة</th>
                        <th class="px-2 py-2 min-w-16 max-w-20  border-black border-2">الكمية</th>
                        <th class="px-2 py-2 min-w-16 max-w-20  border-black border-2">سعر الوحدة</th>
                        <th class="px-2 py-2 min-w-16 max-w-20  border-black border-2">المخزن</th>
                        <th class="px-2 py-2 min-w-32 max-w-52  border-black border-2">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 7; $i++)

                    <tr class="text-right">
                        <td class="py-2 px-2 border-black border-2">{{$i}}</td>
                        <td class="py-2 px-2 border-black border-2">اسمنت عمران</td>
                        <td class="py-2 px-2 border-black border-2">كيس</td>
                        <td class="py-2 px-2 border-black border-2">{{number_format(rand(1, 10))}}</td>
                        <td class="py-2 px-2 border-black border-2">{{number_format(rand(200, 100000))}}</td>
                        <td class="py-2 px-2 border-black border-2">المحل</td>
                        <td class="py-2 px-2 border-black border-2">{{ number_format(rand(200, 100000)) }}</td>
                    </tr>
                    @endfor
<tr class="">
    <td class="bg-gray-300 py-2 px-2 border-black border-2 text-left font-bold" colspan="6">الخصم الأجمالي</td>
    <td class="py-2 px-2 border-black border-2 text-right font-bold" >50,000</td>
</tr>
<tr class="">
    <td class="bg-gray-300 py-2 px-2 border-black border-2 text-left font-bold" colspan="6">الاجمالي الفرعي</td>
    <td class="py-2 px-2 border-black border-2 text-right font-bold" >1,000</td>
</tr>
<tr class="bg-gray-300">
    <td class="py-2 px-2 border-black border-2 text-left font-bold" colspan="6">الاجمالي</td>
    <td class=" py-2 px-2 border-black border-2 text-right font-bold" > 100,000,000 ريال يمني</td>
</tr>
                </tbody>
            </table>
        </div>
                {{-- body --}}
                <div class="w-full p-3 relative flex justify-start bg-gray-200 border-black  rounded-lg  my-2 text-center font-bold">
                    <p  class="mt-2">توقيع العميل</p>
                    <p class="tracking-widest mr-2 mt-2">..................</p>
                    <p class="absolute left-0 bottom-0">المستخدم:صادق الريعاني<br>{{ \Carbon\Carbon::parse(now())->format('Y-m-d H:i:s') }}  </p>
                </div>
    </div>


</div>







<script>
             window.print();
</script>

</body>
</html>
