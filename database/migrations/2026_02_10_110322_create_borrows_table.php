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

            $table->date('original_due_date');

            $table->date('current_due_date')->nullable();

            $table->integer('extended_count')->default(0);

            $table->dateTime('pickup_at')->nullable();
            $table->dateTime('return_date')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'picked_up',
                'returned'
                ])->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->integer('late_days')->default(0);
            $table->decimal('fine_amount', 10, 2)->default(0);

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
