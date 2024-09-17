@extends('inventory.index')
@section('inventory')
<div colspan="4"  class="flex flex-col  justify-center items-center  ">
    
    <div class="relative  border text-black border-gray-200 rounded-lg w-[50%]  ">
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
<div class="  min-w-full shadow-md rounded px-1   max-h-screen overflow-x-auto py-1 text-sm ">
    
    <table class="min-w-full bg-white  text-sm  " >
        <thead class=" ">
           <tr>
            <td class="py-1   text-right bg-white"> 
                @for ($i = 0; $i < 10; $i++)
                <div class=" md:flex md:justify-around  px-1   border rounded shadow-md   text-sm " >
                              
                   <div class="min-w-full  container grid grid-cols-2 gap-1 py-1 ">
                      
                        <div class="text-right  lg:grid grid-cols-5    ">  
                           <div class="text-right ">            
                                    <label for="i" class=" text-sm font-medium text-[#2430d3]">  عنوان الجرد : جرد الاصناف ل2024 </label>
                               </div>
                         <div class="text-right ">  
                           <label for="Supplirname" class=" text-sm font-medium ">  رقم الجرد : 1 </label>
                         </div>
                       
                         <div class="text-right "> 
                           <div  class=" text-right  ">  
                               <label for="num" class=" text-sm font-medium">   المستخدم: جمال </label>
                                
                                         </div> 
                           
                             </div>
                             <div id="newInvoice "  class=" text-right">
                               <label for="num" class=" text-sm font-medium text-red-700">التاريخ الجرد: <span>2024/10/11</span> </label>
                               </div>
                            
                         </div>
                         <div id="newInvoice " class=" " >
                          <label for="num" class=" text-left">
                              <a href="{{route('inventory.show')}}"  class="text-sm py-2  leading-none rounded-md hover:bg-gray-100" >                    
      
                           
                              <svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                  <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                  <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                
                              </a>
                          </label>     
                   
                </div>
               
                         {{-- <div class="text-left  lg:grid grid-cols-4 gap-1">   --}}
                         
                         {{-- <div class="text-left ">  
                           <label for="num" class="text-sm font-medium">   الاجمالي/ 2,0000,000</label>
                         </div>
                       
                         <div class="text-center "> 
                           <div  class=" text-center  ">  
                               <label for="num" class="text-sm font-medium">  الخصم/ 100,000 </label>
                                
                                         </div> 
                           
                             </div>
                             <div id="newInvoice"  >
                               <label for="num" class="labelSale text-left"> الاجمالي الصافي/ 1,000,000</label>
                               </div> --}}
                               {{-- <div id="newInvoice " class=" " >
                                   <label for="num" class=" text-left">
                                       <a href="{{route('sale_refunds.show')}}"  class="text-sm py-2  leading-none rounded-md hover:bg-gray-100" >                    
               
                                    
                                       <svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                           <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                           <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                         </svg>
                                         
                                       </a>
                                   </label>     
                            
                         </div> --}}
                         
                       </div>
                         
                       
                </div>
               </div>
               
                <br>
                
                @endfor
                
            </td>
           </tr>
        </thead>
    </table>
</div>


@endsection