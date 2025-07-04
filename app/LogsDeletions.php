<?php

namespace App;

use App\Models\Operation;
use Illuminate\Support\Facades\Auth;

trait LogsDeletions
{
    public static function bootLogsDeletions()
    {
        static::deleted(function ($model) {
            Operation::create([
                'user_id' => Auth::id(),
                'accounting_period_id' => 1, // ممكن تغيّرها حسب مشروعك
                'type' => 'حذف',
                'model_type' => self::translateModelName($model),
                'model_id' => $model->id,
                'message' => 'تم حذف ' . self::translateModelName($model) . ' برقم: ' . $model->id,
            ]);
        });
    }
    protected static function translateModelName($model)
    {
        return match (class_basename($model)) {
            'DailyEntrie' => ' قيد يومي',
                // أضف ما تشاء من نماذج هنا
            default => class_basename($model),
        };
    }
}
