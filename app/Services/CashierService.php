<?php

namespace App\Services;

use App\Models\User;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;

class CashierService
{
    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    public function createCustomer(User $user): string
    {
        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        return $customer->id;
    }

    public function createPaymentIntent(User $user, int $amount, string $currency = 'eur'): array
    {
        $intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $user->stripe_id,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        return [
            'client_secret' => $intent->client_secret,
            'intent_id' => $intent->id,
        ];
    }

    public function attachPaymentMethod(User $user, string $paymentMethodId): void
    {
        $customer = Customer::retrieve($user->stripe_id);
        $customer->default_source = $paymentMethodId;
        $customer->save();
    }

    public function chargeCustomer(User $user, int $amount, string $description = ''): bool
    {
        try {
            $intent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'customer' => $user->stripe_id,
                'description' => $description,
                'confirm' => true,
                'off_session' => true,
            ]);

            return $intent->status === 'succeeded';
        } catch (\Exception $e) {
            \Log::error('Stripe charge failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
