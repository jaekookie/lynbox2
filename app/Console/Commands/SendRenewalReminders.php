<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendRenewalReminders extends Command
{
    protected $signature = 'subscriptions:send-reminders {--days=3 : Days before renewal}';
    protected $description = 'Send renewal reminders for upcoming subscription renewals';

    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle(): int
    {
        $days = $this->option('days');
        $targetDate = now()->addDays($days);

        $subscriptions = Subscription::where('status', 'active')
            ->whereBetween('next_renewal_date', [
                $targetDate->startOfDay(),
                $targetDate->endOfDay()
            ])
            ->with('user', 'box')
            ->get();

        $this->info("Sending {$subscriptions->count()} renewal reminders ({$days} days before renewal)...");

        foreach ($subscriptions as $subscription) {
            try {
                $this->notificationService->notifyRenewalReminder($subscription, $days);
                $this->line("✓ Reminder sent for subscription #{$subscription->id}");
            } catch (\Exception $e) {
                $this->error("Error sending reminder for subscription #{$subscription->id}: {$e->getMessage()}");
            }
        }

        return Command::SUCCESS;
    }
}
