<x-mail::message>
# ¡Hola, {{ $barber->name }}!

Aquí tienes tu **agenda de citas programadas para mañana** para que puedas organizarte con anticipación.

<x-mail::panel>
## Resumen de Citas para Mañana
Citas Totales: **{{ $appointments->count() }}**
</x-mail::panel>

### Detalle de la Agenda:

| Hora | Cliente | Servicio | Estado |
| :--- | :--- | :--- | :--- |
@foreach($appointments as $appointment)
| {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }} | {{ $appointment->client->name }} | {{ $appointment->service->name }} | {{ $appointment->status === 'confirmed' ? 'Confirmada' : 'Pendiente' }} |
@endforeach

---

¡Que tengas un excelente día de trabajo mañana!

Saludos,<br>
{{ config('app.name', 'Barber Shop') }} Administrador
</x-mail::message>
