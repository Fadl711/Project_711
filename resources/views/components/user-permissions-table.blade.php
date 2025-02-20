@props(['permissions', 'userId'])

@php
    $permissions = $permissions ?? collect();
    $userId = $userId ?? null;
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="bg-white rounded-lg shadow-md p-4 mb-4">
    <h3 class="text-lg font-semibold mb-4">صلاحيات المستخدم</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-right">اسم الصفحة</th>
                    <th class="px-4 py-2 text-center">القراءة</th>
                    <th class="px-4 py-2 text-center">الكتابة</th>
                    <th class="px-4 py-2 text-center">الحذف</th>
                    <th class="px-4 py-2 text-center">التعديل</th>
                    <th class="px-4 py-2 text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                    <tr class="border-b hover:bg-gray-50" id="permission-row-{{ $permission->permission_id }}">
                        <td class="px-4 py-2">{{ $permission->Authority_Name }}</td>
                        <td class="px-4 py-2 text-center">
                            <input type="checkbox" 
                                class="permission-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                data-permission="Readability"
                                data-user="{{ $userId }}"
                                data-page="{{ $permission->Authority_Name }}"
                                {{ $permission->Readability ? 'checked' : '' }}>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <input type="checkbox" 
                                class="permission-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                data-permission="Writing_ability"
                                data-user="{{ $userId }}"
                                data-page="{{ $permission->Authority_Name }}"
                                {{ $permission->Writing_ability ? 'checked' : '' }}>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <input type="checkbox" 
                                class="permission-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                data-permission="Deletion_authority"
                                data-user="{{ $userId }}"
                                data-page="{{ $permission->Authority_Name }}"
                                {{ $permission->Deletion_authority ? 'checked' : '' }}>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <input type="checkbox" 
                                class="permission-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                data-permission="Ability_modify"
                                data-user="{{ $userId }}"
                                data-page="{{ $permission->Authority_Name }}"
                                {{ $permission->Ability_modify ? 'checked' : '' }}>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button 
                                onclick="deletePermission('{{ $permission->permission_id }}')"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    // تحديث الصلاحيات عند تغيير checkbox
    $(document).on('change', '.permission-checkbox', function() {
        var checkbox = $(this);
        var permission = checkbox.data('permission');
        var userId = checkbox.data('user');
        var page = checkbox.data('page');
        var isChecked = checkbox.is(':checked');

        $.ajax({
            url: '{{ route('update.permission') }}',
            method: 'POST',
            data: {
                id: userId,
                permission: permission,
                value: isChecked,
                selectValue: page,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (!response.success) {
                    // إذا فشل التحديث، إرجاع حالة الـ checkbox
                    checkbox.prop('checked', !isChecked);
                    alert(response.message || 'حدث خطأ أثناء تحديث الصلاحية');
                }
            },
            error: function() {
                // في حالة الخطأ، إرجاع حالة الـ checkbox
                checkbox.prop('checked', !isChecked);
                alert('حدث خطأ أثناء تحديث الصلاحية');
            }
        });
    });
});

function deletePermission(permissionId) {
    if (!permissionId) {
        alert('معرف الصلاحية غير صحيح');
        return;
    }

    if (confirm('هل أنت متأكد من حذف هذه الصلاحية؟')) {
        $.ajax({
            method: 'POST',
            url: '{{ route('delete.user.permission') }}',
            data: {
                _token: '{{ csrf_token() }}',
                permission_id: permissionId
            },
            success: function(response) {
                if (response.success) {
                    $('#permission-row-' + permissionId).fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    alert(response.message || 'حدث خطأ أثناء حذف الصلاحية');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                var errorMessage = 'حدث خطأ أثناء حذف الصلاحية';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    }
}
</script>
