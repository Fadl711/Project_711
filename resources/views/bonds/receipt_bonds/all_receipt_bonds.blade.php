   
<div class=" text-sm">
    <div class="flex flex-col gap-4 justify-center items-center py-2">
    
        <div class="relative  border border-gray-200 rounded-lg w-[30%] ">
            <input type="text" class="rounded-md w-full text-left" placeholder="Search ">
    
            <button type="submit" class="absolute right-6 top-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
            </button>
    
        </div>
    </div>
    <div class=" min-w-full shadow rounded-lg px-  max-h-screen overflow-x-auto  ">
    <table class="min-w-full bg-white  text-sm " >
        <thead class="bg-[#2430d3] text-white  ">
           <tr>

            <th class="bg-white "> </th>
            <th colspan="2" class="py-1  border text-center">حساب القبض </th>
            <th colspan="2" class="py-1  border text-center">حساب الدفع</th>
            
           </tr>
            <tr>
              
                <th class=" border text-right "> التاريخ</th>
                <th class="py-1  border text-right">   الرئيسي</th>
                <th class="py-1  border text-right">   الفرعي </th>
                <th class="py-1  border text-right"> الرئيسي</th>
                <th class="py-1  border text-right"> الفرعي</th>
                <th class="py-1  border text-right">المبلغ</th>
                <th class="py-1  border text-right">البيان	</th>
                
                <th class="py-1  border text-right">العملة</th>
                <th class="py-1  border text-right ">المرجع</th>
                <th class=" border text-right"> عرض - تحرير</th>
              
                
            </tr>
        </thead>
        <tbody >
            @for ($i = 0; $i < 10; $i++)
            <tr class="transition-all duration-500">
               
                <td class=" border text-right">09/09/2024 </td>
                <td class=" border text-right">صندوق مالية </td>
                <td class=" border text-right">صندوق 2</td>
                <td class=" border text-right">العملاء</td>
                <td class=" border text-right">جمال</td>
                <td class=" border text-right">100,000</td>
                <td class=" border text-right"> تسديد ماتبقا من حسابه </td>
                <td class=" border text-right">ريال</td>
                <td class=" border text-right">21</td>
                <td class=" border text-right">  
                    <button >
                    <svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                        <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                      </svg>
                      </button>
                    
                    <button class="px-1"><svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>
                      </svg>
                      </button>
                    
                    </td>


                
            </tr>
          @endfor
            
            <!-- يمكنك إضافة المزيد من الصفوف هنا -->
        </tbody>
    </table>
</div>
