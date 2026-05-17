<x-mail::message>
# ¡Hola, {{ $appointment->client->name }}! 👋

Este es un recordatorio amigable de tu cita programada para **mañana**. ¡Nos estamos preparando para darte el mejor servicio y consentirte como te mereces!

<x-mail::panel>
## 📅 Detalles de tu Cita para Mañana:
* **Fecha:** {{ \Carbon\Carbon::parse($appointment->date)->locale('es')->isoFormat('dddd D [de] MMMM, Y') }}  
* **Hora:** **{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}**  
* **Servicio:** {{ $appointment->service->name }}  
* **Barbero:** {{ $appointment->barber->name }}
* **Precio:** **${{ number_format($appointment->service->price, 2) }}**
</x-mail::panel>

💡 **Nota:** Hemos adjuntado a este correo un **comprobante digital (Ticket) en PDF** con toda la información de tu cita y recomendaciones para tu visita. Puedes guardarlo directamente en tu móvil.

Si por alguna razón necesitas reprogramar o cancelar tu cita, por favor avísanos con al menos **2 horas de anticipación** haciendo clic en el siguiente enlace:

<x-mail::button :url="route('dashboard')">
Ver Mis Citas en el Sistema
</x-mail::button>

¡Te esperamos mañana con todo listo! ¡Que tengas un excelente día!

Saludos,<br>
El equipo de **{{ config('app.name', 'Barber Shop') }}**
</x-mail::message>
