<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLedgeMain extends Model
{
    use HasFactory;

 // الأعمدة التي يمكن تعيينها بشكل جماعي
    protected $fillable = [
      'User_id',
      'Main_id',
      'accounting_id',
    ];

}
