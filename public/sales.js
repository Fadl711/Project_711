function Profitproduct() {
    var purchase_price = parseFloat($('#Purchase_price').val()); // الحصول على قيمة سعر الشراء
    var selling_price = parseFloat($('#Selling_price').val());   // الحصول على قيمة سعر البيع
    // التأكد من أن القيم المدخلة صحيحة
    if (selling_price > 0 && purchase_price > 0) {
        var profit = selling_price - purchase_price; // حساب الربح
        $('#Profit').val(profit).trigger('change'); // عرض الربح مع تقريب إلى خانتين عشريتين
    } else {
        $('#Profit').val(''); // تفريغ الحقل في حال وجود قيم غير صالحة
    }
} 
$('#Purchase_price, #Selling_price,#Quantityprice').on('input', function() {
    Profitproduct(); // بدء الحساب عند تغيير القيم في الحقول
});
const successMessage = $('#successMessage');
const errorMessage = $('#errorMessage');
// دالة لحساب السعر ال��جمالي
function parseNumber(value) {
    return parseFloat(value?.replace(/,/g, '')) || 0;
}
function formatNumber(num) {
          // تنسيق الأرقام مع فواصل الآلاف

    return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
function TotalPrice() {
    var price = parseNumber($('#Purchase_price').val());
    var Purchase_price = parseNumber($('#Purchase_price').val());
    var sellingPrice = parseNumber($('#Selling_price').val());
    var quantity = parseNumber($('.quantity-field').val());
    var Yr_cost = parseNumber($('#Yr_cost').val());
    var Profit = parseNumber($('#Profit').val());
    var discount_rate = parseNumber($('#discount_rate').val());
    var QuantityCategorie= parseNumber($('#QuantityCategorie').val());

    if ((price > 0 || sellingPrice > 0) && quantity > 0) {
        let Quantityprice = QuantityCategorie * quantity ||quantity * QuantityCategorie;
        let total_price = price * quantity;
        let total_price2 = sellingPrice * quantity;
        let total_priceS = sellingPrice * quantity;
        let PurchasePrice = Purchase_price * quantity;
        let discount = (discount_rate > 0 && sellingPrice > 0) ? (total_priceS * discount_rate) / 100 : 0;
        let cost = Yr_cost * total_price;
        let Profit = total_price2 - PurchasePrice;
        let loss = total_priceS - (total_price + discount);

        // تقريب القيم
        // total_price = total_price.toFixed(2);
        total_price = total_price.toFixed(2);
        discount = discount.toFixed(2);
        Quantityprice = Quantityprice.toFixed(2);
        cost = cost.toFixed(2);
        Profit = Profit.toFixed(2);
        loss = loss.toFixed(2);

        // تحديث القيم


// تعيين القيم مع التنسيق
$('#total_Purchase_price').val(formatNumber(total_price)).trigger('change');
$('#Quantityprice').val(formatNumber(Quantityprice)).trigger('change');
$('#Total').val(formatNumber(total_price2)).trigger('change');
$('#TotalPurchase').val(formatNumber(PurchasePrice)).trigger('change');
$('#total_price').val(formatNumber(total_priceS - discount)).trigger('change');
$('#total_discount_rate').val(formatNumber(discount)).trigger('change');
$('#Cost').val(formatNumber(cost)).trigger('change');
$('#Profit').val(formatNumber(Profit)).trigger('change'); // عرض الربح مع تقريب إلى خانتين عشريتين
        // إضافة التنسيق عند الخسارة
        if (loss < 0) {
            $('#loss').val(loss).trigger('change');
            var ff= $('#loss');
            ff.css({
                'background-color': '#fee2e2', // لون 
                'color': '#991b1b',            // لون 
                'font-weight': 'bold',        // 
                'border-color': '#991b1b',    // (اختياري) لإضافة تنسيق للحواف
            });
            $('#loss').val(loss).trigger('change');
        } //   
        // إزالة تنسيق الخسارة
        else {
            $('#loss').addClass('inputSale');
            $('#loss').val('').trigger('change'); // تحديث القيمة

        }

    } else {
        // تفريغ الحقول
        $('#Total').val('').trigger('change');
        $('#Profit').val('').trigger('change');
        $('#TotalPurchase').val('').trigger('change');
        $('#total_Purchase_price').val('').trigger('change');
        $('#total_Selling_price').val('').trigger('change');
        $('#total_discount_rate').val('').trigger('change');
        $('#total_price').val('').trigger('change');
        $('#loss').val('').trigger('change');
        $('#Cost').val('').trigger('change');
        $('#Quantityprice').val('').trigger('change');
    }
}
// إضافة الحدث
$('#Purchase_price, #Selling_price, #total_discount_rate, #discount_rate, .quantity-field, #Yr_cost').on('input keyup', TotalPrice);



// دالة لحساب التكلفة المتكررة
function RepeatedCost() {
    // إزالة الفواصل وتحويل القيم إلى أعداد عشرية
    var total_cost = parseFloat($('#Total_cost').val().replace(/,/g, '')) || 0; 
    var Total_invoice = parseFloat($('#Total_invoice').val().replace(/,/g, '')) || 0;
    if (!isNaN(total_cost) && !isNaN(Total_invoice) && Total_invoice > 0) {
        var cost = total_cost / Total_invoice; // حساب النسبة
        $('#Yr_cost').val(cost); // عرض النتيجة
    } else {
        $('#Yr_cost').val(''); // تفريغ الحقل في حال وجود خطأ
    }
}
// تحديث الحقل كلما تم إدخال قيمة جديدة
$('#Total_cost, #Total_invoice').on('input', function() {
    RepeatedCost(); 
});


$(document).ready(function() {
    $('.select2').select2();
});





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

$('#warehouse_id').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي
    showProductName(mainAccountId);
    setTimeout(() => {
        $('#warehouse_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
    }, 1000);
    
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
           const Customer_name_id= $('#Customer_name_id');
           const transaction_type= $('#transaction_type');
           $('#sales_invoice_id').val(data.last_invoice_id);
                $('input[name="payment_type"][value="' + data.payment_type + '"]').prop('checked', true);                $('#total_price_sale').val(data.total_price_sale);
                $('#net_total_after_discount').val(data.net_total_after_discount);
                $('#discount').val(data.discount);
                $('#date').val(data.created_at);
                $('#TotalProfit').val(data.Profit);
                $('#note').val(data.note);

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
                displaySales(data.sales);

             
                transaction_type.empty();
                
                Customer_name_id.empty();
    const  subAccountOptions = 
          `
          <option selected  value="${data.Customer_id}">${data.Customer_name}</option>`
     ;
  
     data.customers.forEach(customer => {
        $('#Customer_name_id').append(new Option(customer.sub_name, customer.sub_account_id));
    });
    Customer_name_id.append( `<option selected  value="${data.Customer_id}">${data.Customer_name}</option>`);

    const  transaction_typ = 
          `
          <option value="${data.transaction_valueType}">${data.transaction_typelabel}</option>`
     ;

     transaction_type.append(transaction_typ);
            } else {
                alert(data.message || 'لا توجد مبيعات مرتبطة بهذه الفاتورة.');
            }
        },
  
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
        if ($('#discount_rate').length)
         {
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
function emptyData(){
    $('#product_name,#total_price,#loss,#Quantityprice,#QuantityCategorie,#TotalPurchase').val('');
        $('#Barcode').val('');
        $('#InventoryId').val('');
        $('#TotalProfit').val('');
        $('#Quantity').val('');
        $('#Purchase_price').val('');
        $('#Selling_price').val('');
        $('#Total').val('');
        $('#TotalPurchase').val('');
        $('#Cost').val('');
        $('#Discount_earned').val('');
        $('#Profit').val('');
        $('#Exchange_rate').val('');
        $('#product_id').val('');
        $('#note').val('');
        $('#QuantityPurchase').val('');
        $('#Categorie_name').val('');
        $('#discount_rate').val('');
        $('#total_discount_rate').val('');
        $('#purchase_id,#sale_id').val('');
        $('#product_id').select2('open');
     
};
function emptyDataProduct(){
    $('#total_price,#loss,#Quantityprice,#QuantityCategorie,#TotalPurchase').val('');
        $('#Barcode').val('');
        $('#Quantity').val('');
        $('#Purchase_price').val('');
        $('#Selling_price').val('');
        $('#Total').val('');
        $('#TotalPurchase').val('');
        $('#Cost').val('');
        $('#Profit').val('');
        $('#QuantityPurchase').val('');
        $('#Categorie_name').val('');
        $('#discount_rate').val('');
        $('#total_discount_rate').val('');
     
};