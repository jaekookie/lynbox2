<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'tracking_number',
        'status',
        'shipped_at',
        'delivered_at',
        'delivery_address',
        'estimated_delivery',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'estimated_delivery' => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isPreparing(): bool
    {
        return $this->status === 'preparation';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    public function progressPercentage(): int
    {
        return match ($this->status) {
            'preparation' => 25,
            'shipped' => 66,
            'delivered' => 100,
            'returned' => 0,
            default => 0,
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'preparation' => 'Préparation',
            'shipped' => 'Expédié',
            'delivered' => 'Livré',
            'returned' => 'Retourné',
            default => 'Inconnu',
        };
    }
}
