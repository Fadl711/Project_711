<?php

namespace App\Enum;

enum PaymentType: int
{
    case CASH = 1;       // نقداً
    case CREDIT = 2;     // أجل
    case BANK_TRANSFER = 3; // تحويل بنكي
    case CHEQUE = 4;     // شيك

    public function label(): string
    {
        return match($this) {
            self::CASH => 'نقداً',
            self::CREDIT => 'أجل',
            self::BANK_TRANSFER => 'تحويل بنكي',
            self::CHEQUE => 'شيك',
        };
    }
    
}
