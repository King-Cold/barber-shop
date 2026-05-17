<x-mail::message>
# ¡Hola, {{ $barber->name }}! 👋

Aquí tienes tu **agenda de citas programadas para mañana** para que puedas planificar tu jornada de trabajo y dar el mejor servicio a nuestros clientes.

<x-mail::panel>
## 📅 Resumen de tu Jornada de Trabajo
* Citas Totales: **{{ $appointments->count() }}**
* Citas Confirmadas: **{{ $appointments->where('status', 'confirmed')->count() }}**
* Estimado de Ganancias: **${{ number_format($appointments->sum(function($app) { return $app->service->price; }), 2) }}**
</x-mail::panel>

### ✂️ Detalle de tus Citas para Mañana:

<x-mail::table>
| Hora | Cliente | Servicio | Estado |
| :--- | :--- | :--- | :--- |
@foreach($appointments as $appointment)
| **{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}** | {{ $appointment->client->name }} | {{ $appointment->service->name }} | {{ $appointment->status === 'confirmed' ? '✅ Confirmada' : '⏳ Pendiente' }} |
@endforeach
</x-mail::table>

---

💡 **Nota:** Hemos adjuntado a este correo un archivo **PDF de alta calidad** con el detalle completo de tu agenda, listo para que lo descargues, lo guardes en tu móvil o lo imprimas para tenerlo a mano en la barbería.

¡Que tengas un excelente y muy productivo día de trabajo mañana! 💪

Saludos,<br>
El Equipo de **{{ config('app.name', 'Barber Shop') }}**
</x-mail::message>
