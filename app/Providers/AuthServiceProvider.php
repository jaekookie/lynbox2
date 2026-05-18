<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Models\Delivery;
use App\Models\Review;
use App\Models\DeliveryAddress;
use App\Models\Invoice;
use App\Policies\SubscriptionPolicy;
use App\Policies\DeliveryPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\DeliveryAddressPolicy;
use App\Policies\InvoicePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Subscription::class => SubscriptionPolicy::class,
        Delivery::class => DeliveryPolicy::class,
        Review::class => ReviewPolicy::class,
        DeliveryAddress::class => DeliveryAddressPolicy::class,
        Invoice::class => InvoicePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
