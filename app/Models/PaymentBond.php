<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentBond extends Model
{
    use HasFactory;
    protected $table = 'payment_bonds';
    protected $primaryKey = 'payment_bond_id';

    protected $fillable = [
        'Main_debit_account_id',
        'Debit_sub_account_id',
        'Amount_debit',
        'Main_Credit_account_id',
        'Credit_sub_account_id',
        'Statement',
        'Currency_id',
        'User_id',
    ];
}
