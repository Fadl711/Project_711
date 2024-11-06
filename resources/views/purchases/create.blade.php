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
  <script>
$(document).ready(function() {
    $('#main_account_debit_id').on('change', function() {
        const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي
        showAccounts(mainAccountId,null);
    });
});
</script>
<div id="successMessage" class="alert-success" style="display: none;"></div>
<div id="errorMessage" class="alert-errorMessage" style="display: none;"></div>
<div class="min-w-[20%] px-1  bg-white rounded-xl ">
    <div class=" flex items-center">
        <div class="w-full min-w-full  py-1">
            <form id="invoicePurchases">
                @csrf
                <div class="flex gap-4">
                    <div class="flex">
                        <label for="" class="labelSale">نقدا</label>
                        <input type="radio" name="Payment_type"   value="نقدا"  >
                    </div>
                    <div class="flex">
                        <label for="" class="labelSale">اجل</label>
                        <input type="radio" name="Payment_type" value="اجل"   >
                    </div>
                </div>
                <div class="md:justify- text-right grid md:grid-cols-8 gap-2">
                    <div>
                        <label for="transaction_type" class="labelSale">نوع العملية </label>
                        <select dir="ltr" id="transaction_type" class="inputSale input-field" name="transaction_type">
                            @foreach ($transactionTypes as $transactionType)
                                <option value="{{ $transactionType->value }}">{{ $transactionType->label() }}</option>
                            @endforeach
                        </select>
                        </div>
                    <div>
                        <label for="Supplier_id" class="labelSale">اسم المورد</label>
                        <select name="Supplier_id" id="Supplier_id" dir="ltr" class="input-field w-full select2 inputSale" >
                            @isset($AllSubAccounts)
                            {{-- <option value="" selected> </option> --}}

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
  
                    <div id="newInvoice1" style="display: block">
                        <button  id="newInvoice" class="inputSale flex font-bold">
                            اضافة الفاتورة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="flex max-md:block p-1  ">
    <div class="min-w-[30%] border-x bg-white   rounded-xl">
        <form   id="ajaxForm">
            @csrf 
            <div  class=" gap-2 grid grid-cols-3 px-1  ">
                <div>
                    <label for="account_debitid" class="labelSale font-medium ">  مخازن الستيراد</label>
                    <select name="account_debitid" id="account_debitid" dir="ltr" class="input-field  select2 inputSale" required>
                       @isset($Warehouse)
                     <option value="" selected>اختر المخزن</option>
                      @foreach ($Warehouse as $mainAccount)
                      <option value="{{$mainAccount->sub_account_id}}">{{$mainAccount->sub_name}}</option>
                      @endforeach
                      @endisset 
                    </select>
                </div>
                <div >
                    <label for="main_account_debit_id" class=" font-medium labelSale">  حساب التصدير </label>
                   <select name="main_account_debit_id" id="main_account_debit_id" dir="ltr" class="input-field  select2 inputSale" >
                      @isset($mainAccounts)
                    <option value="" selected>اختر الحساب</option>
                     @foreach ($mainAccounts as $mainAccount)
                          <option value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                     @endforeach
                     @endisset 
                   </select>
               </div>
               <div >
                   <label for="sub_account_debit_id" class="labelSale font-medium  ">  تحديد الدائن</label>
                   <select name="sub_account_debit_id" id="sub_account_debit_id" dir="ltr" class="input-field select2 inputSale" required>
                       <option value="" selected>اختر الحساب الفرعي</option>
                       </select>
           </div>
            </div>
            <div class="flex gap-1 px-1"> 
                <div >
                    <label for="product_id" class="block font-medium  labelSale">بحث عن المنتج</label>
                    <select name="product_id" id="product_id" dir="ltr" class="input-field select2 inputSale" required>
                        <option value="" selected>اختر منتج</option>
                        @isset($products)
                            @foreach ($products as $product)
                                <option value="{{$product->product_id}}">{{$product->product_name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="">
                    <label for="product_name" class="labelSale">اسم المنتج</label>
                    <input type="text" name="product_name" id="product_name" class="inputSale " required />
                </div>
                
                <div class="">
                    <label for="Quantity" class="labelSale">الكمية</label>
                    <input type="number" name="Quantity" id="Quantity" placeholder="0" class="inputSale english-numbers" required />
                </div>
                <div class="">
                    <label for="QuantityPurchase" class="labelSale"> الكمية المتوفره</label>
                    <input type="number" name="QuantityPurchase" id="QuantityPurchase" placeholder="0" class="inputSale english-numbers"  />
                </div>
            </div>
           
            <div class="flex gap-1 px-1">
                <div class="">
                    <label for="Purchase_price" class="labelSale">سعر الشراء</label>
                    <input type="number" name="Purchase_price" id="Purchase_price" placeholder="0" class="inputSale"  />
                </div>
                <div  class=" sm:col-span-3 labelSale mt-7">
                    <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700"  id="saveButton">اضافة </button>
                </div>
                <div class="">
                    <label for="Cost" class="labelSale">تكلفة الصنف</label>
                    <input type="number" name="Cost" id="Cost" placeholder="0" class="inputSale" />
                </div>
                <div class="">
                    <label for="Total" class="labelSale">الاجمالي</label>
                    <input type="number" name="Total" id="Total" placeholder="0" class="inputSale" required />
                </div>
            </div>
            <div class="flex gap-1 px-1">
                <div class="">
                    <label for="Discount_earned" class="labelSale">الخصم المكتسب</label>
                    <input type="number" name="Discount_earned" id="Discount_earned" placeholder="0" class="inputSale" />
                </div>
                <div class="">
                    <label for="Selling_price" class="labelSale">سعر البيع</label>
                    <input type="number" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" />
                </div>
                <div class="">
                    <label for="Profit" class="labelSale">الربح</label>
                    <input type="number" name="Profit" id="Profit" placeholder="0" class="inputSale" />
                </div>
            </div>
            <div class="flex gap-1 px-1">
                <div class="">
                    <label for="Barcode" class="labelSale">الباركود</label>
                    <input type="number" name="Barcode" id="Barcode" placeholder="0" class="inputSale" />
                </div>
                <div>
                <label for="Currency_id" class="labelSale">العملة الشراء</label>
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
                    <label for="Exchange_rate" class="labelSale"> سعر الصرف</label>
                    <input type="number" name="Exchange_rate" id="Exchange_rate" class="inputSale" />
                </div>
            </div>
                <div class="px-1">
                    <label for="note" class="labelSale">الوصف</label>
                    <textarea name="note" id="note" placeholder="0" class="inputSale"></textarea>
            </div>
            <div class="flex px-1 gap-1">
                <div>
                <label for="purchase_invoice_id" class="labelSale">رقم الفاتورة</label>
                <input type="number" name="purchase_invoice_id" id="purchase_invoice_id" placeholder="0" class="inputSale" required />
            </div>
            <div class="">
                <label for="supplier_name" class="labelSale">رقم المورد</label>
                <input type="number" name="supplier_name" id="supplier_name" placeholder="0" class="inputSale"  />
            </div>
            <div class="">
                <label for="purchase_id" class="labelSale">رقم القيد</label>
                <input type="number" name="purchase_id"   id="purchase_id"  class="inputSale"  />
            </div>
            </div>
            <div class="flex" id="printEndSave">
                <div class="col-span-6 sm:col-span-3">
                    <button class="inputSale mt-2 flex " type="button" id="savaAndPrint" >
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
    </div>

    <div class="container mx-auto " id="mainAccountsTable">
        <div class="w-full overflow-x-auto bg-white">
            <table id="mainAccountsTable"   class="w-full mb-4 text-sm">

                <thead>
                    <tr class="bg-blue-100">
                        <th class="border border-black px-2 py-1">الباركود</th>
                        <th class="border border-black px-2 py-1">اسم الصنف</th>
                        <th class="border border-black px-2 py-1">الكمية</th>
                        <th class="border border-black px-2 py-1">السعر الشراء</th>
                        <th class="border border-black px-2 py-1">السعر البيع</th>
                        <th class="border border-black px-2 py-1">الإجمالي</th>
                        <th class="border border-black px-2 py-1">التكلفة</th>
                        <th class="border border-black px-2 py-1">التخفيض</th>
                        <th class="border border-black px-2 py-1">العلامة التجارية</th>
                    </tr>
                </thead>
                <tbody>       
            </table>
    </div>
    <button onclick="openInvoiceWindow(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح الفاتورة</button>

<script>
    function openInvoiceWindow(e) {
        const successMessage= $('#successMessage');
        const invoiceField = document.getElementById('purchase_invoice_id').value; // الحصول على قيمة حقل رقم الفاتورة
        if(invoiceField){
            e.preventDefault(); // منع تحديث الصفحة
        const url = `{{ route('invoicePurchases.print', ':invoiceField') }}`.replace(':invoiceField', invoiceField); // استبدال القيمة في الرابط

        window.open(url, '_blank', 'width=600,height=800'); // فتح الرابط مع استبدال القيمة
        }
    else{        

        successMessage.text('لا توجد فاتورة').show();
                          setTimeout(() => {
                          successMessage.hide();
                          }, 3000);
    }
    
    }
</script>
<button onclick="openAndPrintInvoice2(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح وطباعة الفاتورة</button>
<div id="successMessage" style="display:none;" class="text-red-500 font-semibold mt-2"></div>

<script>
    function openAndPrintInvoice2(e) {
        const successMessage = $('#successMessage');
        const invoiceField = document.getElementById('purchase_invoice_id').value; // الحصول على قيمة حقل رقم الفاتورة
        if (invoiceField) {
            e.preventDefault(); // منع تحديث الصفحة
            const url = `{{ route('invoicePurchases.print', ':invoiceField') }}`.replace(':invoiceField', invoiceField); // استبدال القيمة في الرابط
            
            // فتح الرابط في نافذة جديدة
            const newWindow = window.open(url, '_blank', 'width=600,height=800');

            // التأكد من أن النافذة فتحت بنجاح
            if (newWindow) {
                newWindow.onload = function() {
                    setTimeout(() => {
                        newWindow.print(); // طباعة المحتوى بعد تحميله
                        newWindow.close(); // إغلاق النافذة بعد الطباعة
                    }, 1000); // تأخير بسيط للسماح بتحميل المحتوى
                };
            } else {
                successMessage.text('تعذر فتح النافذة. يرجى التحقق من إعدادات المتصفح.').show();
                setTimeout(() => {
                    successMessage.hide(); // إخفاء الرسالة بعد 3 ثوانٍ
                }, 3000);
            }
        } else {
            successMessage.text('لا توجد فاتورة').show(); // عرض الرسالة
            setTimeout(() => {
                successMessage.hide(); // إخفاء الرسالة بعد 3 ثوانٍ
            }, 3000);
        }
    }
</script>


   
    </script>
<script type="text/javascript">
   $(function() {

        const form = $('#invoicePurchases'),
              submitButton = $('#newInvoice'),
              product_id   = $('#product_id'),
               successMessage = $('#successMessage'),
               errorMessage =   $('#errorMessage'),
              invoiceField = $('#purchase_invoice_id'), // حقل رقم الفاتورة
              supplier_id = $('#supplier_name'),
              account_debitid = $('#sub_account_debit_id'),

            
              // حقل رقم الفاتورة
              csrfToken = $('input[name="_token"]').val();
        submitButton.click(function(e) {// عند الضغط على زر الحفظ
            e.preventDefault(); // منع تحديث الصفحة
            submitButton.prop('disabled', true).text('جاري الإرسال...');            // تعطيل الزر لتجنب الضغط المكرر
            const formData = new FormData(form[0]);// جمع بيانات النموذج باستخدام serialize
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
                account_debitid.focus();
            } else {
                console.warn('حقل "رقم الفاتورة" غير موجود.');
            }
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
    $(document).ready(function () {
      const form = $('#ajaxForm');
      const successMessage = $('#successMessage');
      const errorMessage = $('#errorMessage');
      const inputs = $('.input-field'); // تحديد جميع الحقول
      const Product_name = $('#product_name');
      const selectedPaymentType = $('input[name="Payment_type"]');

      selectedPaymentType.focus();
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
            const selectedPaymentType = $('input[name="Payment_type"]:checked').val();
    formData.append('Payment_type', selectedPaymentType || ''); // إضافة القيمة المختارة أو قيمة فارغة إذا لم يتم اختيار شيء

    // إذا كان هناك حقل Receipt_number، أضفه أيضًا
    formData.append('Receipt_number', $('#Receipt_number').val() || '');
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
                       successMessage.show().text(data.message);
                       errorMessage.hide();

                      setTimeout(() => {
                          successMessage.hide();
                      }, 3000);
                      addToTable(data.purchase);
                    //   $('#Total_invoice').val(data.Purchasesum);
                      $('#Total_invoice').val(data.Purchasesum);

                      $('#product_id').focus();
                      
                      emptyData();
                  } else {
                      // إظهار رسالة عند وجود نفس الاسم
                      errorMessage.show().text(data.message);
                      setTimeout(() => {
                        errorMessage.hide();
                      }, 5000);
                      Product_name.focus();
                  }
              },
              error: function () {
                errorMessage.show().text(data.message);
                      setTimeout(() => {
                        errorMessage.hide();
                      }, 8000);
                      Product_name.focus();
        }
          });
      };
    });
    </script>

<script src="{{url('purchases/purchases.js')}}"></script>
<script src="{{ url('purchases.js') }}"></script>
<style>
    .alert-success {
        color: green;
        font-weight: bold;
    }
    .alert-errorMessage {
        color: rgb(212, 50, 50);
        font-weight: bold;
    }
  </style>

<script>
$(document).ready(function() {
        Barcode        = $('#Barcode'),
        QuantityPurchase = $('#QuantityPurchase'),
        account_debitid = $('#account_debitid'),
        product_name   = $('#product_name'),
        Selling_price  = $('#Selling_price'),
        Purchase_price = $('#Purchase_price'),
        Quantity       = $('#Quantity'),
        Total_cost     = $('#Total_cost').val(),
        Cost           = $('#Cost').val(),

    $('#product_id').on('change', function() {    // عند تغيير المنتج المختار في القائمة

        var productId = $(this).val(); // الحصول على قيمة المنتج المختار
        if (productId) { // تحقق من وجود منتج محدد
            $.ajax({
                url: `/api/products/search?id=${productId}`, // استدعاء API بناءً على product_id
                method: 'GET',
                data:account_debitid,
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
    if (!isNaN(total_cost) && !isNaN(purchase_price) && purchase_price > 0) {    // التأكد من أن السعر الإجمالي وسعر الشراء أرقام صالحة لتجنب قسمة على صفر أو أخطاء
        var cost = total_cost / purchase_price; // حساب التكلفة
        $('#Cost').val(cost); // إضافة السعر إلى الحقل مع تقريبه إلى خانتين عشريتين
    } else {
        $('#Cost').val(''); // في حال وجود خطأ في المدخلات، يتم تفريغ الحقل
    }
});
</script>
  <script>
  
  function editData(id) {

    $.ajax({
        type: 'GET',
        url: '{{ route("purchases.edit", ":id") }}'.replace(':id', id),
        success: function(data) {
            // تحديث الحقول في النموذج
            $('#product_name').val(data.Product_name);
            $('#Barcode').val(data.Barcode);
            $('#Quantity').val(data.quantity);
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
            $('#purchase_id').val(data.purchase_id);
  },
        error: function(xhr, status, error) {
            console.error("خطأ في جلب بيانات التعديل:", error);
        }
    });
}

function deleteData(id) {
    var successMessage = $('#successMessage');
    if (confirm('هل أنت متأكد من حذف البيانات؟')) {
        $.ajax({
            type: 'DELETE',
            url: '{{ route("purchases.destroy", ":id") }}'.replace(':id', id),
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // إزالة الصف من DOM بدون إعادة تحميل الصفحة
                $('#row-' + id).remove();
                successMessage.text('تم حذف البيانات بنجاح!').show();
                      setTimeout(() => {
                      successMessage.hide();
                      }, 500);
            },
            error: function(xhr, status, error) {
                alert('حدث خطأ أثناء الحذف. الرجاء المحاولة مرة أخرى.');
                console.error('Error:', error);
            }
        });
    }
}
  </script>
@endsection
