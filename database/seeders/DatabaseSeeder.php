<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Department;
use App\Models\MainAccount;
use App\Models\Plant;
use App\Models\SubAccount;
use App\Models\User;
use App\Models\UserPermission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
//                 $plants = [
//             [
//                 'code' => 'PLT-001',
//                 'name' => 'المصنع الرئيسي',
//                 'location' => 'اليمن, الصناعية الثانية',
//                 'area' => 50000,
//                 'establishment_date' => '2010-05-15',
//                 'status' => 'active',
//                 'employee_count' => 250,
//                 'annual_production_capacity' => 1000000,
//                 'description' => 'المصنع الرئيسي للشركة ويحتوي على أحدث خطوط الإنتاج',
//                 'facilities' => json_encode(['مختبر جودة', 'صالة تدريب', 'عيادة طبية']),
//   'created_by' => 1,
//                         'updated_by' => 1,            ],
//             [
//                 'code' => 'PLT-002',
//                 'name' => 'مصنع الخرج',
//                 'location' => 'الخرج، المنطقة الصناعية',
//                 'area' => 30000,
//                 'establishment_date' => '2015-11-20',
//                 'status' => 'active',
//                 'employee_count' => 150,
//                 'annual_production_capacity' => 750000,
//                 'description' => 'متخصص في الإنتاج الكمي للمنتجات الأساسية',
//                 'facilities' => json_encode(['مخازن تبريد', 'نظام أتمتة متكامل']),
//   'created_by' => 1,
//                         'updated_by' => 1,            ],
//             [
//                 'code' => 'PLT-003',
//                 'name' => 'مصنع عصر الجديد',
//                 'location' => 'صنعاء، المدينة الصناعية',
//                 'area' => 45000,
//                 'establishment_date' => '2022-03-10',
//                 'status' => 'under_construction',
//                 'employee_count' => 0,
//                 'annual_production_capacity' => 0,
//                 'description' => 'قيد الإنشاء، من المتوقع أن يكون الأكثر تطوراً',
//                 'facilities' => json_encode([]),
//   'created_by' => 1,
//                         'updated_by' => 1,            ]
//         ];

        // foreach ($plants as $plant) {
        //     Plant::create($plant);
        // }
//          $departments = [
//             [
//                 'code' => 'DEPT-001',
//                 'name' => 'الإنتاج الرئيسي',
//                 'plant_id' => 1,
//                 'manager_name' => 'أحمد محمد',
//                 'phone' => '0112345678',
//                 'email' => 'production@example.com',
//                 'type' => 'production',
//                 'employee_count' => 120,
//                 'budget' => 5000000,
//                 'establishment_date' => '2010-05-15',
//                 'description' => 'القسم الرئيسي للإنتاج في المصنع الرئيسي',
//                 'equipment' => json_encode(['خط تعبئة آلي', 'ماكينات تغليف', 'ناقلات']),
//   'created_by' => 1,
//                         'updated_by' => 1,            ],
//             [
//                 'code' => 'DEPT-002',
//                 'name' => 'مراقبة الجودة',
//                 'plant_id' => 1,
//                 'manager_name' => 'سارة عبدالله',
//                 'phone' => '0112345679',
//                 'email' => 'quality@example.com',
//                 'type' => 'quality',
//                 'employee_count' => 30,
//                 'budget' => 1500000,
//                 'establishment_date' => '2010-06-01',
//                 'description' => 'قسم متخصص في ضبط جودة المنتجات',
//                 'equipment' => json_encode(['أجهزة تحليل', 'مختبر كيميائي', 'أجهزة قياس']),
//   'created_by' => 1,
//                         'updated_by' => 1,            ],
//             [
//                 'code' => 'DEPT-003',
//                 'name' => 'الصيانة',
//                 'plant_id' => 1,
//                 'manager_name' => 'خالد سعيد',
//                 'phone' => '0112345680',
//                 'email' => 'maintenance@example.com',
//                 'type' => 'maintenance',
//                 'employee_count' => 25,
//                 'budget' => 2000000,
//                 'establishment_date' => '2010-06-15',
//                 'description' => 'قسم صيانة خطوط الإنتاج والمعدات',
//                 'equipment' => json_encode(['رافعات', 'أدوات كهربائية', 'أدوات قياس']),
//   'created_by' => 1,
//                         'updated_by' => 1,            ],
//             [
//                 'code' => 'DEPT-004',
//                 'name' => 'الإنتاج 1',
//                 'plant_id' => 2,
//                 'manager_name' => 'علي حسن',
//                 'phone' => '0123456789',
//                 'email' => 'production1@example.com',
//                 'type' => 'production',
//                 'employee_count' => 80,
//                 'budget' => 3500000,
//                 'establishment_date' => '2015-11-20',
//                 'description' => 'قسم الإنتاج الرئيسي في مصنع الخرج',
//                 'equipment' => json_encode(['خطوط تعبئة', 'ماكينات خياطة', 'ناقلات']),
//                 'created_by' => 1,
//                         'updated_by' => 1,

//             ]
//         ];

        // foreach ($departments as $department) {
        //     Department::create($department);
        // }


         User::create([
            'id' => 1,
            'name' => 'Fadl',
            'email' => 'fadl@example.com',
            'password' => 'qweasdzxc',
        ]);
         Currency::create([
            'currency_name' => 'يمني',
        ]);
        // MainAccount::create([
        //     'main_account_id' => 1,
        //     'Nature_account' => 'مدين',
        //     'AccountClass' => 4,
        //     'account_name' => '  حقوق الملكيه ' ,
        //     'typeAccount' => 3,
        //     'Type_migration' => 2,
        //     'User_id' => 1,

        // ]);
        // SubAccount::create([
        //     'sub_account_id'=>1,
        //     'sub_name'=>'راس المال',
        //     'main_id'=>1,
        //     'account_class'=>4,
        //     'type_account'=>3,
        //     'user_id' => 1,
        // ]);
        // SubAccount::create([
        //     'sub_account_id'=>2,
        //     'sub_name'=>'الارباح والخسائر',
        //     'main_id'=>1,
        //     'account_class'=>4,
        //     'type_account'=>3,
        //     'user_id' => 1,
        // ]);
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
            'النسخ الاحتياطي',
            'تحديث النظام',
            'الإعدادات الافتراضية',
            'الإعدادات',
            'قيد الارباح',
            'العملات',
            'ادارت المستخدمين',
            'الخصم والتحليل'
        ];
        // foreach($pages as $page){

        //     UserPermission::create([
        //         'Authority_Name'=>$page,
        //         'Readability'=>true,
        //         'Writing_ability'=>true,
        //         'Ability_modify'=>true,
        //         'Deletion_authority'=>true,
        //         'User_id'=>1,
        //     ]);
        // }
    }
}
