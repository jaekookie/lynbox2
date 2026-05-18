<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\User;
use App\Notifications\SubscriptionRenewalReminder;
use App\Notifications\PaymentSuccessful;
use App\Notifications\DeliveryShipped;
use App\Notifications\DeliveryDelivered;
use App\Notifications\PaymentFailed;

class NotificationService
{
    public function notifyPaymentSuccessful(Invoice $invoice): void
    {
        $user = $invoice->subscription->user;
        $user->notify(new PaymentSuccessful($invoice));
    }

    public function notifyPaymentFailed(Invoice $invoice): void
    {
        $user = $invoice->subscription->user;
        $user->notify(new PaymentFailed($invoice));
    }

    public function notifyDeliveryShipped(Delivery $delivery): void
    {
        $user = $delivery->subscription->user;
        $user->notify(new DeliveryShipped($delivery));
    }

    public function notifyDeliveryDelivered(Delivery $delivery): void
    {
        $user = $delivery->subscription->user;
        $user->notify(new DeliveryDelivered($delivery));
    }

    public function notifyRenewalReminder(Subscription $subscription, int $daysUntilRenewal = 3): void
    {
        $user = $subscription->user;
        $user->notify(new SubscriptionRenewalReminder($subscription, $daysUntilRenewal));
    }

    public function notifySubscriptionPaused(Subscription $subscription): void
    {
        $user = $subscription->user;
        \Mail::send('emails.subscription-paused', [
            'user' => $user,
            'subscription' => $subscription,
        ], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Votre abonnement a été mis en pause');
        });
    }

    public function notifySubscriptionReactivated(Subscription $subscription): void
    {
        $user = $subscription->user;
        \Mail::send('emails.subscription-reactivated', [
            'user' => $user,
            'subscription' => $subscription,
        ], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Votre abonnement a été réactivé');
        });
    }

    public function notifySubscriptionCancelled(Subscription $subscription): void
    {
        $user = $subscription->user;
        \Mail::send('emails.subscription-cancelled', [
            'user' => $user,
            'subscription' => $subscription,
        ], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Votre abonnement a été annulé');
        });
    }
}
