<form action="">
    <div class="flex items-center shadow-md p-4 bg-white">
          <div class="w-[40%]">
              <div class="font-bold  text-center">    <label for="" class="border-b-2 border-black font-bold ">التاريخ </label>
                  <input type="date" class="inputSale" placeholder="505,550"> 
              </div>
         </div>
          <div class="text-gray-700 px-4 w-[20%]">
              <div class="font-bold  text-center">    
                  <label for="b" class="text-center ">   سند صرف </label>
                  <select   dir="ltr" id="accountty"  class="inputSale " style="display:block" required>
                  <option value="CA" selected>  </option>
                  <option value="US" > الصندوق </option>
                  <option value="DE">البنك</option>
                </select>  
              </div>
          </div>
          <div class="text-gray-700 w-[40%] ">
              <div class="font-bold  text-center ">   
                  <span class="">  
                  <label for="" class="border-b-2 border-black font-bold text-base " >المبلغ </label>
                  <input id="maont" type="number" class="inputSale" placeholder="505,550" required> 
                  </span>
              </div>
              <div class="font-bold  text-center">
                  <label for="b" class="text-center ">  جهة الدفع </label>
                  <select   dir="ltr" id="accountty" class="inputSale " style="display:block" required>
                  <option value="CA" selected>  </option>
                  <option value="US" >حساب رئيسي </option>
                  <option value="DE">فرعي</option>
                </select>           
              </div>
              <div class="font-bold  text-center">   <label for="b" class="text-center ">    تقيد المبلغ لحساب /الدائن</label> 
                  <select   dir="ltr" id="accountty" class="inputSale " style="display:block">
                      <option value="CA" selected>  </option>
                      <option value="US" >حساب رئيسي </option>
                      <option value="DE">فرعي</option>
                    </select>   
              </div>
          </div> 
      </div>
  <br>
  <div class="shadow-md p-4 bg-white"> 
      <ul class="space-y-2  ">
          <li>
              <ul class="grid grid-cols-3 w-full ">
                  <li class="border-2 border-black py-2 text-center  rounded "> جهة المستفيد </li>
                  <li class="border-2 border-black py-2 text-center  rounded p ">إيداع في حساب</li>
                  <li class="border-2 border-black py-2 text-center  rounded ">البيان</li>
              </ul>
              <ul class="grid grid-cols-3  w-full py-1">
                  <li class="text-center"> <select   dir="ltr" id="accountty" class="inputSale " style="display:block">
                          <option value="CA" selected>  </option>
                          <option value="US" >حساب رئيسي </option>
                          <option value="DE">فرعي</option>
                        </select>  
                      </li>
                  <li class=" text-center">
                        <select   dir="ltr" id="accou" class="inputSale " style="display:block">
                      <option value="CA" selected>  </option>
                      <option value="US" >حساب رئيسي </option>
                      <option value="DE">فرعي</option>
                    </select>   </li>
                  <li class=" text-center">             
                         <textarea class="inputSale" name="" id="" cols="30" rows="1"></textarea>
                  </li>
              </ul>
          </li>
       </ul>
      </div>  
  <div class="flex place-content-center py-4 ">
      <div class="mx-10" id="newInvoice" >
          <button  onclick="datainvoices()" data-modal-target="crud-modal" data-modal-toggle="crud-modal" type="submit" class=" text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">     
                           حفظ الحساب 
              </button>
          </div>
          <div class="mx-10" id="newInvoice" >
              <button type="button"  class="text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">           
                               الغاء الحساب 
                    </button>
              </div>
      </div>
  </form>
  
  {{-- alert --}}
  <div  id="crud-modal"    style="display: " class=" bg-black bg-opacity-50 overflow-y-auto overflow-x-hidden hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0  h-full">
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
           
  <div class="" x-data="invoices()" >
     
      <div class="text-gray-500 cursor-pointer w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-300 inline-flex items-center justify-center" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" @click="printInvoice()">
               
                  
          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
              <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z"/>
              <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
              <rect x="7" y="13" width="10" height="8" rx="2" />
          </svg>
            
      </div>
      
      
          <div class="container w-full " id="js-print-template" x-ref="printTemplate">
      
              <div class=" border-2 border-black rounded-b-lg my-2 ">
                  <div class="bg-gray-200 p-8 rounded-lg  flex justify-between w-full ">
                      <div class="text-right">
                          <h2 class="text-xl font-bold mb-2">    محلات صادق الريعاني للتجارة والمقاولات </h2>
                          <p>مواد بناء <strong>-</strong> ادوات كهربائية <strong>-</strong> دهانات</p>
                          <p> الصباحة السوق الاعلئ بعد سوق القات </p>
                          <p> 772020232-77774633-123456789</p>
                      </div>
                      <div class="flex items-center justify-center">
                          <div class="w-24 h-20 bg-gray-300 flex items-center justify-center translate-x-2 ">
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
      
              <div class="w-full p-3 flex justify-between bg-gray-100 border-black border-2 rounded-lg h-40 my-2 text-center font-bold ">
                  <div class="p-3  bg-gray-100 border-black  h-16 my-1 text-right font-bold space-y-2 ">
      
                      <p >رقم السند : 55</p>
                      <p > تاريخ السند : {{ \Carbon\Carbon::now()->format('Y/m/d') }}</p>
                      <p >رقم المرجع : 21</p>
                  </div>
                  <div class="p-3 w-52 bg-gray-100 border-black   h-16 my-1 text-center text-2xl font-bold underline underline-offset-8">
      
                      <p > سند قبض /نقد</p>
                  </div>
                  <div class=" w-52 bg-gray-100 h-16 my-1 text-center font-bold space-y-2">
                      <div>
      
                          <p class="border-b-2 border-black font-bold text-base">المبلغ </p>
                          <p id="maont2" class="bg-white h-10 font-bold text-lg pt-2">505,550 <span class="pb-1  font-normal">ر.ي </span></p>
                      </div>
                      <p class="text-right text-base">إيداع في حساب : الصندوق</p>
                  </div>
              </div>
              {{-- end header --}}
      {{--         
      @php
              function convertNumberToWords($number)
          {
              $formatter = new \NumberFormatter('ar', \NumberFormatter::SPELLOUT);
              return $formatter->format($number);
          }
      
          $number = 505550 ;
          $oo = convertNumberToWords($number) . ' ريال  يمني';
      @endphp --}}
      
      
              {{-- body srart --}}
              <div class="w-full p-3 relative space-y-2  text-base bg-gray-200 border-black  rounded-lg  my-2 text-right font-bold">
                  <div class="flex justify-between">
                      {{-- {{"(".$oo.")"}}  --}}
                      <p class="">أستلمنا من الأخ / جمال علي احمد سعد المغربي</p>
                      <p class="">مبلغ وقدره <span class="text-lg">:</span> <span class="font-bold text-base">{{number_format(505550)}}</span> ر.ي  </p>
                  </div>
                  <br>
                  <p>تقيد المبلغ لحساب /الدائن</p>
                  <div class="">
                      <table class=" bg-gray-100 w-full text-center  border-black border-2 my-2 mx-2">
                          <thead>
                              <tr class="bg-gray-300 ">
                                  <th class="px-2 py-2  border-black border-2 " >م</th>
                                  <th class=" min-w-52 max-w-72 border-black border-2">اسم الحساب </th>
                                  <th class="px-2 py-2 min-w-40 max-w-40  border-black border-2">البيان</th>
                                  <th class="px-2 py-2 min-w-16 max-w-24  border-black border-2">المبلغ </th>
                                  <th class="px-2 py-2 min-w-10 max-w-14  border-black border-2">العملة</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr class="text-right">
                                  <td class="py-2 px-2 border-black border-2">1</td>
                                  <td class="py-2 px-2 border-black border-2">جمال علي احمد سعد المغربي</td>
                                  <td class="py-2 px-2 border-black border-2">باقي دين من حسابه</td>
                                  <td class="py-2 px-2 border-black border-2">{{number_format(505550)}}</td>
                                  <td class="py-2 px-2 border-black border-2">ر.ي</td>
                              </tr>
                          </tbody>
                      </table>
                      <br>
                      <hr class="border-t-2 bg-black">
                      <br>
                      <br>
                      <div class="flex justify-between mx-10">
      
                         
                          <p >المحاسب............................ </p>
                      </div>
                  </div>
              </div>
                      {{-- body --}}
      
          </div>
      </div>
      </div>
      </div>
      
              </div>
              <!-- Modal body -->
          </div>
      </div>
  </div>
  
  <script>
      function datainvoices(){
          var maont = document.getElementById('crud-modal').value;
      }
     
      function invoices() {
      
  
  
  
           return {
               printInvoice() {
                  // var modal = document.getElementById('crud-modal');
                  //  modal.classList.remove('hidden');
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