@extends('layout')
@section('conm')
    <div class="container mx-auto px-4 py-4 max-w-4xl">
        <div class="bg-white rounded-xl shadow-lg p-4 mb-8">
            <div class="mb-4">
                <label for="userSelect" class="block text-sm font-medium text-gray-700 mb-2">اختر مستخدم</label>
                <select id="userSelect" class="w-full p-2 border rounded-md sel">
                    <option value="">اختر مستخدم</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="permissionsContainer" style="display: none;">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="space-y-4 w-full">
                        <div class="user-permissions">
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center gap-4">
                                    <h1 class="text-lg font-semibold">الصلاحيات:</h1>
                                    <button id="grantAllPermissions"
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm flex items-center gap-2">
                                        <i class="fas fa-check-double"></i>
                                        منح جميع الصلاحيات
                                    </button>
                                    <button id="deleteAllPermissions"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm flex items-center gap-2">
                                        <i class="fas fa-trash"></i>
                                        حذف جميع الصلاحيات
                                    </button>
                                </div>
                                <div>
                                    <label for="select1" class="ml-2">اختر الصفحة:</label>
                                    <select id="select1" class=" sel p-2 border rounded-md">
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
                                        {{-- <option value="المردودات">المردودات</option> --}}
                                        <option value="الإعدادات">الإعدادات</option>
                                        <option value="الخصم والتحليل">الخصم والتحليل</option>
                                        <option value="ادارت المستخدمين">ادارت المستخدمين </option>
                                        <option value="تحديث النظام">تحديث النظام  </option>
                                        <option value="النسخ الاحتياطي">النسخ الاحتياطي  </option>
                                        <option value="العملات">العملات  </option>
                                        <option value="قيد الارباح">قيد الارباح  </option>
                                        <option value="الإعدادات الافتراضية"> الإعدادات الافتراضية  </option>
                                    </select>
                                </div>
                            </div>

                            <div id="permissionsTableContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var selectedUserId = null;

            $('#userSelect').on('change', function() {
                var userId = $(this).val();

                // تنظيف الجدول والمتغيرات
                $('#permissionsTableContainer').empty();
                selectedUserId = userId;

                if (userId) {
                    $('#permissionsContainer').show();
                    loadUserPermissions(userId);
                } else {
                    $('#permissionsContainer').hide();
                }
            });

            $('#grantAllPermissions').on('click', function() {
                if (!selectedUserId) return;

                if (confirm('هل أنت متأكد من منح جميع الصلاحيات لهذا المستخدم؟')) {
                    $.ajax({
                        url: '{{ route('grant.all.permissions') }}',
                        method: 'POST',
                        data: {
                            user_id: selectedUserId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                loadUserPermissionsTable(selectedUserId);
                                alert('تم منح جميع الصلاحيات بنجاح');
                            } else {
                                alert(response.message || 'حدث خطأ أثناء منح الصلاحيات');
                            }
                        },
                        error: function() {
                            alert('حدث خطأ أثناء منح الصلاحيات');
                        }
                    });
                }
            });

            $('#deleteAllPermissions').on('click', function() {
                if (!selectedUserId) return;

                if (confirm('هل أنت متأكد من حذف جميع الصلاحيات لهذا المستخدم؟')) {
                    $.ajax({
                        url: '{{ route('delete.all.permissions') }}',
                        method: 'POST',
                        data: {
                            user_id: selectedUserId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                loadUserPermissionsTable(selectedUserId);
                                alert('تم حذف جميع الصلاحيات بنجاح');
                            } else {
                                alert(response.message || 'حدث خطأ أثناء حذف الصلاحيات');
                            }
                        },
                        error: function() {
                            alert('حدث خطأ أثناء حذف الصلاحيات');
                        }
                    });
                }
            });

            $('#select1').on('change', function() {
                var selectedPage = $(this).val();
                if (!selectedUserId || !selectedPage) return;

                $.ajax({
                    url: '{{ route('create.permission') }}',
                    method: 'POST',
                    data: {
                        user_id: selectedUserId,
                        page: selectedPage,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            loadUserPermissionsTable(selectedUserId);
                            $('#select1').val('');
                        } else {
                            alert(response.message || 'حدث خطأ أثناء إضافة الصلاحية');
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء إضافة الصلاحية');
                    }
                });
            });

            function loadUserPermissions(userId) {
                if (!userId || userId !== selectedUserId) return;

                $.ajax({
                    url: '{{ route('get.user.permissions') }}',
                    method: 'POST',
                    data: {
                        id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (userId === selectedUserId) {
                            loadUserPermissionsTable(userId);
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء تحميل بيانات المستخدم');
                    }
                });
            }

            function loadUserPermissionsTable(userId) {
                if (!userId || userId !== selectedUserId) return;

                $.ajax({
                    url: '{{ route('get.user.permissions.table') }}',
                    method: 'POST',
                    data: {
                        user_id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (userId === selectedUserId) {
                            $('#permissionsTableContainer').empty().html(response);
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء تحميل جدول الصلاحيات');
                    }
                });
            }
        });
    </script>
@endsection
