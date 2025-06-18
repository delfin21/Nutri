<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->text('reason');
            $table->string('evidence_path'); // image path (or json for multiple later)
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_response')->nullable(); // admin reply or rejection reason
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('return_requests');
    }
};
