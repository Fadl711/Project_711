@extends('layout')
@section('conm')




    <div class="container mx-auto px-4 py-4  max-w-4xl h-screen overflow-y-scroll">

        @foreach($users as $user)
        <div class="bg-white rounded-xl shadow-lg p-4 mb-8 w-1/2">

            <div class="flex items-center space-x-4 mb-6">
                <div class="space-y-4">
                    <div class="user-permissions" data-user-id="{{ $user->id }}">
                        <h3 class="text-lg font-medium text-gray-800">أسم المستخدم:{{ $user->name }}</h3>
                        <hr class="my-4">
                        <h1 class="text-lg">الصلاحيات:</h1>
                        <div class="space-y-3 bg-gray-200">
                                <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" aria-label="View permission" data-permission="Readability" @if(isset($userpermissions->where('User_id', $user->id)->first()->Readability) && ($userpermissions->where('User_id', $user->id)->first()->Readability == true || $userpermissions->where('User_id', $user->id)->first()->Readability == 1)) checked @else '' @endif>
                                    <span class="text-gray-700">صلاحية العرض</span>
                                </label>
                                <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" aria-label="Edit permission" data-permission="Ability_modify"  @if(isset($userpermissions->where('User_id', $user->id)->first()->Ability_modify) && ($userpermissions->where('User_id', $user->id)->first()->Ability_modify == true || $userpermissions->where('User_id', $user->id)->first()->Ability_modify == 1)) checked @else '' @endif>
                                    <span class="text-gray-700">صلاحية التعديل</span>
                                </label>
                                <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" aria-label="Write permission" data-permission="Writing_ability"@if(isset($userpermissions->where('User_id', $user->id)->first()->Writing_ability) && ($userpermissions->where('User_id', $user->id)->first()->Writing_ability == true || $userpermissions->where('User_id', $user->id)->first()->Writing_ability == 1)) checked @else '' @endif>
                                    <span class="text-gray-700">صلاحية الكتابة</span>
                                </label>
                                <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" aria-label="Admin permission" data-permission="Deletion_authority" @if(isset($userpermissions->where('User_id', $user->id)->first()->Deletion_authority) && ($userpermissions->where('User_id', $user->id)->first()->Deletion_authority == true || $userpermissions->where('User_id', $user->id)->first()->Deletion_authority == 1)) checked @else '' @endif>
                                    <span class="text-gray-700">صلاحية الإدارة</span>
                                </label>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
@endforeach
    </div>


    <script>
        $(document).ready(function() {
            $('input[type="checkbox"]').on('change', function() {
                var permission = $(this).data('permission');
                var isChecked = $(this).is(':checked');
                var userId = $(this).closest('.user-permissions').data('user-id');

                $.ajax({
                    url: '{{ route('update.permission') }}',
                    method: 'POST',
                    data: {
                        id: userId,
                        permission: permission,
                        value: isChecked,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Permission updated successfully');
                    },
                    error: function(xhr) {
                        console.log('An error occurred');
                    }
                });
            });
        });
    </script>
@endsection
