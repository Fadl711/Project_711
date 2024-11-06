@extends('bonds.index')
@section('bonds')

  <br>
  {{-- <button onclick="window.history.back()">رجوع</button> --}}

  <form action="">
  <div class="flex container  shadow-md py-4 px-2 bg-white">
        <div class="w-[40%] max-sm:w-[30%]">
            <div class="text-center">    
                <label for="b" class="text-center ">   سند القبض </label>
                <select   dir="ltr" id="accountty"  class="inputSale " style="display:block" required>
                <option value="CA" selected>  </option>
                <option value="US" > الصندوق </option>
                <option value="DE">البنك</option>
              </select>  
            </div>
            <div class=" text-center">  
                  <label for="" class=" text-center ">التاريخ </label>
                <input type="date" class="inputSale" placeholder="505,550"> 
            </div>
            
       </div>
        <div class="text-gray-700  px-2">
            
        </div>
        <div class="text-gray-700 w-[50%] max-sm:w-[70%] ">
            <div class="  text-center  ">  
                <div class="flex  " role=""> 
                    <div  class=" text-center  ">  
                        <label for="" class=" text-center" >العمله </label>
                        <select   dir="ltr" id="accountty" class="inputSale "  required>
                            <option value="CA" selected>  </option>
                            <option value="US" >حساب رئيسي </option>
                            <option value="DE">فرعي</option>
                          </select>             
                       </div>
                       <div  class=" text-center  ">  
                        <label for="" class=" text-center" >الصرف </label>
                        <input id="maont" type="number" class="inputSale px-1" placeholder="505,550" required> 
                       </div>

                <div class="">  
                <label for="" class=" text-center " >المبلغ </label>
                <input id="maont" type="number" class="inputSale px-1" placeholder="505,550" required> 
                </div>
                
                </div>
            </div>
            <div class=" text-center">
                <label for="b" class="text-center ">  حساب القبض </label>
                <select   dir="ltr" id="accountty" class="inputSale "  required>
                <option value="CA" selected>  </option>
                <option value="US" >حساب رئيسي </option>
                <option value="DE">فرعي</option>
              </select>           
            </div>
            <div class="text-center ">  
                 <label for="b" class="text-center ">  إيداع في حساب </label> 
                <select   dir="ltr" id="accountty" class="inputSale " >
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
                <li class=" text-center px-1"> جهة الدفع </li>
                <li class=" text-center px-1 ">تقيد المبلغ لحساب /الدائن </li>
                <li class=" text-center px-1">البيان</li>
            </ul>
            <ul class="grid grid-cols-3  w-full py-1">
                <li class="text-center"> <select   dir="ltr" id="accountty" class="inputSale " style="display:block">
                        <option value="CA" selected>  </option>
                        <option value="US" >حساب رئيسي </option>
                        <option value="DE">فرعي</option>
                      </select>  
                    </li>
                <li class=" text-center px-1">
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
    @endsection