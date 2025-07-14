<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentMaintenance extends Model
{
    use HasFactory;

    protected $primaryKey = 'maintenance_id';

    protected $fillable = [
        'line_id',
        'equipment_code',
        'maintenance_type',
        'maintenance_code',
        'description',
        'scheduled_date',
        'start_time',
        'end_time',
        'estimated_cost',
        'actual_cost',
        'technician_id',
        'parts_replaced',
        'status',
        'downtime_hours',
        'notes',
        'approved_by',
        'verified_by'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'downtime_hours' => 'decimal:2',
        'parts_replaced' => 'array'
    ];

    public function productionLine(): BelongsTo
    {
        return $this->belongsTo(ProductionLine::class, 'line_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(SubAccount::class, 'technician_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public static function getMaintenanceTypes(): array
    {
        return [
            'preventive' => 'وقائية',
            'corrective' => 'تصحيحية',
            'predictive' => 'تنبؤية',
            'breakdown' => 'عطل مفاجئ'
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'scheduled' => 'مجدولة',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتملة',
            'canceled' => 'ملغاة'
        ];
    }

    public function calculateDowntime(): float
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }
        return $this->start_time->diffInHours($this->end_time, true);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_date < now();
    }

    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'in_progress',
            'start_time' => now()
        ]);
    }

    public function markAsCompleted(float $actualCost, array $partsReplaced, string $notes): void
    {
        $this->update([
            'status' => 'completed',
            'end_time' => now(),
            'actual_cost' => $actualCost,
            'parts_replaced' => $partsReplaced,
            'downtime_hours' => $this->calculateDowntime(),
            'notes' => $notes
        ]);
    }
}