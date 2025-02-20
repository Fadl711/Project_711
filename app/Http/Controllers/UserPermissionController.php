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
        $users = User::all();
        return view('settings.permission_users', compact('users'));
    }

    public function updatePermission(Request $request)
    {
        try {
            $userId = $request->input('id');
            $permissionName = $request->input('permission');
            $value = $request->input('value');
            $page = $request->input('selectValue');

            // التحقق من صحة المدخلات
            if (!$userId || !$permissionName || !$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'جميع الحقول مطلوبة'
                ]);
            }

            // التحقق من صحة اسم الصلاحية
            $validPermissions = ['Readability', 'Writing_ability', 'Deletion_authority', 'Ability_modify'];
            if (!in_array($permissionName, $validPermissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'اسم الصلاحية غير صحيح'
                ]);
            }

            // تحويل القيمة إلى boolean
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);

            // البحث عن الصلاحية أو إنشاء واحدة جديدة
            $permission = UserPermission::where('User_id', $userId)
                ->where('Authority_Name', $page)
                ->first();

            if (!$permission) {
                $permission = new UserPermission();
                $permission->User_id = $userId;
                $permission->Authority_Name = $page;
            }

            // تحديث الصلاحية
            $permission->$permissionName = $value;
            $permission->save();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الصلاحية بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating permission: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الصلاحية'
            ]);
        }
    }

    public function getPermissions(Request $request)
    {
        try {
            $userId = $request->input('id');
            $permissions = UserPermission::where('User_id', $userId)->get();
            
            return response()->json([
                'permissions' => $permissions
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting permissions: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء جلب الصلاحيات'], 500);
        }
    }

    public function getUserPermissions(Request $request)
    {
        try {
            $userId = $request->input('id');
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json(['error' => 'المستخدم غير موجود'], 404);
            }

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting user permissions: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء جلب بيانات المستخدم'], 500);
        }
    }

    public function deletePermission(Request $request)
    {
        try {
            $permissionId = $request->input('permission_id');
            
            if (!$permissionId) {
                \Log::error('Permission ID not provided');
                return response()->json(['success' => false, 'message' => 'معرف الصلاحية غير موجود'], 400);
            }
            
            $permission = UserPermission::where('permission_id', $permissionId)->first();
            
            if (!$permission) {
                \Log::error('Permission not found with ID: ' . $permissionId);
                return response()->json(['success' => false, 'message' => 'الصلاحية غير موجودة'], 404);
            }
            
            $permission->delete();
            return response()->json(['success' => true, 'message' => 'تم حذف الصلاحية بنجاح']);
        } catch (\Exception $e) {
            \Log::error('Error deleting permission: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حذف الصلاحية'], 500);
        }
    }

    public function getUserPermissionsTable(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            
            if (!$userId) {
                return response()->json(['error' => 'معرف المستخدم مطلوب'], 400);
            }

            // استخدام معرف المستخدم المحدد بدلاً من المستخدم الحالي
            $permissions = UserPermission::where('User_id', $userId)->get();
            
            return view('components.user-permissions-table', [
                'permissions' => $permissions,
                'userId' => $userId
            ])->render();
        } catch (\Exception $e) {
            \Log::error('Error loading permissions table: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء تحميل جدول الصلاحيات'], 500);
        }
    }

    public function createPermission(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $page = $request->input('page');

            // التحقق من وجود الصلاحية
            $existingPermission = UserPermission::where('User_id', $userId)
                ->where('Authority_Name', $page)
                ->first();

            if ($existingPermission) {
                return response()->json([
                    'success' => false,
                    'message' => 'الصلاحية موجودة مسبقاً لهذا المستخدم'
                ]);
            }

            // إنشاء صلاحية جديدة
            $permission = UserPermission::create([
                'User_id' => $userId,
                'Authority_Name' => $page,
                'Readability' => false,
                'Writing_ability' => false,
                'Deletion_authority' => false,
                'Ability_modify' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الصلاحية بنجاح'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating permission: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الصلاحية'
            ], 500);
        }
    }

    public function grantAllPermissions(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            
            // قائمة بجميع الصفحات المتاحة
            $pages = [
                'الحسابات',
                'القيود',
                'السندات',
                'المبيعات',
                'الفواتير المبيعات',
                'المشتريات',
                'الفواتير المشتريات',
                'المنتجات',
                'سجلات الترحيل',
                'التقارير',
                'المردودات',
                'الإعدادات'
            ];

            foreach ($pages as $page) {
                // إنشاء أو تحديث الصلاحية
                UserPermission::updateOrCreate(
                    [
                        'User_id' => $userId,
                        'Authority_Name' => $page
                    ],
                    [
                        'Readability' => true,
                        'Writing_ability' => true,
                        'Deletion_authority' => true,
                        'Ability_modify' => true
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'تم منح جميع الصلاحيات بنجاح'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error granting all permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء منح الصلاحيات'
            ], 500);
        }
    }

    public function deleteAllPermissions(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            
            // حذف جميع الصلاحيات للمستخدم
            $deleted = UserPermission::where('User_id', $userId)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف جميع الصلاحيات بنجاح'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد صلاحيات لحذفها'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting all permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصلاحيات'
            ], 500);
        }
    }

}
