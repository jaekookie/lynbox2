<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Box;
use App\Models\Subscription;
use App\Models\Delivery;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $demoUser = User::where('email', 'demo@lynbox.com')->first();
        $premium = User::where('email', 'premium@lynbox.com')->first();
        
        if (!$demoUser || !$premium) return;

        // Créer des abonnements pour l'utilisateur de démonstration
        $beautyBox = Box::where('title', 'Summer Glow Beauty Box')->first();
        $foodBox = Box::where('title', 'Gourmet Food Box')->first();

        // Abonnement actif à Beauty Box
        if ($beautyBox) {
            $sub1 = Subscription::create([
                'user_id' => $demoUser->id,
                'box_id' => $beautyBox->id,
                'status' => 'active',
                'next_renewal_date' => now()->addMonths(1),
                'current_price' => $beautyBox->price,
                'stripe_subscription_id' => 'sub_test_' . uniqid(),
            ]);

            // Créer des livraisons
            Delivery::create([
                'subscription_id' => $sub1->id,
                'status' => 'delivered',
                'tracking_number' => 'TRACK' . str_pad($sub1->id, 8, '0', STR_PAD_LEFT),
                'estimated_delivery' => now()->subDays(5),
                'delivered_at' => now()->subDays(5),
            ]);

            Delivery::create([
                'subscription_id' => $sub1->id,
                'status' => 'shipped',
                'tracking_number' => 'TRACK' . str_pad($sub1->id + 1000, 8, '0', STR_PAD_LEFT),
                'estimated_delivery' => now()->addDays(3),
            ]);

            // Créer des factures
            Invoice::create([
                'subscription_id' => $sub1->id,
                'amount' => $beautyBox->price,
                'status' => 'paid',
                'stripe_invoice_id' => 'inv_test_' . uniqid(),
                'paid_at' => now()->subDays(10),
                'invoice_number' => 'INV-' . str_pad($sub1->id, 6, '0', STR_PAD_LEFT),
            ]);

            Invoice::create([
                'subscription_id' => $sub1->id,
                'amount' => $beautyBox->price,
                'status' => 'paid',
                'stripe_invoice_id' => 'inv_test_' . uniqid(),
                'paid_at' => now()->subDays(40),
                'invoice_number' => 'INV-' . str_pad($sub1->id + 100, 6, '0', STR_PAD_LEFT),
            ]);
        }

        // Abonnement en pause à Food Box
        if ($foodBox) {
            $sub2 = Subscription::create([
                'user_id' => $demoUser->id,
                'box_id' => $foodBox->id,
                'status' => 'paused',
                'paused_at' => now()->subDays(15),
                'next_renewal_date' => now()->addMonths(1),
                'current_price' => $foodBox->price,
                'stripe_subscription_id' => 'sub_test_' . uniqid(),
            ]);

            Delivery::create([
                'subscription_id' => $sub2->id,
                'status' => 'delivered',
                'tracking_number' => 'TRACK' . str_pad($sub2->id, 8, '0', STR_PAD_LEFT),
                'estimated_delivery' => now()->subDays(20),
                'delivered_at' => now()->subDays(20),
            ]);
        }

        // Abonnements pour l'utilisateur premium
        $bookBox = Box::where('title', 'Bibliophile Express')->first();
        if ($bookBox) {
            $sub3 = Subscription::create([
                'user_id' => $premium->id,
                'box_id' => $bookBox->id,
                'status' => 'active',
                'next_renewal_date' => now()->addMonths(3),
                'current_price' => $bookBox->price,
                'stripe_subscription_id' => 'sub_test_' . uniqid(),
            ]);

            Delivery::create([
                'subscription_id' => $sub3->id,
                'status' => 'in_transit',
                'tracking_number' => 'TRACK' . str_pad($sub3->id, 8, '0', STR_PAD_LEFT),
                'estimated_delivery' => now()->addDays(5),
            ]);

            Invoice::create([
                'subscription_id' => $sub3->id,
                'amount' => $bookBox->price,
                'status' => 'paid',
                'stripe_invoice_id' => 'inv_test_' . uniqid(),
                'paid_at' => now()->subDays(5),
                'invoice_number' => 'INV-' . str_pad($sub3->id + 200, 6, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
