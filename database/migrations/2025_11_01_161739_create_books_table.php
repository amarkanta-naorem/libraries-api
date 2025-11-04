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
            $table->foreignId('parent_id')->nullable()->constrained('books')->cascadeOnDelete();
            $table->string('isbn', 13)->unique();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->string('edition')->nullable();
            $table->enum('format', ['hardcover', 'paperback', 'e-book', 'audiobook'])->nullable();
            $table->string('language')->default('english');

            $table->auditable(); // App\Providers\AppServiceProvider::boot()

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
