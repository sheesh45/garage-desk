<?php

namespace App\Models;

use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'job_card_id',
        'type',
        'quantity',
        'reference',
        'notes',
        'moved_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => StockMovementType::class,
            'moved_at' => 'datetime',
        ];
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function jobCard(): BelongsTo
    {
        return $this->belongsTo(JobCard::class);
    }
}
