@extends('layout')
@section('conm')

<div class="bg-white rounded-lg shadow-lg  max-w-xl mx-auto">
  
    <table class="w-full  mb-">
        <thead>
            <tr class="bg-indigo-700 text-white w-full border-x-4 border-x-indigo-700 border-y-4 border-y-indigo-700 ">
                <td colspan="2" class=" text-right  bg-white text-black  "> <div class=" py-2"> <div class="flex items-center">
                    <img class="h-8 w-8 mr-2" src="https://tailwindflex.com/public/images/logos/favicon-32x32.png"
                        alt="Logo" />
                    <div class="text-gray-700 font-semibold text-lg">محلات الريعاني</div>
                    

                </div>                    <div class="text-sm"> العنوان :</div>
                <div class="text-sm"> التلفون:7754545515455</div>

            </div></td>
                <td colspan="1" class="  items- py-4">
                    <div class="text-sm">التاريخ: 01/05/2023</div>
                    <div class="text-sm">رقم الفاتورة #: 12345</div>
                </td>
                <td colspan="1" class="  items-">
                    <h2 class="text-2xl font-bold mb-4">فاتورة المبيعات </h2>

                </td>
            </tr>
            <tr class=" w-full  ">
                <td colspan="2" class=" text-right  bg-white text-black  "> 
                    <div class=" py-2"> 
                        <div class="block items-center">
                    
                    <div class="text-indigo-700 ">اسم العميل : جمال </div>
                    <div class="text-sm text-indigo-700 "> العنوان :</div>
                    <div class="text-sm text-indigo-700 "> التلفون:7754545515455</div>

                    

                </div>   

            </div></td>
      
                <td colspan="" class="  ">
                    <div class="block justify-end mb-8">
                    <h2 class="text-1xl font-bold mb-">نوع الدفع :  <label for="" class=" text-indigo-700 ">نقدا</label></h2>
                  
                      
                     
                    </div>
                </td>
                <td>  <div class="text-gray-700 mr-2">المجموع:   $450.50</div>
                    <div class="text-gray-700 mr-2"> المدفوع : $25.50</div>
</td>
               
            </tr>
            <tr class="border-b border-b-indigo-700 py-2 " style="display:">
                <th class="text-center text-indigo-700   py-2">وصف</th>
                <th class="text-center text-indigo-700 py-2">	كمية</th>
                <th class="text-center text-indigo-700  py-2">سعر</th>
                <th class="text-center text-indigo-700  py-2">المجموع</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i<=10;$i++)
            <tr >
                <td class="text-center text-gray-700  py-2">Product 1</td>
                <td class="text-center text-gray-700  py-2 border-r border-r-indigo-700">1</td>
                <td class="text-center text-gray-700  py-2">$100.00</td>
                <td class="text-center text-gray-700  py-2 ">$100.00</td>

            </tr>
          
            @endfor
      
        </tbody>
    </table>
    {{-- <div class="flex justify-end mb-8">
        <div class="text-gray-700 mr-2">Subtotal:</div>
        <div class="text-gray-700">$425.00</div>
    </div> --}}
    <hr   class="bg-indigo-700 w-full ">
    <div class="text-right mb-8">
        <div class="text-gray-700 mr-2"> المتبقي : $25.50</div>

    </div>
    
    <div class="border-t-2 border-gray-300 ">

        <div class="flex justify-between">
        <div class="text-gray-700 mb-2 p-2"> <h2 class="text-1xl font-bold mb-">ملاحظة</h1> يجب سداد المبلغ خلال 30 يومًا. يخضع التأخر في السداد لرسوم. <br>
            يرجى تحرير الشيكات باسمjamal-ك وإرسالها بالبريد 
    
            
     
    </div>
    <div class="text-gray-700 mb-2 p-2"> <h2 class="text-1xl font-bold mb-">توقيع العميل :</h1> 
        
  <br>
</div>
        </div>
</div>
@endsection