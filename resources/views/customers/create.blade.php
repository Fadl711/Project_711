@extends('layout')

@section('conm')
<x-nav-customer/>   
<div id="successMessage" class="alert-success" style="display: none;"></div>
<div id="errorMessage" style="display: none; color: red;"></div>


    <div id="displayContainer" class="overflow-y-auto max-h-[80vh] bg-white px-4 py-1 rounded-lg shadow-md">
                <form id="ajaxForm" class="p-4 md:p-5" method="POST">
                    @csrf
                    <div id="successMessage" class="alert-success" style="display: none;"></div>
                    <div class="grid gap-4 mb-4 md:flex">
                        <div class="mb-2">
                            <label class="labelSale" for="sub_name">اسم العميل</label>
                            <input name="sub_name" class="inputSale input-field" id="sub_name" type="text" placeholder="اسم الحساب الجديد"/>
                        </div>
                        <div class="mb-2">
                            <label for="Phone" class="labelSale"> التلفون</label>
                            <input type="text" name="Phone" id="Phone" class="input-field inputSale" />
                        </div>
                        <div class="mb-2">
                            <label for="name_The_known" class="labelSale">العنوان</label>
                            <input type="text" name="name_The_known" id="name_The_known" class="input-field inputSale" />
                        </div>
                        <div class="mb-2">
                            <label class="labelSale" for="debtor_amount">رصيد افتتاحي مدين (اخذ)</label>
                            <input name="debtor_amount" class="inputSale input-field" id="debtor_amount" type="number" placeholder="0"/>
                        </div>
                        <div class="mb-2">
                            <label class="labelSale" for="creditor_amount">رصيد افتتاحي دائن (عاطي)</label>
                            <input name="creditor_amount" class="inputSale input-field" id="creditor_amount" type="number" placeholder="0"/>
                        </div>
                    </div>
                    @auth
                    <input type="hidden" name="User_id" required id="User_id" value="{{Auth::user()->id}}">
                    @endauth
                    <button type="submit" id="submit" class="text-white inline-flex items-center bgcolor hover:bg-stone-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        حفظ البيانات
                    </button>
                </form>
            </div>
            <script>
              $(document).ready(function () {
    $('#ajaxForm').on('submit', function(event) {
        event.preventDefault(); // منع تحديث الصفحة

        // التحقق من أن حقل اسم العميل ليس فارغًا
        var subName = $('#sub_name').val();
        if (subName.trim() === '') {
            $('#errorMessage').text('يرجى إدخال اسم العميل').show();
            return;
        }

        // تجميع بيانات النموذج
        var formData = $(this).serialize(); // استخدام serialize لجمع البيانات

        // إرسال الطلب باستخدام AJAX
        $.ajax({
            url: '{{ route("customers.store") }}',
            method: 'POST',
            data: formData,
            success: function(data) {
                if (data.success) {
                    // إظهار رسالة النجاح
                    $('#successMessage').show().text('تم الحفظ بنجاح!');
                    $('#sub_name').focus(); // إعادة التركيز على حقل الاسم بعد الحفظ
                    // إخفاء الرسالة بعد 3 ثوانٍ
                    setTimeout(function() {
                        $('#successMessage').show().text(data.message);
                    }, 8000);
                    // تفريغ النموذج بعد الحفظ
                    $('#sub_name').val('');           // إعادة تعيين حقل الاسم
                    $('#debtor_amount').val('');      // إعادة تعيين حقل المبلغ المدين
                    $('#creditor_amount').val('');    // إعادة تعيين حقل المبلغ الدائن
                    $('#Phone').val('');              // إعادة تعيين حقل الهاتف
                    $('#name_The_known').val('');     // إعادة تعيين حقل الاسم المعروف
                    $('#Known_phone').val('');        // إعادة تعيين حقل الهاتف المعروف
                    // إضافة البيانات المحفوظة إلى الجدول
                    $('#sub_name').focus();
                } else {
                    // إظهار رسالة عند وجود نفس الاسم
                    $('#errorMessage').show().text(data.message || 'يوجد نفس هذا الاسم من قبل');
                    $('#sub_name').focus();
                    setTimeout(function() {
                        $('#errorMessage').hide();
                    }, 8000);
                }
            },
                error: function(xhr) {
    if (xhr.status === 400) {
        // إظهار رسالة خطأ عند وجود نفس الاسم أو خطأ آخر
        $('#errorMessage').text(xhr.responseJSON.message || 'حدث خطأ في حفظ البيانات').show();
    } else {
        // رسالة خطأ عام
        $('#errorMessage').show().text('حدث خطأ غير متوقع');
    }
}

        });
    });
});

            </script>
@endsection