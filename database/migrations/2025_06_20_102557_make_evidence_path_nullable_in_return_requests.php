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
    Schema::table('return_requests', function (Blueprint $table) {
        $table->string('evidence_path')->nullable()->change();
    });
}

public function down()
{
    Schema::table('return_requests', function (Blueprint $table) {
        $table->string('evidence_path')->nullable(false)->change();
    });
}
};
