<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Skip order_id since it's already removed manually
            if (!Schema::hasColumn('payments', 'response_payload')) {
                $table->json('response_payload')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'response_payload')) {
                $table->dropColumn('response_payload');
            }
        });
    }
};
