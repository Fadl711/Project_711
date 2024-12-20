<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournal extends Model
{
    use HasFactory;
  
    protected $table = 'general_journals';
    
    protected $primaryKey = 'page_id';
    protected $fillable = [
       
        'accounting_period_id',
      
    ];
    public $timestamps = true; 
    // تأكد من أن لديك created_at و updated_at في الجدول  
}
