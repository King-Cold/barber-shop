<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barber extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $fillable = ['user_id', 'name', 'specialty', 'phone', 'email', 'address', 'photo'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(BarberSchedule::class);
    }

    protected static function booted()
    {
        static::created(function ($barber) {
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
        });
    }
}
