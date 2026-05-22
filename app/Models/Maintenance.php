<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    public const TYPE_PREVENTIVE = 'Preventive Maintenance';
    public const TYPE_REPAIR = 'Repair';
    public const TYPE_INSPECTION = 'Inspection';
    public const TYPE_CLEANING = 'Cleaning';
    public const TYPE_SOFTWARE_UPDATE = 'Software Update';
    public const TYPE_HARDWARE_REPLACEMENT = 'Hardware Replacement';
    public const TYPE_EMERGENCY_SERVICE = 'Emergency Service';

    public const STATUS_PENDING = 'Pending';
    public const STATUS_ONGOING = 'Ongoing';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_CANCELLED = 'Cancelled';

    protected $fillable = [
        'company_id',
        'organization_id',
        'admin_id',
        'maintenance_type',
        'status',
        'issue_reported',
        'action_taken',
        'parts_replaced',
        'cost',
        'printer_status',
        'coin_acceptor_status',
        'paper_stock',
        'ink_status',
        'network_status',
        'performed_at',
        'next_maintenance_at',
        'notes',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'organization_id' => 'integer',
        'admin_id' => 'integer',
        'cost' => 'integer',
        'performed_at' => 'datetime',
        'next_maintenance_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
