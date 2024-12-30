@extends('layout')

@section('conm')
<h1>تقرير كشف حساب</h1>

<form id="Account" method="POST" class="mb-2">
    @csrf
    <ul class="flex flex-col sm:flex-row gap-4 items-center bg-white p-1 rounded-lg shadow-md mb-2">
        <li class="w-full text-center">
            <label for="horizontal-list-radio-license" class="labelSale">إعدادات العرض</label>
        </li>
        @foreach(['1' => 'تلقائي', '2' => 'اليوم', '3' => 'هذا الأسبوع', '4' => 'هذا الشهر'] as $key => $label)
            <li class="w-full text-center">
                <input type="radio" name="list-radio" value="{{ $key }}" {{ $key == 1 ? 'checked' : '' }} class="mr-2"> {{ $label }}
            </li>
        @endforeach
        <li class="w-full flex items-center justify-center">
            <input type="checkbox" name="list-radio" value="5" class="mr-2">
            <label class="text-sm font-medium">من:</label>
            <input type="date" name="from-date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
        </li>
        <li class="w-full flex items-center justify-center">
            <label class="text-sm font-medium">إلى:</label>
            <input type="date" name="to-date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
        </li>
    </ul>

    <div class="gap-2 grid grid-cols-3 bg-white p-1 rounded-lg shadow-md mb-2">
        <div>
            <label for="main_account_debit_id" class="labelSale">الحساب الرئيسي</label>
            <select name="main_account_debit_id" id="main_account_debit_id" class="input-field select2 inputSale" required>
                <option value="" selected>اختر الحساب</option>
                @isset($MainAccounts)

                @foreach($MainAccounts as $mainAccount)
                    <option value="{{ $mainAccount->main_account_id }}">
                        {{ $mainAccount->account_name }}-{{ $mainAccount->main_account_id }}
                    </option>
                @endforeach
                @endisset

            </select>
        </div>
        <div>
            <label for="sub_account_debit_id" class="labelSale">الحساب الفرعي</label>
            <select name="sub_account_debit_id" id="sub_account_debit_id" class="input-field select2 inputSale" required>
                <option value="" selected>اختر الحساب الفرعي</option>
            </select>
        </div>

        <div class="gap-2 grid grid-cols-2">
            @foreach(['mainAccount' => 'الحساب الرئيسي', 'subAccount' => 'الحساب الفرعي'] as $key => $label)
            <div class="flex">
                <input
                    type="radio"
                    name="account-list-radio"
                    value="{{ $key }}"
                    class="mr-2"
                    {{ (old('account-list-radio', $selectedAccountListRadio ?? 'subAccount') === $key) ? 'checked' : '' }}>
                <label class="labelSale">{{ $label }}</label>
            </div>
        @endforeach
       
        </div>
       
    </div>
    <div class="gap-2 grid grid-cols-3 bg-white p-1 rounded-lg shadow-md mb-2">
<div></div>
    <div class="items-center text-center ">
        <label for="Quantit" class="labelSale"> نوع التقرير</label>
        <select name="list" id="list" class="input-field select2 inputSale" required>
            <option value="" selected>اختر نوع التقرير</option>
            @foreach([
              'summary' => ' كشف كلي',
              'detail' => ' كشف تحليلي',
              'FullDisclosureOfSubAccounts' => 'كشف  للحسابات الفرعية ',
              'FullDisclosureOfAccounts' => ' كشف كلي للحسابات ',
              'Disclosure_of_all_sub_accounts_after_migration' => 'كشف كلي الحسابات الفرعية بعد الترحيل',
              'Full_disclosure_of_accounts_after_migration' => 'كشف  كلي للحسابات  بعد الترحيل',

             ]
            as $key => $label)
            <option value="{{ $key }}" > {{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>
</form>
<div id="errorMessage" class="text-red-500 text-xs mt-2 hidden"></div>
<button onclick="openInvoiceWindow(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح الفاتورة</button>
<button onclick="openAndPrintInvoice2(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح وطباعة الفاتورة</button>
<div id="successMessage" style="display:none;" class="text-red-500 font-semibold mt-2"></div>

<script>
   function openInvoiceWindow(e) {
    e.preventDefault(); // منع تحديث الصفحة
    let invoiceField = 0; // تعريف المتغير بـ let لتجنب الأخطاء
const invoiceField1 = $('#sub_account_debit_id').val();
const invoiceField2 = $('#main_account_debit_id').val();
const listRadio = $('input[name="list-radio"]:checked').val(); // الخيار المحدد لعرض القائمة
const accountListRadio = $('input[name="account-list-radio"]:checked').val(); // الحساب الرئيسي أو الفرعي
const viewType = $('#list').val(); // كشف كلي أو تحليلي

// التحقق من نوع التقرير
if (viewType === "") {
    invoiceField = -1;
    displayMessage('يرجى تحديد نوع التقرير', 'error'); // عرض رسالة خطأ
}

// التحقق من الحسابات
if (accountListRadio === "mainAccount") {
    if (invoiceField2) {
        invoiceField = invoiceField2;
    } else {
        displayMessage('يرجى تحديد الحساب الرئيسي', 'error'); // عرض رسالة خطأ
    }
} else if (accountListRadio === "subAccount") {
    if (invoiceField1) {
        invoiceField = invoiceField1;
    } else {
        displayMessage('يرجى تحديد الحساب الفرعي', 'error'); // عرض رسالة خطأ
    }
}

// معالجة الحالة الخاصة لتقرير الكشف الكلي بعد الترحيل
if (viewType === "Full_disclosure_of_accounts_after_migration") {
    invoiceField = 0;
}

// عرض رسالة خطأ إذا لم يتم تحديد أي خيار
if (invoiceField === -1) {
    displayMessage('يرجى تحديد  نوع التقرير أو الحساب', 'error'); // عرض رسالة خطأ
}

    if (invoiceField>=0) {
        const url = `{{ route('customers.statement', ':invoiceField') }}`
            .replace(':invoiceField', invoiceField)
            + `?list=${viewType}&listradio=${listRadio}&accountlistradio=${accountListRadio}`;

        window.open(url, '_blank', 'width=800,height=800');
    } else {
        displayMessage('يرجى تحديد الحساب الفرعي', 'error'); // عرض رسالة خطأ
    }
}


  

    function displayMessage(message, type) {
        const successMessage = $('#successMessage');
        successMessage
            .text(message)
            .removeClass()
            .addClass(type === 'error' ? 'text-red-500 font-semibold' : 'text-green-500 font-semibold')
            .fadeIn();

        setTimeout(() => {
            successMessage.fadeOut();
        }, 3000);
    }
    </script>


<script src="{{ url('purchases.js') }}"></script>
<script >


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


// دالة لجلب معلومات المنتج بناءً على التصنيف
function GetProduct(CategorieId) {
    const SellingPriceInput = $('#Selling_price'); // حقل سعر البيع
    const QuantityCategorie = $('#QuantityCategorie'); // حقل سعر البيع

    if (!CategorieId) {
        alert('يرجى اختيار التصنيف.');
        return;
    }

    // إرسال طلب AJAX إذا كان التصنيف صالحًا
    $.ajax({
        url: `/GetProduct/${CategorieId}/price`, // رابط API ديناميكي
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            // التحقق إذا كانت البيانات تحتوي على سعر البيع
            if (response.Selling_price) {
                SellingPriceInput.val(response.Selling_price); // تحديث حقل سعر البيع
                QuantityCategorie.val(response.Quantityprice); // تحديث حقل سعر البيع
            } else {
                SellingPriceInput.val(''); // إفراغ الحقل إذا لم تتوفر قيمة
                alert('سعر البيع غير متوفر.');
            }
            $('#Supplier_id').select2('open');

        },
        error: function (xhr) {
            // التعامل مع الأخطاء
            console.error('حدث خطأ أثناء جلب بيانات المنتج:', xhr.responseText);

            // تنبيه المستخدم بخطأ واضح
            alert('حدث خطأ أثناء جلب سعر المنتج. يرجى المحاولة لاحقًا.');
        }
    });
}

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
    if (mainAccountId) {

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
            console.error('حدث خطأ في الحصول على الحسابات الفرعية.');
        }
    });
};
}

</script>

@endsection
