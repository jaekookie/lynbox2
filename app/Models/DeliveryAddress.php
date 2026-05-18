<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'first_name',
        'last_name',
        'phone',
        'street_address',
        'city',
        'postal_code',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullAddress(): string
    {
        return "{$this->street_address}, {$this->postal_code} {$this->city}, {$this->country}";
    }

    public function setAsDefault(): void
    {
        $this->user->deliveryAddresses()->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }
}
