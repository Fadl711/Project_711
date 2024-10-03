<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessData extends Model
{
    use HasFactory;
       protected $primaryKey = 'business_data_id';

    protected $fillable = [
        'business_data_id',
        'Company_Logo',
        'Company_Name',
        'Company_NameE',
        'Phone_Number',
        'Services',
        'ServicesE',
        'Company_Address',
        'Company_AddressE',
    ];
}
