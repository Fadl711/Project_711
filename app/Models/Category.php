<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'categorie_id';

    protected $fillable = [
        'Categorie_name',
        'product_id',
        'Purchase_price',
        'Selling_price',
        'Quantityprice',

        'user_id',

    ];
}
