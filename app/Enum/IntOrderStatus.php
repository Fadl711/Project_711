<?php

namespace App\Enum;

enum IntOrderStatus: int
{
    case FINANCAL_CENTER_LIST=1; // قائمة المركز المالي
    
    case INCOME_STATEMENT=2; //قائمة الدخل.
    case ASSETS=3;
    case LIABILITIES_OPPONENTS=4;
    case EXPENSES=5;
    case REVENUE=6;
     


}
