<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\MainAccount;
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

         User::create([
            'id' => 1,
            'name' => 'Fadl',
            'email' => 'fadl@example.com',
            'password' => 'qweasdzxc',
        ]);
/*         Currency::create([
            'currency_name' => 'يمني',
        ]); */
        MainAccount::create([
            'main_account_id' => 1,
            'Nature_account' => 'مدين',
            'AccountClass' => 4,
            'account_name' => '  حقوق الملكيه ' ,
            'typeAccount' => 3,
            'Type_migration' => 2,
            'User_id' => 1,

        ]);
        SubAccount::create([
            'sub_account_id'=>1,
            'sub_name'=>'راس المال',
            'main_id'=>1,
            'account_class'=>4,
            'type_account'=>3,
            'user_id' => 1,
        ]);
        SubAccount::create([
            'sub_account_id'=>2,
            'sub_name'=>'الارباح والخسائر',
            'main_id'=>1,
            'account_class'=>4,
            'type_account'=>3,
            'user_id' => 1,
        ]);
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
        foreach($pages as $page){

            UserPermission::create([
                'Authority_Name'=>$page,
                'Readability'=>true,
                'Writing_ability'=>true,
                'Ability_modify'=>true,
                'Deletion_authority'=>true,
                'User_id'=>1,
            ]);
        }
    }
}
