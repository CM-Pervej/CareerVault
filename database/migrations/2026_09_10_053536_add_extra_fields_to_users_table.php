<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users',function(Blueprint $table){
            $table->string('slug')->unique()->after('name');
            $table->string('role')->default('user')->after('password')->index();
            $table->string('status')->default('active')->after('role')->index();
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users',function(Blueprint $table){
            $table->dropUnique(['slug']);
            $table->dropColumn(['role','status','last_login_at','slug']);
            $table->dropSoftDeletes();
        });
    }
};