<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_platform', function (Blueprint $table) {
            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('platform_id')
                ->constrained('platforms')
                ->cascadeOnDelete();

            $table->string('profile_url', 2048)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('last_verified_at')->nullable();

            $table->timestamps();

            $table->primary(['company_id', 'platform_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_platform');
    }
};