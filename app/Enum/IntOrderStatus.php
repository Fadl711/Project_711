<?php

namespace App\Enum;

enum IntOrderStatus: int
{
    case FINANCAL_CENTER_LIST=1; // قائمة المركز المالي
    
    case INCOME_STATEMENT=2; //قائمة الدخل
  


public function label(): string
{
    return match($this) {
        self::FINANCAL_CENTER_LIST => 'قائمة المركز المالي',
        self::INCOME_STATEMENT => 'قائمة الدخل',
     
    };
}


}
