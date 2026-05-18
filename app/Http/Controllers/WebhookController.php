<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Stripe\Event;
use Stripe\Stripe;

class WebhookController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handleStripeWebhook(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('cashier.webhook.secret');

        try {
            $event = Event::constructFrom(json_decode($payload, true));
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        try {
            $event = Event::retrieveObject(
                json_decode($payload)
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve event'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
            case 'charge.dispute.created':
                $this->handleChargeDisputeCreated($event->data->object);
                break;
        }

        return response()->json(['success' => true]);
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        $metadata = $paymentIntent->metadata;

        if (isset($metadata->invoice_id)) {
            $invoice = Invoice::findOrFail($metadata->invoice_id);
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'stripe_invoice_id' => $paymentIntent->id,
            ]);

            $this->notificationService->notifyPaymentSuccessful($invoice);
        }
    }

    private function handlePaymentIntentFailed($paymentIntent)
    {
        $metadata = $paymentIntent->metadata;

        if (isset($metadata->invoice_id)) {
            $invoice = Invoice::findOrFail($metadata->invoice_id);
            $invoice->markAsFailed();

            $this->notificationService->notifyPaymentFailed($invoice);
        }
    }

    private function handleChargeDisputeCreated($dispute)
    {
        \Log::error('Stripe dispute created', [
            'dispute_id' => $dispute->id,
            'amount' => $dispute->amount,
        ]);
    }
}
