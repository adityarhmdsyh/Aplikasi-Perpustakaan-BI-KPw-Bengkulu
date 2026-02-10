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
        Schema::create('borrow_extensions', function (Blueprint $table) {
    $table->id();

    $table->foreignId('borrow_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->date('requested_due_date');
    $table->date('approved_due_date')->nullable();

    $table->enum('status', ['pending', 'approved', 'rejected'])
          ->default('pending');

    $table->foreignId('approved_by')
          ->nullable()
          ->references('id')
          ->on('users')
          ->nullOnDelete();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_extensions');
    }
};
