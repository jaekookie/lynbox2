<?php

namespace App\Services;

use App\Models\User;
use App\Models\Invoice;

class PaymentService
{
    public function createPaymentMethod(User $user, string $paymentMethodId): bool
    {
        try {
            $user->addPaymentMethod($paymentMethodId);
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to add payment method', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function deletePaymentMethod(User $user, string $paymentMethodId): bool
    {
        try {
            $user->deletePaymentMethod($paymentMethodId);
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to delete payment method', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function setDefaultPaymentMethod(User $user, string $paymentMethodId): bool
    {
        try {
            $user->updateDefaultPaymentMethod($paymentMethodId);
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to set default payment method', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function retryFailedInvoice(Invoice $invoice): bool
    {
        $subscription = $invoice->subscription;
        $paymentService = new self();

        try {
            $subscription->user->charge(
                (int) ($invoice->amount * 100),
                $subscription->user->defaultPaymentMethod(),
                [
                    'description' => "Retry payment for invoice #{$invoice->invoice_number}",
                    'metadata' => [
                        'invoice_id' => $invoice->id,
                        'subscription_id' => $subscription->id,
                    ],
                ]
            );

            $invoice->markAsPaid();
            return true;
        } catch (\Exception $e) {
            \Log::error('Payment retry failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function refundInvoice(Invoice $invoice): bool
    {
        if (!$invoice->isPaid()) {
            return false;
        }

        try {
            if ($invoice->stripe_invoice_id) {
                \Stripe\Refund::create([
                    'invoice' => $invoice->stripe_invoice_id,
                ]);
            }

            $invoice->refund();
            return true;
        } catch (\Exception $e) {
            \Log::error('Refund failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
