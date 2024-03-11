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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->references('uuid')->on('users')->onDelete('cascade');
            $table->string('price')->default('');
            $table->boolean('status')->default(0);
            $table->string('placed_timestamp')->default('');
            $table->string('accept_timestamp')->default('');
            $table->string('started_timestamp')->default('');
            $table->string('delivered_timestamp')->default('');
            $table->string('complete_timestamp')->default('');
            $table->string('canceled_timestamp')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
