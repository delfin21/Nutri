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
        $table->dropColumn('order_id');
        $table->json('order_ids')->nullable()->after('buyer_id');
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn('order_ids');
        $table->foreignId('order_id')->nullable()->constrained()->after('buyer_id');
    });
}

};
