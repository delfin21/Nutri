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
    Schema::table('users', function (Blueprint $table) {
        $table->string('verification_document')->nullable()->after('email');
        $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('verification_document');
        $table->text('verification_feedback')->nullable()->after('verification_status');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['verification_document', 'verification_status', 'verification_feedback']);
    });
}
};
