<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    // "SoftDeletes" permite que cuando elimines una cita, no se borre de verdad de la base de datos,
    // sino que se marque como "eliminada" (deleted_at). Esto protege el historial.
    use SoftDeletes, HasFactory;

    /**
     * $fillable son los campos de la tabla de la base de datos que 
     * Laravel permite llenar masivamente (Mass Assignment) al crear o actualizar.
     */
    protected $fillable = [
        'client_id',
        'barber_id',
        'service_id',
        'date',
        'time',
        'status', // Estados posibles: pending, confirmed, completed, canceled
        'notes'
    ];

    /**
     * RELACIÓN: La cita pertenece a UN cliente.
     * Permite acceder a los datos del cliente desde la cita: $appointment->client->name
     */
    public function client()
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    /**
     * RELACIÓN: La cita pertenece a UN barbero.
     * Permite acceder a los datos del barbero: $appointment->barber->name
     */
    public function barber()
    {
        return $this->belongsTo(Barber::class)->withTrashed();
    }

    /**
     * RELACIÓN: La cita pertenece a UN servicio.
     * Permite acceder al precio o duración del servicio: $appointment->service->price
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
