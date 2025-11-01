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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('profession')->index();
            $table->text('biography');
            if (config('database.default') === 'pgsql') {
                $table->jsonb('socials')->nullable();
            } else {
                $table->json('socials')->nullable();
            }
            $table->enum('gender', ['male', 'female']);

            $table->auditable(); // App\Providers\AppServiceProvider::boot()
            
            $table->timestamps();

            if (config('database.default') === 'mysql') {
                $table->fullText('biography');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
