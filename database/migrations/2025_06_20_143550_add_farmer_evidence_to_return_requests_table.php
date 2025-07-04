<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('return_requests', function (Blueprint $table) {
        $table->string('farmer_evidence_path')->nullable()->after('farmer_response');
    });
}

public function down(): void
{
    Schema::table('return_requests', function (Blueprint $table) {
        $table->dropColumn('farmer_evidence_path');
    });
}

};
