
<form  class="  ">
    {{-- <label class="labelSale" for="email">إعدادات عرض البيانات </label> --}}


        <ul class="items-center w-full  font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale">إعدادات عرض البيانات  </label>
                </div>
            </li>
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale" >اليوم </label>
                    <input checked id="horizontal-list-radio-license" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
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
                <div class=" items-center ">
                    <label for="horizontal" class="w-full  ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">من تاريخ </label>

                    <input name="horizontal" class="inputSale " id="" type="date" placeholder=""/>
                </div>
            </li>
            <li class="w-full text-center border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-passpot" class="w-full  ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> الى تاريخ </label>
                    <input name="horizontal-list-radio-passpot" class="inputSale " id="" type="date" placeholder=""/>
                </div>
            </li>
        </ul>
        

      

    </form>  

<div class="-mx-4 sm:-mx-8 px-4 sm:px-8 overflow-x-auto   p-1">
    <div class="inline-block min-w-full shadow rounded-lg  max-h-[500px] ">
        <table id="myTable" class="min-w-full leading-normal ">
            <thead class="tracking-tight ">
                <tr class="bgcolor">
                    <th scope="col" class="leading-2 tagHt"> حساب</th>
                    <th scope="col" class="leading-2 tagHt ">رقم الحساب</th>
                    <th scope="col" class="leading-2 tagHt "> اجمالي المبالغ المقبوضة</th>
                    <th scope="col" class="leading-2 tagHt "> اجمالي المبالغ المدفوعة</th>

                    <th scope="col" class="leading-2 tagHt "> <p id="myText">الرصيد الحالي </p></th>
                    <th scope="col" class="leading-2 tagHt ">     المزيد التفاصيل
                    </th>
                   
                </tr>
            </thead>
            <tbody class="">
                @for ($i = 0; $i < 20; $i++)
                <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                    <td class="tagTd  border-r border-r-orange-950">
                        <p class="text-gray-900 whitespace-no-wrap"> الصندوق</p>
                    </td>
                    <td class="tagTd  border-r border-r-orange-950">
                        <p class="text-gray-900 whitespace-no-wrap"> 5</p>
                    </td>

                    <td class="tagTd   items-center font-bold ">
          
                            <div class="flex gap-2   items-center mr-5">
                          $41225
                            <a href="#" target="_blank"  class="">
                                <svg class="w-5 text-green-500 text-base font-bold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>                            </a>
                            </div>
                            </td>
                            <td class="tagTd   items-center font-bold ">
          
                                <div class="flex gap-2   items-center mr-5">
                              $41253155
                                <a href="#" target="_blank"  class="">
                                    <svg class="w-5 text-green-500 text-base font-bold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>                            </a>
                                </div>
                                </td>
                            <td class="tagTd ">                                <p class="text-gray-900 whitespace-no-wrap">
                                    $1500
                                </p>
                            </td>
                            <td class="tagTd  ">                                <a href="{{route('users.details')}}"
                            class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                            <span aria-hidden
                            class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                            <span class="relative">المزيد</span>
                        </a>
                    </td>
                     
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
