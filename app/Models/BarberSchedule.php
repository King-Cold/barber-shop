<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarberSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'barber_id',
        'day_of_week',
        'is_working',
        'start_time',
        'end_time',
        'lunch_start_time',
        'lunch_end_time',
    ];

    /**
     * Get the barber that owns this schedule.
     */
    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }
}
