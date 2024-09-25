<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'main_account_id',
        'Nature_account',
         'account_name', 
         'typeAccount',
         'User_id',
         'Type_migration',
        ];
        
 


}
