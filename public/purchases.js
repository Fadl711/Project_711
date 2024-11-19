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
function TotalPrice() {
    // إزالة الفواصل من القيم المدخلة وتحويلها إلى أعداد عشرية
    var price = parseFloat($('#Purchase_price').val().replace(/,/g, '')) || 0;
    var quantity = parseFloat($('#Quantity').val().replace(/,/g, '')) || 0;
    var Yr_cost = parseFloat($('#Yr_cost').val().replace(/,/g, '')) || 0;
    // التأكد من أن القيم المدخلة صالحة وإيجابية
    if (price > 0 && quantity > 0) {
        // حساب السعر الإجمالي والتكلفة
        var total_price = price * quantity;
        var cost = Yr_cost * total_price;
        // تقريب السعر الإجمالي والتكلفة إلى خانتين عشريتين
        total_price = total_price.toFixed(2);
        cost = cost.toFixed(2);
        // تعيين القيم النهائية في الحقول المطلوبة وتفعيل التغيير
        $('#Total').val(total_price).trigger('change');
        $('#Cost').val(cost).trigger('change');
    } else {
        $('#Total').val(''); // تفريغ الحقل في حال وجود قيم غير صالحة
        $('#Cost').val('');
    }
}
// إضافة الحدث لتحديث السعر الإجمالي عند تغيير السعر أو الكمية
$('#Purchase_price, #Quantity, #Yr_cost').on('input', function() {
    TotalPrice();
});
$('#Purchase_price, #Quantity').on('input', function() {
    TotalPrice(); // بد�� الحساب عند تغيير القيم في الحقول
});

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

              <button class="" onclick="editData(${account.purchase_id})">                        <svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>
                  <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>
                </svg></button>
              <button class="" onclick="deleteData(${account.purchase_id})">                            <svg class="" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path class="fill-red-600" d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6
                          .89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.
                          2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 
                          6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083
                           6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 
                           3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z" fill="#F87171">
                           </path>
                      </svg></button>
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
       product.Categorie_names.forEach(categorie => {
           categorieSelect.append(new Option(categorie.Categorie_name, categorie.categorie_id));
       });
       categorieSelect.trigger('change');
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
                  $('#product_name').val('');
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
                      $('#purchase_id').val('');
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
                            <svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <button class="" onclick="deleteData(${purchase.purchase_id})">
                            <svg class="" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="fill-red-600" d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5V5.49999H14.4003V12.5H15.2003ZM16.8003 12.5H16.0003V5.49999H16.8003V12.5ZM13.0003 16.7H14.0003V18.3H13.0003V16.7ZM12.2003 12.5C12.2003 13.6559 12.1986 14.6033 12.2989 15.3498C12.4024 16.1193 12.6273 16.7939 13.1669 17.3334L14.2982 16.2021C14.1055 16.0094 13.9643 15.7286 13.8847 15.1366C13.802 14.5215 13.8003 13.7011 13.8003 12.5H12.2003Z" fill="#ffffff"/>
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
        $('#account_debitid').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
        $('#main_account_debit_id').select2('open');

    });
    $('#sub_account_debit_id,#financial_account_id').on('change', function() {
        $('#account_debitid,#financial_account_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
        $('#product_id').select2('open');


 });
 $('#product_id').on('change', function() {
        $('#account_debitid').select2('close');
        $('#product_id').select2('close');
        $('#Categorie_name').select2('open');

    });
 $('#Categorie_name').on('change', function() {
     $('#Categorie_name').select2('close');
     const selectedPaymentType = $('input[name="Quantity"]');

     selectedPaymentType.focus();
    //  Quantit= $('#Quantity');
    //  Quantit.focus(); // تركيز المؤشر على الحقل


 });
 $('#transaction_type').on('change', function() {
    // $('#transaction_type').close(); // إغلاق حقل الحساب الرئيسي بشكل صحيح
    $('#mainaccount_debit_id').select2('open');

});
$('#mainaccount_debit_id').on('change', function() {
    $('#mainaccount_debit_id').select2('close');
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
                    // alert('خطأ: ' + response.message);
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