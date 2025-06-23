<?php

namespace App\Enum;

enum TransactionType: int
{
    case PURCHASE = 1;
    case RETURN = 2;
    case INVENTORY_TRANSFER = 3;
    case SALE = 4;
    case RETURN_SALE = 5;
    case SHOW_PRICE = 6;
    case BUY_CURRENCY = 7;
    case SELL_CURRENCY = 8;
    case CURRENCY_CONVeERSION = 9;


    public function label(): string
    {
        return match($this) {
            self::PURCHASE => 'شراء',
            self::RETURN => 'مردود مشتريات',
            self::INVENTORY_TRANSFER => 'تحويل مخزني',
            self::SALE => 'مبيعات',
            self::RETURN_SALE => 'مردود مبيعات',
            self::SHOW_PRICE => 'عرض سعر',
            self::BUY_CURRENCY => 'شراء عمله',
            self::SELL_CURRENCY => 'بيع عمله',
            self::CURRENCY_CONVeERSION => 'تحويل عمله',
            
        };

    }
    public static function fromValue(int $value): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return null; // إذا لم يتم العثور على تطابق
    }

    }
    