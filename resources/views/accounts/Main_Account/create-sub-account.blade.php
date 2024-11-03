@extends('layout')
@section('conm')

<x-navbar_accounts/>
<h1>انشأ حساب فرعي</h1>
{{-- @dd($subAccountId); --}}
{{-- @isset($subAccountId)
@dd($subAccountId)
    
@endisset --}}
<script src="{{url('payments.js')}}">   </script>

<script>
    $(document).ready(function() {
        // تهيئة Select2 مع تحديد الحد الأدنى لعدد المدخلات المطلوبة
        $('.select2').select2({
            minimumInputLength: 1 // بدء البحث بعد كتابة حرف واحد
        });

        // التركيز على حقل الاسم عند بدء التشغيل
        $('#sub_name').focus();

        // التعامل مع إرسال النموذج وحفظ البيانات باستخدام jQuery
        $('#ajaxForm').on('submit', function(event) {
            event.preventDefault(); // منع تحديث الصفحة

            // تجميع بيانات النموذج
            var formData = $(this).serialize(); // استخدام serialize لجمع البيانات

            // إرسال الطلب باستخدام AJAX
            $.ajax({
                url: '{{ route("Main_Account.storc") }}',
                method: 'POST',
                data: formData,
                success: function(data) {
                    if (data.success) {
                        // إظهار رسالة النجاح
                        $('#successMessage').show().text('تم الحفظ بنجاح!');
                        $('#sub_name').focus(); // إعادة التركيز على حقل الاسم بعد الحفظ
                        // إخفاء الرسالة بعد 3 ثوانٍ
                        setTimeout(function() {
                            $('#successMessage').hide();
                        }, 8000);
                        // إضافة البيانات المحفوظة إلى الجدول
                        addToTable(data.DataSubAccount);
                   // تفريغ النموذج بعد الحفظ
$('#sub_name').val('');           // إعادة تعيين حقل الاسم
$('#debtor_amount').val('');      // إعادة تعيين حقل المبلغ المدين
$('#creditor_amount').val('');    // إعادة تعيين حقل المبلغ الدائن
$('#Phone').val('');              // إعادة تعيين حقل الهاتف
$('#name_The_known').val('');     // إعادة تعيين حقل الاسم المعروف
$('#Known_phone').val('');        // إعادة تعيين حقل الهاتف المعروف


                    } else {
                        // إظهار رسالة عند وجود نفس الاسم
                        $('#successMessage').show().text(data.message || 'يوجد نفس هذا الاسم من قبل');
                        $('#sub_name').focus();
                        setTimeout(function() {
                            $('#successMessage').hide();
                        }, 8000);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 400) {
                        // إظهار رسالة خطأ عند وجود نفس الاسم
                        $('#successMessage').show().text(xhr.responseJSON.message);
                    } 
                    //else {
                    //     $('#errorMessage').show().text('حدث خطأ أثناء الحفظ.');
                    // }
                }
            });
        });

        // وظيفة لإضافة البيانات إلى الجدول
        function addToTable(account) {
            var newRow = `
                <tr>
                    <td class="text-right tagTd">${account.Main_id}</td>
                    <td class="text-right tagTd">${account.sub_name}</td>
                    <td class="text-right tagTd">${account.debtor_amount || 0}</td>
                    <td class="text-right tagTd">${account.creditor_amount || 0}</td>
                    <td class="text-right tagTd">${account.Phone || ''}</td>
                </tr>
            `;
            $('#invoiceItemsTable tbody').append(newRow);
        }

        // منع السلوك الافتراضي لزر Enter
        $('#ajaxForm').on('keypress', function(event) {
            if (event.which === 13) { // 13 هو كود زر Enter
                event.preventDefault(); // منع إرسال النموذج
            }
        });

        // منع السلوك الافتراضي لزر السهم
      
    });
</script>

<br>
<div id="SubAccount">
    <form id="ajaxForm" class="p-4 md:p-5" method="POST">
        @csrf
        <div id="successMessage" class="alert-success" style="display: none;"></div>

        <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="mb-2">
                <label class="labelSale" for="sub_name">اسم الحساب</label>
                <input name="sub_name" class="inputSale input-field" id="sub_name" type="text" placeholder="اسم الحساب الجديد"/>
            </div>
            <div class="mb-2">
                <label class="labelSale" for="Main_id">الحساب الرئيسي</label>
                <select dir="ltr" class="input-field select2 inputSale" id="Main_id" name="Main_id">
                    @forelse($MainAccounts as $MainAccount)
                    <option value="{{$MainAccount['main_account_id']}}">{{$MainAccount['account_name']}}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="mb-2">
                <label class="labelSale" for="debtor_amount">رصيد افتتاحي مدين (اخذ)</label>
                <input name="debtor_amount" class="inputSale input-field" id="debtor_amount" type="number" placeholder="0"/>
            </div>
            <div class="mb-2">
                <label class="labelSale" for="creditor_amount">رصيد افتتاحي دائن (عاطي)</label>
                <input name="creditor_amount" class="inputSale input-field" id="creditor_amount" type="number" placeholder="0"/>
            </div>
            <div class="mb-2">
                <label for="Phone" class="labelSale">رقم التلفون</label>
                <input type="number" name="Phone" id="Phone" class="input-field inputSale" />
            </div>
            <div class="mb-2">
                <label for="name_The_known" class="labelSale">اسم/ معرف العميل</label>
                <input type="text" name="name_The_known" id="name_The_known" class="input-field inputSale" />
            </div>
            <div class="mb-2">
                <label for="Known_phone" class="labelSale">رقم تلفون/ معرف العميل</label>
                <input type="text" name="Known_phone" id="Known_phone" class="input-field inputSale" />
            </div>
        </div>
        @auth
        <input type="hidden" name="User_id" required id="User_id" value="{{Auth::user()->id}}">
        @endauth
        <button type="submit" id="submit" class="text-white inline-flex items-center bgcolor hover:bg-stone-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            حفظ البيانات
        </button>
    </form>

    <table class="min-w-[85%]" id="invoiceItemsTable">
        <thead>
            <tr class="bgcolor">
                <th class="text-right">رقم الحساب</th>
                <th class="text-right">اسم الحساب</th>
                <th class="text-right">الرصيد مدين</th>
                <th class="text-right">الرصيد دائن</th>
                <th class="text-right">التلفون</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div id="errorMessage" style="display: none; color: red;"></div>

<style>
    .alert-success {
        color: green;
        font-weight: bold;
    }
</style>

@endsection
