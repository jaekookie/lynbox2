<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('box_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->min(1)->max(5);
            $table->text('comment');
            $table->integer('helpful_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'box_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
