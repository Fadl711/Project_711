<?php

namespace App\Http\Controllers\userPermission;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class userPermissionController extends Controller
{
    public function addUser(){
        User::create(['name' => 'read']);
        Permission::create(['name' => 'write']);
        Permission::create(['name' => 'delete']);
        Permission::create(['name' => 'modify']);

        // إنشاء دور وربطه بالصلاحيات
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(['read', 'write', 'delete', 'modify']);

        // إعطاء صلاحيات مباشرة لمستخدم
        $user = User::find(1);
        $user->assignRole('admin');
return redirect()->route('home.index');
    }
}
