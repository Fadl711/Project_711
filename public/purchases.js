// دالة لحساب الربح
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
$('#Purchase_price, #Selling_price').on('input', function() {
    Profitproduct(); // بدء الحساب عند تغيير القيم في الحقول
});
const successMessage = $('#successMessage');
const errorMessage = $('#errorMessage');
// دالة لحساب السعر ال��جمالي
function parseNumber(value) {
    return parseFloat(value?.replace(/,/g, '')) || 0;
}

function TotalPrice() {
    var price = parseNumber($('#Purchase_price').val());
    var sellingPrice = parseNumber($('#Selling_price').val());
    var quantity = parseNumber($('.quantity-field').val());
    var Yr_cost = parseNumber($('#Yr_cost').val());
    var discount_rate = parseNumber($('#discount_rate').val());

    if ((price > 0 || sellingPrice > 0) && quantity > 0) {
        let total_price = price * quantity;
        let total_priceS = sellingPrice * quantity;
        let discount = (discount_rate > 0 && sellingPrice > 0) ? (total_priceS * discount_rate) / 100 : 0;
        let cost = Yr_cost * total_price;
        let loss = total_priceS - (total_price + discount);

        // تقريب القيم
        total_price = total_price.toFixed(2);
        total_priceS = total_priceS.toFixed(2);
        discount = discount.toFixed(2);
        cost = cost.toFixed(2);
        loss = loss.toFixed(2);

        // تحديث القيم
        $('#total_Purchase_price').val(total_price).trigger('change');
        $('#Total').val(total_priceS).trigger('change');
        $('#total_price').val(total_priceS - discount).trigger('change');
        $('#total_discount_rate').val(discount).trigger('change');
        $('#Cost').val(cost).trigger('change');
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
        $('#total_Purchase_price').val('').trigger('change');
        $('#total_Selling_price').val('').trigger('change');
        $('#total_discount_rate').val('').trigger('change');
        $('#total_price').val('').trigger('change');
        $('#loss').val('').trigger('change');
        $('#Cost').val('').trigger('change');
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
function addToTable(account) {
    const rowId = `#row-${account.purchase_id}`;
    const tableBody = $('#mainAccountsTable tbody');
    // التحقق مما إذا كان الصف موجودًا بالفعل
    if ($(rowId).length) {
        // تحديث الصف في الجدول بناءً على القيم الجديدة
        $(`${rowId} td:nth-child(1)`).text(account.Barcode);
        $(`${rowId} td:nth-child(2)`).text(account.Product_name);
        $(`${rowId} td:nth-child(3)`).text(account.category_name);
        $(`${rowId} td:nth-child(4)`).text(account.quantity ? Number(account.quantity).toLocaleString() : '0');
        $(`${rowId} td:nth-child(5)`).text(account.Purchase_price ? Number(account.Purchase_price).toLocaleString() : '0');
        $(`${rowId} td:nth-child(6)`).text(account.Cost ? Number(account.Cost).toLocaleString() : '0');
        $(`${rowId} td:nth-child(7)`).text(account.warehouse_to_id ? Number(account.Discount_earned).toLocaleString() : '0');
        $(`${rowId} td:nth-child(8)`).text(account.Total ? Number(account.Total).toLocaleString() : '0');
        // $(`${rowId} td:nth-child(9)`).text(account.purchase_id ? Number(account.purchase_id).toLocaleString() : '0');
        // $(`${rowId} td:nth-child(10)`).text(account.purchase_id ? Number(account.purchase_id).toLocaleString() : '0');
    } else {
        // إضافة الصف الجديد إلى الجدول إذا لم يكن موجودًا
        const newRow = `
            <tr id="row-${account.purchase_id}">
                <td class="text-right tagTd">${account.Barcode}</td>
                <td class="text-right tagTd">${account.Product_name}</td>
                <td class="text-right tagTd">${account.category_name}</td>
                <td class="text-right tagTd">${account.quantity ? Number(account.quantity).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.Purchase_price ? Number(account.Purchase_price).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.Cost ? Number(account.Cost).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.warehouse_to_id ? Number(account.warehouse_to_id).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.Total ? Number(account.Total).toLocaleString() : '0'}</td>
             <td class="flex">

              <button class="" onclick="editData(${account.purchase_id})">                     <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
</svg>
</button>
              <button class="" onclick="deleteData(${account.purchase_id})">                    <svg class="w-6 h-6 text-red-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
</svg>
</button>
          </td>
            </tr>
        `;
        tableBody.append(newRow); // إضافة الصف الجديد إلى الجدول
    }
}
  // وظيفة لاستعراض تفاصيل المنتج
  function displayProductDetails(product) {
    const invoiceInput = $('#purchase_invoice_id,#sales_invoice_id');
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
        if ($('#QuantityPurchase').length) {
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
            $('#Categorie_name').append(new Option(categorie.Categorie_name, categorie.Categorie_name));
        });
        
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
                  $('#product_name,#total_price,#loss').val('');
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
                      $('#note').val('');
                      $('#QuantityPurchase').val('');
                      $('#Categorie_name').val('');
                      $('#discount_rate').val('');
                      $('#total_discount_rate').val('');
                      $('#purchase_id,#sale_id').val('');
                      $('#product_id').select2('open');


                   
  };
 
//   ___________________________________________حفظ المنتج ___________


// استدعاء الدالة عند الضغط على الزر

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

$(document).on('keydown', function(event) {
    if (event.ctrlKey && event.key === 'ArrowLeft') {
        // الحصول على قيمة purchase_invoice_id من حقل الإدخال
        let currentInvoiceId = $('#purchase_invoice_id').val();
        
        console.log('Current Invoice ID:', currentInvoiceId); // تحقق من القيمة

        $.ajax({
            url: '/get-purchases-by-invoice',
            type: 'GET',
            data: {
                purchase_invoice_id: currentInvoiceId
            },
            success: function(data) {
     
                
                console.log('Purchases Data:', data); // تحقق من البيانات المستلمة
                if (data && data.length > 0) {
                    $('#mainAccountsTable tbody').empty(); // مسح البيانات القديمة

                    displayPurchases(data); // دالة لعرض المشتريات في الجدول
                } else {
                    console.log("No purchases found for this invoice.");
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching purchases:', xhr.responseText);
            }
        });

        event.preventDefault();
    }
});

  
    $('#account_debitid').on('change', function() {
        $(this).select2('close');
        $('#main_account_debit_id').select2('open');

    });
    $('#sub_account_debit_id').on('change', function() {
        $(this).select2('close');
        $('#product_id').select2('open');


 });
    $('#financial_account_id_main').on('change', function() {
        $(this).select2('close');
        $('#financial_account_id').select2('open');


 });
    $('#account_debitid').on('change', function() {
        $(this).select2('close');
        $('#financial_account_id_main').select2('open');


 });
 $('#financial_account_id').on('change', function() {
    $(this).select2('close');
    $('#product_id').select2('open');


});
 $('#product_id').on('change', function() {
    $(this).select2('close');
        $('#Categorie_name').select2('open');


    });
    $('#Categorie_name').on('change', function() {

        $(this).select2('close');

        // تركيز المؤشر على حقل Quantity بعد تأخير بسيط
        setTimeout(function() {
            $('#Quantity').focus();
            console.log('Focused on Quantity'); // للتأكد من التركيز
        }, 100); // تأخير 100 مللي ثانية
    });
    

 $('#transaction_type').on('change', function() {
    $(this).select2('close');
    $('#mainaccount_debit_id').select2('open');

});
$('#mainaccount_debit_id').on('change', function() {
    $(this).select2('close');
    $('#Supplier_id').select2('open');
});
     // عند الكتابة في حقل اجمالي التكلفة
     $('#Total_cost, #Total_invoice,#Yr_cost,#Purchase_price,#Selling_price,#Cost,#Total,#Discount_earned,#Profit').on('input', function() {
        let value = $(this).val();
        // إزالة أي شيء ليس رقماً أو فاصلة عشرية
        value = value.replace(/[^0-9.]/g, '');
        // التأكد من أن الفاصلة العشرية تظهر مرة واحدة فقط
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        // إضافة الفاصلة بعد كل ثلاثة أرقام (فصل الآلاف) 
        if (value) {
            let [integer, decimal] = value.split('.');
            integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ",");  // إضافة الفواصل بين الآلاف
            value = decimal ? integer + '.' + decimal : integer;  // إعادة تركيب الرقم
        }
    
        // تعيين القيمة المعدلة للحقل
        $(this).val(value);
    });
     // إزالة الفواصل من الحقول قبل إرسالها
     $('#Receipt_number,#Quantity').on('input', function() {
        let value = $(this).val();
    
        // إزالة أي شيء ليس رقماً أو فاصلة عشرية
        value = value.replace(/[^0-9.]/g, '');
        $(this).val(value);

    });

   
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
function deleteData(id) {
    var successMessage = $('#successMessage');
    CsrfToken();
    if (confirm('هل أنت متأكد من حذف البيانات؟')) {
        $.ajax({
            type: 'DELETE',
            url: `/purchases/${id}`, // مسار الحذف
            success: function(response) {
                // إزالة الصف من DOM بدون إعادة تحميل الصفحة
                $('#row-' + id).remove();
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
function CsrfToken(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
};
Barcode        = $('#Barcode'),
QuantityPurchase = $('#QuantityPurchase'),
account_debitid = $('#account_debitid'),
product_name   = $('#product_name'),
Selling_price  = $('#Selling_price'),
Purchase_price = $('#Purchase_price'),
Quantity       = $('#Quantity'),
Categorie_name       = $('#Categorie_name'),
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
                displayProductDetails(product);
                setTimeout(() => {
                }, 100);
                $('#Categorie_name').select2('open');


                 // استعراض تفاصيل المنتج إذا تمت الاستجابة بنجاح
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText); // عرض الخطأ إذا حدث خطأ في الاستدعاء
            }
        });
    } else {
        $('#productDetails').hide(); // إخفاء التفاصيل إذا لم يتم اختيار منتج
    }
});


function editData(id) {

    $.ajax({
        type: 'GET',
        url: `/purchases/${id}`, // استدعاء API بناءً على product_id
        success: function(data) {
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
            $('#Categorie_name').val(data.categorie_id);
            // var categorie_name   =$('#Categorie_name');

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