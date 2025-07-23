<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Double_entry extends Model
{
    protected $table = 'double_entries';
    protected $fillable = [
        'account_debit_id',
        'Statement',
        'User_id',
        'currency_id',
    ];
    public function double_entries(){
        return $this->hasMany(DailyEntrie::class,'double_entry_id','id');
    }
}
