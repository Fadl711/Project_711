   // منع السلوك الافتراضي لزر السهم
   $(document).on('keydown', function(event) {
    if (event.key === "ArrowRight" || event.key === "ArrowLeft") {
        var currentIndex = $('.input-field').index(document.activeElement);

        if (event.key === "ArrowRight") {
            $('.input-field').eq(currentIndex + 1).focus(); // نقل التركيز إلى الحقل التالي
        } else if (event.key === "ArrowLeft") {
            $('.input-field').eq(currentIndex - 1).focus(); // نقل التركيز إلى الحقل السابق
        }
    }
});

$('.inputSale').on('keydown', function(e) {
  var inputs = $('.inputSale');
  var currentIndex = inputs.index(this);

  // السهم السفلي (Down Arrow)
  if (e.which === 40) {
      e.preventDefault();
      if (currentIndex + 1 < inputs.length) {
          inputs.eq(currentIndex + 1).focus();
      }
  }

  // السهم العلوي (Up Arrow)
  if (e.which === 38) {
      e.preventDefault();
      if (currentIndex - 1 >= 0) {
          inputs.eq(currentIndex - 1).focus();
      }
  }
});


function toggleLoading(state) {
  if (state) {
      $('#submitButton').prop('disabled', true).text('جارٍ الحفظ...');
  } else {
      $('#submitButton').prop('disabled', false).text('حفظ القيد');
  }
}
$(document).ready(function() {
    // تفعيل Select2
    $('.select2').select2();
    });
    $('#Invoice_type').on('change', function () {
        const Invoice_typeId = $(this).val(); // الحصول على معرف التصنيف المحدد
        $(this).select2('close');
        if (!Invoice_typeId) {
            console.warn('لم يتم اختيار تصنيف.');
            return; // إنهاء التنفيذ إذا لم يتم اختيار تصنيف
        }
        // استدعاء الدالة لجلب المنتج بناءً على التصنيف
        GetInvoiceNumber(Invoice_typeId);
        // إغلاق القائمة المنسدلة بعد التأخير
        setTimeout(() => {
            $('#Invoice_type').select2('close');
            
        }, 1000);
        setTimeout(function() {
            console.log('Focused on Quantity'); // للتأكد من التركيز
        }, 100); // تأخير 100 مللي ثانية
    });
    function GetInvoiceNumber(Invoice_typeId) {
        const Invoice_number = $('#Invoice_id'); // حقل سعر البيع
    
        if (!Invoice_typeId) {
            alert('يرجى اختيار التصنيف.');
            return;
        }

        // إرسال طلب AJAX إذا كان التصنيف صالحًا
        $.ajax({
            url:"{{url('/invoice_purchases/')}}/"+Invoice_typeId+"/GetInvoiceNumber",
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // التحقق إذا كانت البيانات تحتوي على سعر البيع
                Invoice_number.empty();
                const  purchase_invoice = data.map(invoice =>
                      `<option value="${invoice.purchase_invoice_id ??invoice.sales_invoice_id}">${invoice.purchase_invoice_id??invoice.sales_invoice_id}</option>`
                  ).join('');
      
              // إضافة الخيارات الجديدة إلى القائمة الفرعية
              Invoice_number.append(purchase_invoice);
            //   Invoice_number.select2('destroy').select2();
    
            },
            error: function (xhr) {
                // التعامل مع الأخطاء
                console.error('حدث خطأ أثناء جلب بيانات المنتج:', xhr.responseText);
    
                // تنبيه المستخدم بخطأ واضح
                alert('حدث خطأ أثناء جلب سعر المنتج. يرجى المحاولة لاحقًا.');
            }
        });
    }