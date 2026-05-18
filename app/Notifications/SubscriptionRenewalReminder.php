<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionRenewalReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Subscription $subscription, private int $daysUntilRenewal = 3)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Rappel: Renouvellement de votre abonnement')
            ->greeting('Rappel de renouvellement!')
            ->line('Votre abonnement à ' . $this->subscription->box->title . ' sera renouvelé dans ' . $this->daysUntilRenewal . ' jours.')
            ->line('Date de renouvellement: ' . $this->subscription->next_renewal_date->format('d M Y'))
            ->line('Montant: ' . number_format($this->subscription->current_price, 2, ',', ' ') . '€')
            ->action('Gérer l\'abonnement', route('subscriptions.show', $this->subscription))
            ->line('Vous pouvez mettre en pause ou annuler votre abonnement à tout moment.');
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage(array(
            'subscription_id' => $this->subscription->id,
            'box_title' => $this->subscription->box->title,
            'renewal_date' => $this->subscription->next_renewal_date,
            'message' => 'Votre abonnement à ' . $this->subscription->box->title . ' sera renouvelé dans ' . $this->daysUntilRenewal . ' jours.',
        ));
    }
}
