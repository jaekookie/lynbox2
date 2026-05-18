<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyPoints extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'total_earned',
        'total_redeemed',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addPoints(int $points): void
    {
        $this->increment('balance', $points);
        $this->increment('total_earned', $points);
    }

    public function deductPoints(int $points): bool
    {
        if ($this->balance >= $points) {
            $this->decrement('balance', $points);
            $this->increment('total_redeemed', $points);
            return true;
        }
        return false;
    }

    public function getAvailableRewards(): int
    {
        return intval($this->balance / 10);
    }
}
