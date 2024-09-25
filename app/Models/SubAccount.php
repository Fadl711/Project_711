<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    use HasFactory;
    protected $fillable = [
      'sub_name', 
        'Main_id',
      'debtor', 
      'creditor',
      'name_The_known',
      'Known_phone',
      'User_id',
      'Phone',
    //  'email',
    // 'address',
    // 'supplier',
    // 'customer',
  ];
}
