<h1 class="font-bold">اضافة الحساب الجديد</h1>
<br>
<form>
<div class="mb-4 md:flex md:justify-around">
    <div class="md:ml-2">
        <label class="labelSale" for="email">اسم الحساب</label>
        <input name="" class="inputSale" id="brand" type="text" placeholder="اسم الحساب الجديد"/>
    </div>
    <div class="md:ml-2 ">
        <label class="labelSale  " for="accountType"> نوع الحساب</label>
        <select id="accountType" class=" text-left inputSale">
          <option selected></option>
          <option value="US">الاصول</option>
          <option value="CA">خصوم وحقوق الملكية</option>
          <option value="FR">المصروفات</option>
          <option value="DE">الايرادات</option>
        </select>
      </div>
      <div class="md:ml-2">
        <label class="labelSale" for="email">كود الحساب</label>
        <input name="" class="inputSale " id="" type="text" placeholder=""/>
    </div>
    <div class="md:ml-2">
        <label class="labelSale" for="email">  رصيدافتتاحي مدين (اخذ)</label>
        <input name="" class="inputSale " id="" type="text" placeholder="0"/>
    </div>
    <div class="md:ml-2">
        <label class="labelSale" for="lastName" >رصيدافتتاحي دائن (عاطي) </label>
        <input name="" class="inputSale " id="" type="number"  placeholder="0"/>
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

