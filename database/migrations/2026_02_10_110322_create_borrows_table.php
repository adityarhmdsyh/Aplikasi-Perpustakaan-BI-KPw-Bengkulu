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
        Schema::create('borrows', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->date('borrow_date');
    $table->date('due_date');
    $table->date('return_date')->nullable();

    $table->enum('status', [
        'pending',
        'approved',
        'rejected',
        'picked_up',
        'returned',
        'extended'
    ])->default('pending');

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
        Schema::dropIfExists('borrows');
    }
};
