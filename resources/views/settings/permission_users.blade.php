@extends('layout')
@section('conm')




    <div class="container mx-auto px-4 py-4  max-w-4xl h-screen overflow-y-scroll">


        <div class="bg-white rounded-xl shadow-lg p-4 mb-8 w-1/2">
            <select id="userSelect">
                <option value="">اختر مستخدم</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center space-x-4 mb-6">
                <div class="space-y-4">
                    <div class="user-permissions" data-user-id="">
                        <h1 class="text-lg">الصلاحيات:</h1>
                        <div class="space-y-3 bg-gray-200">
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>

    </div>


    <script>


$(document).ready(function() {
    // عندما يتغير اختيار المستخدم من الـ select الأول
    $('#userSelect').on('change', function() {
        var userId = $(this).val();  // الحصول على ID المستخدم الذي تم اختياره

        if (userId) {  // التأكد من أن هناك مستخدم تم اختياره
            $.ajax({
                url: '{{ route('get.user.permissions') }}',  // المسار إلى الـ route في Laravel
                method: 'POST',
                data: {
                    id: userId,  // إرسال ID المستخدم
                    _token: '{{ csrf_token() }}'  // تضمين توكن CSRF للطلب
                },
                success: function(response) {
                    // تحديث الـ data-user-id للـ div
                    $('.user-permissions').data('user-id', response.id);

                    // عرض الصلاحيات أو الاختيارات الخاصة بالمستخدم
                    var html = `
                        <h3 class="text-lg font-medium text-gray-800">أسم المستخدم: ${response.name}</h3>
                        <label for="select1"> اختار الصفحة  :</label>
                        <select id="select1">
                            <option value="">اختر</option>
                            <option value="الحسابات">الحسابات</option>
                            <option value="القيود">القيود</option>
                            <option value="السندات">السندات</option>
                            <option value="المبيعات">المبيعات</option>
                            <option value="الفواتير المبيعات">الفواتير المبيعات</option>
                            <option value="المشتريات">المشتريات</option>
                            <option value="الفواتير المشتريات">الفواتير المشتريات</option>
                            <option value="المنتجات">المنتجات</option>
                            <option value="سجلات الترحيل">سجلات الترحيل</option>
                            <option value="التقارير">التقارير</option>
                            <option value="المردودات">المردودات</option>
                            <option value="الإعدادات">الإعدادات</option>
                        </select>
                        <div class="permissions-container mt-4"></div>
                    `;

                    // إضافة الـ select داخل الـ div
                    $('.user-permissions').html(html);  // أعد كتابة محتوى الـ .user-permissions بـ HTML الجديد
                },
                error: function(xhr) {
                    console.log('حدث خطأ أثناء جلب البيانات.');
                }
            });
        } else {
            // إذا لم يتم اختيار أي مستخدم، نقوم بإخفاء البيانات
            $('.user-permissions').html('');
        }
    });

    // استخدم الوفد لربط الحدث بـ select الذي سيتم إضافته ديناميكياً
    $(document).on('change', '#select1', function() {
        var selectedValue = $(this).val();
        var userId = $('.user-permissions').data('user-id');
        var permissionsContainer = $(this).closest('.user-permissions').find('.permissions-container');

        if (selectedValue) {
            // إرسال طلب AJAX لعرض الصلاحيات بناءً على قيمة الـ select1
            $.ajax({
                url: '{{ route('get.permissions') }}',  // المسار الخاص بجلب الصلاحيات
                method: 'POST',
                data: {
                    id: userId,
                    type: selectedValue,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // تفريغ الصلاحيات السابقة
                    permissionsContainer.empty();

                    // إضافة الصلاحيات الجديدة
                    var checkedReadability = response.permissions.Readability ? 'checked' : '';
                    var checkedWriting_ability = response.permissions.Writing_ability ? 'checked' : '';
                    var checkedDeletion_authority = response.permissions.Deletion_authority ? 'checked' : '';
                    var checkedAbility_modify = response.permissions.Ability_modify ? 'checked' : '';

                    var html = `
                        <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                aria-label="Read permission" data-permission="Readability" ${checkedReadability}>
                            <span class="text-gray-700">صلاحية العرض</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                aria-label="Write permission" data-permission="Writing_ability" ${checkedWriting_ability}>
                            <span class="text-gray-700">صلاحية التعديل</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                aria-label="Delete permission" data-permission="Deletion_authority" ${checkedDeletion_authority}>
                            <span class="text-gray-700">صلاحية الكتابة</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                aria-label="Modify permission" data-permission="Ability_modify" ${checkedAbility_modify}>
                            <span class="text-gray-700">صلاحية الإدارة</span>
                        </label>
                    `;
                    permissionsContainer.append(html); // إضافة HTML الخاص بالصلاحيات
                },
                error: function(xhr) {
                    console.log('حدث خطأ أثناء جلب الصلاحيات.');
                }
            });
        } else {
            permissionsContainer.html(''); // إذا لم يتم اختيار الصلاحية، إخفاء الصلاحيات.
        }
    });

    // تحديث الصلاحيات عند تغيير checkbox
    $('body').on('change', 'input[type="checkbox"]', function() {
        var permission = $(this).data('permission');
        var isChecked = $(this).is(':checked');
        var userId = $('.user-permissions').data('user-id');
        var selectValue = $('#select1').val();

        $.ajax({
            url: '{{ route('update.permission') }}',
            method: 'POST',
            data: {
                id: userId,
                permission: permission,
                value: isChecked,
                selectValue: selectValue,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('تم تحديث الصلاحية بنجاح');
            },
            error: function(xhr) {
                console.log('حدث خطأ أثناء تحديث الصلاحية.');
            }
        });
    });
});






    </script>
@endsection
