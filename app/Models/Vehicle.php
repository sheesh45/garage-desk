<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'registration_number',
        'brand',
        'model',
        'year',
        'vehicle_type',
        'fuel_type',
        'engine_number',
        'chassis_number',
        'odometer',
        'insurance_expiry',
        'puc_expiry',
        'last_service_at',
        'next_service_due_at',
        'qr_code',
    ];

    protected function casts(): array
    {
        return [
            'insurance_expiry' => 'date',
            'puc_expiry' => 'date',
            'last_service_at' => 'date',
            'next_service_due_at' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function jobCards(): HasMany
    {
        return $this->hasMany(JobCard::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(ServiceReminder::class);
    }
}
