<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->index(['product_id', 'created_at'], 'wishlists_product_created_index');
            $table->index('created_at', 'wishlists_created_at_index');
        });

        Schema::table('seller_followers', function (Blueprint $table) {
            $table->index(['seller_id', 'created_at'], 'seller_followers_seller_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('seller_followers', function (Blueprint $table) {
            $table->dropIndex('seller_followers_seller_created_index');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIndex('wishlists_product_created_index');
            $table->dropIndex('wishlists_created_at_index');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }
};
