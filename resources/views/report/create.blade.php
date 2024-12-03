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
                @foreach($MainAccounts as $mainAccount)
                    <option value="{{ $mainAccount->main_account_id }}">
                        {{ $mainAccount->account_name }}-{{ $mainAccount->main_account_id }}
                    </option>
                @endforeach
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
                    <input type="radio" name="account-list-radio" value="{{ $key }}" class="mr-2" {{ $key == 'subAccount' ? 'checked' : '' }}>
                    <label class="labelSale">{{ $label }}</label>
                </div>
            @endforeach
            <div class="flex ">
                <input type="radio" name="list" value="sumAmuont" class="mr-2">  
                <label for="" class="labelSale"> كشف كلي</label>
            </div>
            <div class="flex ">
                <input type="radio" name="list" value="allAmuont" checked class="mr-2">  
                <label for="" class="labelSale  ">  كشف تحليلي</label>
            </div>
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

    const successMessage = $('#successMessage');
    const invoiceField = $('#sub_account_debit_id').val();
    const listRadio = $('input[name="list-radio"]:checked').val(); // الحصول على قيمة الخيار المحدد
    const accountListRadio = $('input[name="account-list-radio"]:checked').val(); // الحصول على الخيار المحدد
    const viewType = $('input[name="list"]:checked').val(); // الحصول على خيار كشف الكلي أو التحليلي

    if (invoiceField) {
        // توليد الرابط مع القيم المحددة
        const url = `{{ route('customers.statement', ':invoiceField') }}`
            .replace(':invoiceField', invoiceField)
            + `?list=${viewType}&listradio=${listRadio}&accountlistradio=${accountListRadio}`;

        // فتح الرابط في نافذة جديدة
        window.open(url, '_blank', 'width=800,height=800');
    } else {
        // عرض رسالة خطأ إذا لم يتم تحديد الحساب الفرعي
        successMessage.text('لا توجد فاتورة').show();
        setTimeout(() => {
            successMessage.hide();
        }, 3000);
    }
}

</script>
<script>function openAndPrintInvoice2(e) {
    const successMessage = $('#successMessage');
    var invoiceField = $('#sub_account_debit_id').val();
    var invoiceField = $('#sub_account_debit_id').val();

    if (invoiceField) {
        e.preventDefault(); // منع تحديث الصفحة
        const url = `{{ route('customers.statement', ':invoiceField?') }}`.replace(':invoiceField', invoiceField);

        // فتح الرابط في نافذة جديدة
        const newWindow = window.open(url, '_blank', 'width=600,height=800');

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
        displayMessage('لا توجد فاتورة', 'error');
    }
}

// وظيفة عرض الرسائل
function displayMessage(message, type) {
    const successMessage = $('#successMessage');
    successMessage.text(message).removeClass().addClass(type === 'error' ? 'text-red-500 font-semibold' : 'text-green-500 font-semibold').fadeIn();

    setTimeout(() => {
        successMessage.fadeOut();
    }, 3000);
}

</script>

<script src="{{url('purchases/purchases.js')}}"></script>
<script src="{{ url('purchases.js') }}"></script>

@endsection