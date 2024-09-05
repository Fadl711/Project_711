


<div class="" x-data="invoices()" >
    <div class="relative mr-4 inline-block " >

        <div class="text-gray-500 cursor-pointer w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-300 inline-flex items-center justify-center" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" @click="printInvoice()">
            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                <rect x="7" y="13" width="10" height="8" rx="2" />

            </svg>	 --}}

            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" >
                {{-- <rect x="0" y="0" width="24" height="24" stroke="none"></rect> --}}
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z"/>
                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                {{-- <rect x="7" y="13" width="10" height="8" rx="2" /> --}}
            </svg>

        </div>
        <div class="text-gray-500 cursor-pointer w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-300 inline-flex items-center justify-center" @click="()">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M7.926 10.898 15 7.727m-7.074 5.39L15 16.29M8 12a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm12 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm0-11a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
              </svg>




        </div>
        <div class="text-gray-500 cursor-pointer w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-300 inline-flex items-center justify-center" @click="()">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
              </svg>




        </div>



    </div>
    <div class="container w-full" id="js-print-template" x-ref="printTemplate">

{{--  --}}
        <div class=" border-2 border-black rounded-b-lg my-2 ">
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

function invoices() {
     return {
         printInvoice() {
             var printContents = document.getElementById('js-print-template').innerHTML;
             var originalContents = document.body.innerHTML;
             document.body.innerHTML = printContents;
             window.onafterprint = function() {
                 document.body.innerHTML = originalContents;
                 window.focus();
                 window.location.reload(); // reload the page after printing

                 // Add this line to close the modal window
                 document.getElementById('crud-modal').classList.add('hidden');
             };
             window.print();
         }
     }
 }
</script>

