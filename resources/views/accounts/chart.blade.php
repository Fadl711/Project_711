

<div class="flex-grow bg-white rounded-xl shadow-md px-6 py-4 flex flex-col items-end">
    <div class="text-xs font-semibold tracking-wide uppercase py-1 px-3 rounded-full"
        style="background-color: rgb(123, 255, 253); color: rgb(0, 119, 117);">New</div>
    <div class="grid grid-cols-7 gap-1 flex-grow self-stretch">
        <div class="flex flex-col justify-end items-center">
            <div class="w-4 h-4 mx-auto rounded-full" style="background-color: rgba(245, 6, 138, 0.788);"></div>
            <div class="text-center text-xs text-gray-400 font-semibold mt-2"> الصندوق <br>545454</div>
        </div>
        <div class="flex flex-col justify-end items-center">
            <div class="w-4 h-20 mx-auto rounded-full" style="background-color: rgb(0, 255, 244);"></div>
            <div class="text-center text-xs text-gray-400 font-semibold mt-2">البنك <br>544545</div>
        </div>
        <div class="flex flex-col justify-end items-center">
            <div class="w-4 h-16 mx-auto rounded-full" style="background-color: rgba(76, 0, 255, 0.58);"></div>
            <div class="text-center text-xs text-gray-400 font-semibold mt-2">البضاعة <br>54565</div>
        </div>
        <div class="flex flex-col justify-end items-center">
            <div class="w-4 h-24 mx-auto rounded-full" style="background-color: rgba(0, 255, 42, 0.615);"></div>
            <div class="text-center text-xs text-gray-400 font-semibold mt-2">المبيعات <br> 5455454</div>
        </div>
        <div class="flex flex-col justify-end items-center">
            <div class="w-4 h-32 mx-auto rounded-full" style="background-color: rgba(237, 0, 91, 0.632);"></div>

            <div class="text-center text-xs text-gray-400 font-semibold mt-2">المصروفات <br>58151</div>

        </div>
       
        <div class="flex flex-col justify-end items-center">
            <div class="w-4 h-10 mx-auto rounded-full" style="background-color: rgb(229, 255, 123);"></div>
            <div class="text-center text-xs text-gray-400 font-semibold mt-2">العملاء <br>45462</div>
        </div>
        <div class="flex flex-col justify-end items-center">
            <div class="w-4 h-10 mx-auto rounded-full" style="background-color: rgba(133, 178, 9, 0.793);"></div>
            <div class="text-center text-xs text-gray-400 font-semibold mt-2">الموردين <br> 64655</div>
        </div>
    </div>
</div>

<h1 class="text-center  font-bold py-2"> الحسابات</h1>
<div class="-mx-4 sm:-mx-8 px-4 sm:px-8 overflow-x-auto   p-1">
    <div class="inline-block min-w-full shadow rounded-lg  max-h-[500px] ">
        <table id="myTable" class="min-w-full leading-normal ">
            <thead class="tracking-tight ">
                <tr class="bgcolor">
                    <th scope="col" class="leading-2 tagHt ">رقم الحساب</th>

                    <th scope="col" class="leading-2 tagHt ">اسم الحساب</th>
                    <th scope="col" class="leading-2 tagHt "> الرصيد الحالي </th>
                   
                 
                    <th scope="col" class="leading-2 tagHt "> المزيد التفاصيل</th>
                </tr>
            </thead>
            <tbody class="">
            
               <tr class="bg-white transition-all duration-500 hover:bg-gray-50"> 
                   
                    <td class="tagTd  border-r border-r-orange-950">1</td>
                    <td  id="financial_accoun" class="tagTd  border-r border-r-orange-950">الصندوق</td>
                    <td class="tagTd   items-center font-bold ">$41225</td>
                            <td class="tagTd  "><a href="{{route('users.details')}}"
                            class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                            <span aria-hidden
                            class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                            <span class="relative">المزيد</span>
                        </a>
                    </td>
                </tr>
    
            </tbody>
        </table>
    </div>
</div>