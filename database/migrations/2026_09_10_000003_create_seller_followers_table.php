<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'seller_id']);
            $table->index('seller_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_followers');
    }
};
