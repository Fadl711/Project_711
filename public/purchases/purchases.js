

$('#main_account_debit_id ,#financial_account_id_main').on('change', function() {
    const mainAccountId2 = $(this).val(); // الحصول على ID الحساب الرئيسي
    showAccounts(mainAccountId2,null);
    setTimeout(() => {
        $('#main_account_debit_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
        $('#financial_account_id_main').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
        $('#sub_account_debit_id').select2('open');
        $('#financial_account_id').select2('open');
    }, 1000);

});
$('#mainaccount_debit_id,#financial_account_id_main').on('change', function() {
    const mainAccountId1 = $(this).val(); // الحصول على ID الحساب الرئيسي
    showAccounts(null,mainAccountId1);
    setTimeout(() => {
        $('#mainaccount_debit_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
        $('#Supplier_id').select2('open');
    }, 1000);

});
function showAccounts(mainAccountId2,mainAccountId1)
{
    var mainAccountId=null;
    if(mainAccountId2)
    {
       var mainAccountId=mainAccountId2;
     var  sub_account_debit_id= $('#sub_account_debit_id,#financial_account_id');
     mainAccountId1=null;
    }
    if(mainAccountId1)
        {
           var mainAccountId=mainAccountId1;
         var  sub_account_debit_id= $('#Supplier_id');
        }
    if (mainAccountId!==null) {
        
        $.ajax({
            url: `/main-accounts/${mainAccountId}/sub-accounts`, // استخدام القيم الديناميكية
            
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

