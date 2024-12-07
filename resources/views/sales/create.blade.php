@extends('layout')
@section('conm')
{{-- <p class="text-right  " > بيانات الدفع</p> --}}
<style>
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
<div id="successMessage" style="display:none; color:green;"></div>
<div id="errorMessage" style="display:none; color:red;"></div>

<div class="min-w-[20%] px-1  bg-white rounded-xl ">
    <div class=" flex items-center">
        <div class="w-full min-w-full  py-1">
            <form id="invoiceSales"   >
                @csrf
                <div class="flex gap-4">
                    @foreach ($PaymentType as $index => $item)
    <div class="flex">
        <label for="" class="labelSale">{{$item->label()}}</label>
        <input type="radio" name="payment_type" value="{{$item->value}}" 
            {{ $index === 0 ? 'checked' : '' }} required>
    </div>
@endforeach

                    
                </div>
                <div class="grid md:grid-cols-8 gap-2 text-right">
                    <div>
                        <label for="transaction_type" class="labelSale">نوع العملية</label>
                        <select dir="ltr" id="transaction_type" class="inputSale input-field" name="transaction_type">
                            @foreach ($transactionTypes as $transactionType)
                                @if (in_array($transactionType->value, [4, 5]))
                                    <option value="{{ $transactionType->value }}">{{ $transactionType->label() }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="md:ml-6 relative ">
                        <label for="Customer_name_id" class="labelSale">اسم العميل</label>
                        <select name="Customer_name_id" id="Customer_name_id" class="inputSale select2 input-field">
                            @isset($customers)
                            @foreach ($customers as $cur)
                            <option @isset($DefaultCustomer)
                            @selected($cur->sub_account_id==$DefaultCustomer->subaccount_id)
                            @endisset
                            value="{{$cur->sub_account_id}}">{{$cur->sub_name}}</option>
                             @endforeach
                             @endisset
                        </select>
                        <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class=" w-10  absolute top-0 right-20 focus:outline-none " type="button">
                            <i class="fas fa-plus "></i>
                        </button>
                    </div>
                    <div>
                        <label for="shipping_bearer" class="labelSale">جهة تحمل الشحن</label>
                        <select name="shipping_bearer" id="shipping_bearer" class="inputSale select2 input-field">
                            <option value="customer">العميل</option>
                            <option value="merchant">التاجر</option>
                        </select>
                    </div>
                    <div>
                        <label for="currency_id" class="labelSale">العملة الفاتورة</label>
                         <select   dir="ltr" id="currency_id" class="inputSale input-field select2" name="currency_id"   >
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
                        <label for="shipping_amount" class="labelSale">تكلفة الشحن</label>
                        <input type="text" name="shipping_amount" id="shipping_amount" class="inputSale" step="0.01" placeholder="0.00">
                    </div>
                  
                    <div>
                        <label for="total_price_sale" class="labelSale">الإجمالي</label>
                        <input type="text" name="Purchasesum" id="total_price_sale" class="inputSale" step="0.01" placeholder="0.00">
                    </div>
                    <div>
                        <label for="discount" class="labelSale">الخصم  الممنوح</label>
                        <input type="text" name="discountd" id="discount" class="inputSale"  placeholder="0.00">
                    </div>
                    <div>
                        <label for="net_total_after_discount" class="labelSale"  > الإجمالي بعد الخصم </label>
                        <input type="text" name="net_total_after_discount" id="net_total_after_discount"   class="inputSale"  />
                    </div>
                     
                    @auth
                        <input type="hidden" name="User_id" id="User_id" value="{{ Auth::user()->id }}">
                    @endauth
                    <div id="newInvoice1" style="display: block">
                        <button id="saveinvoiceSales" type="button" class="inputSale flex font-bold">
                            إضافة الفاتورة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- alert --}}
<div id="crud-modal" tabindex="-1" aria-hidden="true" class=" bg-black bg-opacity-50  hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0  h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">اضافة عميل جديد</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            {{-- @include('includes.form') --}}
        </div>
    </div>
</div>
{{-- alert --}}
<div class="flex max-md:block p-1 ">
    <div class="min-w-[30%] border-x bg-white   rounded-xl">
        <form id="ajaxForm">
            @csrf
            <div class="gap-2 grid grid-cols-3 px-1">
                <div>
                    <label for="account_debitid" class="labelSale"> مخازن </label>
                    {{-- warehouse_to_id --}}
                    <select name="account_debitid" id="account_debitid"  dir="ltr" class="input-field select2 inputSale" required>
                        @isset($Warehouse)
                        @foreach ($Warehouse as $cur)
                        <option @isset($DefaultCustomer)
                        @selected($cur->sub_account_id==$DefaultCustomer->warehouse_id)
                        @endisset
                        value="{{$cur->sub_account_id}}">{{$cur->sub_name}}</option>
                         @endforeach
                         @endisset
                    </select>
                </div>
                <div>
                    <label for="financial_account_id_main" class="labelSale"> حساب الدفع</label>
                    <select name="financial_account_id_main" id="financial_account_id_main" dir="ltr" class="input-field select2 inputSale" required>
                        @isset($MainAccounts)
                            <option value="" selected>اختر الحساب</option>
                            @foreach ($MainAccounts as $mainAccount)
                                <option value="{{ $mainAccount['main_account_id'] }}">{{ $mainAccount->account_name }} - {{ $mainAccount->main_account_id }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <label for="financial_account_id" class="labelSale"> تحديد الحساب</label>
                    <select name="financial_account_id" id="financial_account_id" dir="ltr" class="input-field select2 inputSale" required>
                        <option value="" selected>اختر الحساب </option>
                         @isset($financialts)
                         @isset($financial_account)
                        @foreach ($financialts as $financialt)
                        @if ($financialt->sub_account_id==$financial_account)
                        <option  selected value="{{$financialt->sub_account_id}}">{{$financialt->sub_name}}</option>

                        @endif
                        @endforeach
                        @endisset
                        @endisset



                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-1 px-1">
                <div>
                    {{-- <label for="product_id" class="block labelSale">بحث عن المنتج</label> --}}
                    <select name="product_id" id="product_id" dir="ltr" class="input-field select2 inputSale" required>
                        @isset($products)
                            <option value=""> بحث عن المنتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-1 px-1">
              
                <div>
                    <label for="Categorie_name" class="block labelSale">الوحدة </label>
                    <select name="Categorie_name" id="Categorie_name" dir="ltr" class="input-field select2 inputSale" required>
                    </select>
                </div>
                <div>
                    <label for="QuantityCategorie" class="labelSale">الكمية الوحدة</label>
                    <input type="text" name="QuantityCategorie" id="QuantityCategorie" placeholder="0" class="inputSale  english-numbers" required />
               
                </div>
                <div>
                    <label for="Quantity" class="labelSale">الكمية</label>
                    <input type="text" name="Quantity" id="Quantity" placeholder="0" class="inputSale quantity-field english-numbers" required />
                </div>
                <div>
                    <label for="Quantityprice" class="labelSale">الجمالي الكميه</label>
                    <input type="text" name="Quantityprice" id="Quantityprice" placeholder="0" class="inputSale english-numbers" required />
                </div>
            </div>      
            <div class="grid grid-cols-3 gap-1 px-1">
                {{-- <button id="saveButton" type="button">حفظ</button> --}}

                <div>
                    <label for="Selling_price" class="labelSale">سعر البيع</label>
                    <input type="text" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" />
                </div>
                    <input type="hidden" name="Purchase_price" id="Purchase_price" placeholder="0" class="inputSale" />
                    <input type="hidden" name="total_Purchase_price" id="total_Purchase_price" placeholder="0" class="inputSale total-fieldP" />
                <div>
                    <label for="discount_rate" class="labelSale">نسبة الخصم</label>
                    <select name="discount_rate" id="discount_rate" dir="ltr" class="input-field select2 inputSale" required>
                    </select>
                </div>
                <div>
                    <label for="total_discount_rate " class="labelSale">الخصم</label>
                    <input type="text" name="total_discount_rate" id="total_discount_rate" placeholder="0" class="inputSale" />
                </div>
            </div>
            <div class="grid grid-cols-3 gap-1 px-1">
                <div>
                    <label for="Barcode" class="labelSale">الباركود</label>
                    <input type="text" name="Barcode" id="Barcode" placeholder="0" class="inputSale" />
                </div>
                <div>
                    <label for="QuantityPurchase" class="labelSale">الكمية المتوفرة</label>
                    <input type="text" name="QuantityPurchase" id="QuantityPurchase" placeholder="0" class="inputSale english-numbers"   />
                </div>
                <div>
                    <label for="loss" class="labelSale">الخسارة</label>
                    <input type="text" name="loss" id="loss" placeholder="0" class=" inputSale" />
                </div>
            </div>
            <div class="grid grid-cols-3 gap-1 px-1">
                <div>
                    <label for="Total" class="labelSale total-field">الاجمالي</label>
                    <input type="text" name="Total" id="Total" placeholder="0" class="inputSale total-field" required />
                </div>
                <div>
                    <label for="total_price" class="labelSale"> الإجمالي  الصافي </label>
                    <input type="text" name="total_price" id="total_price" placeholder="0" class="inputSale" required />
                </div>
                <div>
                    <label for="currency" class="labelSale">العملة</label>
                    <select   dir="ltr" id="currency" class="inputSale input-field select2" name="currency"   >
                        @isset($Currency_name)
                        @foreach ($Currency_name as $cur)
                        <option @isset($cu)
                        @selected($cur->currency_id==$cu->Currency_id)
                        @endisset
                        value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
                         @endforeach
                         @endisset
                      </select>                </div>
            </div>
            <div class="flex px-1 gap-1">
                <div>
                    <label for="sales_invoice_id" class="labelSale">رقم الفاتورة</label>
                    <input type="text" name="sales_invoice_id" id="sales_invoice_id" placeholder="0" class="inputSale" required />
                </div>
                <div>
                    <label for="Customer_id" class="labelSale">العميل</label>
                    <input type="text" name="Customer_id" id="Customer_id" class="inputSale" required />
                </div>
                <div>
                    <label for="sale_id" class="labelSale">رقم القيد</label>
                    <input type="text" name="sale_id" id="sale_id" class="inputSale" />
                </div>
            </div>
            <div class="flex px-1 gap-1">

            <div class="col-span-6 sm:col-span-3" >
                <button class="flex inputSale mt-2 " type="button" onclick="deleteInvoiceSale()" >
                        <svg class="w-6 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z"/>
                        </svg>
                        <span class="textNav mr-1"> حذف</span>
                </button>
                </div>
                <div class="col-span-6 sm:col-span-3" >
                    <button onclick="openInvoiceWindow(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح الفاتورة</button>
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
                        <th class=" px-2 py-1  tagTd">السعر البيع</th>
                        <th class=" px-2 py-1  tagTd">المخزن</th>
                        <th class=" px-2 py-1  tagTd">الإجمالي</th>
                        <th class=" px-2 py-1  tagTd"></th>
                        <th class=" px-2 py-1  tagTd "></th>
                    </tr>
                </thead>
                <tbody>       
            </table>
    </div>

    </div>
    
</div>

<!-- إطار الطباعة -->
<script>
    $(document).ready(function () {
    

    
    $('#account_debitid').on('change', function() {
        $(this).select2('close');
        $('#main_account_debit_id').select2('open');

    });
});
    function openInvoiceWindow(e) {
const successMessage= $('#successMessage');
const invoiceField = document.getElementById('sales_invoice_id').value; // الحصول على قيمة حقل رقم الفاتورة
if(invoiceField){
    e.preventDefault(); // منع تحديث الصفحة
const url = `{{ route('invoiceSales.print', ':invoiceField') }}`.replace(':invoiceField', invoiceField); // استبدال القيمة في الرابط

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
<script>
$(document).ready(function () {
    const form = $('#ajaxForm');
const inputs = $('.input-field '); // تحديد جميع الحقول
form.on('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
    }
});
           // استدعاء وظيفة الحفظ عند الضغط على زر +
        $(document).on('keydown', function (e) {
            if (e.key === '+') {
                e.preventDefault();
                handleSave();
            }
        });
        // استدعاء وظيفة الحفظ عند الضغط على زر الحفظ
        $('#saveButton').on('click', function () {
            handleSave();
        });
    
        // الوظيفة الرئيسية للحفظ
        function handleSave() {
            const quantityAvailable = parseFloat($('#QuantityPurchase').val()) || 0;
            const quantityRequested = parseFloat($('#Quantityprice').val()) || 0;
    
            // التحقق من الكمية المتوفرة
            if (quantityRequested > quantityAvailable) {
                const confirmation = confirm(
                    `الكمية المتوفرة: ${quantityAvailable}\n` +
                    `الكمية المطلوبة: ${quantityRequested}\n` +
                    `هل تريد المتابعة بالرغم من ذلك؟`
                );
                if (!confirmation) {
                    alert('تم إلغاء العملية. لم يتم حفظ التعديلات.');
                    return;
                }
            }
    
            checkLoss();
        }
    
        // التحقق من الخسارة
        function checkLoss() {
            const lossAmount = parseFloat($('#loss').val()) || 0;
    
            if (lossAmount < 0) {
                const confirmation = confirm(
                    `هذه العملية ستؤدي إلى خسارة بمقدار ${lossAmount.toFixed(2)}.\n` +
                    `هل تريد المتابعة بالرغم من ذلك؟`
                );
                if (!confirmation) {
                    alert('تم إلغاء العملية. لم يتم حفظ التعديلات.');
                    return;
                }
            }
    
            submitFormAjax();
        }
    
        // إرسال النموذج باستخدام AJAX
        function submitFormAjax() {
            const formData = new FormData($('#ajaxForm')[0]);
            const submitButton = $('#saveButton');
            submitButton.prop('disabled', true);
    
            $.ajax({
                url: '{{ route("sales.store") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    handleAjaxSuccess(response);
                    submitButton.prop('disabled', false);
                },
                error: function (xhr) {
                    handleAjaxError(xhr);
                    submitButton.prop('disabled', false);
                }
            });
        }
    
        // التعامل مع النجاح في الطلب
        function handleAjaxSuccess(response) {
            if (response.success) {
                $('#successMessage').show().text(response.message).fadeOut(3000);
                addToTableSale(response.purchase);
                $('#total_price_sale').val(response.total_price_sale);
                $('#net_total_after_discount').val(response.net_total_after_discount);
                $('#discount').val(response.discount);
                emptyData();
                $('#product_id').select2('open').focus();
            } else {
                alert('خطأ أثناء الحفظ! ' + response.message || 'حدث خطأ أثناء حفظ البيانات.');
            }
        }
    
        // التعامل مع الأخطاء في الطلب
        function handleAjaxError(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                for (const field in errors) {
                    const inputField = $(`#${field}`);
                    const parentDiv = inputField.closest('div');
                    parentDiv.find('.error-message').remove();
                    inputField.addClass('is-invalid');
                    parentDiv.append(`<div class="error-message text-danger text-sm">${errors[field][0]}</div>`);
                }
            } else {
                alert('خطأ في الاتصال! لم يتم الاتصال بالخادم، يرجى المحاولة لاحقًا.');
            }
        }
        // إزالة الأخطاء عند تغيير القيم
        $('select, input').on('input change', function () {
            const parentDiv = $(this).closest('div');
            $(this).removeClass('is-invalid');
            parentDiv.find('.error-message').remove();
        });
    });
   
</script>

 <script>
  $(document).ready(function () {
    $('.select2').select2(); // تفعيل المكتبة select2
    $('#saveinvoiceSales').on('click', function () {
        saveData();
        });
    const form = $('#invoiceSales');

    form.on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    // الإرسال باستخدام Ctrl + Shift
    $(document).keydown(function (event) {
        if (event.ctrlKey && event.shiftKey) {
            event.preventDefault(); // منع الإرسال الافتراضي للنموذج
            saveData(); // استدعاء دالة الحفظ
        }
    });

    // دالة الحفظ باستخدام Ajax
    function saveData() {
        const formData = new FormData(form[0]); // إنشاء FormData من النموذج

        // تعريف الحقول والرسائل
        const successMessage = $('#successMessage'),
            errorMessage = $('#errorMessage'),
            invoiceField = $('#sales_invoice_id'),
            customerIdField = $('#Customer_id');

        $.ajax({
            url: '{{ route("invoiceSales.store") }}', // مسار التخزين
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // التوكن الخاص بـ Laravel
            },
            data: formData,
            processData: false, // ضروري مع FormData
            contentType: false, // ضروري مع FormData
            success: function (response) {
                if (response.success) {
                    // تحديث الحقول إذا كانت موجودة
                    if (invoiceField.length) {
                        invoiceField.val(response.invoice_number).trigger('change');
                    }
                    if (customerIdField.length) {
                        customerIdField.val(response.customer_number).trigger('change');
                    }

                    // تنظيف الجدول وإظهار رسالة النجاح
                    $('#mainAccountsTable tbody').empty();
                    displayMessage(successMessage, response.message);
                    $('#product_id').select2('open'); // فتح القائمة المنسدلة
                } else {
                    displayMessage(errorMessage, response.message || 'حدث خطأ غير معروف.');
                }
            },
            error: function (xhr) {
                const errorMsg = xhr.responseJSON?.message || 'حدث خطأ غير متوقع.';
                displayMessage(errorMessage, errorMsg);
            }
        });
    }

    // دالة مساعدة لعرض الرسائل
    function displayMessage(element, message, duration = 2000) {
        element.text(message).show();
        setTimeout(() => {
            element.hide();
        }, duration);
    }
});

 </script>

<script>
    $(document).ready(function() {
        $('#start-scanner').on('click', function() {
            startBarcodeScanner();
        });
      
        function startBarcodeScanner() {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#camera'),
                    constraints: {
                        width: 1280,
                        height: 720,
                        facingMode: "environment"  // "environment" للكاميرا الخلفية، و"user" للكاميرا الأمامية
                    }
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "upc_reader"],
                    multiple: false
                },
                locate: true
            }, function(err) {
                if (err) {
                    console.error(err);
                    alert("حدث خطأ في الوصول إلى الكاميرا. تأكد من منح الأذونات.");
                    return;
                }
                console.log("تم تشغيل الكاميرا.");
                Quagga.start();
            });

            Quagga.onDetected(function(data) {
                const code = data.codeResult.code;
                $('#product_id').val(code).trigger('change');   
                $.ajax({
        url: `/api/products/search?id=${code}`, // استدعاء API بناءً على product_id
        method: 'GET',
        data:account_debitid,
        success: function(product) {
            displayProductDetails(product); // استعراض تفاصيل المنتج إذا تمت الاستجابة بنجاح
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText); // عرض الخطأ إذا حدث خطأ في الاستدعاء
        }
    });
                                 Quagga.stop();
                alert("تم قراءة الباركود: " + code);
            });
        }
        
    });
</script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
     <script src="{{ url('sales.js') }}"></script>
     <script src="{{ url('purchases.js') }}"></script>
     <script src="{{url('purchases/purchases.js')}}"></script>



@endsection
