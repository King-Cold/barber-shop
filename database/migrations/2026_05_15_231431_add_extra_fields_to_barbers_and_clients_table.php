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
        Schema::table('barbers', function (Blueprint $table) {
            if (!Schema::hasColumn('barbers', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            $table->string('address')->nullable()->after('email');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('address')->nullable()->after('email');
            $table->string('photo')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barbers', function (Blueprint $table) {
            $table->dropColumn(['email', 'address']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['address', 'photo']);
        });
    }
};
