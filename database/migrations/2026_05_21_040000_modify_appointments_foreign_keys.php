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
        Schema::table('appointments', function (Blueprint $table) {
            // Drop existing foreign key constraints
            $table->dropForeign(['client_id']);
            $table->dropForeign(['barber_id']);

            // Make the columns nullable
            $table->unsignedBigInteger('client_id')->nullable()->change();
            $table->unsignedBigInteger('barber_id')->nullable()->change();

            // Re‑create foreign keys with NULL on delete
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->nullOnDelete();

            $table->foreign('barber_id')
                ->references('id')
                ->on('barbers')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop the nullable foreign keys
            $table->dropForeign(['client_id']);
            $table->dropForeign(['barber_id']);

            // Revert columns to NOT NULL
            $table->unsignedBigInteger('client_id')->nullable(false)->change();
            $table->unsignedBigInteger('barber_id')->nullable(false)->change();

            // Re‑create original foreign keys with cascade delete
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');

            $table->foreign('barber_id')
                ->references('id')
                ->on('barbers')
                ->onDelete('cascade');
        });
    }
};
