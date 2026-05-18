<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessful extends Notification implements ShouldQueue
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
            ->subject('Paiement réussi - Facture #' . $this->invoice->invoice_number)
            ->greeting('Paiement confirmé!')
            ->line('Votre paiement de ' . number_format($this->invoice->amount, 2, ',', ' ') . '€ a été traité avec succès.')
            ->line('Numéro de facture: ' . $this->invoice->invoice_number)
            ->action('Voir la facture', route('invoices.show', $this->invoice))
            ->line('Merci pour votre confiance!');
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage(array(
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'amount' => $this->invoice->amount,
            'message' => 'Votre paiement de ' . number_format($this->invoice->amount, 2, ',', ' ') . '€ a été traité avec succès.',
        ));
    }
}
