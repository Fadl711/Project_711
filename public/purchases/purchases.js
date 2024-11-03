function saveinvoice()
{
    $(function() {
        const form = $('#invoicePurchases'),
              submitButton = $('#newInvoice'),
              product_id   = $('#product_id'),
               successMessage = $('#successMessage'),
               errorMessage =   $('#errorMessage'),
    
              invoiceField = $('#purchase_invoice_id'), // حقل رقم الفاتورة
              supplier_id = $('#supplier_name'), // حقل رقم الفاتورة
               // حقل رقم الفاتورة
              csrfToken = $('input[name="_token"]').val();
        // عند الضغط على زر الحفظ
        submitButton.click(function(e) {
            e.preventDefault(); // منع تحديث الصفحة
            // تعطيل الزر لتجنب الضغط المكرر
            submitButton.prop('disabled', true).text('جاري الإرسال...');
            // جمع بيانات النموذج باستخدام serialize
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
            const invoiceInput = $('#purchase_invoice_id');
            const invoiceInput2 = $('#supplier_name');
            if (invoiceInput.length || invoiceInput2.length) {
                invoiceField.val(response.invoice_number).trigger('change');
                supplier_id.val(response.supplier_id).trigger('change');
    
            } else {
                console.warn('حقل "رقم الفاتورة" غير موجود.');
            }
            $('#product_id').focus();
            successMessage.text(response.message).show();
                          setTimeout(() => {
                          successMessage.hide();
                          }, 500);
        } else {
            alert('خطأ: ' + (response.message || 'حدث خطأ غير معروف.'));
        }
    })
    .fail(function(xhr) {
        console.error('خطأ:', xhr.responseText);
        alert('حدث خطأ أثناء إرسال الطلب. حاول مرة أخرى لاحقاً.');
    })
    .always(function() {
        submitButton.prop('disabled', false).text('إضافة الفاتورة');
    });
        });
        form.find('input').keydown(function(e) {
            if (e.key === "Enter") {
                e.preventDefault(); // منع حفظ النموذج عند الضغط على Enter
                $(this).next('input').focus(); // الانتقال إلى الحقل التالي
            }
        });
    });
}
function showAccounts(mainAccountId)
{
    
    if (mainAccountId!==null) {

    $.ajax({
        url: `/main-accounts/${mainAccountId}/sub-accounts`, // استخدام القيم الديناميكية

        type: 'GET',
    dataType: 'json',
    success: function(data) {
            $('#sub_account_debit_id').empty();
            const subAccountOptions = data.map(subAccount =>
                `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
            ).join('');

        // إضافة الخيارات الجديدة إلى القائمة الفرعية
        $('#sub_account_debit_id').append(subAccountOptions);
        $('#sub_account_debit_id').select2('destroy').select2();
        
        // إعادة تهيئة Select2 بعد إضافة الخيارات
    },
        error: function(xhr) {
            console.error('حدث خطأ في الحصول على الحسابات الفرعية.', xhr.responseText);
        }
    });
};
}

