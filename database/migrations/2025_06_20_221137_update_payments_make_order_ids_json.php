<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'order_id')) {
                $table->dropColumn('order_id');
            }

            if (!Schema::hasColumn('payments', 'order_ids')) {
                $table->json('order_ids')->nullable()->after('buyer_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'order_ids')) {
                $table->dropColumn('order_ids');
            }

            if (!Schema::hasColumn('payments', 'order_id')) {
                $table->foreignId('order_id')->nullable()->constrained()->after('buyer_id');
            }
        });
    }
};
