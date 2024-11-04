<?php

namespace App\Enum;

enum TransactionType: int
{
    case PURCHASE = 1;
    case RETURN = 2;
    case INVENTORY_TRANSFER = 3;

    public function label(): string
    {
        return match($this) {
            self::PURCHASE => 'عملية شراء',
            self::RETURN => 'عملية مردود',
            self::INVENTORY_TRANSFER => 'تحويل مخزني',
        };
    }
    }
    