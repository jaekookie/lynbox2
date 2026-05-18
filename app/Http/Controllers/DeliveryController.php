<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Subscription;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = auth()->user()->subscriptions()
            ->with('deliveries')
            ->get()
            ->flatMap(fn ($sub) => $sub->deliveries);

        return view('deliveries.index', compact('deliveries'));
    }

    public function show(Delivery $delivery)
    {
        $this->authorize('view', $delivery);

        $delivery->load('subscription.user', 'subscription.box');

        return view('deliveries.show', compact('delivery'));
    }

    public function track(Delivery $delivery)
    {
        $this->authorize('view', $delivery);

        return response()->json([
            'delivery' => $delivery,
            'progress' => $delivery->progressPercentage(),
            'status_label' => $delivery->getStatusLabel(),
        ]);
    }
}
