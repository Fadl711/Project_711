@extends('layout')
@section('conm')
{{-- <p class="text-right  " > بيانات الدفع</p> --}}
<style>

.select2-container--default .select2-selection--single {
    height: 40px; /* ارتفاع العنصر الأساسي */
    line-height: 45px; 
    
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
            <form id="invoiceSales" method="POST">
                @csrf
                
                <div class="flex gap-4">
                    @isset($PaymentType)
                    @foreach ($PaymentType as $index => $item)
    <div class="flex">
        <label for="" class="labelSale">{{$item->label()}}</label>
        <input type="radio" name="payment_type" value="{{$item->value}}" {{ $index === 0 ? 'checked' : '' }} required>
    </div>
                       @endforeach
                       @endisset
                </div>
                <div class="grid md:grid-cols-8   gap-2 text-right">
                    <div>
                        <label for="transaction_type" class="labelSale">نوع العملية</label>
                        <select dir="ltr" id="transaction_type" class="inputSale input-field" name="transaction_type">
                            @isset($transactionTypes)
                            @foreach ($transactionTypes as $transactionType)
                                @if (in_array($transactionType->value, [4, 5]))
                                    <option value="{{ $transactionType->value }}">{{ $transactionType->label() }}</option>
                                @endif
                            @endforeach
                            @endisset
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
                    {{-- <div>
                        <label for="shipping_amount" class="labelSale">تكلفة الشحن</label>
                        <input type="text" name="shipping_amount" id="shipping_amount" class="inputSale" step="0.01" placeholder="0.00">
                    </div> --}}
                    <div>
                        <label for="total_price_sale" class="labelSale">الإجمالي</label>
                        <input type="text" name="total_price_sale" id="total_price_sale" class="inputSale" step="0.01" placeholder="0.00">
                    </div>
                    <div>
                        <label for="discount" class="labelSale">الخصم  الممنوح</label>
                        <input type="text" name="discount" id="discount" class="inputSale"  placeholder="0.00">
                    </div>
                    <div>
                        <label for="net_total_after_discount" class="labelSale"  > الإجمالي بعد الخصم </label>
                        <input type="text" name="net_total_after_discount" id="net_total_after_discount"   class="inputSale"  />
                    </div>
                    @auth
                        <input type="hidden" name="User_id" id="User_id" value="{{ Auth::user()->id }}">
                    @endauth
                    {{-- <div id="newInvoice1" style="display: block">
                        <button id="saveinvoiceSales" type="button" class="inputSale flex font-bold">
                            إضافة الفاتورة
                        </button>
                    </div> --}}
                </div>
                <div class="grid md:grid-cols-4   gap-2 text-right " id="grid2">
                    <div>
                        <label for="financial_account_id_main" class="labelSale"> حساب الدفع</label>
                        <select name="financial_account_id_main" id="financial_account_id_main" dir="ltr" class=" select2 inputSale" >
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
            </form>
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
                    <label for="Supplier" class="labelSale">اسم المورد الصنف</label>
                    <select name="Supplier" id="Supplier" dir="ltr" class="input-field w-full select2 inputSale" >
                      <option selected value=""></option>
                        @isset($subAccountSupplierid)
                        @foreach ($subAccountSupplierid as $Supplier)
                        <option value="{{$Supplier->sub_account_id}}">{{$Supplier->sub_name}}</option>
                         @endforeach
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
                        <option selected value=""></option>
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

                    {{-- <input type="text" name="total_discount_rate" id="total_discount_rate" placeholder="0" class="inputSale" /> --}}
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
                    <input type="number" name="sales_invoice_id" id="sales_invoice_id" placeholder="0" class="inputSale" required />
                </div>
                <div class="" >
                    <button class="flex inputSale mt-2 " type="button" id="delete_invoiceSales">
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
     
        function CsrfToken() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

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
        $('#main_account_debit_id').select2('open');
    });
});


$('#financial_account_id_main').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي
    showAccounts(mainAccountId);
    setTimeout(() => {
        $('#mainaccount_debit_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
        $('#Supplier_id').select2('open');
    }, 1000);

});


function showAccounts(mainAccountId)
{
    if(mainAccountId)
    {
     var  sub_account_debit_id= $('#financial_account_id');
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
function addToTableSale(account) {
    const rowId = `#row-${account.sale_id}`;
    const tableBody = $('#mainAccountsTable tbody');
    // التحقق مما إذا كان الصف موجودًا بالفعل
    if ($(rowId).length) {
        // تحديث الصف في الجدول بناءً على القيم الجديدة
        $(`${rowId} td:nth-child(1)`).text(account.Barcode);
        $(`${rowId} td:nth-child(2)`).text(account.Product_name);
        $(`${rowId} td:nth-child(3)`).text(account.Category_name);
        $(`${rowId} td:nth-child(4)`).text(account.Quantityprice ? Number(account.Quantityprice).toLocaleString() : '0');
        $(`${rowId} td:nth-child(5)`).text(account.Selling_price ? Number(account.Selling_price).toLocaleString() : '0');
        $(`${rowId} td:nth-child(6)`).text(account.warehouse_to_id ? Number(account.warehouse_to_id).toLocaleString() : '0');
        $(`${rowId} td:nth-child(7)`).text(account.total_amount ? Number(account.total_amount).toLocaleString() : '0');
        $(`${rowId} td:nth-child(9)`).text(account.sale_id ? Number(account.sale_id).toLocaleString() : '0');
    } else {
        // إضافة الصف الجديد إلى الجدول إذا لم يكن موجودًا
        const newRow = `
            <tr id="row-${account.sale_id}">
                <td class="text-right tagTd">${account.Barcode}</td>
                <td class="text-right tagTd">${account.Product_name}</td>
                <td class="text-right tagTd">${account.Category_name}</td>
                <td class="text-right tagTd">${account.Quantityprice ? Number(account.Quantityprice).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.Selling_price ? Number(account.Selling_price).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.warehouse_to_id ? Number(account.warehouse_to_id).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.total_amount ? Number(account.total_amount).toLocaleString() : '0'}</td>
             <td class="flex">
                    <button class="edit-btn" onclick="editDataSale(${account.sale_id})">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                        </svg>
                    </button>
                            <button class="delete-payment" onclick="deleteDataSale(${account.sale_id})">
                                     <svg class="w-6 h-6 text-red-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
</svg>
                        </button>

                    
                </td>
              </tr>
              `;
          tableBody.append(newRow); // إضافة الصف الجديد إلى الجدول
    }
}

function displaySales(sales) {
    let uniqueInvoices = new Set(); // Set لتخزين الفواتير الفريدة
    let rows = ''; // متغير لتخزين الصفوف
    $('#mainAccountsTable tbody').empty(); // تنظيف الجدول
    sales.forEach(function (sale) {
        // إضافة شرط للتأكد من عدم تكرار البيانات
        if (!uniqueInvoices.has(sale.sale_id)) {
            uniqueInvoices.add(sale.sale_id);
            rows += `
                <tr id="row-${sale.sale_id}">
                     <td  class="text-right tagTd">${sale.Barcode || '-'}</td>
            <td  class="text-right tagTd">${sale.Product_name || '-'}</td>
            <td  class="text-right tagTd">${sale.Category_name || '-'}</td>
            <td  class="text-right tagTd">${sale.Quantityprice || '0'}</td>
            <td  class="text-right tagTd">${sale.Selling_price || '0.00'}</td>
            <td  class="text-right tagTd">${sale.warehouse_to_id || '-'}</td>
            <td  class="text-right tagTd">${sale.total_price || '0.00'}</td>
                    <td class="flex">
                        <button class="" onclick="editDataSale(${sale.sale_id})">
                                           <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
</svg>
                        </button>
                        <button class="delete-payment" onclick="deleteDataSale(${sale.sale_id})">
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

function deleteDataSale(id) {
    var successMessage = $('#successMessage');
    var successMessage = $('#successMessage');
    CsrfToken();
    if (confirm('هل أنت متأكد من حذف البيانات؟')) {
        $.ajax({
            type: 'DELETE',
            url: "{{url('/sales/')}}/"+id, // مسار الحذف
            success: function(response) {
                // إزالة الصف من DOM بدون إعادة تحميل الصفحة
                $('#row-' + id).fadeOut(); // إخفاء الصف
                // تحديث إجمالي الفاتورة
                $('#total_price_sale').val(response.total_price_sale || '0'); 
                $('#net_total_after_discount').val(response.net_total_after_discount || '0'); 
                $('#discount').val(response.discount || '0'); 
                successMessage.text('تم حذف البيانات بنجاح!').show();
                setTimeout(() => {
                    successMessage.hide();
                }, 500);
            },
            error: function(xhr, status, error) {
                errorMessage.text('حدث خطأ أثناء الحذف. الرجاء المحاولة مرة أخرى.').show();
                setTimeout(() => {
                    errorMessage.hide();
                }, 500);            }
        });
    }
};



function editDataSale(id) {
    categorie_name= $('#Categorie_name'),

    $.ajax({
        type: 'GET',
        url: "{{url('/sales/')}}/"+id, // مسار الحذف

        success: function(data) {
            $('#product_id').val(data.product_id);
            $('#Barcode').val(data.Barcode);
            $('#Quantity').val(data.Quantityprice);
            $('#Quantityprice').val(data.quantity);
            $('#Selling_price').val(data.Selling_price);
            $('#Total').val(data.total_amount);
            $('#total_discount_rate').val(data.discount);
            $('#total_price').val(data.total_price);
            $('#sales_invoice_id').val(data.Invoice_id);
            $('#Customer_id').val(data.Customer_id);
            $('#sale_id').val(data.sale_id);
            $('#Categorie_name').val(data.Category_name);
            let discount_rate=  $('#discount_rate');
            let categorie_name=  $('#Categorie_name');
  
            discount_rate.empty();
              categorie_name.empty();
              const  subAccountOptions = 
                    `
                    <option value="${data.Category_name}">${data.Category_name}</option>`
               ;
    
            categorie_name.append(subAccountOptions);
            const  discount = 
            `
            <option value="${data.discount_rate}">${data.discount_rate}</option>
            `
       ;
  
       discount_rate.append(discount);
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
$(document).on('click', '#delete_invoiceSales', function (e) {
    e.preventDefault(); 
       const invoiceId = $('#sales_invoice_id').val();        // الحصول على معرف الفاتورة من الحقل
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
        url: "{{url('/sales-invoices/')}}/"+invoiceId, // مسار الحذف
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
                success: function(response) {
            if (response.success) {
                window.location.reload();
                successMessage.show().text(response.message);
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
    });


$(document).on('keydown', function (event) {
    let currentInvoiceId = $('#sales_invoice_id').val();

    if (event.ctrlKey && event.key === 'ArrowRight') {
        fetchSalesByInvoice("{{url('/get-sales-by-invoice/ArrowRight/')}}", currentInvoiceId);
        event.preventDefault();
    }

    if (event.ctrlKey && event.key === 'ArrowLeft') {
        fetchSalesByInvoice("{{('/get-sales-by-invoice/ArrowLeft/')}}", currentInvoiceId);
        event.preventDefault();
    }
});

function fetchSalesByInvoice(url, currentInvoiceId) {
    if (!currentInvoiceId) {
        console.error('Invoice ID is empty!');
        alert('يرجى إدخال رقم الفاتورة.');
        return;
    }
    $.ajax({
        url: url,
        type: 'GET',
        data: { sales_invoice_id: currentInvoiceId },
        success: function (data) {
            $('#invoiceSales #grid2 #invoiceid2').hide();

            const rows = `
<div id="invoiceid2">
    <label for="invoice_number"> حفظ التعديل</label>
    <button type="button" class="btn btn-primary" onclick="UpdateInvoiceSales(${data.last_invoice_id}, event)">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
        </svg>
    </button>
</div>
`;

$('#invoiceSales #grid2').append(rows);
            $('#mainAccountsTable tbody').empty();

            if (data.sales && data.sales.length > 0) {
                $('#sales_invoice_id').val(data.last_invoice_id);
                displaySales(data.sales);
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
    
    const inputs = $('.input-field '); // تحديد جميع الحقول
    const form = $('#ajaxForm');
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
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
    } else if (xhr.status === 0) {
        alert('خطأ في الاتصال بالخادم. يرجى التحقق من إعدادات الشبكة.');
    } else {
        alert('خطأ في الاتصال! لم يتم الاتصال بالخادم، يرجى المحاولة لاحقًا. (رمز الخطأ: ' + xhr.status + ')');
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
        let rows = '';
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
                $('#invoiceSales #grid2 #invoiceid').empty();
                $('#invoiceSales #grid2 #invoiceid2').empty();

                if (response.success) {
                    $('#invoiceSales #grid2 #invoiceid2').hide();

                    // تحديث الحقول إذا كانت موجودة
                    displayMessage(successMessage, response.message);
                   invoiceField.val(response.invoice_number).trigger('change');

                    if (invoiceField.length)
                     {
                        const rows = `
<div id="invoiceid2" class="">
    <label for=""> حفظ التعديل</label>
    <button id="invoiceid" class="btn btn-primary" data-invoice-id="${response.last_invoice_id}" onclick="UpdateInvoiceSales(${response.last_invoice_id}, event)"  >
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
        </svg>
    </button>
</div>
`;
$('#invoiceSales #grid2').append(rows);

                    }
                    if (customerIdField.length) {
                        customerIdField.val(response.customer_number).trigger('change');
                    }

                   
                    // تنظيف الجدول وإظهار رسالة النجاح
                    $('#mainAccountsTable tbody').empty();
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
    window.UpdateInvoiceSales = function(id, event) {
    event.preventDefault(); // منع الإرسال الافتراضي للنموذج
        // إنشاء FormData من النموذج
        const formData = new FormData($('#invoiceSales')[0]);

        // تعريف الحقول والرسائل
        const successMessage = $('#successMessage'),
              errorMessage = $('#errorMessage');
              let payment_type = $('input[name="payment_type"]:checked').val();
              let sales_invoice_id = $('#sales_invoice_id').val();
        // إضافة معرف الفاتورة إلى FormData
        formData.append('sales_invoice_id', sales_invoice_id);  
        formData.append('payment_type', payment_type);

        

        $.ajax({
            url: '{{ route("invoiceSales.update") }}', // مسار التخزين
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#total_price_sale').val(response.total_price_sale);
                    $('#net_total_after_discount').val(response.net_total_after_discount);
                    $('#discount').val(response.discount);
                    successMessage.show().text(response.message).fadeOut(3000);
                } else {
                    errorMessage.show().text(response.message).fadeOut(3000);
                }
            },
            error: function(xhr) {
                const errorMessageText = xhr.responseJSON?.message || 'حدث خطأ غير متوقع.';
                console.error(errorMessageText);
                $('#errorMessage').show().text(errorMessageText).fadeOut(3000);
            }
        });
    };

   
});

    

    </script>
<script>
    // $(document).ready(function() {
    //     $('#start-scanner').on('click', function() {
    //         startBarcodeScanner();
    //     });
      
    //     function startBarcodeScanner() {
    //         Quagga.init({
    //             inputStream: {
    //                 name: "Live",
    //                 type: "LiveStream",
    //                 target: document.querySelector('#camera'),
    //                 constraints: {
    //                     width: 1280,
    //                     height: 720,
    //                     facingMode: "environment"  // "environment" للكاميرا الخلفية، و"user" للكاميرا الأمامية
    //                 }
    //             },
    //             decoder: {
    //                 readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "upc_reader"],
    //                 multiple: false
    //             },
    //             locate: true
    //         }, function(err) {
    //             if (err) {
    //                 console.error(err);
    //                 alert("حدث خطأ في الوصول إلى الكاميرا. تأكد من منح الأذونات.");
    //                 return;
    //             }
    //             console.log("تم تشغيل الكاميرا.");
    //             Quagga.start();
    //         });

    //         Quagga.onDetected(function(data) {
    //             const code = data.codeResult.code;

    //             $('#product_id').val(code).trigger('change');   
                
    //             $.ajax({

    //         url: `/api/products/search?id=${code}`, // استدعاء API بناءً على product_id
    //     method: 'GET',
    //     data:account_debitid,
    //     success: function(product) {
    //         displayProductDetails(product); // استعراض تفاصيل المنتج إذا تمت الاستجابة بنجاح
    //     },
    //     error: function(xhr) {
    //         console.error('Error:', xhr.responseText); // عرض الخطأ إذا حدث خطأ في الاستدعاء
    //     }
    // });
    //                              Quagga.stop();
    //             alert("تم قراءة الباركود: " + code);
    //         });
    //     }
        
    // });
</script>
     {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script> --}}
     {{-- <script src="{{ url('sales.js') }}"></script> --}}
     <script src="{{ url('purchases.js') }}"></script>
     {{-- <script src="http://localhost/jamal-711/public/purchases.js"></script> --}}
     {{-- <script src="{{url('purchases/purchases.js')}}"></script> --}}



@endsection
