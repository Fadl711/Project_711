// عند اختيار الحساب الرئيسي (المدين)
$('#main_account_id').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (المدين)

    if (mainAccountId !== null && mainAccountId !== '') {    
        // طلب AJAX لجلب الحسابات الفرعية بناءً على الحساب الرئيسي
        SubAccount(mainAccountId);
    }
});

// دالة لجلب الحسابات الفرعية
function SubAccount(mainAccountId) {
    const subAccountDiv = $('#subAccountDiv'); // الحصول على الحقل المخفي
    const the_way_of_deportation1 = $('#the_way_of_deportation1');
    // تفريغ القائمة الفرعية
    $('#sub_account_id').empty();
    
    $.ajax({
        url: `/main-accounts/${mainAccountId}/sub-accounts`, // استخدام القيم الديناميكية
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // تعبئة الحسابات الفرعية الجديدة
            const subAccountOptions = data.map(subAccount =>
                `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
            ).join('');
            const subAccountOption = '<option selected value="all">الكل</option>';
            // إضافة الخيارات الجديدة إلى القائمة الفرعية
            $('#sub_account_id').append(subAccountOption);
            $('#sub_account_id').append(subAccountOptions);
            // إعادة تهيئة Select2 بعد إضافة الخيارات
            $('#sub_account_id').select2('destroy').select2();
        },
        error: function() {
            console.error('Error fetching sub-accounts.');
        }
    });
}
