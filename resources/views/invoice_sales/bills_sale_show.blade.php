


<div class="" x-data="invoices()" >
    <div class="relative mr-4 inline-block " >
                    
        <div class="text-gray-500 cursor-pointer w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-300 inline-flex items-center justify-center" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" @click="printInvoice()">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                <rect x="7" y="13" width="10" height="8" rx="2" />
            </svg>				  
        </div>
      
    </div>

<div class="bg-white rounded-lg shadow-lg px-2 py-2 max-w-xl mx-auto " id="js-print-template" x-ref="printTemplate">
    <div class="flex items-center justify-between border-b border-[#f8c21f]  ">
        <div class="text-gray-70 0">
            <div class="font-bold  ml-5">  محلات الريعاني </div>

            <div class=""><span  class="text-[#f8c21f]  flex ">   العنوان :  <label class="text-black " for="">صنعاء</label> </span></div>
            <div class=""><span  class="text-[#f8c21f]  flex   ">   التلفون :  <label class="text-black " for="">776327938</label> </span>
            </div>
        </div>
        <div class="text-gray-700 px-4 ">
            <div class="font-bold text-xl block"> 
                <img class="h-20 w-20 mr-2 " src="build/assets/img/theme/react.jpg"
                    alt="Logo" />
                  
                     </div>
                     <div class="font-bold  ">  محلات الريعاني </div>

        </div>
        <div class="text-gray-700 ">
            <div class="font-bold  ">فاتورة المبيعات </div>

            <div class="">  <span  class="text-[#f8c21f]  flex "> التاريخ : <label class="text-black " for="">01/05/2024 </label>  </span></div>
            <div class="">   <span class="text-[#f8c21f]  flex ">   رقم الفاتورة:  <label class="text-black " for="">134555</label>   </span></div>
        </div>
    </div>
    <div class="flex items-center justify-between border-b border-[#f8c21f]   ">
        <div class="">
           

            <div class=""><span  class="text-[#f8c21f]  flex ">   اسم العميل :  <label class="text-black " for=""> جمال علي احمد</label> </span></div>
            <div class=""><span  class="text-[#f8c21f]  flex">   التلفون :  <label class="text-black " for="">776327938</label> </span>
            </div>
        </div>
        <div class="text-gray-700 px-4"> 
          <div class=""><span  class="text-[#f8c21f] ">  الدفع :  <label for="" class="text-black   ">نقدا</label> </span></div>
        </div>
        <div class="text-gray-700">
            
            <div class=" ">  <span  class="text-[#f8c21f] flex "> التاريخ : <label class="text-black " for="">01/05/2024 </label>  </span></div>
            <div class=" ">   <span  class="text-[#f8c21f]  flex ">   رقم القيد:  <label class="text-black" for="">134555</label>   </span></div>
        </div>
    </div>
    <table class="w-full text-left mb-8">
        <thead>
        
                <tr class="border-b border-b-[#f8c21f]   " style="display:">
                    <th class="text-center text-[#f8c21f]    ">وصف</th>
                    <th class="text-center text-[#f8c21f]  ">	كمية</th>
                    <th class="text-center text-[#f8c21f]   ">سعر</th>
                    <th class="text-center text-[#f8c21f]   ">اجمالي السعر</th>

          
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i<=20;$i++)
            <tr >
             
                <td class="py-1   text-center">Product 1</td>
                <td class="py-1   text-center">1</td>
                <td class="py-1   text-center">$100.00</td>
                <td class="py-1   text-center">$100.00</td>

            </tr>
          
            @endfor
        
          
        </tbody>
    </table>
    <div class="  block">
        <div class="text-right mb-8 flex">
        <div class="text-gray-700 mr-2">الإيجمالي بعد الخصم:</div>
        <div class="text-gray-700">$425.00</div>
        </div>
  
    <div class="text-right mb-8 flex">
        <div class="text-gray-700 mr-2">الخصم:</div>
        <div class="text-gray-700">$25.50</div>

    </div>
    <div class="flex justify-end mb-8">
        <div class="text-gray-700 mr-2">الجمالي الفاتورة:</div>
        <div class="text-gray-700 font-bold text-xl">$450.50</div>
    </div>
    <div class="border-t-2 border-[#f8c21f] ">
        <div class="text-gray-700 mb-2">Payment is due within 30 days. Late payments are subject to fees.</div>
      
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
  


