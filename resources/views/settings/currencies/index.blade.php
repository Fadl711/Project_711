@extends('layout')
@section('conm')

<div class="  container grid grid-cols-2 gap-1 py-2">
    <div class=" text-center xl:grid grid-cols-2 gap-1">
      <div  class=" text-center  ">  

      <label for="" class=" text-center" >العمله </label>
      <select   dir="ltr" id="accountty" class="inputSale "  required>
          <option value="YER" selected>ريال يمني  </option>
          <option value="US" > ريال سعودي </option>
          <option value="USD">دولار</option>
        </select>             
     </div>
     <div  class=" text-center  ">  
      <label for="" class=" text-center"  > سعر الصرف  </label>
      <input id="maont" type="number" class="inputSale " required> 
     </div>
    </div>
     <div class="text-center  xl:grid grid-cols-2 gap-1">  
      <div class="text-center ">  
      <label for="" class=" text-center " >المبلغ </label>
      <input id="maont" type="number" class="inputSale "  required> 
      </div>
     
    
    
         
      </div>
    </div>
    <div class="text-center ">  
      
     
                        <button type="button" class="mr-3  bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">اضافة عملة</button>
                        <button type="button" class=" bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">تعديل العمله</button></td>
                        <button type="button" class="  bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline"> تلقائي</button>

                    </div>
@endsection