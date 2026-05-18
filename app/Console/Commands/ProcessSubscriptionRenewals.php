<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\SubscriptionService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessSubscriptionRenewals extends Command
{
    protected $signature = 'subscriptions:process-renewals';
    protected $description = 'Process due subscription renewals and handle payments';

    private SubscriptionService $subscriptionService;
    private NotificationService $notificationService;

    public function __construct(
        SubscriptionService $subscriptionService,
        NotificationService $notificationService
    ) {
        parent::__construct();
        $this->subscriptionService = $subscriptionService;
        $this->notificationService = $notificationService;
    }

    public function handle(): int
    {
        $dueSubscriptions = Subscription::where('status', 'active')
            ->where('next_renewal_date', '<=', now())
            ->with('user', 'box')
            ->get();

        $this->info("Processing {$dueSubscriptions->count()} due subscriptions...");

        $successCount = 0;
        $failureCount = 0;

        foreach ($dueSubscriptions as $subscription) {
            try {
                if ($this->subscriptionService->processRenewal($subscription)) {
                    $this->notificationService->notifyPaymentSuccessful($subscription->invoices()->latest()->firstOrFail());
                    $successCount++;
                    $this->line("✓ Renewed subscription #{$subscription->id} for {$subscription->user->email}");
                } else {
                    $this->notificationService->notifyPaymentFailed($subscription->invoices()->latest()->firstOrFail());
                    $failureCount++;
                    $this->warn("✗ Failed to renew subscription #{$subscription->id}");
                }
            } catch (\Exception $e) {
                $failureCount++;
                $this->error("Error processing subscription #{$subscription->id}: {$e->getMessage()}");
            }
        }

        $this->info("\nRenewal Summary:");
        $this->line("Success: {$successCount}");
        $this->line("Failed: {$failureCount}");

        return Command::SUCCESS;
    }
}
