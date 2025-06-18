<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::table('return_requests', function (Blueprint $table) {
        // DO NOT add farmer_response again — it already exists
        $table->timestamp('responded_at')->nullable()->after('farmer_response');
        $table->timestamp('resolved_at')->nullable()->after('admin_response');
        $table->boolean('is_resolved')->default(false)->after('resolved_at');
    });
}

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropColumn([
                'farmer_response',
                'responded_at',
                'resolved_at',
                'is_resolved',
            ]);
        });
    }
};
