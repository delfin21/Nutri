<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->unsignedBigInteger('order_id')->nullable()->after('buyer_id');
        $table->json('response_payload')->nullable()->after('status');
        $table->boolean('is_test')->default(false)->after('response_payload');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
