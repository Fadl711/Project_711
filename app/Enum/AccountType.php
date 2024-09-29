<?php

namespace App\Enum;

enum AccountType: int
{
    //
    case CURRENT_ASSETS =1;//  أصول متداولة
    case FIXED_ASSETS=2;// اصول ثابتة 

case LIABILITIES_OPPONENTS=3;
case EXPENSES=4;
case REVENUE=5;
public function label(): string
{
    return match($this) {
        self::CURRENT_ASSETS => 'أصول متداولة',
        self::FIXED_ASSETS => 'اصول ثابتة  ',
        self::LIABILITIES_OPPONENTS => 'حقوق الملكية/الخصوم',
        self::EXPENSES => 'المصروفات',
        self::REVENUE => 'الإيرادات',
    };
}
}
