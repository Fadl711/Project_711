
@extends('accounts.index')
@section('accounts')

<div class="mb-4 max-lg:flex md:flex   w-full" >
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
<div id="SubAccount" style="display:">
  {{-- id="addItemForm"  --}}
  <form  id="addItemForm" class="p-4 md:p-5 " >
    {{-- method="POST" action="{{route('add_account.store')}}" --}}
    @csrf
    <div id="successMessage" class="alert-success" style="display: none;"></div>

    <div class="grid gap-4 mb-4 grid-cols-2">
      <div class="mb-2">
        <label class="labelSale" for="sub_name">اسم الحساب</label>
        <input name="sub_name" class="inputSale" id="sub_name" type="text" placeholder="اسم الحساب الجديد"/>
    </div>
    <div class="mb-2">

      <label class="labelSale  " for="Main_id">  الحساب الرئيسي</label>
      <select  dir="ltr"  id="Main_id" name="Main_id" class="  inputSale " >
        @foreach ($pos as $post)
        <option value=" {{$post['main_account_id']}}">  {{$post['account_name']}}</option>
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
      <div class="mb-2">
          <label for="email" class="labelSale">البريد الإلكتروني</label>
          <input type="email" name="email" id="email" placeholder="Email" class="inputSale" />
      </div>
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
    <button type="submit" id="submit" class="text-white inline-flex items-center bgcolor hover:bg-stone-400  font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
      
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
    <td id="sub_name1"></td>
    <td id="debtor1"></td>
    <td id="creditor1"></td>

</tbody>
</table>
  </div>


<div class="" id="mainaccount" style="display:none">

  <form   class="p-4 md:p-5 " method="POST" action="">
    @csrf

  <div class="flex ">
    <label class="" for="email">طبيعة الحساب</label>
    
    <div class="flex px-4" >
    <label for="debtor" class="labelSale">مدين</label>
    
      <input id="debtor" type="radio" value="" name="Nature_account" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    </div>
    <div class="flex ">
    <label for="creditor" class="labelSale">دائن</label>
      <input id="creditor" type="radio" value="" name="Nature_account" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    </div>
    </div>
  <div class="grid gap-4 mb-4 grid-cols-2">

    <div class="mb-2">
      <label class="labelSale" for="account_name">اسم الحساب</label>
      <input name="account_name" class="inputSale" id="account_name" type="text" placeholder="اسم الحساب الجديد"/>
  </div>

  <div class="mb-2">
    <label class="labelSale  " for="Type_account_id"> تصنيف الحساب</label>
      <select id="Type_account_id" class=" text-left inputSale">
        <option selected ></option>
        @foreach ($TypesAccounts as $typesAccount)
        <option value="{{$typesAccount['id']}}" >{{$typesAccount['TypesAccount']}} -{{$typesAccount['id']}}</option>
        @endforeach
      </select>
    </div>
    <div class="mb-2">
        <label class="labelSale" for="debtor">  رصيدافتتاحي مدين (علية)</label>
        <input name="debtor" class="inputSale " id="debtor" type="text" placeholder="0"/>
    </div>
    <div class="mb-2">
      <label class="labelSale" for="creditor" >رصيدافتتاحي دائن (لة) </label>
      <input name="creditor" class="inputSale " id="creditor" type="number"  placeholder="0"/>
  </div>
  <div class="mb-2">
    <label class="labelSale  " for="accountType"> يرحل الى </label>
      <select id="accountType" class=" text-left inputSale">
        <option selected></option>
        @foreach ($Deportattons as $Deportatton)
        <option >{{$Deportatton['Deportatton']}} - {{$Deportatton['id']}}</option>
        @endforeach
       
      </select>
    </div>
    <div class="mb-2">
      <label for="Phone" class="labelSale">رقم التلفون</label>
      <input type="number" name="Phone" id="Phone" placeholder=" Phone Number" class="inputSale" />
  </div>
  <div class="mb-2">
      <label for="email" class="labelSale">البريد الإلكتروني</label>
      <input type="email" name="email" id="email" placeholder="Email" class="inputSale" />
  </div>
  <div class="mb-2">
      <label for="address" class="labelSale">العنوان</label>
        <input type="text" name="address" id="address" placeholder="العنوان العميل" class="inputSale" />
      </div>
  <div class="mb-2">
      <label for="name_The_known" class="labelSale">اسم/ معرف العميل</label>
        <input type="text" name="name_The_known" id="name_The_known" placeholder="رقم معرف" class="inputSale" />
      </div>
  <div class="mb-2">
      <label for="Known_phone" class="labelSale">رقم تلفون/ معرف العميل</label>
        <input type="text" name="Known_phone" id="Known_phone" placeholder="اسم معرف" class="inputSale" />
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


<script src={{url('node_modules/jquery/dist/')}}></script>
<style>
    .alert-success {
        color: green;
        font-weight: bold;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
 
<script type="text/javascript">
  // تحميل الأصناف الحالية عند تحميل الصفحة
  $(document).ready(function() {
    $('#addItemForm').on('submit', function(event) {
                event.preventDefault();

                let formData = {
                  Main_id: $('#Main_id').val(),

                  sub_name: $('#sub_name').val(),
                  debtor: $('#debtor').val(),
                  creditor: $('#creditor').val(),
                    _token: '{{ csrf_token() }}' // CSRF token
                };

                $.ajax({
                    url: '/add_account/store',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // عرض رسالة النجاح
                        $('#successMessage').text(response.message).show();
                        $('#sub_name1').text(response.posts.sub_name).show();
                        $('#debtor1').text(response.posts.debtor).show();
                        $('#creditor1').text(response.posts.creditor).show();
                        // $('#successMessage').text(response.posts.message).show();
                          
                         
                        // إخفاء الرسالة بعد 3 ثوانٍ
                        setTimeout(function() {
                            $('#successMessage').hide();
                        }, 3000);

                        // تفريغ الحقول بعد الإضافة
                        $('#sub_name').val('');
                        $('#debtor').val('');
                        $('#creditor').val('');
                    },
                    error: function(response) {
                        alert('Error adding customer');
                    }
                });
            });
        });
</script>
<script>
  // addEventListener
  document.getElementById('accountty').addEventListener('focusout',function(event){
    event.preventDefault();
    var accountty= document.getElementById('accountty').value;    
var acaaa= document.getElementById('SubAccount');
  var aaa= document.getElementById('mainaccount');
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
<script>
  document.getElementById('accountty').addEventListener('change',function(){
  const selectElement= document.getElementById('accountty').value;
  var acaaa= document.getElementById('SubAccount');
  var aaa= document.getElementById('mainaccount');
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


})
</script>
<script>
  
document.getElementById('accountty').addEventListener('focusin',function(){
    var accountty= document.getElementById('accountty').value;    
var acaaa= document.getElementById('SubAccount');
  var aaa= document.getElementById('mainaccount');
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
@endsection