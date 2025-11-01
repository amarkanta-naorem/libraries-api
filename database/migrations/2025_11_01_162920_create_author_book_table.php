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
        Schema::create('author_book', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();

            $table->integer('sort_order')->nullable();
            $table->string('role')->nullable();

            $table->primary(['author_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_book');
    }
};
