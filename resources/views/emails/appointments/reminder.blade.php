<x-mail::message>
# ¡Hola, {{ $appointment->client->name }}!

Este es un recordatorio amigable de tu cita programada para **mañana**.

<x-mail::panel>
## Detalles de tu Cita
**Fecha:** {{ \Carbon\Carbon::parse($appointment->date)->format('d M, Y') }}  
**Hora:** {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}  
**Servicio:** {{ $appointment->service->name }}  
**Barbero:** {{ $appointment->barber->name }}
</x-mail::panel>

¡Te esperamos para brindarte el mejor servicio! Si por alguna razón no puedes asistir, por favor infórmanos lo antes posible.

Saludos,<br>
El equipo de {{ config('app.name', 'Barber Shop') }}
</x-mail::message>
