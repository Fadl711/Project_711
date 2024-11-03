@extends('layout')
@section('conm')
<style>
    /* تثبيت الأرقام بالإنجليزية */
    .english-numbers {
        font-feature-settings: 'tnum';
        direction: ltr;
        unicode-bidi: plaintext;
    }
    td{
      text-align: right;
    }
  </style>
  {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

  <script>
$(document).ready(function() {
    $('#main_account_debit_id').on('change', function() {
        const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي

        showAccounts(mainAccountId,null);
       
    });
    // $('#account_debitid').on('change', function() {
    //     const mainAccountId2 = $(this).val(); // الحصول على ID الحساب الرئيسي

    //     showAccounts(null,mainAccountId2);
       
    // });
});



</script>
<div id="successMessage" class="alert-success" style="display: none;"></div>
<div class="min-w-[20%] px-1 border-x border-y border-orange-950 rounded-xl ">
    <div class="text-bro flex items-center">
        <div class="w-full min-w-full bg-white py-1">
            <form id="invoicePurchases">
                {{-- method="POST" action="{{route('Purchases.getMainAccounts')}}" --}}
                @csrf

                <div class="flex gap-4">

                    <div class="flex">
                     
                        <label for="" class="labelSale">نقدا</label>
                        <input type="radio" name="Payment_type" value="نقدا"  >
                    </div>
                    
                    <div class="flex">
                        <label for="" class="labelSale">اجل</label>
                        <input type="radio" name="Payment_type" value="اجل" >
                    </div>
                  
                    <div class="flex">
                        <label for="" class="labelSale">تحويل مخزني</label>
                        <input type="radio" name="Payment_type" value="تحويل مخزني" >
                    </div>
                </div>
                <div class="md:justify- text-right grid md:grid-cols-8 gap-2">
                    <div>
                        <label for="Supplier_id" class="labelSale">اسم المورد</label>
                        <select name="Supplier_id" id="Supplier_id" dir="ltr" class="input-field w-full select2 inputSale" >
                            @isset($AllSubAccounts)
                                @foreach ($AllSubAccounts as $subAccount)
                                    @if ($subAccount->Main_id == $mainAccount_supplier->main_account_id)
                                        <option value="{{$subAccount->Main_id}}">{{$subAccount->sub_name}}</option>
                                    @endif
                                @endforeach
                            @endisset
                           

                        </select>
                    </div>
                    <div >
                        <label for="Receipt_number" class="labelSale">رقم الإيصال</label>
                        <input type="number" name="Receipt_number" id="Receipt_number" placeholder="0" class="inputSale" />
                    </div>
                    <div >
                        <label for="Total_cost" class="labelSale">اجمالي التكلفة</label>
                        <input type="number" name="Total_cost" id="Total_cost" placeholder="0" class="inputSale" />
                    </div>
                    <div >
                        <label for="Total_invoice" class="labelSale">أجمالي الفاتورة</label>
                        <input type="number" name="Total_invoice" id="Total_invoice" placeholder="0" class="inputSale" />
                    </div>
                    @auth
                    <input type="hidden" name="User_id"  id="User_id" value="{{Auth::user()->id}}">
                    @endauth
                        <div >
                             <label for="main_account_debit_id" class=" font-medium labelSale">حساب الدفع/الرئيسي</label>
                            <select name="main_account_debit_id" id="main_account_debit_id" dir="ltr" class="input-field  select2 inputSale" >
                               <!-- إضافة خيارات الحسابات -->
                               @isset($mainAccounts)
                             <option value="" selected>اختر الحساب</option>
                              @foreach ($mainAccounts as $mainAccount)
                                   <option value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                              @endforeach
                              @endisset 
                            </select>
                        </div>
                        <div >
                            <label for="sub_account_debit_id" class="labelSale font-medium  ">حساب الدفع/الفرعي</label>
                            <select name="sub_account_debit_id" id="sub_account_debit_id" dir="ltr" class="input-field select2 inputSale" >
                                <!-- سيتم تعبئة الخيارات بناءً على الحساب الرئيسي المحدد -->
                                <option value="" selected>اختر الحساب الفرعي</option>
                                </select>
                    </div>
                    <!-- حساب الدائن -->
                    <div style="display: block">
                        <button id="newInvoice" class="inputSale flex font-bold">
                            اضافة الفاتورة
                        </button>
                    </div>
                </div>
                {{-- <button  type="submit" id="submit">submit</button> --}}
            </form>
        </div>
    </div>
</div>
<div class="flex max-md:block p-1">
    <div class="min-w-[30%] border-x border-y border-orange-950 rounded-xl">
        <form   id="ajaxForm">
            @csrf 
            <div  class=" gap-2 grid grid-cols-3 px-1 ">
                <div>
                    <label for="account_debitid" class="block font-medium ">حساب المخزن/الرئيسي</label>
                    <select name="account_debitid" id="account_debitid" dir="ltr" class="input-field  select2 inputSale" required>
                       <!-- إضافة خيارات الحسابات -->
                       @isset($Warehouse)
                     <option value="" selected>اختر المخزن</option>
                      @foreach ($AllSubAccounts as $mainAccount)
                      @if ($mainAccount->Main_id==$Warehouse->main_account_id)
                      <option value="{{$mainAccount['sub_account_id']}}">{{$mainAccount->sub_name}}-{{$mainAccount->sub_account_id}}</option>

                      @endif
                      @endforeach
                      @endisset 
                    </select>
                </div>
                <div >
                    <label for="sub_account_debitid" class="block font-medium  ">حساب المخزن/الفرعي</label>
                    <select name="sub_account_debitid" id="sub_account_debitid" dir="ltr" class="input-field select2 inputSale" >
                        <!-- سيتم تعبئة الخيارات بناءً على الحساب الرئيسي المحدد -->
                        <option value="" selected>اختر الحساب الفرعي</option>
                        </select>
            </div>
            <div >
                <label for="product_id" class="block font-medium  ">بحث عن المنتج</label>
                <select name="product_id" id="product_id" dir="ltr" class="input-field select2 inputSale" required>
                    <option value="" selected>اختر منتج</option>
                    @isset($products)
                        @foreach ($products as $product)
                            <option value="{{$product->product_id}}">{{$product->product_name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            </div>
            
            <div class="flex"> 
             
                <div class="mb-1 p-1">
                    <label for="product_name" class="btn">اسم المنتج</label>
                    <input type="text" name="product_name" id="product_name" class="inputSale " required />
                </div>
                
                <div class="mb-1 p-1">
                    <label for="Quantity" class="btn">الكمية</label>
                    <input type="number" name="Quantity" id="Quantity" placeholder="0" class="inputSale english-numbers" required />
                </div>
            </div>
            <div  class="col-span-6 sm:col-span-3 py-2">
                <button class="inputSale flex font-bold"   id="saveButton">اضافة الصنف</button>
            </div>
            <div class="flex">
                <div class="px-1">
                    <label for="Purchase_price" class="btn">سعر الشراء</label>
                    <input type="number" name="Purchase_price" id="Purchase_price" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                    <label for="Cost" class="btn">تكلفة الصنف</label>
                    <input type="number" name="Cost" id="Cost" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                    <label for="Total" class="btn">الاجمالي</label>
                    <input type="number" name="Total" id="Total" placeholder="0" class="inputSale" />
                </div>
            </div>
            <div class="flex">
                <div class="px-1">
                    <label for="Discount_earned" class="btn">الخصم المكتسب</label>
                    <input type="number" name="Discount_earned" id="Discount_earned" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                    <label for="Selling_price" class="btn">سعر البيع</label>
                    <input type="number" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                    <label for="Profit" class="btn">الربح</label>
                    <input type="number" name="Profit" id="Profit" placeholder="0" class="inputSale" />
                </div>
            </div>
            <div class="flex  ">
                <div class="px-1">
                    <label for="Barcode" class="btn">الباركود</label>
                    <input type="number" name="Barcode" id="Barcode" placeholder="0" class="inputSale" />
                </div>
                <div>
                <label for="Currency_id" class="btn">العملة الشراء</label>
                <select   dir="ltr" id="Currency_id" class="inputSale input-field " name="Currency_id"  >
                    @isset($Currency_name)


                  @foreach ($Currency_name as $cur)
                  <option @isset($cu)
                  @selected($cur->currency_id==$cu->Currency_id)
                  @endisset
                  value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
                   @endforeach
                   @endisset
                  </select>
                </div>
                <div>
                    <label for="Exchange_rate" class="btn"> سعر الصرف</label>
                    <input type="number" name="Exchange_rate" id="Exchange_rate" class="inputSale" />
                </div>
            </div>

                <div class="px-1">
                    <label for="note" class="btn">الوصف</label>
                    <textarea name="note" id="note" placeholder="0" class="inputSale"></textarea>
            </div>
            <div class="flex">
                <div>
                <label for="purchase_invoice_id" class="labelSale">رقم الفاتورة</label>
                <input type="number" name="purchase_invoice_id" id="purchase_invoice_id" placeholder="0" class="inputSale" required />
            </div>
            <div class="">
                <label for="supplier_name" class="labelSale">رقم المورد</label>
                <input type="number" name="supplier_name" id="supplier_name" placeholder="0" class="inputSale" required />
            </div>
            </div>

            <div class="flex" id="printEndSave">
                <div class="col-span-6 sm:col-span-3">
                    <button class="inputSale mt-2 flex" type="">
                        <svg class="w-6 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                        </svg>
                        <span class="textNav mr-1">حفظ وطباعة</span>
                    </button>
                    </div>
                    <div class="flex flex-col">
                        <input type="hidden" name="User_id" value="{{Auth::user()->id}}"/>
                      </div>
                <div class="col-span-6 sm:col-span-3" >
                <button class="flex inputSale mt-2 " type="submit" >
                        <svg class="w-6 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z"/>
                        </svg>
                        <span class="textNav mr-1"> حفظ</span>
                </button>
                </div>
            </div>
        </form>
<script type="text/javascript">
    $(document).ready(function () {
      const form = $('#ajaxForm');
      const successMessage = $('#successMessage');
      const errorMessage = $('#errorMessage');
      const inputs = $('.input-field'); // تحديد جميع الحقول
      const Product_name = $('#Product_name');
      Product_name.focus();
    form.on('keydown', function (event) {
          if (event.key === 'Enter') {
              event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
          }
      });
      $(document).on('keydown', function (event) {      // التنقل بين الحقول باستخدام السهم الأيمن أو الأيسر
          if (event.key === "ArrowRight" || event.key === "ArrowLeft") {
              let currentIndex = inputs.index(document.activeElement);
              if (currentIndex !== -1) {
                  if (event.key === "ArrowRight") {
                      if (currentIndex < inputs.length - 1) {
                          $(inputs[currentIndex + 1]).focus(); // نقل التركيز إلى الحقل التالي
                      }
                  } else if (event.key === "ArrowLeft") {
                      if (currentIndex > 0) {
                          $(inputs[currentIndex - 1]).focus(); // نقل التركيز إلى الحقل السابق
                      }
                  }
              }
          }
      });

      $(document).keydown(function(event) {
            if (event.ctrlKey && event.shiftKey) {
                event.preventDefault(); // منع السلوك الافتراضي (حفظ الصفحة)
                saveData(); // استدعاء دالة الحفظ
            }
        });
        $('#saveButton').click(function() {
            saveData(); // استدعاء دالة الحفظ
        });
      function saveData() {
        event.preventDefault(); // منع تحديث الصفحة
            const formData = new FormData($('#ajaxForm')[0]);
            $.ajax({
                url: '{{ route("Purchases.storc") }}', // استبدل هذا بالمسار الخاص بك
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // إرسال التوكن الخاص بـ Laravel
                },
                data: formData,
                processData: false, // ضروري مع FormData
                contentType: false, // ضروري مع FormData
              success: function (data) {
                  if (data.success) {
                      successMessage.show().text('تم الحفظ بنجاح!');
                      setTimeout(() => {
                          successMessage.hide();
                      }, 500);
                      addToTable(data.purchase);
                      $('#product_name').val('');
                      $('#Barcode').val('');
                      $('#Quantity').val('');
                      $('#Purchase_price').val('');
                      $('#Selling_price').val('');
                      $('#Total').val('');
                      $('#Cost').val('');
                      $('#Discount_earned').val('');
                      $('#Profit').val('');
                      $('#Exchange_rate').val('');
                      $('#product_id').val('');
                      $('#Total_cost').val('');
                      $('#note').val('');
                      $('#Total_invoice').val(data.Purchasesum);
                      $('#product_id').focus();
                  } else {
                      // إظهار رسالة عند وجود نفس الاسم
                      errorMessage.show().text('يوجد نفس هذا الاسم من قبل');
                      Product_name.focus();
                      setTimeout(() => {
                        errorMessage.hide();
                      }, 1000);
                  }
              },
              error: function () {
                  errorMessage.show().text('حدث خطأ أثناء الحفظ.');
              }
          });
      };

    //   // وظيفة لإضافة البيانات إلى الجدول
      function addToTable(account) {
          const tableBody = $('#mainAccountsTable tbody');
          const newRow = `
              <tr>
                  <td class="text-right tagTd">${account.Product_name}</td>
                  <td class="text-right tagTd">${account.Barcode}</td>
                  <td class="text-right tagTd">${account.Quantity || 0}</td>
                  <td class="text-right tagTd">${account.Purchase_price || 0}</td>
                  <td class="text-right tagTd">${account.Selling_price}</td>
                  <td class="text-right tagTd">${account.Total}</td>
                  <td class="text-right tagTd">${account.Cost}</td>
                  <td class="text-right tagTd">${account.Discount_earned}</td>
                  <td class="text-right tagTd">${account.note}</td>
                  <td class="flex">

                    <button class="" onclick="editData(${account.purchase_id})">                        <svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>
                      </svg></button>
                    <button class="" onclick="deleteData(${account.purchase_id})">                            <svg class="" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="fill-red-600" d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z" fill="#F87171"></path>
                            </svg></button>
                </td>
              </tr>
          `;
          tableBody.append(newRow); // إضافة الصف الجديد إلى الجدول
      }
    });
    </script>
</div>
<div class="  overflow-x-auto   px-1">
    <div class=" min-w-full  rounded-lg  max-h-[500px] ">
        <table id="mainAccountsTable" class="min-w-full leading-normal ">
            <thead class="tracking-tight ">
            <tr class="bgcolor">
                <th scope="col" class="leading-2 tagHt">اسم الصنف</th>
                <th scope="col" class="leading-2 tagHt  ">الباركود</th>
                <th scope="col" class="leading-2 tagHt ">الكمية</th>
                <th scope="col" class="leading-2 tagHt ">السعر الشراء</th>
                <th scope="col" class="leading-2 tagHt ">السعر البيع</th>
                <th scope="col" class="leading-2 tagHt"> الإجمالي</th>
                <th scope="col" class="leading-2 tagHt ">التكلفة</th>
                <th scope="col" class="leading-2 tagHt">التخفيض</th>
                <th scope="col" class="leading-2 tagHt">العلامة التجارية</th>
                <th scope="col" class="leading-2 tagHt">تعديل الشراء</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
        </tbody>
    </table>
</div>
</div>
</div>
<script src="{{url('purchases/purchases.js')}}"></script>

<script src="{{ url('purchases.js') }}"></script>
<style>
    .alert-success {
        color: green;
        font-weight: bold;
    }
  </style>

<script>
$(document).ready(function() {
        Barcode        = $('#Barcode'),
        product_name   = $('#product_name'),
        Selling_price  = $('#Selling_price'),
        Purchase_price = $('#Purchase_price'),
        Quantity       = $('#Quantity'),
        Total_cost     = $('#Total_cost').val(),
        Cost           = $('#Cost').val(),
    // عند تغيير المنتج المختار في القائمة
    $('#product_id').on('change', function() {
        var productId = $(this).val(); // الحصول على قيمة المنتج المختار
        if (productId) { // تحقق من وجود منتج محدد
            $.ajax({
                url: `/api/products/search?id=${productId}`, // استدعاء API بناءً على product_id
                method: 'GET',
                success: function(product) {
                    displayProductDetails(product); // استعراض تفاصيل المنتج إذا تمت الاستجابة بنجاح
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText); // عرض الخطأ إذا حدث خطأ في الاستدعاء
                }
            });
        } else {
            $('#productDetails').hide(); // إخفاء التفاصيل إذا لم يتم اختيار منتج
        }
    });
var total_cost = parseFloat($('#Total_cost').val());
    var purchase_price = parseFloat($('#Purchase_price').val());
    // التأكد من أن السعر الإجمالي وسعر الشراء أرقام صالحة لتجنب قسمة على صفر أو أخطاء
    if (!isNaN(total_cost) && !isNaN(purchase_price) && purchase_price > 0) {
        var cost = total_cost / purchase_price; // حساب التكلفة
        $('#Cost').val(cost); // إضافة السعر إلى الحقل مع تقريبه إلى خانتين عشريتين
    } else {
        $('#Cost').val(''); // في حال وجود خطأ في المدخلات، يتم تفريغ الحقل
    }
    // وظيفة لاستعراض تفاصيل المنتج
    function displayProductDetails(product) {
        $('#Quantity').focus();
        const invoiceInput = $('#purchase_invoice_id');
        if (invoiceInput.length) {
            Barcode.val(product.Barcode).trigger('change');
           product_name  .val(product.product_name).trigger('change');
           Selling_price .val(product.Selling_price).trigger('change');
           Purchase_price.val(product.Purchase_price).trigger('change');
           var total_cost = parseFloat($('#Total_cost').val()); // جلب القيمة من الحقل كرقم عشري
           if ( product.Selling_price > 0 && product.Purchase_price > 0) {
            var profit = product.Selling_price - product.Purchase_price; // حساب التمويز
            $('#Profit').val(profit).trigger('change'); // ��ضافة النتيجة مع تقريبها لخانتين عشريتين
           }
           else {
    $('#Profit').val('');
}
//product.Purchase_price و total_cost التحقق من أن  قيم صالحة
if (!isNaN(total_cost) && total_cost > 0 && product.Purchase_price > 0) {
    var cost = total_cost / product.Purchase_price; // حساب التكلفة
    $('#Cost').val(cost).trigger('change'); // إضافة النتيجة مع تقريبها لخانتين عشريتين
} else {
    $('#Cost').val(''); // في حال وجود خطأ أو قيم غير صالحة، يتم تفريغ الحقل
}
if (!isNaN(total_cost) && total_cost > 0 && product.Purchase_price > 0) {
    var cost = total_cost / product.Purchase_price; // حساب التكلفة
    $('#Cost').val(cost).trigger('change'); // إضافة النتيجة مع تقريبها لخانتين عشريتين
} else {
    $('#Cost').val(''); // في حال وجود خطأ أو قيم غير صالحة، يتم تفريغ الحقل
}
        }
      }
});
</script>
  <script>
  $(function() {
    const form = $('#invoicePurchases'),
          submitButton = $('#newInvoice'),
          product_id   = $('#product_id'),
           successMessage = $('#successMessage'),
           errorMessage =   $('#errorMessage'),

          invoiceField = $('#purchase_invoice_id'), // حقل رقم الفاتورة
          supplier_id = $('#supplier_name'), // حقل رقم الفاتورة
           // حقل رقم الفاتورة
          csrfToken = $('input[name="_token"]').val();
    // عند الضغط على زر الحفظ
    submitButton.click(function(e) {
        e.preventDefault(); // منع تحديث الصفحة
        // تعطيل الزر لتجنب الضغط المكرر
        submitButton.prop('disabled', true).text('جاري الإرسال...');
        // جمع بيانات النموذج باستخدام serialize
        const formData = new FormData(form[0]);
        $.ajax({
    url: '{{ route("invoicePurchases.store") }}',
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken },
    data: formData,
    processData: false,
    contentType: false,
})
.done(function(response) {
    if (response.success) {
        const invoiceInput = $('#purchase_invoice_id');
        const invoiceInput2 = $('#supplier_name');
        if (invoiceInput.length || invoiceInput2.length) {
            invoiceField.val(response.invoice_number).trigger('change');
            supplier_id.val(response.supplier_id).trigger('change');

        } else {
            console.warn('حقل "رقم الفاتورة" غير موجود.');
        }
        $('#product_id').focus();
        successMessage.text(response.message).show();
                      setTimeout(() => {
                      successMessage.hide();
                      }, 500);
    } else {
        alert('خطأ: ' + (response.message || 'حدث خطأ غير معروف.'));
    }
})
.fail(function(xhr) {
    console.error('خطأ:', xhr.responseText);
    alert('حدث خطأ أثناء إرسال الطلب. حاول مرة أخرى لاحقاً.');
})
.always(function() {
    submitButton.prop('disabled', false).text('إضافة الفاتورة');
});
    });
    form.find('input').keydown(function(e) {
        if (e.key === "Enter") {
            e.preventDefault(); // منع حفظ النموذج عند الضغط على Enter
            $(this).next('input').focus(); // الانتقال إلى الحقل التالي
        }
    });
});
function editData(id) {
    $.ajax({
        type: 'GET',
        url: '{{ route("purchases.edit", ":id") }}'.replace(':id', id),
        success: function(data) {
            $('#product_name').val(data.Product_name);
            $('#Barcode').val(data.Barcode);
            $('#Quantity').val(data.Quantity);
            $('#Purchase_price').val(data.Purchase_price);
            $('#Selling_price').val(data.Selling_price);
            $('#Total').val(data.Total);
            $('#Cost').val(data.Cost);
            $('#Discount_earned').val(data.Discount_earned);
            $('#Profit').val(data.Profit);
            $('#Exchange_rate').val(data.Exchange_rate);
            $('#product_id').val(data.product_id);
            $('#Total_cost').val(data.Total_cost);
            $('#note').val(data.note);
            $('#purchase_invoice_id').val(data.Purchase_invoice_id);
            $('#supplier_name').val(data.Supplier_id);
        }
    });
}
function deleteData(id) {
    if (confirm('هل أنت متأكد من حذف البيانات؟')) {
        $.ajax({
            type: 'DELETE',
            url: '{{ route("purchases.destroy", ":id") }}'.replace(':id', id),
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                location.reload();
            }
        });
    }
}// عند اختيار الحساب الرئيسي (المدين)


  </script>
@endsection
