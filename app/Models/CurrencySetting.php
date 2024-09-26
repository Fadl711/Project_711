<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencySetting extends Model
{
    use HasFactory;
    // In your CurrencySetting model
protected $primaryKey = 'currency_settings_id';
    protected $fillable =[
        'currency_settings_id',
        'Currency_id',
    ];
}
