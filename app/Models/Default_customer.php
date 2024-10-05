<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Default_customer extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable =[
        'id',
        'subaccount_id',
    ];
}
