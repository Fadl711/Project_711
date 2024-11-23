<?php

namespace App\Enum;

enum AccountClass: int
{
    //
    case CUSTOMER = 1;  // الحالة الجديدة للعملاء
    case SUPPLIER =2;
    case STORE=3;
    case OTHER = 4;
    case BOX = 5;

    public function label(): string
{
    return match($this) {
        self::CUSTOMER => 'العميل',
        self::SUPPLIER =>'المورد',

        self::STORE=>'المخزن',
        self::OTHER   => 'أخرى',
        self::BOX   => 'الصندوق',
       };
}
}
