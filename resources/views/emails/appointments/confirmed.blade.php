<x-mail::message>
# ¡Hola, {{ $appointment->client->name }}!

Tu cita en **{{ config('app.name', 'Barber Shop') }}** ha sido reservada. Para garantizar tu lugar, por favor **confirma tu asistencia** haciendo clic en el botón de abajo.

<x-mail::button :url="URL::signedRoute('appointments.client-confirm', ['appointment' => $appointment->id])" color="success">
Confirmar mi Cita ahora
</x-mail::button>

<x-mail::panel>
## Resumen de tu Reserva
**Servicio:** {{ $appointment->service->name }}  
**Barbero:** {{ $appointment->barber->name }}  
**Fecha:** {{ \Carbon\Carbon::parse($appointment->date)->format('d M, Y') }}  
**Hora:** {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}  
**Monto Total:** ${{ number_format($appointment->service->price, 2) }}
</x-mail::panel>

Hemos adjuntado un comprobante en PDF a este correo para que tengas todos los detalles a la mano. Una vez confirmes tu cita, el sistema actualizará automáticamente tu estado.

### Información Importante:
* Por favor, llega 5 minutos antes de tu hora programada.
* Si necesitas cancelar, por favor hazlo con al menos 24 horas de anticipación.

Gracias por elegirnos. ¡Nos vemos pronto!

Saludos,<br>
El equipo de {{ config('app.name', 'Barber Shop') }}
</x-mail::message>
