<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->string('signature', 64)->nullable()->after('status');
        });

        // Initialize signatures for existing records
        $conversions = DB::table('conversions')->orderBy('id', 'asc')->get();
        $key = config('app.signature_key');

        foreach ($conversions as $conversion) {
            $data = [
                $conversion->vendor_id,
                $conversion->affiliate_id,
                $conversion->vendor_order_id,
                $conversion->product_name ?? '',
                (string)$conversion->sale_amount,
                (string)$conversion->commission_amount,
                $conversion->status,
            ];
            $signature = hash_hmac('sha256', implode('|', $data), $key);

            DB::table('conversions')->where('id', $conversion->id)->update([
                'signature' => $signature,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->dropColumn('signature');
        });
    }
};
