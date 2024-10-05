<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_invoice_id',
        'Receipt_number',
         'Total_invoice', 
         'Paid',
         'Remaining',
         'Total_cost',
         'User_id',
         'Payment_type_id',
         'Currency_id',
         'Supplier_id',
         
        

        ];
}
