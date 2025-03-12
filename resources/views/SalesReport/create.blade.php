@extends('layout')

@section('conm')
<h1> تقارير ارباح وخسائر الاصناف </h1>

<form id="Account" method="POST" class="mb-2">
    @csrf
    <ul class="flex flex-col sm:flex-row gap-4 items-center bg-white p-1 rounded-lg shadow-md mb-2">
        <li class="w-full text-center">
            <label for="horizontal-list-radio-license" class="labelSale">إعدادات العرض</label>
        </li>
        @foreach(['1' => 'تلقائي', '2' => 'اليوم', '3' => 'هذا الأسبوع', '4' => 'هذا الشهر','5'=>'حسب التاريخ'] as $key => $label)
            <li class="w-full text-center">
                <input type="radio" name="list-radio" value="{{ $key }}" {{ $key == 1 ? 'checked' : '' }} class="mr-2"> {{ $label }}
            </li>
        @endforeach
        <li class="w-full flex items-center justify-center">
            <label class="text-sm font-medium">من:</label>
            <input type="date" name="from-date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
        </li>
        <li class="w-full flex items-center justify-center">
            <label class="text-sm font-medium">إلى:</label>
            <input type="date" name="to-date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
        </li>
    </ul>

    <div class="gap-2 grid grid-cols-4 bg-white p-1 rounded-lg shadow-md mb-2">
        <div>
            <label for="sub_account_id" class="labelSale">العميل </label>
            <select name="sub_account_id" id="sub_account_id" class="input-field select2 inputSale" >
                @isset($SubAccountCostmers)
                @foreach($SubAccountCostmers as $sub_account)
                    <option value="{{ $sub_account->sub_account_id }}">
                        {{ $sub_account->sub_name }}
                    </option>
                @endforeach
                @endisset

            </select>
        </div>
        <div>
            <label for="product_id" class="labelSale"> المنتجات</label>
            <select name="product_id" id="product_id" class="input-field select2 inputSale" >
                <option value="" selected>اختر منتج </option>
                @isset($Products)
                @foreach($Products as $Product)
                    <option value="{{ $Product->product_id }}">
                        {{ $Product->product_name }}-{{ $Product->product_id }}
                    </option>
                @endforeach
                @endisset
            </select>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 bg-slate-100">
            @foreach(['mainAccount' => 'الحساب المحدد', 'subAccount' => 'تلقائي'] as $key => $label)
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 bg-slate-100">
            @foreach(['ShowAllProducts' => 'عرض كل المنتجات', 'SelectedProduct' => 'المنتج المحدد'] as $key => $label)
                <div class="flex items-center mb-2">
                    <input 
                        type="radio" 
                        name="DisplayMethod" 
                        value="{{ $key }}" 
                        class="mr-2" 
                        {{ (old('DisplayMethod', $selectedAccountListRadio ?? 'SelectedProduct') === $key) ? 'checked' : '' }}>
                    <label class="text-sm font-semibold">{{ $label }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="gap-2 grid grid-cols-3 bg-white p-1 rounded-lg shadow-md mb-2">
<div>
    <div class="items-center text-center ">
        <label for="Quantit" class="block text-sm font-semibold mb-1">نوع التقرير</label>
        <select name="Quantit"  id="Quantit" class="input-field select2 w-full border border-gray-300 rounded-lg p-2 text-right" required>
            <option value="" selected>اختر النوع</option>
            @foreach([
                                'inventoryList' => 'امر جرد',

                'Quantityonly' => 'الكمية',
                      'QuantityCosts' => 'الكمية والتكاليف',
                      'QuantityCostsSupplier' => 'الكمية والتكاليف حسب حركة الموردين',
                      'QuantitySupplier' => 'الكمية حسب حركة الموردين'] as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>
</div>
</form>

<div id="errorMessage" class="text-red-500 text-xs mt-2 hidden"></div>
{{-- <button onclick="openAndPrintInvoice2(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح وطباعة الفاتورة</button> --}}
{{-- <div id="successMessage" style="disp
3lay:none;" class="text-red-500 font-semibold mt-2"></div> --}}
<div id="errorMessage" class="text-red-500 text-xs mt-2 hidden"></div>
<button onclick="openInvoiceWindow(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح الفاتورة</button>
<button onclick="openAndPrintInvoice2(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح وطباعة الفاتورة</button>
<div id="successMessage" style="display:none;" class="text-red-500 font-semibold mt-2"></div>
</div>
<script></script>

<script>
      $(document).ready(function() {
$('.select2').select2();

$('#sub_account_id').on('change', function() {
$('#sub_account_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
$('#product_id').select2('open'); // إغلاق حقل الحساب الرئيسي بشكل صحيح


});
});
 
function openInvoiceWindow(event) {
    event.preventDefault(); // منع تحديث الصفحة

    // التحقق من وجود العناصر في DOM
    const subAccountElement = $('#sub_account_id');
    const productElement = $('#product_id');
    const displayMethodElement = $('input[name="DisplayMethod"]:checked');
    const quantitElement = $('#Quantit');
    const dateListElement = $('input[name="account-list-radio"]:checked');

    if (subAccountElement.length === 0 || productElement.length === 0 || displayMethodElement.length === 0 || quantitElement.length === 0 || dateListElement.length === 0) {
        console.error('One or more elements not found.');
        return;
    }

    // الحصول على القيم
    const sub_account_id = subAccountElement.val();
    const product_id = productElement.val();
    const DisplayMethod = displayMethodElement.val();
    const Quantit = quantitElement.val();
    const accountList = dateListElement.val();

    // // التحقق من القيم الفارغة
    // if (!sub_account_id || !product_id || !DisplayMethod || !Quantit || !dateList) {
    //     alert('يرجى ملء جميع الحقول المطلوبة.');
    //     return;
    // }

    // التحقق من تاريخ البداية والنهاية
    const fromDate = $('input[name="from-date"]').val();
    const toDate = $('input[name="to-date"]').val();

    // if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
    //     alert('تاريخ البداية يجب أن يكون أقل من أو يساوي تاريخ النهاية.');
    //     return;
    // }

    // بناء الرابط
    const url = `{{ route('SalesReport.print')}}`
        + `?sub_account_id=${encodeURIComponent(sub_account_id)}&product_id=${encodeURIComponent(product_id)}&DisplayMethod=${encodeURIComponent(DisplayMethod)}&Quantit=${encodeURIComponent(Quantit)}&accountList=${encodeURIComponent(accountList)}`;

    // فتح النافذة
    window.open(url, '_blank', 'width=1000,height=800');
}
    
    function openAndPrintInvoice2(event) {
      event.preventDefault(); // منع تحديث الصفحة
    
        const invoiceField = $('#product_id').val();
    
        if (invoiceField) {
        const url = `{{ route('SalesReport.print', ':invoiceField') }}`
            .replace(':sub_account_id', sub_account_id)
            + `?coustmar_id=${coustmar_id}&product_id=${product_id}&DisplayMethod=${DisplayMethod}`;

            window.open(url, '_blank', 'width=100,height=100');
            if (newWindow) {
                newWindow.onload = function() {
                    setTimeout(() => {
                        newWindow.print();
                        newWindow.close();
                    }, 1000);
                };
            } else {
                displayMessage('تعذر فتح النافذة. يرجى التحقق من إعدادات المتصفح.', 'error');
            }
        } else {
            displayMessage('يرجى تحديد الحساب الفرعي', 'error');
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
