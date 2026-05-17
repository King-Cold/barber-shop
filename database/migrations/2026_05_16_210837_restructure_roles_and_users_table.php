<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Seed roles
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Administrador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Super Administrador', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Update Users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('id')->nullable();
        });

        // Migrate data (default everyone to Admin for now)
        DB::table('users')->update(['role_id' => 1]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->string('role')->default('admin');
        });

        Schema::dropIfExists('roles');
    }
};
