


<div class="" x-data="invoices()" >
    <div class="relative mr-4 inline-block " >
                    
        <div class="text-gray-500 cursor-pointer w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-300 inline-flex items-center justify-center" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" @click="printInvoice()">
            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                <rect x="7" y="13" width="10" height="8" rx="2" />

            </svg>	 --}}
            
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z"/>
                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                <rect x="7" y="13" width="10" height="8" rx="2" />
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
    
      
<div class="bg-white rounded-lg shadow-lg px-2 py-2 max-w-xl mx-auto " id="js-print-template" x-ref="printTemplate">
    <div class="flex items-center justify-between border-b border-[#6b571a]  ">
        <div class="text-gray-70 0">
            <div class="font-bold  ml-5">  محلات الريعاني </div>

            <div class=""><span  class="text-[#6b571a]  flex   ">   التلفون :-  <label class="text-black " for="">776327938</label> </span>
                <div class=""><span  class="text-[#6b571a]  flex ">   رقم البريد :  <label class="text-black " for="">1055186</label> </span></div>

            </div>
        </div>
        <div class="text-gray-700 px-4 ">
            <div class="font-bold text-xl block"> 
                <img class="h-20 w-20 mr-2 " src="build/assets/img/theme/react.jpg"
                    alt="Logo" />
                  
                     </div>
                     {{-- <div class="font-bold  ">  محلات الريعاني </div> --}}

        </div>
        <div class="text-gray-700 ">
            <div class="font-bold  ">فاتورة المبيعات </div>

            <div class="">  <span  class="text-[#6b571a]  flex "> التاريخ : <label class="text-black " for="">01/05/2024 </label>  </span></div>
            <div class="">   <span class="text-[#6b571a]  flex ">   رقم الفاتورة:  <label class="text-black " for="">134555</label>   </span></div>
        </div>
    </div>
    <div class="flex items-center justify-between border-b border-[#6b571a]   ">
        <div class="">
           

            <div class=""><span  class="text-[#6b571a]  flex ">   اسم العميل :  <label class="text-black " for=""> جمال علي احمد</label> </span></div>
            <div class=""><span  class="text-[#6b571a]  flex">   التلفون :  <label class="text-black " for="">776327938</label> </span></div>
        </div>
        <div class="text-gray-700 px-4"> 
          <div class=""><span  class="text-[#6b571a] ">  الدفع :  <label for="" class="text-black   ">نقدا</label> </span></div>
        </div>
        <div class="text-gray-700">
            
            <div class=" ">  <span  class="text-[#6b571a] flex "> التاريخ : <label class="text-black " for="">01/05/2024 </label>  </span></div>
            <div class=" ">   <span class="text-[#6b571a]  flex ">   رقم القيد:  <label class="text-black" for="">134555</label>   </span></div>
        </div>
    </div>
    <table class="w-full text-lef mb-8">
        <thead>
        
                <tr class="border-b border-b-[#6b571a]   " style="display:">
                    <th class="text-center text-[#6b571a]    ">وصف</th>
                   
                    <th class="text-center text-[#6b571a]   "> سعر الصنف</th>
                    <th class="text-center text-[#6b571a]  ">	كمية</th>
                    <th class="text-center text-[#6b571a]   ">اجمالي السعر</th>

          
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i<=20;$i++)
            <tr >
             
                <td class="py-1   text-center">رنج</td>
                <td class="py-1   text-center">$100.00</td>
                <td class="py-1   text-center">1</td>
               
                <td class="py-1   text-center">$100.00</td>

            </tr>
          
            @endfor
            <tr class="  " style="display:">
                <th colspan="4" class="text-right   "> 
                    <div class="block   mr-8 ">
                   <div class=" text-sm">الجمالي الفاتورة:$450.50</div>
                   <div class=" text-sm text-right "> الخصم:$</div>
                   <div class="flex text-right ">

                   <div class=" text-sm flex">صافي الفاتورة:$450.50</div>
                   <div class=" text-sm px-3 flex">الفين ومائتين وخمسين .ريال يمني</div>
                   </div>
                   {{-- <div class=" text-sm px-3">الفين ومائتين وخمسين .ريال يمني</div> --}}
               </div> </th>
                {{-- <th colspan="3"  class="text-right   mr-10 text-sm">الفين ومائتين وخمسين .ريال يمني </th> --}}
              
            

      
        </tr>
       
   
<tr class="">
    <th colspan="2" class="text-center py-2"> 

         <div class=" text-sm ">توقيع المبيعات :_____________</div>
 </th>
    {{-- <th colspan="3"  class="text-right   mr-10 text-sm">الفين ومائتين وخمسين .ريال يمني </th> --}}
  
    
    <th colspan="2" class="text-center"> 
       <div class=" text-sm">توقيع العميل:_____________</div>

       
   </th>
  

</tr>
        
          
        </tbody>
        
    </table>
    <div class="flex px-2 bg-[#6b571a]  rounded-lg">
        <div class=" text-sm flex "> 
           

            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z"/>
              </svg>
              <span class="text-white px-2 " >967776327938+</span>
              
    </div>
    <div class=" text-sm flex px-2"> 
       

        <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z"/>
          </svg>
          
          <span class="text-white px-2 " >الصباحة خط الحديدة قبل مفرق  لؤلؤة </span>
</div>
    
    </div>
</div>
</div>





<script>
     
 function invoices() {
     return {
         printInvoice() {
             var printContents = this.$refs.printTemplate.innerHTML;
             var originalContents = document.body.innerHTM
             document.body.innerHTML = printContents;
             window.print();
             document.body.innerHTML = originalContents;
         }
     }
 }
</script>
  


