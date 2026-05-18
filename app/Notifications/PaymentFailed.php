<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Invoice $invoice)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Paiement échoué - Facture #' . $this->invoice->invoice_number)
            ->greeting('Attention!')
            ->line('Le paiement de votre facture #' . $this->invoice->invoice_number . ' a échoué.')
            ->line('Montant: ' . number_format($this->invoice->amount, 2, ',', ' ') . '€')
            ->action('Réessayer le paiement', route('invoices.retry', $this->invoice))
            ->line('Veuillez vérifier votre moyen de paiement.');
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage(array(
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'amount' => $this->invoice->amount,
            'message' => 'Le paiement de votre facture a échoué.',
        ));
    }
}
