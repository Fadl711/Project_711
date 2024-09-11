@extends('bonds.index')
@section('bonds')
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
          <button   type="submit" class=" text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">     
                           حفظ التعديل 
              </button>
          </div>
          <div class="mx-10" id="newInvoice" >
              <button type="button"  class="text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">           
                               الغاء التعديل 
                    </button>
              </div>
              <div class="mx-10" id="newInvoice" >
                <button type="button"  class="text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">           
                            حذف
                      </button>
                </div>
      </div>
  </form>
      @endsection