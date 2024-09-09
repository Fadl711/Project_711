

<div class="mb-4 max-lg:flex md:flex  w-full" >
  <div class="lg:ml-2 w-[30%] max-sm:ml-2">
    <label class="labelSale  " for="accountty"> نوع الحساب</label>
    <select   dir="ltr" id="accountty" class="inputSale " style="display:block">
      <option value="CA" selected>  </option>
      <option value="US" >حساب رئيسي </option>
      <option value="DE">فرعي</option>
    </select>
  </div>
  <div class="lg:ml-2 w-[40%] max-sm:ml-2">
    <label class="labelSale" for="accountType"> الكود  </label>
    <select  dir="ltr"  id="" class="  inputSale " >
      <option selected>105</option>
    
      <option value="DE">451</option>
    </select>
  </div>
</div>
<br>
<div id="ac" style="display:none">
<form>
  
    

<div class="mb-4 md:flex md:justify-around w-full">
  <div class="mb-4 max-lg:flex md:flex  w-full" >
    <div class="lg:ml-2 w-[35%] max-sm:ml-2">
        <label class="labelSale" for="email">اسم الحساب</label>
        <input name="" class="inputSale" id="brand" type="text" placeholder="اسم الحساب الجديد"/>
    </div>

    <div class="lg:ml-2 w-[35%] max-sm:ml-2">
      <label class="labelSale  " for="accountType">  الحساب الرئيسي</label>
      <select  dir="ltr"  id="accountType" class="  inputSale " >
        <option selected>المصروفات</option>
      
        <option value="DE">الصندوق الرئيسي</option>
        <option value="DE">البنك  </option>
      </select>
    </div>
      {{-- <div class="md:ml-2">
        <label class="labelSale" for="email">كود الحساب</label>
        <input name="" class="inputSale " id="" type="text" placeholder=""/>
    </div> --}}
    </div>
  

</div>
<div class="mb-4 max-lg:flex md:flex  w-full" >
  <div class="lg:ml-2 w-[35%] max-sm:ml-2">
    <label class="labelSale" for="email">  رصيدافتتاحي مدين (اخذ)</label>
    <input name="" class="inputSale " id="" type="text" placeholder="0"/>
</div>
<div class="lg:ml-2 w-[35%] max-sm:ml-2">

    <label class="labelSale" for="lastName" >رصيدافتتاحي دائن (عاطي) </label>
    <input name="" class="inputSale  " id="" type="number"  placeholder="0"/>
</div>
</div>



<div class="flex place-content-center ">
  
<div class="mx-10" id="newInvoice" >
    <button type="button" class="text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">     
                     حفظ الحساب 
        </button>
    </div>
    <div class="mx-10" id="newInvoice" >
        <button type="button" class="text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">           
                         الغاء الحساب 
              </button>
        </div>
</div>
</form>   
</div> 


<div class="" id="acass" style="display:none">
<form>
  
  


  
  <div class="mb-4 max-lg:flex md:flex  w-full" >
    <div class="lg:ml-2 w-[35%] max-sm:ml-2">
      <label class="labelSale" for="email">اسم الحساب</label>
      <input name="" class="inputSale" id="brand" type="text" placeholder="اسم الحساب الجديد"/>
  </div>
  <div class="lg:ml-2 w-[35%] max-sm:ml-2">
      <label class="labelSale  " for="accountType"> تصنيف الحساب</label>
      <select id="accountType" class=" text-left inputSale">
        <option selected></option>
        <option value="US">الاصول</option>
        <option value="CA">خصوم وحقوق الملكية</option>
        <option value="FR">المصروفات</option>
        <option value="DE">الايرادات</option>
      </select>
    </div>
  </div>
  <div class="mb-4 max-lg:flex md:flex  w-full" >
    {{-- <div class="lg:ml-2 w-[35%] max-sm:ml-2">
      <label class="labelSale" for="email">كود الحساب</label>
      <input name="" class="inputSale " id="" type="text" placeholder=""/>
  </div> --}}
  <div class="lg:ml-2 w-[35%] max-sm:ml-2">
    <label class="labelSale" for="email">  رصيدافتتاحي مدين (اخذ)</label>
    <input name="" class="inputSale " id="" type="text" placeholder="0"/>
</div>
<div class="lg:ml-2 w-[35%] max-sm:ml-2">
    <label class="labelSale" for="lastName" >رصيدافتتاحي دائن (عاطي) </label>
    <input name="" class="inputSale " id="" type="number"  placeholder="0"/>
</div>
  </div>
 
  

<div class="flex ">
  <label class="" for="email">طبيعة الحساب</label>
  
  <div class="flex " >
  <label for="inline-radio" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">مدين</label>
  
    <input id="inline-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
  </div>
  <div class="flex ">
  <label for="inline-2-radio" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">دائن</label>
    <input id="inline-2-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
  </div>
  </div>
<div class="flex place-content-center ">
<div class="mx-10" id="newInvoice" >
  <button type="button" class="text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">     
                   حفظ الحساب 
      </button>
  </div>
  <div class="mx-10" id="newInvoice" >
      <button type="button" class="text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">           
                       الغاء الحساب 
            </button>
      </div>
</div>
</form>    
</div>

  <!-- Dropdown menu -->
  
  
<script>
var acaaa= document.getElementById('ac');
  var aaa= document.getElementById('acass');
  const selectedOption=this.value;
  if(selectedOption=='DE'){ 
    // alert("jfj");
    acaaa.style.display="block";
    aaa.style.display="none";
  }else if(selectedOption=='US'){ 

    aaa.style.display="block";
    acaaa.style.display="none";
  }else {
    aaa.style.display="none";
    acaaa.style.display="none";

  }
document.getElementById('accountty').addEventListener('change',function(){
  const selectElement= document.getElementById('accountty').value;
  var acaaa= document.getElementById('ac');
  var aaa= document.getElementById('acass');
  const selectedOption=this.value;
  if(selectedOption=='DE'){ 
    // alert("jfj");
    acaaa.style.display="block";
    aaa.style.display="none";
  }else if(selectedOption=='US'){ 

    aaa.style.display="block";
    acaaa.style.display="none";
  }else {
    aaa.style.display="none";
    acaaa.style.display="none";

  }


});

</script>
