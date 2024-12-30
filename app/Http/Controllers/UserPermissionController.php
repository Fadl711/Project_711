<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users=User::all();
        $userpermissions=UserPermission::all();
        return view('settings.permission_users',compact('users','userpermissions'));
    }
    public function updatePermission(Request $request)
    {
        $userId = $request->input('id');
        $permission = $request->input('permission');
        $value = $request->input('value') === 'true' ? 1 : 0;
        $name = $request->input('selectValue');
        $validPermissions = ['Readability', 'Writing_ability', 'Deletion_authority', 'Ability_modify'];


        if (in_array($permission, $validPermissions)) {
            UserPermission::updateOrCreate(
                ['User_id' => $userId, 'Authority_Name' => $name],
                [$permission => (bool) $value]
            );

        }

        return response()->json(['success' => true]);
    }
    public function getPermissions(Request $request)
    {
        $userId = $request->get('id');
        $type = $request->get('type');
        $permissions=UserPermission::firstOrCreate(['User_id'=>$userId,'Authority_Name'=>$type]);

        return response()->json(['permissions' => $permissions]);
    }
    public function getUserPermissions(Request $request)
{
    $userId = $request->input('id');

    // جلب المستخدم بناءً على ID
    $user = User::find($userId);

    // جلب الصلاحيات الخاصة بالمستخدم

    // إعداد الاستجابة
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
    ]);
}


}
