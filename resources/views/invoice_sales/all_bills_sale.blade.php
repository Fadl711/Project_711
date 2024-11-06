<div class="container max-h-screen ">
    <form  class="p-1  shadow-md ">
    {{-- <label class="labelSale" for="email">إعدادات عرض البيانات </label> --}}
        <ul class="items-center w-full  font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale">إعدادات العرض والبحث  </label>
                </div>
            </li>
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale" checked >تلقائي </label>
                    <input checked id="horizontal-list-radio-license" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600 ">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale" >اليوم </label>
                    <input  id="horizontal-list-radio-license" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-id" class="labelSale">هذا الاسبوع </label>
                    <input id="horizontal-list-radio-id" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center text-center ">
                    <label for="horizontal-list-radio-military" class="labelSale ">هذا الشهر</label>
                    <input id="horizontal-list-radio-military" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center  p-1">
                    <label for="horizontal" class="w-full  ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">من تاريخ </label>

                    <input name="horizontal" class="inputSale " id="" type="date" placeholder=""/>
                </div>
            </li>
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center  p-1">
                    <label for="horizontal-list-radio-passpot" class="w-full  ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> الى تاريخ </label>
                    <input name="horizontal-list-radio-passpot" class="inputSale " id="" type="date" placeholder=""/>
                </div>
            </li>
        </ul>
    </form>
    <div class="flex  gap-4 justify-center items-center  ">
        <div class="">

            <select class="relative  border border-gray-200 rounded-lg w-full max-w-lg" name="" id="">
                <option value="" selected>كل الفواتير</option>
                <option value="" >اول فاتوره</option>
                <option value="" >اخر فاتوره</option>
            </select>
            </div>
        <div class="border-gray-200 rounded-lg w-full max-w-lg">
            <input type="text" class="rounded-md w-full text-left" placeholder="Search" name="search" value="">
        </div>
    </div>

    <div class="overflow-y-scroll px-1">
        <div class="min-w-full rounded-lg max-h-screen ">
            <br>
            <div class="bg-white   shadow-lg  max-w-xl mx-auto  rounded-2xl p-1 " >
                @for($s=0; $s<=10;$s++)
                <div class="bg-white   shadow-lg  max-w-xl mx-auto  rounded-2xl p-2 border-x-2 border-y-2 border-[#f8c21f]" >
                    <table   class="w-full ">
                        <thead class="tracking-tight   " >
                            <tr  class="bg-[#f8c21f] text-black w-full border-x border-y border-[#f8c21f]  ">
                                <td colspan="1" class=" text-right  p-1  bg-white text-black  ">
                                    <div class="flex items-center ">
                                        <div class="text-indigo-700 text-sm ">اسم العميل : <label class="text-black" for="">     جمال علي احمد</label> </div>
                                    </div>
                                    <div class="text-indigo-700  text-sm"> التلفون: <label class="text-black" for="">775454554 </label></div>
                                    <div class="text-sm text-indigo-700">الرقم التعريفي :  <label class="text-black" for="">15 </label></div>
                                </td>
                                <td colspan="2" class="p-1 ">
                                    <h2 class="text-1xl font-bold text-white"> محلات الريعاني </h2>
                                    <h2 class="text-1xl font-bold text-white"> icon  </h2>
                                    <h2 class="text- font- text-white ">فاتورة المبيعات </h2>
                                </td>
                                <td colspan="1" class="   p-1 text-sm  ">
                                    <div class="text-sm text-indigo-700" > <label class="text-white text-sm" for="">01/05/2023</label>  :التاريخ</div>
                                    <div class="text-sm text-indigo-700"> <label class="text-white text-sm" for="">12345</label> :رقم الفاتورة  </div>
                                    <div class="text-sm text-indigo-700"> <label class="text-white text-sm" for="">1345</label> :رقم القيد  </div>
                                </td>
                            </tr>
                        </thead>
                    <tbody>
                        <tr class="  border-b border-[#f8c21f] ">
                            <th scope="col" class="text-center text-sm"><div class="text-indigo-700 ">اجمالي الفاتورة</div></th>
                            <th scope="col" class="text-center text-sm"><div class="text-indigo-700 ">المبلغ المدفوع</div></th>
                            <th scope="col" class="text-center text-sm"><div class="text-indigo-700 ">المبلغ المتبقي</div></th>
                            <th scope="col" class="text-center text-sm"></th>
                        </tr>
                        <tr class="">
                            <td class=" text-center  text-[#1ff823] ">$442</td>
                            <td class=" text-center">$442</td>
                            <td class=" text-center  text-[rgb(193,44,44)]">$442</td>
                            <td class=" text-center text-indigo-700 "> <a href="{{ route('print_bills_sale') }}" onclick="openSmallWindow(event)">
                            عرض مبيعات  الفاتورة   </a>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            @endfor
        </div>
    </div>
    </div>

<br>
<br>





<script>
    function openSmallWindow(event) {
        event.preventDefault(); // لمنع الفتح الافتراضي للرابط
        window.open("{{ route('print_bills_sale') }}", "_blank", "width=600,height=800,left=700,top=100");
    }
</script>
