<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barber extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'name', 'specialty', 'phone', 'email', 'address', 'photo'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
