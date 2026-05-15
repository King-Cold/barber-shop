<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    protected $fillable = ['name', 'specialty', 'phone', 'photo'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
