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
        Schema::create('capital_withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('capital_id');
            $table->unsignedInteger('user_id');
            $table->integer('amount')->nullable();
            $table->integer('interest')->nullable();
            $table->integer('balance');
            $table->date('date');
            $table->string('trx_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capital_withdraws');
    }
};
