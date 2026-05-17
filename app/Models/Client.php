<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['user_id', 'name', 'phone', 'email', 'address', 'photo'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
