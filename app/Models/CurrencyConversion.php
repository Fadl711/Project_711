<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyConversion extends Model
{
      protected $table = 'currency_conversions';

    protected $fillable=[
        "user_id",

    ];
    //
}
