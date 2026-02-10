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
        Schema::create('app_reviews', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->unsignedTinyInteger('rating');
    $table->text('review')->nullable();
    $table->boolean('is_anonymous')->default(false);
    $table->boolean('is_show')->default(true);

    $table->timestamps();

    $table->unique('user_id'); // 1 user = 1 review
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_reviews');
    }
};
