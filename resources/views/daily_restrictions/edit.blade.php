@extends('daily_restrictions.index')
@section('restrictions')

<form action="" class="">
    <div class=" container  -md py-4 px-1 ">
    <div class="flex container ">
          <div class="w-[50%] shadow-md px-1  border  rounded-md py-2 bg-white">
              <div class="text-center  px-2 ">    
                  <label for="b" class="text-center ">    حساب المدين/الرئيسي </label>
                  <select   dir="ltr" id="accountty"  class="inputSale " style="display:block" required>
                  <option value="CA" selected>  </option>
                  <option value="US" > الصندوق </option>
                  <option value="DE">البنك</option>
                </select>  
              </div>
              <div class="text-center  px-2 ">    
                <label for="b" class="text-center ">    حساب المدين/الفرعي </label>
                <select   dir="ltr" id="accountty"  class="inputSale " style="display:block" required>
                <option value="CA" selected>  </option>
                <option value="US" > الصندوق </option>
                <option value="DE">البنك</option>
              </select>  
            </div>
              
         </div>
          <div class="  px-2">
              
          </div>
          <div class=" w-[50%] shadow-md rounded-md px-1 py-2 bg-white border  ">
              <div class="  text-center  ">  
                  
              </div>
              <div class=" text-center  px-2 ">
                  <label for="b" class="text-center ">   حساب الدائن/الرئيسي</label>
                  <select   dir="ltr" id="accountty" class="inputSale "  required>
                  <option value="CA" selected>  </option>
                  <option value="US" >حساب رئيسي </option>
                  <option value="DE">فرعي</option>
                </select>           
              </div>
              <div class="text-center  px-2 ">  
                   <label for="b" class="text-center ">  حساب الدائن/الفرعي </label> 
                  <select   dir="ltr" id="accountty" class="inputSale " >
                      <option value="CA" selected>  </option>
                      <option value="US" >حساب رئيسي </option>
                      <option value="DE">فرعي</option>
                    </select>   
              </div>
          </div> 
       
        </div>
        <br>
        <div class="px-3  ">
          <div class=" shadow-md  bg-white  rounded-md border">
          

            <div class="  container grid grid-cols-2 gap-1 py-2">
              <div class=" text-center xl:grid grid-cols-2 gap-1">
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
                <input id="maont" type="number" class="inputSale " placeholder="505,550" required> 
               </div>
              </div>
               <div class="text-center  xl:grid grid-cols-2 gap-1">  
                <div class="text-center ">  
                <label for="" class=" text-center " >المبلغ </label>
                <input id="maont" type="number" class="inputSale " placeholder="505,550" required> 
                </div>
              
                <div class="text-center ">  
                    <label for="" class=" text-center " >المبلغ بعد الصرف</label>
                    <input id="maont" type="number" class="inputSale " placeholder="505,550" required> 
                    </div>
                 
                   
                </div></div>
                
                <div class="text-center px-3 py-">  
                  <label for="" class=" text-center " >  البيان</label>
                  <textarea id="maont" type="number" class="inputSale " placeholder="505,550" required> </textarea>
                  </div>
                  
    
       
    <div class="inline-flex container rounded-md shadow-sm px-2 py-2" role="group">
      <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
      
            
       حفظ التعديل
      </button>
      <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z" clip-rule="evenodd"/>
              <path fill="currentColor" d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z"/>
            </svg>
            
            الغاء التعديل
        
        
      </button>
     
    </div>
  </div>
        </div>
           
            <br>
          </div>
        
    


</form>
@endsection
