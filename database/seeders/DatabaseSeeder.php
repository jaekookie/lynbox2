<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TestUserSeeder::class,
            CategorySeeder::class,
            BoxSeeder::class,
            SubscriptionSeeder::class,
        ]);
    }
}
