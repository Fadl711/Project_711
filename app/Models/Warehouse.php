<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;


    protected $fillable = [
        'Store_name',
        'Store_location',
        'Stock_level',
        'user_id',
    ];

}
