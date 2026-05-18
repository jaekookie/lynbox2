<?php

namespace App\Services;

use App\Models\User;
use App\Models\Invoice;

class LoyaltyService
{
    private const POINTS_PER_DOLLAR = 1;
    private const BONUS_MULTIPLIER = 1.5;

    public function awardPointsForPurchase(User $user, float $amount): int
    {
        $points = (int) ($amount * self::POINTS_PER_DOLLAR);

        $user->initializeLoyaltyPoints();
        $user->loyaltyPoints->addPoints($points);

        return $points;
    }

    public function awardBonusPoints(User $user, int $points): void
    {
        $user->initializeLoyaltyPoints();
        $bonusPoints = (int) ($points * self::BONUS_MULTIPLIER);
        $user->loyaltyPoints->addPoints($bonusPoints);
    }

    public function redeemPoints(User $user, int $pointsToRedeem): bool
    {
        $user->initializeLoyaltyPoints();
        return $user->loyaltyPoints->deductPoints($pointsToRedeem);
    }

    public function getMembershipTier(User $user): string
    {
        $totalSpent = $user->invoices()
            ->where('status', 'paid')
            ->sum('amount');

        return match (true) {
            $totalSpent >= 1000 => 'platinum',
            $totalSpent >= 500 => 'gold',
            $totalSpent >= 200 => 'silver',
            default => 'standard',
        };
    }

    public function upgradeToNextTier(User $user): bool
    {
        $currentTier = $user->membership_tier;
        $nextTier = $this->getNextTier($currentTier);

        if ($nextTier !== $currentTier) {
            $user->update(['membership_tier' => $nextTier]);
            return true;
        }

        return false;
    }

    private function getNextTier(string $currentTier): string
    {
        return match ($currentTier) {
            'standard' => 'silver',
            'silver' => 'gold',
            'gold' => 'platinum',
            'platinum' => 'platinum',
            default => 'standard',
        };
    }

    public function calculateDiscount(User $user): float
    {
        return match ($user->membership_tier) {
            'platinum' => 0.15,
            'gold' => 0.10,
            'silver' => 0.05,
            default => 0.0,
        };
    }

    public function getTierBenefits(string $tier): array
    {
        return match ($tier) {
            'standard' => [
                'points_multiplier' => 1,
                'free_shipping' => false,
                'priority_support' => false,
            ],
            'silver' => [
                'points_multiplier' => 1.25,
                'free_shipping' => false,
                'priority_support' => false,
            ],
            'gold' => [
                'points_multiplier' => 1.5,
                'free_shipping' => true,
                'priority_support' => true,
            ],
            'platinum' => [
                'points_multiplier' => 2,
                'free_shipping' => true,
                'priority_support' => true,
            ],
            default => [],
        };
    }
}
