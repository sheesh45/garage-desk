<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceCatalog extends Model
{
    use HasFactory;

    protected $table = 'service_catalog';

    protected $fillable = [
        'name',
        'description',
        'estimated_minutes',
        'base_cost',
        'required_parts',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'required_parts' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function jobCards(): BelongsToMany
    {
        return $this->belongsToMany(JobCard::class, 'job_card_service')
            ->withPivot(['quantity', 'unit_price', 'total'])
            ->withTimestamps();
    }
}
