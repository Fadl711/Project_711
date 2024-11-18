<?php

namespace App\Enum;

enum TransactionType: int
{
    case PURCHASE = 1;
    case RETURN = 2;
    case INVENTORY_TRANSFER = 3;
    case RETURN_SALE = 4;

    public function label(): string
    {
        return match($this) {
            self::PURCHASE => 'شراء',
            self::RETURN => 'مردود المشتريات',
            self::INVENTORY_TRANSFER => 'تحويل مخزني',
            self::RETURN_SALE => 'مردود المبيعات',
        };
    }
    }
    