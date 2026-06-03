<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
            $table->string('vendor_order_id')->index();
            $table->decimal('sale_amount', 15, 2);
            $table->decimal('commission_amount', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->timestamps();

            $table->unique(['vendor_id', 'vendor_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};
