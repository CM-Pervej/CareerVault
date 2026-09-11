<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_platforms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('platform_id')
                ->constrained('platforms')
                ->cascadeOnDelete();

            $table->foreignId('connected_platform_id')
                ->constrained('platforms')
                ->cascadeOnDelete();

            $table->string('account_url')->nullable();

            $table->timestamps();

            $table->unique([
                'platform_id',
                'connected_platform_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_platforms');
    }
};