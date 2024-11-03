<?php

namespace App\Enum;

enum TransactionType: int
{
    const PURCHASE = 1;          // عملية شراء
    const RETURN = 2;            // عملية مردود
    const INVENTORY_TRANSFER = 3; // تحويل مخزني

    /**
     * للحصول على القيم النصية
     */
    public function label(): string
{
    return match($this) {
            self::PURCHASE => 'عملية شراء',
            self::RETURN => 'عملية مردود',
            self::INVENTORY_TRANSFER => 'تحويل مخزني',
        };
    }
    }
    