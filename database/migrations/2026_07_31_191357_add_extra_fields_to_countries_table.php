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
        Schema::table('countries', function (Blueprint $table) {
            $table->string('iso_code', 10)->nullable()->after('slug');
            $table->string('phone_code', 20)->nullable()->after('iso_code');
            $table->string('currency', 50)->nullable()->after('phone_code');
            $table->string('capital', 100)->nullable()->after('currency');
            $table->string('region', 100)->nullable()->after('capital');
            $table->string('flag')->nullable()->after('region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn([
                'iso_code',
                'phone_code',
                'currency',
                'capital',
                'region',
                'flag',
            ]);
        });
    }
};
