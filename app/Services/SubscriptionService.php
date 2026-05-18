<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Box;
use App\Models\Invoice;
use App\Models\Delivery;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SubscriptionService
{
    public function createSubscription(User $user, Box $box): Subscription
    {
        $nextRenewalDate = $this->calculateNextRenewalDate($box->billing_cycle);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'box_id' => $box->id,
            'status' => 'active',
            'next_renewal_date' => $nextRenewalDate,
            'current_price' => $box->price,
        ]);

        return $subscription;
    }

    public function pauseSubscription(Subscription $subscription, int $pauseDays = null): void
    {
        $subscription->pause();

        if ($pauseDays) {
            $resumeDate = now()->addDays($pauseDays);
            $subscription->update(['next_renewal_date' => $resumeDate]);
        }
    }

    public function reactivateSubscription(Subscription $subscription): void
    {
        $subscription->reactivate();
        $subscription->update([
            'next_renewal_date' => $this->calculateNextRenewalDate(
                $subscription->box->billing_cycle
            ),
        ]);
    }

    public function cancelSubscription(Subscription $subscription): void
    {
        $subscription->cancel();
    }

    public function processRenewal(Subscription $subscription): bool
    {
        if (!$subscription->isActive() || now()->isBefore($subscription->next_renewal_date)) {
            return false;
        }

        $invoice = $this->createInvoice($subscription);

        if ($this->processPayment($subscription, $invoice)) {
            $this->createDelivery($subscription);
            $this->updateNextRenewalDate($subscription);
            return true;
        }

        $invoice->markAsFailed();
        return false;
    }

    public function processPayment(Subscription $subscription, Invoice $invoice): bool
    {
        try {
            $user = $subscription->user;

            if (!$user->hasPaymentMethod()) {
                return false;
            }

            $user->charge(
                (int) ($invoice->amount * 100),
                $user->defaultPaymentMethod(),
                [
                    'description' => "Renouvellement subscription #{$subscription->id}",
                    'metadata' => [
                        'subscription_id' => $subscription->id,
                        'invoice_id' => $invoice->id,
                    ],
                ]
            );

            $invoice->markAsPaid();
            return true;
        } catch (\Exception $e) {
            \Log::error('Payment failed for subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function createInvoice(Subscription $subscription): Invoice
    {
        return Invoice::create([
            'subscription_id' => $subscription->id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'amount' => $subscription->current_price,
            'status' => 'pending',
        ]);
    }

    public function createDelivery(Subscription $subscription): Delivery
    {
        $estimatedDelivery = now()->addDays(7);

        $delivery = Delivery::create([
            'subscription_id' => $subscription->id,
            'tracking_number' => $this->generateTrackingNumber(),
            'status' => 'preparation',
            'delivery_address' => $this->getDeliveryAddress($subscription->user),
            'estimated_delivery' => $estimatedDelivery,
        ]);

        return $delivery;
    }

    public function updateNextRenewalDate(Subscription $subscription): void
    {
        $nextDate = $this->calculateNextRenewalDate($subscription->box->billing_cycle);
        $subscription->update(['next_renewal_date' => $nextDate]);
    }

    public function modifyRenewalDate(Subscription $subscription, Carbon $newDate): void
    {
        $subscription->update(['next_renewal_date' => $newDate]);
    }

    private function calculateNextRenewalDate(string $billingCycle): Carbon
    {
        return match ($billingCycle) {
            'monthly' => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            'yearly' => now()->addYear(),
            default => now()->addMonth(),
        };
    }

    private function generateInvoiceNumber(): string
    {
        return 'INV-' . now()->format('Y') . '-' . Str::random(8);
    }

    private function generateTrackingNumber(): string
    {
        return 'TRACK-' . Str::random(10);
    }

    private function getDeliveryAddress(User $user): string
    {
        $address = $user->deliveryAddresses()->where('is_default', true)->first();

        if ($address) {
            return $address->getFullAddress();
        }

        return $user->address ?? '';
    }
}
