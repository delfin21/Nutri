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
        Schema::create('farmer_payouts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
    $table->json('order_ids');
    $table->decimal('amount', 10, 2);
    $table->string('method'); // GCash, Maya, Bank
    $table->string('account_name')->nullable(); // Snapshot of payout name
    $table->string('account_number')->nullable(); // Snapshot of payout number/bank
    $table->boolean('is_released')->default(false);
    $table->timestamp('released_at')->nullable();
    $table->string('proof_path')->nullable(); // Optional receipt image
    $table->text('remarks')->nullable(); // Optional notes
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_payouts');
    }
};
