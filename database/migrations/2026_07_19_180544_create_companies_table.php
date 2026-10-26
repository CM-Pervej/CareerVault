<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // Basic information
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('legal_name')->nullable();
            $table->string('tagline')->nullable();
            $table->text('short_desc')->nullable();
            $table->longText('description')->nullable();

            // Branding
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();

            // Official websites
            $table->string('website', 2048)->nullable();
            $table->string('career_page', 2048)->nullable();

            // Centralized contact information
            $table->json('emails')->nullable();
            $table->json('phones')->nullable();

            // Company founding information
            $table->string('founded_by')->nullable();
            $table->unsignedTinyInteger('founded_month')->nullable();
            $table->unsignedSmallInteger('founded_year')->nullable();

            // Company size and employee count
            $table->unsignedInteger('employee_count')->nullable();
            $table->timestamp('employee_count_updated_at')->nullable();

            // Classification
            $table->foreignId('company_size_id')
                ->nullable()
                ->constrained('company_sizes')
                ->nullOnDelete();

            $table->foreignId('company_type_id')
                ->nullable()
                ->constrained('company_types')
                ->nullOnDelete();

            $table->foreignId('ownership_type_id')
                ->nullable()
                ->constrained('ownership_types')
                ->nullOnDelete();

            $table->foreignId('primary_industry_id')
                ->nullable()
                ->constrained('industries')
                ->nullOnDelete();

            // Corporate hierarchy
            $table->foreignId('parent_company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            // Record status
            $table->string('status')->default('active')->index();

            // Real-world operating status
            $table->string('operating_status')->nullable()->index();

            // Verification and presentation
            $table->boolean('is_verified')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('last_verified_at')->nullable();

            // Internal notes
            $table->text('notes')->nullable();

            // Audit fields
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Additional indexes
            $table->index(['company_size_id', 'company_type_id']);
            $table->index(['primary_industry_id', 'status']);
            $table->index(['parent_company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
