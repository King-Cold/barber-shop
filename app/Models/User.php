<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * RELACIÓN: Un usuario "pertenece a" un Rol (Administrador, Barbero, Cliente, etc.).
     * Permite acceder al rol del usuario así: $user->role->name
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * VERIFICACIÓN DE ROL: Comprueba si este usuario es el Super Administrador (Dueño).
     * El rol_id = 2 corresponde al Super Administrador en la base de datos.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role_id == 2;
    }

    /**
     * VERIFICACIÓN DE ROL: Comprueba si este usuario es un Administrador normal.
     * El rol_id = 1 corresponde al Administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role_id == 1;
    }

    /**
     * RELACIÓN OPCIONAL: Si este usuario es un Barbero, aquí se enlaza con su perfil público
     * de la tabla `barbers`. Permite hacer: $user->barber->schedule
     */
    public function barber()
    {
        return $this->hasOne(Barber::class);
    }

    /**
     * RELACIÓN OPCIONAL: Si este usuario es un Cliente, aquí se enlaza con su perfil público
     * de la tabla `clients`. Permite hacer: $user->client->phone
     */
    public function client()
    {
        return $this->hasOne(Client::class);
    }
}
