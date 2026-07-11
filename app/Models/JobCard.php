<?php

namespace App\Models;

use App\Enums\JobCardPriority;
use App\Enums\JobCardStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_number',
        'customer_id',
        'vehicle_id',
        'mechanic_id',
        'created_by',
        'complaint',
        'estimated_delivery_at',
        'priority',
        'status',
        'diagnosis',
        'repair_notes',
        'time_taken_minutes',
        'inspection_notes',
        'delivery_notes',
        'check_in_at',
        'completed_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'estimated_delivery_at' => 'datetime',
            'check_in_at' => 'datetime',
            'completed_at' => 'datetime',
            'delivered_at' => 'datetime',
            'priority' => JobCardPriority::class,
            'status' => JobCardStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(Mechanic::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(ServiceCatalog::class, 'job_card_service')
            ->withPivot(['quantity', 'unit_price', 'total'])
            ->withTimestamps();
    }

    public function parts(): BelongsToMany
    {
        return $this->belongsToMany(InventoryItem::class, 'job_card_part')
            ->withPivot(['quantity', 'unit_price', 'total'])
            ->withTimestamps();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(JobCardPhoto::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
