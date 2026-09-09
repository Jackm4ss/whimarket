<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_access_codes', function (Blueprint $table) {
            $table->unsignedInteger('max_uses')->nullable()->after('email')->comment('Null means unlimited uses');
            $table->unsignedInteger('used_count')->default(0)->after('max_uses');
            $table->boolean('is_locked')->default(false)->after('is_used')->index();
            $table->boolean('is_one_time')->default(false)->after('is_locked');
        });

        // Backfill existing records: if is_used is true, set used_count = 1, max_uses = 1, is_one_time = true, is_locked = true
        DB::table('seller_access_codes')->where('is_used', true)->update([
            'used_count' => 1,
            'max_uses' => 1,
            'is_one_time' => true,
            'is_locked' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('seller_access_codes', function (Blueprint $table) {
            $table->dropColumn(['max_uses', 'used_count', 'is_locked', 'is_one_time']);
        });
    }
};
