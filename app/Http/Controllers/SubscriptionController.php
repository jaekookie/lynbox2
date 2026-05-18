<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\PauseSubscriptionRequest;
use App\Models\Subscription;
use App\Models\Box;
use App\Services\SubscriptionService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    private SubscriptionService $subscriptionService;
    private NotificationService $notificationService;

    public function __construct(
        SubscriptionService $subscriptionService,
        NotificationService $notificationService
    ) {
        $this->subscriptionService = $subscriptionService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $subscriptions = auth()->user()->subscriptions()
            ->with('box', 'box.category', 'deliveries')
            ->get();

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $box = Box::findOrFail($request->box_id);

        if (!$box->isInStock()) {
            return response()->json([
                'message' => 'Cette box n\'est pas disponible.',
            ], 422);
        }

        $subscription = $this->subscriptionService->createSubscription(auth()->user(), $box);

        return response()->json([
            'message' => 'Abonnement créé avec succès.',
            'subscription' => $subscription,
        ], 201);
    }

    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);

        $subscription->load('box', 'box.category', 'deliveries', 'invoices');

        return view('subscriptions.show', compact('subscription'));
    }

    public function pause(Subscription $subscription, PauseSubscriptionRequest $request)
    {
        $this->authorize('update', $subscription);

        $this->subscriptionService->pauseSubscription(
            $subscription,
            $request->pause_days
        );

        $this->notificationService->notifySubscriptionPaused($subscription);

        return response()->json([
            'message' => 'Abonnement mis en pause avec succès.',
            'subscription' => $subscription,
        ]);
    }

    public function reactivate(Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $this->subscriptionService->reactivateSubscription($subscription);
        $this->notificationService->notifySubscriptionReactivated($subscription);

        return response()->json([
            'message' => 'Abonnement réactivé avec succès.',
            'subscription' => $subscription,
        ]);
    }

    public function cancel(Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $this->subscriptionService->cancelSubscription($subscription);
        $this->notificationService->notifySubscriptionCancelled($subscription);

        return response()->json([
            'message' => 'Abonnement annulé avec succès.',
        ]);
    }

    public function modifyRenewalDate(Subscription $subscription, Request $request)
    {
        $this->authorize('update', $subscription);

        $validated = $request->validate([
            'new_date' => 'required|date|after:today',
        ]);

        $this->subscriptionService->modifyRenewalDate(
            $subscription,
            $validated['new_date']
        );

        return response()->json([
            'message' => 'Date de renouvellement modifiée avec succès.',
            'subscription' => $subscription,
        ]);
    }
}
