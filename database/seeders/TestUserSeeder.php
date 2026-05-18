<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // Créer un utilisateur de test principal
        User::firstOrCreate(
            ['email' => 'demo@lynbox.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('demo123456'),
                'phone' => '+33612345678',
                'role' => 'user',
                'membership_tier' => 'silver',
                'member_since' => now()->subMonths(6),
                'email_verified_at' => now(),
            ]
        );

        // Utilisateur admin
        User::firstOrCreate(
            ['email' => 'admin@lynbox.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'phone' => '+33612345679',
                'role' => 'admin',
                'membership_tier' => 'platinum',
                'member_since' => now()->subYears(1),
                'email_verified_at' => now(),
            ]
        );

        // Utilisateur Gold
        User::firstOrCreate(
            ['email' => 'premium@lynbox.com'],
            [
                'name' => 'Premium Customer',
                'password' => Hash::make('test123456'),
                'phone' => '+33612345680',
                'role' => 'user',
                'membership_tier' => 'gold',
                'member_since' => now()->subMonths(3),
                'email_verified_at' => now(),
            ]
        );
    }
}
