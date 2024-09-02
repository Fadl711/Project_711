
<div  class="bg-indigo-700 text-black  text-bold w-full border-x-2 border-y-2 border-indigo-700 flex ">
    <div  class=" text-right   bg-white text-black  ">
        <div colspan="2" class=" items-center  bg-white text-black   ">
            <div class=" py-2"> 
               <div class="flex items-center">
           <img class="h-8 w-full mr-2" src="https://tailwindflex.com/public/images/logos/favicon-32x32.png"
               alt="محلات الريعاني" />
          

       </div>  
       <div class="">
        <span  class="text-indigo-700 flex ">   العنوان :  <label class="text-black text-sm" for="">صنعاء</label> </span>
        <span  class="text-indigo-700 flex ">   التلفون :  <label class="text-black text-sm" for="">776327938</label> </span>
       </div>     
      
   </div>
</div>
       
    </div> 
  
    <div class="px-10 ">
      

        <h2 class="text-1xl font-bold text-white">فاتورة المشتريات </h2>
    </div>
    <div  class="  items-center text-white w-[25%] ">
        <div class="text-sm text-white" > <label class="text-white text-sm" for="">01/05/2023</label>  :التاريخ</div>
        <div class="text-sm "> <label class="text-white text-sm" for="">12345</label> :رقم الفاتورة  </div>
        <div class="text-sm"> <label class="text-white text-sm" for="">1345</label> :رقم الايصال  </div>

    </div>
   
</div>
<div class="block">
    <div class="flex ">
           
           <div class="text-indigo-700 text-sm ">اسم المورد : <label class="text-black" for="">     جمال علي احمد</label> </div>
           <div class="text-indigo-700  text-sm px-4"> التلفون: <label class="text-black" for="">775454554 </label></div>
           <div class="text-sm text-indigo-700">الرقم التعريفي :   <label class="text-black" for="">15 </label></div>
       </div>  
       <div class="flex  border-b border-indigo-700 ">  
        <div class="text-indigo-700 ">   الاجمالي :  <label class="text-black text-sm" for="">$450.50</label> </div>
        <h2 class="text-1xl font-bold text-indigo-700 px-4">نوع الدفع :  <label for="" class="text-black   ">نقدا</label></h2>

        <div class="text-indigo-700 ">   المدفوع :  <label class="text-black text-sm" for="">$400.50</label> </div>



</div>                 
      
   </div>
<div class="  overflow-x-auto   px-1">
    
    <div class=" min-w-full  rounded-lg  max-h-[500px] ">
        
        <table id="myTable" class="min-w-full leading-normal ">
            <thead class="tracking-tight ">
               
                    
              
              
              
                <tr class="border-b border-b-indigo-700 py-2 " style="display:">
                    <th class="text-center text-indigo-700   py-2">وصف</th>
                    <th class="text-center text-indigo-700 py-2">	كمية</th>
                    <th class="text-center text-indigo-700  py-2">سعر</th>
                    <th class="text-center text-indigo-700  py-2">المجموع</th>
                    <th class="text-center text-indigo-700  py-2"></th>

                </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
          
            @for($i=0; $i<=20;$i++)
            <tr >
                <td class="text-center text-gray-700  py-2">Product 1</td>
                <td class="text-center text-gray-700  py-2 border-r border-r-indigo-700">1</td>
                <td class="text-center text-gray-700  py-2">$100.00</td>
                <td class="text-center text-gray-700  py-2 ">$100.00</td>

            </tr>
          
            @endfor
             
        </tbody>
    </table>
</div>
</div>
