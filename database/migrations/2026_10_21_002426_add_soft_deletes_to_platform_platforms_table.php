<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_platforms', function (Blueprint $table) {
            $table->foreignId('deleted_by_platform_id')->nullable()->constrained('platforms')->nullOnDelete();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::table('platform_platforms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('deleted_by_platform_id');
            $table->dropSoftDeletes();
        });
    }
};