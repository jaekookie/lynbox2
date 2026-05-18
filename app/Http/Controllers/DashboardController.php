<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $user->load([
            'subscriptions' => function ($query) {
                $query->with('box', 'box.category')->orderByDesc('updated_at');
            },
            'loyaltyPoints',
        ]);

        $activeSubscriptions = $user->subscriptions()
            ->where('status', 'active')
            ->count();

        $totalBoxesReceived = $user->subscriptions()
            ->with('deliveries')
            ->get()
            ->flatMap(fn ($sub) => $sub->deliveries)
            ->filter(fn ($delivery) => $delivery->isDelivered())
            ->count();

        $latestDelivery = $user->subscriptions()
            ->with('deliveries')
            ->get()
            ->flatMap(fn ($sub) => $sub->deliveries)
            ->sortByDesc('created_at')
            ->first();

        return view('dashboard', [
            'user' => $user,
            'activeSubscriptions' => $activeSubscriptions,
            'totalBoxesReceived' => $totalBoxesReceived,
            'latestDelivery' => $latestDelivery,
        ]);
    }
}
