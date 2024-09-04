<form  class="p-1  ">
    {{-- <label class="labelSale" for="email">إعدادات عرض البيانات </label> --}}
        <ul class="items-center w-full  font-medium text-gray-900 bg-white border border-indigo-700 rounded-lg sm:flex dark:bg-gray-700 dark:border-indigo-700 dark:text-white">
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-indigo-700">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale">إعدادات العرض والبحث  </label>
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale" checked >تلقائي </label>
                    <input checked id="horizontal-list-radio-license" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600 ">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale" >اليوم </label>
                    <input  id="horizontal-list-radio-license" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-id" class="labelSale">هذا الاسبوع </label>
                    <input id="horizontal-list-radio-id" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center text-center ">
                    <label for="horizontal-list-radio-military" class="labelSale ">هذا الشهر</label>
                    <input id="horizontal-list-radio-military" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center  p-1">
                    <label for="horizontal" class="w-full  ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">من تاريخ </label>

                    <input name="horizontal" class="inputSale " id="" type="date" placeholder=""/>
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
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
 
<div class="flex  gap-4 justify-center items-center overflow-x-auto  ">
    <div class=" min-w-full  rounded-lg  max-h-[500px] ">

        <br>
        <div class="bg-white   shadow-lg  max-w-xl mx-auto  rounded-2xl p-1 " >
            @for($s=0; $s<=10;$s++)
            <div class="bg-white   shadow-lg  max-w-xl mx-auto  rounded-2xl p-2 border-x-2 border-y-2 border-indigo-700" >
                <table   class="w-full ">
                    <thead class="tracking-tight   " >
                        <tr  class="bg-indigo-700 text-black  text-bold w-full border-x-2 border-y-2 border-indigo-700  ">
                            <td colspan="1" class=" text-right  p-1  bg-white text-black  ">
                                <div class="flex items-center ">
                                    <div class="text-indigo-700 text-sm ">اسم المورد : <label class="text-black" for="">     جمال علي احمد</label> </div>
                                </div>                   
                                <div class="text-indigo-700  text-sm"> التلفون: <label class="text-black" for="">775454554 </label></div>
                                <div class="text-sm text-indigo-700">الرقم التعريفي :   <label class="text-black" for="">15 </label></div>
                            </td>
                            <td colspan="2" class="p-1 ">
                                <h2 class="text-1xl font-bold text-white"> محلات الريعاني </h2>
                                <h2 class="text-1xl font-bold text-white"> icon  </h2>

                            </td>
                            <td colspan="1" class="   p-1 text-bold text-white ">
                                <div class=" text-white" > <label class="text-white text-sm" for="">01/05/2023</label>  :التاريخ</div>
                                <div class=""> <label class="text-white text-sm" for="">12345</label> :رقم الفاتورة  </div>
                                <div class=""> <label class="text-white text-sm" for="">1345</label> :رقم الايصال  </div>

                            </td>
                           
                        </tr>
                    </thead>
                <tbody>
                    <tr class="  border-b border-indigo-700">
                        <th scope="col"   class="  text-center text-sm"><div class="text-indigo-700 ">اجمالي الفاتورة</div></th>
                        <th scope="col"   class=" text-center text-sm"><div class="text-indigo-700 ">المبلغ المدفوع</div></th>
                        <th  scope="col"  class=" text-center text-sm"><div class="text-indigo-700 ">المبلغ المتبقي</div></th>

                    </tr>
                    <tr class="">
                        <td class=" text-center  text-[#1ff823] ">$442</td>
                        <td class=" text-center">$442</td>
                        <td class=" text-center  text-[rgb(193,44,44)]">$442</td>
                        <td class=" text-center text-indigo-700 "> <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="   focus:outline-none " type="button">
                          عرض المشتريات  الفاتورة   </button>
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

{{-- alert --}}

<div  id="crud-modal"    tabindex="-1" aria-hidden="true" class=" bg-black bg-opacity-50  hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0  h-full">
    <div class="  relative p-2 w-full max-h-full">
        <!-- Modal content -->
        <div class=" bg-white rounded-lg shadow dark:bg-gray-700 container" x-data="invoices()" >
            <!-- Modal header -->
            <div class="flex items-center justify-between p-1 md:p-5 border-b rounded-t dark:border-gray-600">
              
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>

                
            </div>
            
            <!-- Modal body -->
            <div  x-refs="printTemplate"  id="js-print-template" >
            @include('invoice_purchases.bills_purchase_show')
            
        </div>
        </div>
    </div>
</div>
{{-- alert --}}
<br>

<script>
     function invoices() {
        return {
            printInvoice() {
                var printContents = this.$refs.printTemplate.innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        }
    }

    
</script>