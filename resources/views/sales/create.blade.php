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
<div id="errorMessage" style="display: none;" class="alert alert-danger"></div>
<div id="successMessage" style="display: none;" class="alert alert-success"></div>
<div class="min-w-[20%] px-1  bg-white rounded-xl ">
    <div class=" flex items-center">
        <div class="w-full min-w-full  py-1">
            <form id="invoiceSales" method="POST" action="{{route('invoiceSales.store')}}"  >
                @csrf
                <div class="flex gap-4">
                    <div class="flex">
                        <label for="" class="labelSale">آجل</label>
                        <input type="radio" name="payment_type" value="on_credit" required>
                    </div>
                    <div class="flex">
                        <label for="" class="labelSale">نقداً</label>
                        <input type="radio" name="payment_type" value="cash" required>
                    </div>
                </div>
                <div class="grid md:grid-cols-8 gap-2 text-right">
                    <div class="md:ml-6 relative ">
                        <label for="Customer_name_id" class="labelSale">اسم العميل</label>
                        <select name="Customer_name_id" id="Customer_name_id" class="inputSale select2 input-field">
                            @isset($customers)
                            @foreach ($customers as $cur)
                            <option @isset($DefaultCustomer)
                            @selected($cur->sub_account_id==$DefaultCustomer)
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
                        <input type="number" name="shipping_amount" id="shipping_amount" class="inputSale" step="0.01" placeholder="0.00">
                    </div>
                  
                    <div>
                        <label for="total_price_sale" class="labelSale">الإجمالي</label>
                        <input type="number" name="total_price_sale" id="total_price_sale" class="inputSale" step="0.01" placeholder="0.00">
                    </div>
                    <div>
                        <label for="discount" class="labelSale">الخصم  الممنوح</label>
                        <input type="number" name="discount" id="discount" class="inputSale"  placeholder="0.00">
                    </div>
                    <div>
                        <label for="net_total_after_discount" class="labelSale"  > الإجمالي بعد الخصم </label>
                        <input type="text" name="net_total_after_discount" id="net_total_after_discount"   class="inputSale"  />
                    </div>
                     
                    @auth
                        <input type="hidden" name="User_id" id="User_id" value="{{ Auth::user()->id }}">
                    @endauth
                    <div id="newInvoice1" style="display: block">
                        <button type="submit" class="inputSale flex font-bold">
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
            @include('includes.form')
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
                    <label for="warehouse_to_id" class="labelSale"> مخازن </label>
                    <select name="warehouse_to_id" id="warehouse_to_id" dir="ltr" class="input-field select2 inputSale" required>
                        @isset($Warehouse)
                            <option value="" selected>اختر المخزن</option>
                            @foreach ($Warehouse as $mainAccount)
                                <option value="{{ $mainAccount->sub_account_id }}">{{ $mainAccount->sub_name }}</option>
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
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-1 px-1">
                <div>
                    <label for="product_id" class="block labelSale">بحث عن المنتج</label>
                    <select name="product_id" id="product_id" dir="ltr" class="input-field select2 inputSale" required>
                        @isset($products)
                            <option value="9505070441001">اختر منتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <label for="Categorie_name" class="block labelSale">الوحدة </label>
                    <select name="Categorie_name" id="Categorie_name" dir="ltr" class="input-field select2 inputSale" required>
                    </select>
                </div>
                <div>
                    <label for="Quantity" class="labelSale">الكمية</label>
                    <input type="text" name="Quantity" id="Quantity" placeholder="0" class="inputSale english-numbers" required />
                </div>
            </div>      
            <div class="grid grid-cols-3 gap-1 px-1">
                <div>
                    <label for="Selling_price" class="labelSale">سعر البيع</label>
                    <input type="text" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" />
                </div>
                <div>
                    <label for="discount_rate" class="labelSale">نسبة الخصم</label>
                    <select name="discount_rate" id="discount_rate" dir="ltr" class="input-field select2 inputSale" required>
                    </select>
                    {{-- <input type="text" name="discount_rate" id="discount_rate" placeholder="0" class="inputSale" /> --}}
                </div>
                <div>
                    <label for="discount" class="labelSale">الخصم</label>
                    <input type="text" name="discount" id="discount" placeholder="0" class="inputSale" />
                </div>
            </div>
            <div class="grid grid-cols-3 gap-1 px-1">
                <div>
                    <label for="Barcode" class="labelSale">الباركود</label>
                    <input type="number" name="Barcode" id="Barcode" placeholder="0" class="inputSale" />
                </div>
                <div>
                    <label for="QuantityPurchase" class="labelSale">الكمية المتوفرة</label>
                    <input type="number" name="QuantityPurchase" id="QuantityPurchase" placeholder="0" class="inputSale english-numbers" />
                </div>
                <div>
                    <label for="Loss" class="labelSale">الخسارة</label>
                    <input type="text" name="Loss" id="Loss" placeholder="0" class="inputSale" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-1 px-1">
                <div>
                    <label for="total_price" class="labelSale"> الإجمالي بعد الخصم </label>
                    <input type="text" name="total_price" id="total_price" placeholder="0" class="inputSale" required />
                </div>
                <div>
                    <label for="currency" class="labelSale">العملة</label>
                    <input type="text" name="currency" id="currency" class="inputSale" />
                </div>
            </div>
            <div class="flex px-1 gap-1">
                <div>
                    <label for="sales_invoice_id" class="labelSale">رقم الفاتورة</label>
                    <input type="number" name="sales_invoice_id" id="sales_invoice_id" placeholder="0" class="inputSale" required />
                </div>
                <div>
                    <label for="Customer_id" class="labelSale">العميل</label>
                    <input type="number" name="Customer_id" id="Customer_id" class="inputSale" required />
                </div>
                <div>
                    <label for="sale_id" class="labelSale">رقم القيد</label>
                    <input type="number" name="sale_id" id="sale_id" class="inputSale" />
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
    </div>
</div>
 <script>
    $(document).ready(function() {
        $('#Categorie_name').on('change', function() {
     Quantit= $('#Quantity');
     Quantit.focus(); // تركيز المؤشر على الحقل
     $('#Categorie_name').select2('close');


 });
    $('.select2').select2();
    const form = $('#invoiceSales');
        form.on('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
            }
        });

        $('#invoiceSales').on('submit', function (e) {
    successMessage = $('#successMessage'),
          errorMessage = $('#errorMessage'),
          invoiceField = $('#sales_invoice_id'),
          CustomeridField = $('#Customer_id'),
    e.preventDefault();
    let formData = $(this).serialize();

    $.ajax({
        url: "{{ route('invoiceSales.store') }}",
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // إرسال رمز CSRF
        },
        success: function(response) {
            if (response.success) {
                // تحديث الحقول وإظهار رسالة النجاح
                if (invoiceField.length) {
                    invoiceField.val(response.invoice_number).trigger('change');
                }
                if (CustomeridField.length) {
                    CustomeridField.val(response.customer_number).trigger('change');
                }

                $('#mainAccountsTable tbody').empty();
                successMessage.text(response.message).show();

                setTimeout(() => {
                    successMessage.hide();
                }, 2000); // إخفاء الرسالة بعد 2 ثانية
            }else {
                errorMessage.text(response.message || 'حدث خطأ غير معروف.').show();
            }    
        },
        error: function(xhr) {
            // عرض الأخطاء إذا وجدت
            alert('حدث خطأ: ' + xhr.responseJSON.message);
        }
    });

    return false; // منع التنفيذ التلقا��ي للنموذج

 
});


     
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
