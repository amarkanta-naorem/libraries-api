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
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            if (config('database.default') === 'pgsql') {
                $table->jsonb('socials')->nullable();
            } else {
                $table->json('socials')->nullable();
            }

            $table->auditable(); // App\Providers\AppServiceProvider::boot()
            
            $table->timestamps();

            if (config('database.default') === 'mysql') {
                $table->fullText('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publishers');
    }
};
