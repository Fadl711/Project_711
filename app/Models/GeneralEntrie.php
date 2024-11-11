<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralEntrie extends Model
{
    use HasFactory;
    protected $table = 'general_entries';

    // تحديد المفتاح الأساسي
    protected $primaryKey = 'id';

    // إذا كان المفتاح الأساسي غير متزايد تلقائيًا، يمكنك تحديد هذا:
    // public $incrementing = false;

    // تحديد نوع المفتاح الأساسي

    // تحديد الحقول التي يمكن ملؤها (Mass Assignable)
    protected $fillable = [
        'sub_id',
        'Main_id',
        'Daily_entry_id',
        'Daily_Page_id',
        'User_id',
        'General_ledger_page_number_id',
        'accounting_period_id',
        'entry_type',
        'amount',
        'currency',
        'description',
        'entry_date',
        'status',
        'typeAccount',
        'Invoice_type',
        'Invoice_id',
        'Currency_name',
    ];
}