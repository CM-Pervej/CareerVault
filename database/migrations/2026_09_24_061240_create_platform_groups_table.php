<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained('platforms')->cascadeOnDelete();

            $table->string('name');
            $table->string('slug');
            $table->string('group_type')->nullable();

            $table->string('url', 2048);
            $table->string('short_desc')->nullable();
            $table->text('description')->nullable();

            $table->string('access_type')->default('public');
            $table->boolean('is_bangladesh_focused')->default(false);

            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamp('last_verified_at')->nullable();
            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->unique(['platform_id', 'slug']);

            $table->index('platform_id');
            $table->index('name');
            $table->index('group_type');
            $table->index('access_type');
            $table->index('is_bangladesh_focused');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_groups');
    }
};