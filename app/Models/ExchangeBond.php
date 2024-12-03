<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeBond extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_type',
        'Main_debit_account_id',
        'Debit_sub_account_id',
        'Amount_debit',
        'accounting_period_id',
        'transaction_type',

        'Main_Credit_account_id',
        'Credit_sub_account_id',
        'Statement',
        'Currency_id',
        'User_id',
    ];
}
