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
   
.select2-container--default .select2-dropdown {
            width: 400px; /* ارتفاع العنصر الأساسي */

    max-width: 400px; /* ارتفاع القائمة */
}
.select2-container--default .select2-selection--single {
    height: 40px; /* ارتفاع العنصر الأساسي */
    line-height: 45px; 
    
}
.select2-container--default .select2-selection__rendered {
    padding-top: 5px; /* تحسين النصوص */
}
  </style>
  <div id="successMessage" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
    <p class="font-bold">تم بنجاح!</p>
  </div>
<div id="errorMessage" style="display: none;" class="alert alert-danger"></div>
<div id="successMessage" style="display: none;" class="alert alert-success"></div>
<div class=" px-1  bg-white rounded-xl ">
    <div class=" flex">
        <div class="w-full   py-1">
            <form id="invoicePurchases" action="{{ route('invoicePurchases.store') }}" method="POST" >
                @csrf
              
                <div class="flex gap-1">
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
                <div class=" text-right grid grid-cols-2 md:grid-cols-4 gap-1">
                    <div>
                        <label for="transaction_type" class="labelSale transaction_type">نوع العملية</label>
                        <select  id="transaction_type" class="inputSale input-field select2" name="transaction_type">
                         <option selected value="{{ null }}">{{"حدد نوع العملية "}}</option>

                            @foreach ($transaction_types as $transactionType)
                                @if (in_array($transactionType->value, [1,2,3,8,9,10]))
                                    <option value="{{ $transactionType->value }}">{{ $transactionType->label() }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                        
                       <div class="supplier_div" >
                        <label for="Supplier_id" class="labelSale supplier_id">اسم المورد</label>
                        <select required name="Supplier_id" id="Supplier_id" class="input-field w-full select2 inputSale " >
                          <option selected value="{{ null }}">{{"حدد اسم المورد "}}</option>
                            @isset($subAccountSupplierid)
                            @foreach ($subAccountSupplierid as $Supplier)
                            <option value="{{$Supplier->sub_account_id}}">{{$Supplier->sub_name}}</option>
                             @endforeach
                             @endisset
                        </select>
                    </div>
                    <div >
                        <label for="main_account_debit_id" class="main_accountdebit  labelSale">  حساب التصدير  </label>
                       <select required name="main_account_debit_id" id="main_account_debit_id"  class="input-field  select2 inputSale" required >
                          @isset($main_accounts)
                        <option value="{{ null }}" selected>اختر الحساب</option>
                         @foreach ($main_accounts as $mainAccount)
                              <option value="{{$mainAccount->main_account_id}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                         @endforeach
                         @endisset 
                       </select>
                   </div>

                   <div >
                       <label for="sub_account_debit_id" class="labelSale  sub_account_debit_id ">  تحديد الدائن</label>
                       <select name="sub_account_debit_id" id="sub_account_debit_id"  class="input-field select2 inputSale" required>
                           <option value="" selected>اختر الحساب الفرعي</option>
                           </select>
               </div>
               <div >
                <label for="Receipt_number" class="labelSale">رقم الإيصال</label>
                <input type="text" name="Receipt_number" id="Receipt_number" placeholder="0" class="inputSale english-numbers" />
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
                        <select    id="Currency_id" class="inputSale input-field " name="Currency_id"  >
                            @isset($Currency_name)
                          @foreach ($Currency_name as $cur)
                          <option @isset($cu) @selected($cur->currency_id==$cu->Currency_id)@endisset value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
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
            <div  class=" gap-2 grid grid-cols-2   ">
                <div>
                    <label for="account_debitid" class="labelSale account_debitid ">  مخازن الستيراد</label>
                    <select name="account_debitid" id="account_debitid"  class="input-field  select2 inputSale" required>
                       @isset($Warehouse)
                     <option value="" selected>اختر المخزن</option>
                      @foreach ($Warehouse as $mainAccount)
                      <option value="{{$mainAccount->sub_account_id}}">{{$mainAccount->sub_name}}</option>
                      @endforeach
                      @endisset 
                    </select>
                </div>
                <div >
                    <label for="product_id" class="block  product_id labelSale">بحث  </label>
                    <select name="product_id" id="product_id" dir="ltr" class="input-field select2 inputSale" required>
                        @isset($products)
                        <option value="9505070441001"  >اختر منتج</option>
                        @foreach ($products as $product)
                        <option value="{{$product->product_id}}">{{$product->product_name}}</option>
                        @endforeach
                        @endisset
                    </select>
                </div>
            </div>
            <div  class=" gap-2 grid grid-cols-3 px-1  ">
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
            </div>
            <div class="flex px-1 gap-1">
                <div>
                <label for="purchase_invoice_id" class="labelSale">رقم الفاتورة</label>
                <input type="number" name="purchase_invoice_id" id="purchase_invoice_id" placeholder="0" class="inputSale" required />
            </div>
            <div class="col-span-6 sm:col-span-3" >
                <button class="flex inputSale mt-2 " id="delete_invoice" type="button" >
                        <svg class="w-6 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z"/>
                        </svg>
                        <span class="textNav mr-1"> حذف</span>
                </button>
                </div>
                <div class="col-span-6 sm:col-span-3 mt-2 px-4" >
                    <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700"  id="saveButton">اضافة </button>
                </div>
            </div>
            <div class="flex" id="printEndSave">
                    <div class="flex flex-col">
                        @auth
                        <input type="hidden" name="User_id" value="{{Auth::user()->id}}"/>
                        @endauth
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
          transaction_type = $('.transaction_type'),
          invoiceField = $('#purchase_invoice_id'),
          supplierField = $('#supplier_name'),
          main_accountdebit = $('.main_accountdebit'),
          sub_account_debit_id = $('.sub_account_debit_id'),
          supplier_id = $('.supplier_id'),
          product_id = $('.product_id'),
          csrfToken = $('input[name="_token"]').val();

            $('#transaction_type').on('change', function() {
                 let type=$(this).val();
              

                 if(type==1){
                        main_accountdebit.html(" الحساب الرئيسي/ الدفع").show();
                        supplier_id.html("اسم المورد").show();
                        sub_account_debit_id.html(" الحساب الفرعي/ الدفع ").show();
                         $(".account_debitid").html("المخزن ").show();
                         product_id.html("  البحث عن المنتج  ").show();



                 }

                 if(type==2){
                        main_accountdebit.html("الحساب الرئيسي /المدين").show();
                        supplier_id.html("اسم المورد").show();
                        sub_account_debit_id.html(" الحساب الفرعي /المدين ").show();
                        // $(".supplier_div").hide();
                        $(".account_debitid").html("المخزن المرسل").show();
                       product_id.html("  البحث عن المنتج المردود ").show();

                 }
                 if(type==3){
                        main_accountdebit.html("المخزن الرئيسي /المصدر ").show();
                        supplier_id.html("اسم المورد").show();
                        sub_account_debit_id.html(" المخزن الفرعي /المصدر ").show();
                         $(".account_debitid").html("المخزن المستقبل ").show();
                         product_id.html("البحث عن المنتج").show();

                 }
                 if(type==9){
                        main_accountdebit.html("   الحساب الرئيسي/ خسائر النقص  ").show();
                            sub_account_debit_id.html(" الحساب الفرعي/ خسارة النقص ").show();
                            product_id.html("  البحث عن المنتج الناقص ").show();
                            supplier_id.html("اسم المورد (اختياري)").show();
                 }
                 if(type==10){
                        main_accountdebit.html("   الحساب الرئيسي/ خسائر الأتلاف  ").show();
                            sub_account_debit_id.html(" الحساب الفرعي/ خسارة الأتلاف ").show();
                            product_id.html("  البحث عن المنتج التالف ").show();
                            supplier_id.html("اسم المورد (اختياري)").show();
                        }
                        if(type==8){
                            main_accountdebit.html("   الحساب الرئيسي/ إيرادات زيادة المخزون  ").show();
                            sub_account_debit_id.html(" الحساب الفرعي/ إيراد زيادة المخزون  ").show();
                            product_id.html("  البحث عن المنتج الزايد ").show();
                           supplier_id.html("اسم المورد (اختياري)").show();
                 }

                
    // $('#product_id').select2('open');
});
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
            headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },            data: formData,
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
 
<script >
$(document).ready(function () {

$(document).on('keydown', function (event) {
    let currentInvoiceId = $('#purchase_invoice_id').val();

    if (event.ctrlKey && event.key == 'ArrowRight') {
        fetchPurchasesByInvoice("{{url('/get-purchases-by-invoice/ArrowRight/')}}", currentInvoiceId);
        event.preventDefault();
    }

    if (event.ctrlKey && event.key == 'ArrowLeft') {
        fetchPurchasesByInvoice("{{ url('/get-purchases-by-invoice/ArrowLeft/') }}/" ,currentInvoiceId);

        event.preventDefault();
    }
});
function CsrfToken() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}
function fetchPurchasesByInvoice(url, currentInvoiceId) {
    if (!currentInvoiceId) {
        console.error('Invoice ID is empty!');
        event.preventDefault();

        alert('يرجى إدخال رقم الفاتورة.');

        return;
    }
    $.ajax({
        url: url,
        type: 'GET',
        data: { purchase_invoice_id: currentInvoiceId },
        success: function (data) {

           
            $('#purchase_invoice_id').val(data.last_invoice_id);
            $('#mainAccountsTable tbody').empty();
            
            if (data.sales && data.sales.length > 0) {
                displayPurchases(data.sales);
                
                
                
                $('#Supplier_id').empty();  // لتفريغ الخيارات القديمة
                data.suppliers.forEach(supplier => {
         $('#Supplier_id').append(new Option(supplier.sub_name, supplier.sub_account_id));
        });

        $('#Supplier_id').append( `<option selected  value="${data.SupplierId}">${data.Supplier_name}</option>`);
        $('#transaction_type').empty();  // لتفريغ الخيارات القديمة
        
    // إضافة الخيارات الجديدة
    data.TransactionTypes.forEach(TransactionType => {
        $('#transaction_type').append(new Option(TransactionType.label, TransactionType.value));
    });


    $('#transaction_type').append( `<option selected  value="${data.transaction_valueType}">${data.transaction_typelabel}</option>`);
 
            } else {
                alert(data.message || 'لا توجد مبيعات مرتبطة بهذه الفاتورة.');
            }
        },
        error: function (xhr) {
            console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
            alert('حدث خطأ أثناء جلب البيانات. يرجى المحاولة لاحقًا.');
        }
    });
}



$(document).on('click', '.delete-payment', function (e) {
    e.preventDefault();
    var successMessage = $('#successMessage'); // الرسالة الناجحة
    var errorMessage = $('#errorMessage'); // الرسالة الخطأ
    const Total_invoice = $('#Total_invoice'); // إجمالي الفاتورة

    let paymentId = $(this).data('id');
    // let url = `/purchases/${paymentId}`; // تصحيح مسار الحذف

    if (confirm('هل أنت متأكد أنك تريد حذف هذا الصنف؟')) {
        $.ajax({
            url:"{{url('/purchases/')}}/"+paymentId, // استدعاء API بناءً على product_id
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
        $('#account_debitid').on('change', function() {
    $(this).select2('close');
    $('#product_id').select2('open');
});   
        $('#Supplier_id').on('change', function() {
    const receipt_number = $('#Receipt_number');
    $('#Receipt_number').focus(); // تركيز المؤشر على الحقل
    $('#Supplier_id').select2('close');
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
          // استدعاء وظيفة الحفظ عند الضغط على زر +
         
        $('#saveButton').click(function() {
            saveData(event); // استدعاء دالة الحفظ
        });
        $(document).on('keydown', function (event) {
            if (event.key === '+') {
                event.preventDefault();
                saveData(event); // استدعاء دالة الحفظ
            }
        });
        function saveData(event) {
    event.preventDefault(); // منع تحديث الصفحة

    const form = $('#ajaxForm'); // تخزين العنصر في متغير
    const formData = new FormData(form[0]);

    const selectedPaymentType = $('input[name="Payment_type"]:checked').val();
    formData.append('Payment_type', selectedPaymentType || ''); // إضافة القيمة المختارة أو قيمة فارغة إذا لم يتم اختيار شيء
    formData.append('Receipt_number', $('#Receipt_number').val() || ''); // إضافة رقم الإيصال

    $.ajax({
        url: '{{ route("Purchases.storc") }}', // المسار الخاص بك
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false, // ضروري مع FormData
        contentType: false, // ضروري مع FormData
        success: function(data) {
            if (data.success) {
                $('#errorMessage').hide(); // تأكد من وجود عنصر بهذا المعرف
                $('#successMessage').removeClass('hidden').text(data.message);

                // إخفاء التنبيه بعد 3 ثوانٍ
                setTimeout(() => {
                    $('#successMessage').addClass('hidden');
                }, 3000);

                addToTable(data.purchase);
                $('#Total_invoice').val(data.Purchasesum);
                emptyData();
            } else {
                // إظهار رسالة عند وجود نفس الاسم
                $('#errorMessage').show().text(data.message);
                setTimeout(() => {
                    $('#errorMessage').hide();
                }, 5000);
                $('#Product_name').focus(); // تأكد من وجود عنصر بهذا المعرف
            }
        },
        error: function(xhr) {
            const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'حدث خطأ أثناء الإرسال.';
            $('#errorMessage').show().text(errorMsg);
            setTimeout(() => {
                $('#errorMessage').hide();
            }, 8000);
            $('#Product_name').focus(); // تأكد من وجود عنصر بهذا المعرف
        }
    });
}

// تأكد من إضافة حدث `keydown` على النموذج
$('#ajaxForm').on('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
    }
});

function editData(id) {

$.ajax({
    url:"{{url('/purchases/')}}/"+id,
    type: 'GET',
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
     // استدعاء API بناءً على product_id
    success: function(data) {
        $('#product_id').val(data.product_id);
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
        $('#Categorie_name').val(data.categorie_id);

        categorie_name.empty();
        const  subAccountOptions = 
              `
              <option value="${data.categorie_id}">${data.categorie_id}</option>`
         ;

      // إضافة الخيارات الجديدة إلى القائمة الفرعية
      categorie_name.append(subAccountOptions);
 
        
},
    error: function(xhr, status, error) {
        // console.error("خطأ في جلب بيانات التعديل:", error);
        errorMessage.show().text(data.message);
        setTimeout(() => {
          errorMessage.hide();
        }, 5000);
    }
});
}
      function displayPurchases(sales) {
    let uniqueInvoices = new Set(); // Set لتخزين الفواتير الفريدة
    let rows = ''; // متغير لتخزين الصفوف
    $('#mainAccountsTable tbody').empty(); // تنظيف الجدول
    sales.forEach(function (purchase) {
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
<a href="#" class="text-red-600 hover:underline delete-payment" data-id="${purchase.purchase_id}" >حذف</a>

                    </td>
                </tr>
            `;
        }
    });
    $('#mainAccountsTable tbody').append(rows);
}
function addToTable(account) {
    const rowId = `#row-${account.purchase_id}`;
    const tableBody = $('#mainAccountsTable tbody');

    // التحقق مما إذا كان الصف موجودًا بالفعل
    if ($(rowId).length) {
        // تحديث الصف في الجدول بناءً على القيم الجديدة
        $(`${rowId} td:nth-child(1)`).text(account.Barcode);
        $(`${rowId} td:nth-child(2)`).text(account.Product_name);
        $(`${rowId} td:nth-child(3)`).text(account.categorie_id);
        $(`${rowId} td:nth-child(4)`).text(account.quantity ? Number(account.quantity).toLocaleString() : '0');
        $(`${rowId} td:nth-child(5)`).text(account.Purchase_price ? Number(account.Purchase_price).toLocaleString() : '0');
        $(`${rowId} td:nth-child(6)`).text(account.Cost ? Number(account.Cost).toLocaleString() : '0');
        $(`${rowId} td:nth-child(7)`).text(account.warehouse_to_id ? 
            Number(account.warehouse_to_id).toLocaleString() : 
            (account.warehouse_from_id ? Number(account.warehouse_from_id).toLocaleString() : '0'));
        $(`${rowId} td:nth-child(8)`).text(account.Total ? Number(account.Total).toLocaleString() : '0');
    } else {
        // إنشاء صف جديد إذا لم يكن موجودًا
        const newRow = `
            <tr id="row-${account.purchase_id}">
                <td class="text-right tagTd">${account.Barcode}</td>
                <td class="text-right tagTd">${account.Product_name}</td>
                <td class="text-right tagTd">${account.categorie_id}</td>
                <td class="text-right tagTd">${account.quantity ? Number(account.quantity).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.Purchase_price ? Number(account.Purchase_price).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.Cost ? Number(account.Cost).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.warehouse_to_id ? 
                    Number(account.warehouse_to_id).toLocaleString() : 
                    (account.warehouse_from_id ? Number(account.warehouse_from_id).toLocaleString() : '0')}</td>
                <td class="text-right tagTd">${account.Total ? Number(account.Total).toLocaleString() : '0'}</td>
                <td class="flex">
                    <button class="edit-btn" onclick="editData(${account.purchase_id})">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                        </svg>
                    </button>
                    <a href="#" class="text-red-600 hover:underline delete-payment" data-id="${account.purchase_id}" >حذف</a>

                    
                </td>
            </tr>
        `;
        tableBody.append(newRow); // إضافة الصف الجديد
    }
}

function CsrfToken() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

$(document).on('click', '#delete_invoice', function (e) {
    e.preventDefault();
    const invoiceId = $('#purchase_invoice_id').val(); // الحصول على معرف الفاتورة من الحقل
    if (!invoiceId) {
        $('#errorMessage').show().text('لم يتم العثور على معرف الفاتورة.');
        setTimeout(() => {
            $('#errorMessage').hide();
        }, 5000);
        return;
    }
    // تأكيد الحذف
    if (!confirm('هل أنت متأكد من حذف الفاتورة وجميع المشتريات المرتبطة بها؟')) {
        return;
    }
    // إرسال طلب الحذف باستخدام Ajax
    $.ajax({
        url:"{{url('/purchase-invoices/')}}/"+invoiceId, // مسار الحذف
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                successMessage.show().text(response.message);
                setTimeout(() => {
                    successMessage.hide();
                    window.location.reload(); // إعادة تحميل الصفحة بعد إخفاء الرسالة
                }, 5000);
            } else {
                $('#errorMessage').show().text(response.message);
                setTimeout(() => {
                    $('#errorMessage').hide();
                }, 5000);
            }
        },
        error: function(xhr) {
            $('#errorMessage').show().text(xhr.responseJSON.message);
            setTimeout(() => {
                $('#errorMessage').hide();
            }, 5000);
        }
    });
});
});
$(document).ready(function() {

$('#Categorie_name').on('change', function() {
const Categoriename = $(this).val();
var  mainAccountId= $('#product_id').val();
$('#TotalPurchase,#Profit').val('');
getUnitPriceCategorie(mainAccountId,Categoriename);
$(this).select2('close');
setTimeout(function() {
$('#Quantity').focus();
console.log('Focused on Quantity'); // للتأكد من التركيز
}, 10);

});

function getUnitPriceCategorie(mainAccountId,categoryName)
{

if (mainAccountId!==null) {
const baseUrl = "{{ url('/GetProduct') }}";
$.ajax({
    url: `${baseUrl}/${mainAccountId}/price?mainAccountId=${mainAccountId}&Categoriename=${categoryName}`,
    type: 'GET',
    dataType: 'json',
    success: function(response) {
        if (response.product) {

        //    displayProductDetails(response.product);
            $('#Purchase_price').val(response.product.Purchase_price).trigger('change');
            $('#QuantityCategorie').val(response.product.Quantityprice).trigger('change');
            $('#Selling_price').val(response.product.Selling_price).trigger('change');
        } else {
            console.error('لم يتم العثور على المنتج أو السعر غير متوفر.');
        }
    },
    error: function(xhr) {
        console.error('حدث خطأ في الحصول على المنتج.', xhr.responseText);
    }
});
};
}

$('#product_id').on('change', function() { // عند تغيير المنتج المختار في القائمة
var productId = $(this).val(); // الحصول على قيمة المنتج المختار
var account_debitid = $('#account_debitid').val(); // الحصول على قيمة المنتج المختار

if (productId) { // تحقق من وجود منتج محدد
    
    $.ajax({
        url: "{{ url('/api/products/search/') }}/?id=" + productId+ "/&account_debitid="+account_debitid, // استدعاء API بناءً على product_id

        method: 'GET',
        success: function(product) {

            displayProductDetails(product);
            setTimeout(() => {}, 100);
            $('#Categorie_name').select2('open'); // فتح قائمة الفئات
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText); // عرض الخطأ إذا حدث خطأ في الاستدعاء
        }
    });
} else {
    $('#productDetails').hide(); // إخفاء التفاصيل إذا لم يتم اختيار منتج
}
});
});
$('#account_debitid').on('change', function() {
$(this).select2('close');
$('#product_id').select2('open');
});

$('#main_account_debit_id').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي
    showAccounts(mainAccountId);
    setTimeout(() => {
        $('#main_account_debit_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
        $('#sub_account_debit_id').select2('open');
    }, 1000);

});

function showAccounts(mainAccountId)
{
    if(mainAccountId)
    {
     var  sub_account_debit_id= $('#sub_account_debit_id');
    }
   
    if (mainAccountId!==null) {

        $.ajax({
            url: "{{ url('/main-accounts/') }}/" + mainAccountId + "/sub-accounts", // استخدام القيم الديناميكية

            type: 'GET',
            dataType: 'json',

    success: function(data) {
        sub_account_debit_id.empty();
  const  subAccountOptions = data.map(subAccount =>
        `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
    ).join('');

// إضافة الخيارات الجديدة إلى القائمة الفرعية
sub_account_debit_id.append(subAccountOptions);
sub_account_debit_id.select2('destroy').select2();

// إعادة تهيئة Select2 بعد إضافة الخيارات
},
error: function(xhr) {
    console.error('حدث خطأ في الحصول على الحسابات الفرعية.', xhr.responseText);
}
});
};
}

function displayProductDetails(product) {
const invoiceInput = $('#sales_invoice_id');
var   Categorie_name=$('#Categorie_name');
if (invoiceInput.length) {
// التأكد من أن العناصر موجودة قبل تحديثها
if ($('#Barcode').length) {
    $('#Barcode').val(product.Barcode).trigger('change');
}

if ($('#product_name').length) {
    $('#product_name').val(product.product_name).trigger('change');
}
if ($('#Selling_price').length) {
    $('#Selling_price').val(product.Selling_price).trigger('change');
}
if ($('#Purchase_price').length) {
    $('#Purchase_price').val(product.Purchase_price).trigger('change');
}
if ($('#QuantityPurchase').length) 
    {
    $('#QuantityPurchase').val(product.QuantityPurchase).trigger('change');
}
if ($('#discount_rate').length) {
    const discountSelect = $('#discount_rate');
    discountSelect.empty();
    if (product.Regular_discount && product.Special_discount) {
        const discountOptions = `
        <option value="">لم يتم التحديد  </option>
            <option value="${product.Regular_discount}">الخصم العادي: ${product.Regular_discount}%</option>
            <option value="${product.Special_discount}">الخصم الخاص: ${product.Special_discount}%</option>
        `;
        discountSelect.append(discountOptions);
    } else {
        discountSelect.append('<option value="">لا توجد خصومات متاحة</option>');
    }
}

if ($('#created_at').length) {
    $('#created_at').val(product.created_at).trigger('change');
}
// تعبئة قائمة الفئات (الوحدات)
const categorieSelect = $('#Categorie_name');
categorieSelect.empty();
// تفريغ القائمة السابقة
console.time('Select2 Initialization');
product.Categorie_names.forEach(categorie => {
$('#Categorie_name').append(new Option(categorie.Categorie_name, categorie.categorie_id));
});

categorieSelect.append( `<option selected  value=""></option>`);
$('#Categorie_name').select2(); // إعادة التهيئة بعد الإضافة

console.timeEnd('Select2 Initialization'); // عرض الوقت المستغرق

// حساب التمويز بين البيع والشراء
var profit = 0;
if (product.Selling_price > 0 && product.Purchase_price > 0) {
    profit = product.Selling_price - product.Purchase_price; // حساب التمويز بين البيع والشراء
    profit = profit; // تقريب النتيجة إلى خانتين عشريتين
}
// إضافة التمويز إلى حقل الربح
if ($('#Profit').length) {
    $('#Profit').val(profit).trigger('change');
}

// حساب التكلفة
var Yr_cost = parseFloat($('#Yr_cost').val()) || 0; 
// $Yr_cost=  $('#Yr_cost').val(); // عرض النتيجة
// جلب القيمة من الحقل كرقم عشري
if (!isNaN(Yr_cost) && Yr_cost > 0 && product.Purchase_price > 0) {
    var cost = Yr_cost * product.Purchase_price; 
    
    // حساب التكلفة
    cost = cost.toFixed(2); // تقريب النتيجة لخانتين عشريتين
    $('#Cost').val(cost).trigger('change'); // إضافة النتيجة
} else {
    $('#Cost').val(''); // في حال وجود خطأ أو قيم غير صالحة، يتم تفريغ الحقل
}
}
}
    </script>
{{-- <script src="{{url('purchases/purchases.js')}}"></script> --}}
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
