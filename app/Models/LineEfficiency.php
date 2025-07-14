<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineEfficiency extends Model
{
    use HasFactory;

    protected $fillable = [
        'line_id',
        'analysis_date',
        'time_period',
        'availability',
        'performance',
        'quality',
        'oee',
        'planned_downtime',
        'unplanned_downtime',
        'downtime_reasons',
        'improvement_actions',
        'analyst_id',
        'reviewed_by'
    ];

    protected $casts = [
        'analysis_date' => 'date',
        'downtime_reasons' => 'array'
    ];

    public function line()
    {
        return $this->belongsTo(ProductionLine::class, 'line_id');
    }

    public function analyst()
    {
        return $this->belongsTo(User::class, 'analyst_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}