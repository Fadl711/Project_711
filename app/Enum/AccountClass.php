<?php

namespace App\Enum;

enum AccountClass: int
{
    //
    case CUSTOMER = 1;  // الحالة الجديدة للعملاء
    case SUPPLIER =2;
    case STORE=3;
    case OTHER = 4;

    public function label(): string
{
    return match($this) {
        self::CUSTOMER => 'العيل',
        self::SUPPLIER =>'المورد',

        self::STORE=>'المخزن',
        self::OTHER   => 'أخرى',
       };
}
}