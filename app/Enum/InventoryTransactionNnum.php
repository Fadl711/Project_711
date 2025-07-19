<?php

namespace App\Enum;

enum InventoryTransactionNnum: int
{
    //

    // case PURCHASE = 1;
    // case RETURN = 2;
    // case INVENTORY_TRANSFER = 3;
    // case SALE = 4;
    // case RETURN_SALE = 5;
    // case SHOW_PRICE = 6;
    //   كمية الفتتاحية = 6; في جدول مشتريات
    //   عرض سعر = 6; في جدول مبيعات
    //   كمية مرحلة = 7; في جدول مشتريات
    //  شراء = 1;
    // مردود مشتريات= 2;
    //  تحويل مخزني = 3;
    //  مبيعات = 4;
    //  مردود مبيعات = 5;

    //  في جدول حركة المخازن 'receipt',       // استلام مواد خام
    //   في جدول  حركة المخازن 'issue',         // صرف مواد للإنتاج
    //  في جدول  حركة المخازن    'return',        // إرجاع فائض
    //  في جدول  حركة المخازن    'product_in',    // إدخال منتج نهائي
    //    في جدول  حركة المخازن  'waste_out' ,     // إخراج مخلفات
    //   في جدول  حركة المخازن   'consumption'    // استهلاك

    case product_in = 4;
    case receipt = 5;
    case issue = 6;
    case consumption = 7;
    case waste_out =8;
    case Settlement_of_excess_quantities = 9;
    case Settlement_of_missing_quantities = 10;
    case Damaged_quantity = 11;
    case issue_my_warehouse =12 ;
    case receipt_my_warehouse = 13;
  

    public function label(): string
    {
        return match($this) {
            // self::PURCHASE => 'شراء',
            // self::RETURN => 'مردود مشتريات',
            // self::INVENTORY_TRANSFER => 'تحويل مخزني',
            // self::SALE => 'مبيعات',
            // self::RETURN_SALE => 'مردود مبيعات',
            // self::SHOW_PRICE => 'عرض سعر',
            self::issue_my_warehouse => 'صرف  مخزني',
            self::receipt_my_warehouse => 'توريد مخزني',
            self::product_in => 'إدخال منتج نهائي',
            self::receipt => 'استلام مواد خام',
            self::issue => 'صرف مواد للإنتاج',
            self::consumption => 'استهلاك',
            self::waste_out => 'إخراج مخلفات',
            self::Settlement_of_excess_quantities => 'تسوية الكميات الزائدة  ',
            self::Settlement_of_missing_quantities => ' تسوية الكميات الناقصة  ',
            self::Damaged_quantity => 'تسوية الكميات التالفة  ', 
      
            
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
    