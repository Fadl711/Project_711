<?php

namespace App\Enum;

enum AccountClass: int
{
    //
    case CUSTOMER = 1;  // الحالة الجديدة للعملاء
    case SUPPLIER =2;
    case OTHER = 3;

    public function label(): string
{
    return match($this) {
        self::CUSTOMER => 'العملاء',
        self::SUPPLIER => 'الموردين',
        self::OTHER   => 'أخرى',
       };
}
}