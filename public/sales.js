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
                    <button class="edit-btn" onclick="editData(${account.sale_id})">
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

function deleteDataSale(id) {
    var successMessage = $('#successMessage');
    var successMessage = $('#successMessage');
    CsrfToken();
    if (confirm('هل أنت متأكد من حذف البيانات؟')) {
        $.ajax({
            type: 'DELETE',
            url: `/sales/${id}`, // مسار الحذف
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
        url: `/sales/${id}`, // استدعاء API بناءً على product_id
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
    e.preventDefault();    const invoiceId = $('#sales_invoice_id').val();        // الحصول على معرف الفاتورة من الحقل
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
        url: `/sales-invoices/${invoiceId}`, // مسار الحذف
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
        fetchSalesByInvoice('/get-sales-by-invoice/ArrowRight', currentInvoiceId);
        event.preventDefault();
    }

    if (event.ctrlKey && event.key === 'ArrowLeft') {
        fetchSalesByInvoice('/get-sales-by-invoice/ArrowLeft', currentInvoiceId);
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

function showProductName(mainAccountId)
{
     var  productname= $('#product_name');
  
    if (mainAccountId!==null) {
        
        $.ajax({
            url: `/all-products/${mainAccountId}/show`, // استخدام القيم الديناميكية
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                productname.empty();
                const productnameOptions = data.map(uniqueProduct =>
                `
                <option value="${uniqueProduct.product_id}">${uniqueProduct.Product_name}</option>`
            ).join('');
        // إضافة الخيارات الجديدة إلى القائمة الفرعية
        productname.append( `<option value=""></option>`);
        productname.append(productnameOptions);
        productname.select2('destroy').select2();
        // إعادة تهيئة Select2 بعد إضافة الخيارات
    },
        error: function(xhr) {
            console.error('حدث خطأ في الحصول على  المنتاج.', xhr.responseText);
        }
    });
};
}
