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
        Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')
          ->constrained()
          ->cascadeOnDelete();
    $table->string('title');
    $table->string('author');
    $table->string('publisher')->nullable();
    $table->year('year')->nullable();
    $table->string('image')->nullable();
    $table->integer('jumlah_halaman')->nullable();
    $table->string('lokasi_buku');
    $table->string('isbn')->unique();
    $table->integer('stock')->default(0);
    $table->text('description')->nullable();
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
