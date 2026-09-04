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
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('official_name')->nullable();

            $table->string('base_url')->nullable();
            $table->string('job_url')->nullable();
            
            $table->string('short_desc')->nullable();
            $table->text('description')->nullable();

            $table->enum('job_type',['Onsite','Remote','Both'])->default('Both');
            $table->enum('business_model',['Free','Freemium','Paid'])->default('Free');
            $table->boolean('account_required')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_bangladesh_focused')->default(false);
            $table->unsignedTinyInteger('founded_month')->nullable();
            $table->unsignedSmallInteger('founded_year')->nullable();
            
            $table->timestamp('last_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('official_name');
            $table->index('job_type');
            $table->index('business_model');
            $table->index('sort_order');
            $table->index('is_bangladesh_focused');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platforms');
    }
};
