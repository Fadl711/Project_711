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

        if ($permission == 'Readability') { // لاحظ الحرف الكبير
            UserPermission::updateOrCreate(
                ['User_id' => $userId],
                ['Readability' => (bool) $value] // تأكد من أن القيمة تكون منطقية
            );

        }
        if($permission == 'Writing_ability'){

            UserPermission::updateOrCreate(
                ['User_id' => $userId],
                ['Writing_ability' =>  (bool) $value]
            );
        }
        if($permission == 'Deletion_authority'){


            UserPermission::updateOrCreate(
                ['User_id' => $userId],
                ['Deletion_authority' => (bool) $value]
            );
        }
        if($permission == 'Ability_modify'){


            UserPermission::updateOrCreate(
                ['User_id' => $userId],
                ['Ability_modify' => (bool) $value]
            );
        }

        return response()->json(['success' => true]);
    }

}
