<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Barber;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barber_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('day_of_week'); // 1 = Lunes, 7 = Domingo
            $table->boolean('is_working')->default(true);
            $table->time('start_time')->default('09:00:00');
            $table->time('end_time')->default('18:00:00');
            $table->time('lunch_start_time')->nullable()->default('13:00:00');
            $table->time('lunch_end_time')->nullable()->default('14:00:00');
            $table->timestamps();
        });

        // Populate default schedules for existing barbers in the database
        foreach (Barber::all() as $barber) {
            for ($i = 1; $i <= 7; $i++) {
                $barber->schedules()->create([
                    'day_of_week' => $i,
                    'is_working' => $i <= 6, // Lunes a Sábado activo, Domingo inactivo
                    'start_time' => '09:00:00',
                    'end_time' => '18:00:00',
                    'lunch_start_time' => '13:00:00',
                    'lunch_end_time' => '14:00:00',
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barber_schedules');
    }
};
