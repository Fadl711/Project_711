<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    use HasFactory;
    protected $fillable = ['Main_id', 'sub_name', 'debtor', 'creditor','name_The_known','Known_phone','User_id','Phone'];

    // public function invoice()
    // {
    //     return $this->belongsTo(MainAccount::class);
    // }
}
