<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('click_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer_url')->nullable();
            $table->timestamp('clicked_at')->useCurrent();

            $table->index(['affiliate_id', 'clicked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('click_logs');
    }
};
