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
    Schema::create('store_credits', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('buyer_id');
        $table->decimal('amount', 8, 2);
        $table->string('description')->nullable();
        $table->timestamps();

        $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_credits');
    }
};
