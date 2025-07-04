<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Operation extends Model
{
    protected $fillable = [
        'accounting_period_id',
        'message',
        'type', //مثل حذف او تعديل
        'model_type', // مثل: Invoice, Product
        'model_id', // مثل: رقم الفاتورة او القيد
        'user_id',
        'is_seen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public static function createOpertion($modelId, $type, $model_type)
    {
        self::create([
            'user_id' => Auth::id(),
            'accounting_period_id' => 1, // لو عندك فترة محاسبية حالية
            'type' => $type,
            'model_type' =>  $model_type,
            'model_id' => $modelId,
            'message' => "تم {$type} {$model_type} رقم: {$modelId}",
            'is_seen' => 0,
        ]);
    }
}
