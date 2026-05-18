<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryShipped extends Notification implements ShouldQueue
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
            ->subject('Votre colis a été expédié - ' . $this->delivery->tracking_number)
            ->greeting('Votre colis est en route!')
            ->line('Votre box ' . $this->delivery->subscription->box->title . ' a été expédiée.')
            ->line('Numéro de suivi: ' . $this->delivery->tracking_number)
            ->line('Livraison estimée le: ' . optional($this->delivery->estimated_delivery)->format('d M Y'))
            ->action('Suivre mon colis', route('deliveries.track', $this->delivery))
            ->line('Merci de votre patience!');
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage(array(
            'delivery_id' => $this->delivery->id,
            'tracking_number' => $this->delivery->tracking_number,
            'box_title' => $this->delivery->subscription->box->title,
            'message' => 'Votre colis a été expédié avec le numéro ' . $this->delivery->tracking_number,
        ));
    }
}
