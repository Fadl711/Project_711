function addToTableInventory(account) {
    const rowId = `#row-${account.id}`;
    const tableBody = $('#mainAccountsTable tbody');
    // التحقق مما إذا كان الصف موجودًا بالفعل
    if ($(rowId).length) {
        // تحديث الصف في الجدول بناءً على القيم الجديدة
        $(`${rowId} td:nth-child(1)`).text(account.product_id);
        $(`${rowId} td:nth-child(2)`).text(account.Product_name);
        $(`${rowId} td:nth-child(3)`).text(account.Category_name);
        $(`${rowId} td:nth-child(4)`).text(account.quantity ? Number(account.quantity).toLocaleString() : '0');
        $(`${rowId} td:nth-child(5)`).text(account.warehouse_to_id ? Number(account.warehouse_to_id).toLocaleString() : '0');
        $(`${rowId} td:nth-child(6)`).text(account.CostPrice ? Number(account.CostPrice).toLocaleString() : '0');
        $(`${rowId} td:nth-child(7)`).text(account.TotalCost ? Number(account.TotalCost).toLocaleString() : '0');
    } else {
        // إضافة الصف الجديد إلى الجدول إذا لم يكن موجودًا
        const newRow = `
            <tr id="row-${account.id}">
                <td class="text-right tagTd">${account.product_id}</td>
                <td class="text-right tagTd">${account.Product_name}</td>
                <td class="text-right tagTd">${account.Category_name}</td>
               <td class="text-right tagTd">${account.quantity ? Number(account.quantity).toLocaleString() : '0'}</td>
               <td class="text-right tagTd">${account.warehouse_to_id ? Number(account.warehouse_to_id).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.CostPrice ? Number(account.CostPrice).toLocaleString() : '0'}</td>
                <td class="text-right tagTd">${account.TotalCost ? Number(account.TotalCost).toLocaleString() : '0'}</td>
             <td class="flex">
                    <button class="edit-btn" onclick="inventory_edit(${account.id})">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                        </svg>
                    </button>
                            <button class="delete-payment" onclick="inventory_destroy(${account.id})">
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
function inventory_destroy(id) {
    var successMessage = $('#successMessage');
    var successMessage = $('#successMessage');
    CsrfToken();
    if (confirm('هل أنت متأكد من حذف البيانات؟')) {
        $.ajax({
            type: 'DELETE',
            url: `/inventory/${id}/destroy`, // مسار الحذف
            success: function(response) {
                // إزالة الصف من DOM بدون إعادة تحميل الصفحة
                $('#row-' + id).fadeOut(); // إخفاء الصف
                // تحديث إجمالي الفاتورة
                $('#TotalCost').val(response.TotalCost || '0'); 
                successMessage.text(response.message).show();
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
$(document).on('click', '#destroy_invoice', function (e) {
    e.preventDefault();   
     const invoiceId = $('#sales_invoice_id').val();        // الحصول على معرف الفاتورة من الحقل
    if (!invoiceId) {
        $('#errorMessage').show().text('لم يتم العثور على معرف الفاتورة.');
        setTimeout(() => {
            errorMessage.hide();
          }, 5000);
        return;
    } 
    var successMessage = $('#successMessage');
    var successMessage = $('#successMessage');
    // CsrfToken();

    if (confirm('هل أنت متأكد من حذف البيانات؟')
    ) {
        $.ajax({
            url: `/inventory/Invoice/${invoiceId}/destroy`, // مسار الحذف
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إزالة الصف من DOM بدون إعادة تحميل الصفحة
                // $('#row-' + id).fadeOut(); // إخفاء الصف
                if (response.success) {
                    successMessage.show().text(response.message);
                    setTimeout(() => {
                        successMessage.hide();
                    }, 5000); // هذا سيقوم بإعادة تحميل الصفحة بالكامل
                    window.location.reload();
                    // إزالة الصف المرتبط بالفاتورة من الجدول بدون إعادة تحميل الصفحة
                } else {
                    $('#errorMessage').show().text(response.message);
                    setTimeout(() => {
                      errorMessage.hide();
                    }, 5000);
                }
            },
            error: function(xhr, status, error) {
                errorMessage.text('حدث خطأ أثناء الحذف. الرجاء المحاولة مرة أخرى.').show();
                setTimeout(() => {
                    errorMessage.hide();
                }, 5000);            }
        });
    }
});
function inventory_edit(id) {
    categorie_name= $('#Categorie_name'),

    $.ajax({
        type: 'GET',
        url: `/inventory/${id}/edit`, // استدعاء API بناءً على product_id
        success: function(data) {
            $('#product_id').val(data.product_id);
            // $('#Barcode').val(data.Barcode);
            $('#Quantity').val(data.quantity);
            $('#Quantityprice').val(data.Quantityprice);
            $('#QuantityCategorie').val(data.Quantityprice);
            $('#Purchase_price').val(data.CostPrice);
            $('#TotalPurchase').val(data.TotalCost);
            $('#sales_invoice_id').val(data.InventoryInvoiceId);
            $('#InventoryId').val(data.id);
            $('#Categorie_name').val(data.categorie_id);
            let discount_rate=  $('#discount_rate');
            let categorie_name=  $('#Categorie_name');
            discount_rate.empty();
              categorie_name.empty();
              const  subAccountOptions = 
                    `
                    <option value="${data.categorie_id}">${data.Categorie_name}</option>`
               ;
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
function displayInventory(sales) {
    let uniqueInvoices = new Set(); // Set لتخزين الفواتير الفريدة
    let rows = ''; // متغير لتخزين الصفوف
    $('#mainAccountsTable tbody').empty(); // تنظيف الجدول
    sales.forEach(function (sale) {
        // إضافة شرط للتأكد من عدم تكرار البيانات
        if (!uniqueInvoices.has(sale.id)) {
            uniqueInvoices.add(sale.id);
            rows += `
                <tr id="row-${sale.id}">
            <td  class="text-right tagTd">${sale.product_id || '0'}</td>
            <td  class="text-right tagTd">${sale.Product_name || '0'}</td>
            <td  class="text-right tagTd">${sale.Category_name || '0'}</td>
            <td  class="text-right tagTd">${sale.quantity || '0.00'}</td>
            <td  class="text-right tagTd">${sale.warehouse_to_id || '-'}</td>
            <td  class="text-right tagTd">${sale.CostPrice || '-'}</td>
            <td  class="text-right tagTd">${sale.TotalCost || '-'}</td>
                     <td class="flex">
                    <button class="edit-btn" onclick="inventory_edit(${sale.id})">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                        </svg>
                    </button>
                            <button class="delete-payment" onclick="inventory_destroy(${sale.id})">
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
