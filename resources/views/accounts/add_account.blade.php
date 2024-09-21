


<div class="mb-4 max-lg:flex md:flex   w-full" >
  <div class="lg:ml-2 w-[30%] max-sm:ml-2">
    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class=" w-10  absolute top-0 right-20 focus:outline-none " type="button">
      <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M2,21h8a1,1,0,0,0,0-2H3.071A7.011,7.011,0,0,1,10,13a5.044,5.044,0,1,0-3.377-1.337A9.01,9.01,0,0,0,1,20,1,1,0,0,0,2,21ZM10,5A3,3,0,1,1,7,8,3,3,0,0,1,10,5ZM23,16a1,1,0,0,1-1,1H19v3a1,1,0,0,1-2,0V17H14a1,1,0,0,1,0-2h3V12a1,1,0,0,1,2,0v3h3A1,1,0,0,1,23,16Z"></path></g></svg>
    </button>
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
  <form  id="addItemForm"  class="p-4 md:p-5">
    <div class="grid gap-4 mb-4 grid-cols-2">
      <div class="mb-2">
        <label class="labelSale" for="sub_name">اسم الحساب</label>
        <input name="sub_name" class="inputSale" id="sub_name" type="text" placeholder="اسم الحساب الجديد"/>
    </div>
    <div class="mb-2">
      <label class="labelSale  " for="account_id">  الحساب الرئيسي</label>
      <select  dir="ltr"  id="account_id" name="account_id" class="  inputSale " >
        @foreach ($pos as $post)
        <option value="">  {{$post['main_account_id']}}</option>
        @endforeach
      
        {{-- <option value="DE">الصندوق الرئيسي</option>
        <option value="DE">البنك  </option> --}}
      </select>
      </div>
  
      <div class="mb-2">
          <label class="labelSale" for="debtor">  رصيدافتتاحي مدين (اخذ)</label>
          <input name="debtor" class="inputSale " id="debtor" type="number" placeholder="0"/>
      </div>
      <div class="mb-2">
        <label class="labelSale" for="creditor" >رصيدافتتاحي دائن (عاطي) </label>
        <input name="creditor" class="inputSale " id="creditor" type="number"  placeholder="0"/>
    </div>
      <div class="mb-2">
          <label for="Phone" class="labelSale">رقم التلفون</label>
          <input type="number" name="Phone" id="Phone" placeholder=" Phone Number" class="inputSale" />
      </div>
      {{-- <div class="mb-2">
          <label for="email" class="labelSale">البريد الإلكتروني</label>
          <input type="email" name="email" id="email" placeholder="Email" class="inputSale" />
      </div> --}}
      <div class="mb-2">
          <label for="address" class="labelSale">العنوان</label>
            <input type="text" name="address" id="address" placeholder="Address" class="inputSale" />
          </div>
      <div class="mb-2">
          <label for="name_The_known" class="labelSale">اسم/ معرف العميل</label>
            <input type="text" name="name_The_known" id="name_The_known" placeholder="Address" class="inputSale" />
          </div>
      <div class="mb-2">
          <label for="Known_phone" class="labelSale">رقم تلفون/ معرف العميل</label>
            <input type="text" name="Known_phone" id="Known_phone" placeholder="" class="inputSale" />
          </div>
    </div>
    <button type="submit" class="text-white inline-flex items-center bgcolor hover:bg-stone-400  font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
        </svg>
      حفظ البيانات
    </button>
  </form>
  <table class=" min-w-[85%]  " id="invoiceItemsTable">
    <thead>
        <tr class="bgcolor">
            <th scope="col" class="leading-2 tagHt "> رقم الحساب</th>
            <th scope="col" class="leading-2 tagHt ">اسم الحساب</th>
            <th scope="col" class="leading-2 tagHt">الرصيد مدين</th>
            <th scope="col" class="leading-2 tagHt">الرصيد دائن</th>

            <th scope="col" class="leading-2 tagHt"> التلفون</th>
            {{-- <th scope="col" class="leading-2 tagHt"> تعديل</th> --}}
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-300 ">
    <!-- الأصناف سيتم إضافتها هنا ديناميكياً -->
</tbody>
</table>
  </div>


<div class="" id="acass" style="display:none">

  <form class="p-4 md:p-5">
  <div class="flex ">
    <label class="" for="email">طبيعة الحساب</label>
    
    <div class="flex px-4" >
    <label for="inline-radio" class="labelSale">مدين</label>
    
      <input id="inline-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    </div>
    <div class="flex ">
    <label for="inline-2-radio" class="labelSale">دائن</label>
      <input id="inline-2-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    </div>
    </div>
  <div class="grid gap-4 mb-4 grid-cols-2">

    <div class="mb-2">
      <label class="labelSale" for="email">اسم الحساب</label>
      <input name="" class="inputSale" id="brand" type="text" placeholder="اسم الحساب الجديد"/>
  </div>
  <div class="mb-2">
    <label class="labelSale  " for="accountType"> تصنيف الحساب</label>
      <select id="accountType" class=" text-left inputSale">
        <option selected></option>
        <option value="US">الاصول</option>
        <option value="CA">خصوم وحقوق الملكية</option>
        <option value="FR">المصروفات</option>
        <option value="DE">الايرادات</option>
      </select>
    </div>
    <div class="mb-2">
        <label class="labelSale" for="email">  رصيدافتتاحي مدين (اخذ)</label>
        <input name="" class="inputSale " id="" type="text" placeholder="0"/>
    </div>
    <div class="mb-2">
      <label class="labelSale" for="lastName" >رصيدافتتاحي دائن (عاطي) </label>
      <input name="" class="inputSale " id="" type="number"  placeholder="0"/>
  </div>
    <div class="mb-2">
        <label for="phone" class="labelSale">رقم التلفون</label>
        <input type="number" name="phone" id="phone" placeholder=" Phone Number" class="inputSale" />
    </div>
    {{-- <div class="mb-2">
        <label for="email" class="labelSale">البريد الإلكتروني</label>
        <input type="email" name="email" id="email" placeholder="Email" class="inputSale" />
    </div> --}}
    <div class="mb-2">
        <label for="address" class="labelSale">العنوان</label>
          <input type="text" name="address" id="address" placeholder="Address" class="inputSale" />
        </div>
    <div class="mb-2">
        <label for="address" class="labelSale">اسم/ معرف العميل</label>
          <input type="text" name="address" id="address" placeholder="Address" class="inputSale" />
        </div>
    <div class="mb-2">
        <label for="address" class="labelSale">رقم تلفون/ معرف العميل</label>
          <input type="text" name="address" id="address" placeholder="Address" class="inputSale" />
        </div>
  </div>
  <button type="submit" class="text-white inline-flex items-center bgcolor hover:bg-stone-400  font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
      <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
      </svg>
    حفظ البيانات
  </button>
</form>
</div>
<script>
  // تحميل الأصناف الحالية عند تحميل الصفحة
  $(document).ready(function() {
      fetchItems();

      // إضافة صنف جديد
      $('#addItemForm').on('submit', function(event) {
          event.preventDefault();

          let formData = {
            account_id: $('input[name="account_id"]').val(),
              sub_name: $('#sub_name').val(),
              debtor: $('#debtor').val(),
              name_The_known: $('#name_The_known').val(),
              creditor: $('#creditor').val(),
              _token: '{{ csrf_token() }}' // إضافة CSRF token
          };

          $.ajax({
              url: '/invoice/items',
              type: 'POST',
              data: formData,
              success: function(item) {
                  addItemToTable(item);
                  $('#sub_name').val('');
                  $('#debtor').val('');
                  $('#creditor').val('');
              },
              error: function(response) {
                  alert('Error adding item');
              }
          });
      });
  });

  // تحميل الأصناف من الخادم
  function fetchItems() {
  let bn = $('input[name="account_id"]').val();

      $.get('/invoice/${bn}/items', function() { // افترض أن رقم الفاتورة هو 1
          items.forEach(function(item) {
              addItemToTable(item);
          });
      });
  }

  // إضافة صنف إلى الجدول
  function addItemToTable(item) {
      $('#invoiceItemsTable tbody').append(`
          <tr>
              <td>${item.sub_name}</td>
           
              <td>${item.debtor}</td>
              <td>${item.creditor}</td>
          </tr>
      `);
  }
</script>
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
