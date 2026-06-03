<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversions', function (Blueprint $table): void {
            $table->foreignId('payout_id')
                ->nullable()
                ->after('affiliate_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('conversions', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('payout_id');
        });
    }
};
