@extends('layout')
@section('conm')
{{-- @if($errors->any())
    @foreach ($errors()->all() as $error)
    <div class="alert alert-danger">{{$error}}</div>
    @endforeach
@endif --}}
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
   
.select2-container--default .select2-dropdown {
    max-height: 200px; /* ارتفاع القائمة */
    overflow-y: auto; /* تمكين التمرير إذا تجاوز المحتوى الارتفاع */
}
.select2-container--default .select2-selection--single {
    height: 40px; /* ارتفاع العنصر الأساسي */
    line-height: 45px; لتوسيط النص عموديًا
}
.select2-container--default .select2-selection__rendered {
    padding-top: 5px; /* تحسين النصوص */
}
  </style>
<div id="errorMessage" style="display: none;" class="alert alert-danger"></div>
<div id="successMessage" style="display: none;" class="alert alert-success"></div>
<div class="min-w-[20%] px-1  bg-white rounded-xl ">
    <div class=" flex items-center">
        <div class="w-full min-w-full  py-1">
            <form id="invoicePurchases" action="{{ route('invoicePurchases.store') }}" method="POST" >
                @csrf
              
                <div class="flex gap-4">
                    <div class="flex gap-4">
                        @foreach ($PaymentType as $index => $item)
        <div class="flex">
            <label for="" class="labelSale">{{$item->label()}}</label>
            <input type="radio" name="Payment_type" value="{{$item->value}}" 
                {{ $index === 0 ? 'checked' : '' }} required>
        </div>
    @endforeach
    
                        
                    </div>
                </div>
                <div class="md:justify- text-right grid md:grid-cols-9 gap-2">
                    <div>
                        <label for="transaction_type" class="labelSale">نوع العملية</label>
                        <select dir="ltr" id="transaction_type" class="inputSale input-field" name="transaction_type">
                            @foreach ($transactionTypes as $transactionType)
                                @if (in_array($transactionType->value, [1,2,3]))
                                    <option value="{{ $transactionType->value }}">{{ $transactionType->label() }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                        <div >
                            <label for="mainaccount_debit_id" class="  labelSale">  حساب التصدير  </label>
                           <select name="mainaccount_debit_id"  id="mainaccount_debit_id" dir="ltr" class="input-field  select2 inputSale" required >
                              @isset($MainAccounts)
                            <option value="" selected>اختر الحساب</option>
                             @foreach ($MainAccounts as $mainAccount)
                                  <option value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                             @endforeach
                             @endisset 
                           </select>
                       </div>
                       <div>
                        <label for="Supplier_id" class="labelSale">اسم المورد</label>
                        <select name="Supplier_id" id="Supplier_id" dir="ltr" class="input-field w-full select2 inputSale" >
                        </select>
                    </div>
                    <div >
                        <label for="Receipt_number" class="labelSale">رقم الإيصال</label>
                        <input type="text" name="Receipt_number" id="Receipt_number" placeholder="0" class="inputSale english-numbers" />
                    </div>
                    <div>
                        <label for="Total_cost" class="labelSale">اجمالي التكلفة</label>
                        <input type="text" name="Total_cost" id="Total_cost" placeholder="0" class="inputSale" />
                    </div>
                    <div >
                        
                        <label for="Yr_cost" class="labelSale">  اتكلفه الريال </label>
                        <input type="number" name="Yr_cost" id="Yr_cost" placeholder="0" class="inputSale" />
                    </div>
                    <div >
                        <label for="Total_invoice" class="labelSale">أجمالي الفاتورة</label>
                        <input type="text" name="Total_invoice" id="Total_invoice" placeholder="0" class="inputSale" />
                    </div>
                    @auth
                    <input type="hidden" name="User_id"  id="User_id" value="{{Auth::user()->id}}">
                    @endauth
                    <div>
                        <label for="Currency_id" class="labelSale">العملة الفاتورة</label>
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
<div class="flex max-md:block p-1 ">
    <div class="min-w-[30%] border-x bg-white   rounded-xl">
        <form   id="ajaxForm">
            @csrf 
            <div  class=" gap-2 grid grid-cols-3 px-1  ">
                <div>
                    <label for="account_debitid" class="labelSale  ">  مخازن الستيراد</label>
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
                    <label for="main_account_debit_id" class="  labelSale">  حساب التصدير  </label>
                   <select name="main_account_debit_id" id="main_account_debit_id" dir="ltr" class="input-field  select2 inputSale" required >
                      @isset($MainAccounts)
                    <option value="" selected>اختر الحساب</option>
                     @foreach ($MainAccounts as $mainAccount)
                          <option value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                     @endforeach
                     @endisset 
                   </select>
               </div>
               <div >
                   <label for="sub_account_debit_id" class="labelSale   ">  تحديد الدائن</label>
                   <select name="sub_account_debit_id" id="sub_account_debit_id" dir="ltr" class="input-field select2 inputSale" required>
                       <option value="" selected>اختر الحساب الفرعي</option>
                       </select>
           </div>
            </div>
            <div class="flex gap-1 px-1"> 
                <div >
                    <label for="product_id" class="block   labelSale">بحث  </label>
                    <select name="product_id" id="product_id" dir="ltr" class="input-field select2 inputSale" required>
                        @isset($products)
                        <option value="9505070441001"  >اختر منتج</option>
                        @foreach ($products as $product)
                        <option value="{{$product->product_id}}">{{$product->product_name}}</option>
                        @endforeach
                        @endisset
                    </select>
                </div>
                 
                {{-- <div class="">
                    <label for="product_name" class="labelSale">اسم المنتج</label>
                    <input type="text" name="product_name" id="product_name" class="inputSale " required />
                </div> --}}
                <div>
                    <label for="Categorie_name" class="block  labelSale">الوحده  </label>
                    <select name="Categorie_name" id="Categorie_name" dir="ltr" class="input-field select2 inputSale" >
                    </select>
                </div>
                <div class="">
                    <label for="Quantity" class="labelSale">الكمية</label>
                    <input type="number" name="Quantity" id="Quantity" placeholder="0" class="inputSale quantity-field english-numbers" required />
                </div>

               
            </div>
           
            <div class="flex gap-1 px-1">
                <div class="">
                    <label for="Purchase_price" class="labelSale">سعر الشراء</label>
                    <input type="text" name="Purchase_price" id="Purchase_price" placeholder="0" class="inputSale"  required/>
                </div>
                
                <div class="">
                    <label for="Cost" class="labelSale">تكلفة الصنف</label>
                    <input type="text" name="Cost" id="Cost" placeholder="0" class="inputSale" />
                </div>
                <div class="">
                    <label for="TotalPurchase" class="labelSale">الاجمالي</label>
                    <input type="text" name="TotalPurchase" id="TotalPurchase" placeholder="0" class="inputSale total-field" required />
                </div>
            </div>
            <div class="flex gap-1 px-1">
                <div class="">
                    <label for="Discount_earned" class="labelSale">الخصم المكتسب</label>
                    <input type="text" name="Discount_earned" id="Discount_earned" placeholder="0" class="inputSale" />
                </div>
                <div class="">
                    <label for="Selling_price" class="labelSale">سعر البيع</label>
                    <input type="text" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" />
                </div>
                <div class="">
                    <label for="Profit" class="labelSale">الربح</label>
                    <input type="text" name="Profit" id="Profit"  class="inputSale" />
                </div>
            </div>
            <div class="flex gap-1 px-1">
                <div class="">
                    <label for="Barcode" class="labelSale">الباركود</label>
                    <input type="number" name="Barcode" id="Barcode" placeholder="0" class="inputSale" />
                </div>
               
                <div class="">
                    <label for="QuantityPurchase" class="labelSale"> الكمية المتوفره</label>
                    <input type="number" name="QuantityPurchase" id="QuantityPurchase" placeholder="0" class="inputSale english-numbers"   />
                </div>
                <div class="px-1">
                    <label for="note" class="labelSale">الوصف</label>
                    <textarea name="note" id="note"  class="inputSale"></textarea>
            </div>
            </div>            <div class="flex px-1 gap-1">

                
                <div>
                <label for="purchase_invoice_id" class="labelSale">رقم الفاتورة</label>
                <input type="number" name="purchase_invoice_id" id="purchase_invoice_id" placeholder="0" class="inputSale" required />
            </div>
            <div class="">
                <label for="supplier_name" class="labelSale">رقم المورد</label>
                <input type="number" name="supplier_name" id="supplier_name" placeholder="0" class="inputSale" required />
            </div>
            <div class="">
                <label for="purchase_id" class="labelSale">رقم القيد</label>
                <input type="number" name="purchase_id"   id="purchase_id"  class="inputSale"  />
            </div>
            </div>
            <div class="flex" id="printEndSave">
                    <div class="flex flex-col">
                        @auth
                            
                        <input type="hidden" name="User_id" value="{{Auth::user()->id}}"/>
                        @endauth

                      </div>
                      <div class="col-span-6 sm:col-span-3 mt-2 px-4" >
                        <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700"  id="saveButton">اضافة </button>
                    </div>
                <div class="col-span-6 sm:col-span-3" >
                <button class="flex inputSale mt-2 " id="delete_invoice" type="button" onclick="deleteInvoice()" >
                        <svg class="w-6 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z"/>
                        </svg>
                        <span class="textNav mr-1"> حذف</span>
                </button>
                </div>
            </div>
        </form>
    </div>
    <div class="container mx-auto  " id="mainAccountsTable">
        <div class="w-full overflow-y-auto max-h-[80vh]  bg-white">
            <table id="mainAccountsTable"   class="w-full mb-4 text-sm">
                <thead >
                    <tr class="bg-blue-100">
                        <th class=" px-2 py-1  tagTd">م</th>
                        <th class=" px-2 py-1  tagTd">اسم الصنف</th>
                        <th class=" px-2 py-1  tagTd"> الوحدة</th>
                        <th class=" px-2 py-1  tagTd">الكمية</th>
                        <th class=" px-2 py-1  tagTd">السعر الشراء</th>
                        <th class=" px-2 py-1  tagTd">التكلفة</th>
                        <th class=" px-2 py-1  tagTd">المخزن</th>
                        <th class=" px-2 py-1  tagTd">الإجمالي</th>
                        <th class=" px-2 py-1  tagTd"></th>
                        <th class=" px-2 py-1  tagTd "></th>
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
</div>

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
 <script>
  $(function() {
    const form = $('#invoicePurchases'),
          submitButton = $('#newInvoice'),
          successMessage = $('#successMessage'),
          errorMessage = $('#errorMessage'),
          invoiceField = $('#purchase_invoice_id'),
          supplierField = $('#supplier_name'),
          csrfToken = $('input[name="_token"]').val();
    submitButton.click(function(e) {
        e.preventDefault();
        submitButton.prop('disabled', true).text('جاري الإرسال...');
        // إخفاء الرسائل السابقة
        successMessage.hide();
        errorMessage.hide();
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
                // تحديث الحقول وإظهار رسالة النجاح
                if (invoiceField.length) {
                    invoiceField.val(response.invoice_number).trigger('change');
                }
                if (supplierField.length) {
                    supplierField.val(response.supplier_id).trigger('change');
                }

                $('#mainAccountsTable tbody').empty();
                successMessage.text(response.message).show();

                setTimeout(() => {
                    successMessage.hide();
                }, 2000); // إخفاء الرسالة بعد 2 ثانية
            }else {
                errorMessage.text(response.message || 'حدث خطأ غير معروف.').show();
            }
        })
        .fail(function(xhr) {
            if (xhr.status === 422) {
    const errors = xhr.responseJSON.errors;
    const firstErrorField = Object.keys(errors)[0];
    const firstErrorMessage = errors[firstErrorField][0];

    // إظهار الرسالة مع اسم الحقل
    errorMessage.html(`<strong>${firstErrorMessage}</strong>`).show();

    // تسليط الضوء على الحقل الخاطئ
    const errorField = $(`[name="${firstErrorField}"]`);
    errorField.focus();

    // فتح `select2` إذا كان الحقل من نوع `select2`
    if (errorField.hasClass('select2')) {
        errorField.select2('open');
    }
}
 else {
                errorMessage.text('حدث خطأ أثناء إرسال الطلب. حاول مرة أخرى لاحقاً.').show();
            }
        })
        .always(function() {
            submitButton.prop('disabled', false).text('إضافة الفاتورة');
        });
    });
});

    </script>
 
<script type="text/javascript">
$(document).on('click', '.delete-payment', function (e) {
    e.preventDefault();

    var successMessage = $('#successMessage'); // الرسالة الناجحة
    var errorMessage = $('#errorMessage'); // الرسالة الخطأ
    const Total_invoice = $('#Total_invoice'); // إجمالي الفاتورة

    let paymentId = $(this).data('id');
    let url = `/purchases/${paymentId}`; // تصحيح مسار الحذف

    if (confirm('هل أنت متأكد أنك تريد حذف هذا السند؟')) {
        $.ajax({
            url: url,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.status === 'success') {
                    // إخفاء السند من الواجهة
                    $('#row-' + paymentId).fadeOut(); // إخفاء الصف
                    // تحديث إجمالي الفاتورة
                    $('#Total_invoice').val(response.Purchasesum || '0'); 
                } else {
                    errorMessage.text(response.message || 'حدث خطأ أثناء الحذف.').show();
                    setTimeout(() => errorMessage.hide(), 3000);
                }
            },
            error: function (error) {
                console.error('Error deleting payment bond:', error.responseText);
                alert('حدث خطأ أثناء الحذف. يرجى المحاولة لاحقًا.');
            }
        });
    }
});



    $(document).ready(function () {

     
        $('#Supplier_id').on('change', function() {
    const receipt_number = $('#Receipt_number');

    $('#Receipt_number').focus(); // تركيز المؤشر على الحقل

    $('#Supplier_id').select2('close');
  // الانتقال إلى الحقل التالي


});
const Product_name = $('#product_name');
      const form = $('#ajaxForm');
   
      const inputs = $('.input-field'); // تحديد جميع الحقول
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
                saveData(event); // استدعاء دالة الحفظ
            }
        });
        $('#saveButton').click(function() {
            saveData(event); // استدعاء دالة الحفظ
        });
   // دالة لحذف السند باستخدام AJAX بعد التأكيد

       
      function saveData(event) {
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
                      errorMessage.hide();
                       successMessage.show().text(data.message);
                      setTimeout(() => {
                          successMessage.hide();
                      }, 3000);
                      addToTable(data.purchase);
                      $('#Total_invoice').val(data.Purchasesum);
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

      function displayPurchases(purchases) {
    let uniqueInvoices = new Set(); // Set لتخزين الفواتير الفريدة
    let rows = ''; // متغير لتخزين الصفوف
    $('#mainAccountsTable tbody').empty(); // تنظيف الجدول
    purchases.forEach(function (purchase) {
        // إضافة شرط للتأكد من عدم تكرار البيانات
        if (!uniqueInvoices.has(purchase.purchase_id)) {
            uniqueInvoices.add(purchase.purchase_id);
            rows += `
                <tr id="row-${purchase.purchase_id}">
                    <td class="text-right tagTd">${purchase.Barcode}</td>
                    <td class="text-right tagTd">${purchase.Product_name}</td>
                    <td class="text-right tagTd">${purchase.categorie_id}</td>
                    <td class="text-right tagTd">${purchase.quantity}</td>
                    <td class="text-right tagTd">${purchase.Purchase_price}</td>
                    <td class="text-right tagTd">${purchase.Cost}</td>
                    <td class="text-right tagTd">${purchase.warehouse_to_id}</td>
                    <td class="text-right tagTd">${purchase.Total}</td>
                    <td class="flex">
                        <button class="" onclick="editData(${purchase.purchase_id})">
                                           <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
</svg>
                        </button>
                        <button class="" onclick="deleteData(${purchase.purchase_id})">
                                     <svg class="w-6 h-6 text-red-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
</svg>
                        </button>
                    </td>
                </tr>
            `;
        }
    });

    $('#mainAccountsTable tbody').append(rows);
}
     

function CsrfToken() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}


      function deleteInvoice()  {
        CsrfToken();
        const invoiceId = $('#purchase_invoice_id').val();        // الحصول على معرف الفاتورة من الحقل
        if (!invoiceId) {
            $('#errorMessage').show().text('لم يتم العثور على معرف الفاتورة.');
            setTimeout(() => {
                errorMessage.hide();
              }, 5000);
            return;
        }
        // تأكيد الحذف
        if (!confirm('هل أنت متأكد من حذف الفاتورة وجميع المشتريات المرتبطة بها؟')) {
            return;
        }
        // إرسال طلب الحذف باستخدام Ajax
        $.ajax({
            url: `/purchase-invoices/${invoiceId}`, // مسار الحذف
            type: 'DELETE',
            success: function(response) {
                if (response.success) {
                    window.location.reload();
                    successMessage.show().text(response.message);
                    // $('#Total_invoice').val(response.Purchasesum);
                    setTimeout(() => {
                        successMessage.hide();
                    }, 5000); // هذا سيقوم بإعادة تحميل الصفحة بالكامل
                    // إزالة الصف المرتبط بالفاتورة من الجدول بدون إعادة تحميل الصفحة
                } else {
                    $('#errorMessage').show().text(response.message);
                    setTimeout(() => {
                      errorMessage.hide();
                    }, 5000);
                }
            },
            error: function(xhr, status, error) {
                $('#errorMessage').show().text(response.message);
                    setTimeout(() => {
                      errorMessage.hide();
                    }, 5000);   }
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


@endsection
