<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Box;

return new class extends Migration
{
    public function up(): void
    {
        $box = Box::where('title', 'Summer Glow Beauty Box')->first();
        if ($box) {
            $box->update([
                'image_url' => '/storage/boxes/summer-vibes.jpg'
            ]);
        }
    }

    public function down(): void
    {
        $box = Box::where('title', 'Summer Glow Beauty Box')->first();
        if ($box) {
            $box->update([
                'image_url' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=500&h=500&fit=crop'
            ]);
        }
    }
};
