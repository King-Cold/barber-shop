<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barber extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'specialty', 'phone', 'photo'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
