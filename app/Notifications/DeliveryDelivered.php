<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryDelivered extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Delivery $delivery)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre colis est livré!')
            ->greeting('Colis livré!')
            ->line('Votre box ' . $this->delivery->subscription->box->title . ' a été livrée avec succès.')
            ->line('Date de livraison: ' . optional($this->delivery->delivered_at)->format('d M Y H:i'))
            ->action('Laisser un avis', route('reviews.store'))
            ->line('Nous aimerions connaître votre avis sur cette box!');
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage(array(
            'delivery_id' => $this->delivery->id,
            'box_title' => $this->delivery->subscription->box->title,
            'message' => 'Votre colis contenant ' . $this->delivery->subscription->box->title . ' a été livré.',
        ));
    }
}
