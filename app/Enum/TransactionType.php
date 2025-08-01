<?php

namespace App\Enum;

enum TransactionType: int
{
    case PURCHASE = 1;
    case RETURN = 2;
    case issue_my_warehouse =14 ;
    case receipt_my_warehouse = 15;
    case INVENTORY_TRANSFER = 3;
    case SALE = 4;
    case RETURN_SALE = 5;
    case SHOW_PRICE = 6;
    //   كمية الفتتاحية = 6; في جدول مشتريات
    //   عرض سعر = 6; في جدول مبيعات
    //   كمية مرحلة = 7; في جدول مشتريات
    //  شراء = 1;
    //  مردود مشتريات= 2;
    //  تحويل مخزني = 3;
    //  مبيعات = 4;
    //  مردود مبيعات = 5;
    case Settlement_of_excess_quantities = 8 ;
    case Settlement_of_missing_quantities = 9 ;
    case Damaged_quantity = 10;
    case SELL_CURRENCY = 11;
    case CURRENCY_CONVeERSION = 12;
    case BUY_CURRENCY = 13;

    case product_in = 16;
    case receipt = 17;
    case issue = 18;
    case consumption = 19;
    case waste_out =20;
    
    public function label(): string
    {
        return match($this) {
            self::PURCHASE => 'شراء',
            self::RETURN => 'مردود مشتريات',
            self::INVENTORY_TRANSFER => 'تحويل مخزني',
            self::issue_my_warehouse => 'صرف  مخزني',
            self::receipt_my_warehouse => 'توريد مخزني',
            self::product_in => 'إدخال منتج نهائي',
            self::receipt => 'استلام مواد خام',
            self::issue => 'صرف مواد للإنتاج',
            self::consumption => 'استهلاك',
            self::waste_out => 'إخراج مخلفات',
            
            self::SALE => 'مبيعات',
            self::RETURN_SALE => 'مردود مبيعات',
            self::SHOW_PRICE => 'عرض سعر',
            self::Settlement_of_excess_quantities => 'تسوية الكميات الزائدة  ',
            self::Settlement_of_missing_quantities => ' تسوية الكميات الناقصة  ',
            self::Damaged_quantity => 'تسوية الكميات التالفة  ', 
            self::SELL_CURRENCY => 'بيع عمله',
            self::CURRENCY_CONVeERSION => 'تحويل عمله',
            self::BUY_CURRENCY => 'شراء عمله',
         
            
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
    