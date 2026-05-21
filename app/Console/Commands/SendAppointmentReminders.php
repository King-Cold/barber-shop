<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\Barber;
use App\Mail\AppointmentReminder;
use App\Mail\BarberDailyAgenda;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

/**
 * Comando de Consola: barber:reminders
 * 
 * Este script se ejecuta automáticamente todos los días gracias al CRON Job 
 * configurado en bootstrap/app.php. Su objetivo es buscar las citas de "mañana"
 * y enviar correos recordatorios tanto a los clientes como a los barberos.
 */
class SendAppointmentReminders extends Command
{
    /**
     * El nombre o comando exacto para ejecutar este script desde la terminal.
     * Ejemplo: php artisan barber:reminders
     */
    protected $signature = 'barber:reminders';

    /**
     * Breve descripción que aparece cuando ejecutas 'php artisan list'
     */
    protected $description = 'Envía correos de recordatorio a los clientes y la agenda diaria a los barberos para las citas de mañana';

    /**
     * Este es el método principal donde ocurre toda la lógica del comando.
     */
    public function handle()
    {
        // 1. Obtenemos la fecha de mañana
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        // $this->info imprime texto verde en la terminal
        $this->info("Buscando citas programadas para la fecha: {$tomorrow}");

        // 2. Buscamos todas las citas pendientes o confirmadas programadas para mañana
        // Usamos 'with' (Eager Loading) para traer los datos del cliente, barbero y servicio de una sola vez
        $appointments = Appointment::with(['client', 'barber', 'service'])
            ->where('date', $tomorrow)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        // Si no hay citas, detenemos el script
        if ($appointments->isEmpty()) {
            $this->info("No se encontraron citas para mañana.");
            return;
        }

        // ==========================================
        // FASE 1: Enviar recordatorios a los clientes
        // ==========================================
        $clientCount = 0;
        foreach ($appointments as $appointment) {
            // Verificamos que el cliente tenga un correo registrado
            if ($appointment->client && $appointment->client->email) {
                try {
                    // Intentamos enviar el correo de recordatorio
                    Mail::to($appointment->client->email)->send(new AppointmentReminder($appointment));
                    $this->line("Recordatorio enviado al cliente: {$appointment->client->email}");
                    $clientCount++;
                } catch (\Exception $e) {
                    // Si falla (por ejemplo, límite de Mailtrap alcanzado), esperamos 11 segundos y reintentamos
                    $this->warn("Límite de correos alcanzado para {$appointment->client->email}. Reintentando en 11 segundos...");
                    sleep(11);
                    try {
                        Mail::to($appointment->client->email)->send(new AppointmentReminder($appointment));
                        $this->line("Recordatorio enviado al cliente (tras reintento): {$appointment->client->email}");
                        $clientCount++;
                    } catch (\Exception $retryException) {
                        // Si falla por segunda vez, mostramos un error rojo en la consola
                        $this->error("No se pudo enviar correo al cliente {$appointment->client->email}: " . $retryException->getMessage());
                    }
                }
                
                // Pausa general de 3 segundos entre correos para evitar bloqueos del servidor de correo
                sleep(3);
            }
        }

        // ==========================================
        // FASE 2: Enviar agendas a los barberos
        // ==========================================
        // Agrupamos la lista gigante de citas dividiéndola por cada barbero
        $barberAppointments = $appointments->groupBy('barber_id');
        $barberCount = 0;

        foreach ($barberAppointments as $barberId => $appointmentsList) {
            $barber = Barber::find($barberId);
            if ($barber && $barber->email) {
                try {
                    // Enviamos un resumen de todas sus citas al barbero
                    Mail::to($barber->email)->send(new BarberDailyAgenda($barber, $appointmentsList));
                    $this->line("Agenda enviada al barbero: {$barber->email} (Total de citas: {$appointmentsList->count()})");
                    $barberCount++;
                } catch (\Exception $e) {
                    // Manejo de errores y reintentos (igual que con los clientes)
                    $this->warn("Límite de correos alcanzado para el barbero {$barber->email}. Reintentando en 11 segundos...");
                    sleep(11);
                    try {
                        Mail::to($barber->email)->send(new BarberDailyAgenda($barber, $appointmentsList));
                        $this->line("Agenda enviada al barbero (tras reintento): {$barber->email}");
                        $barberCount++;
                    } catch (\Exception $retryException) {
                        $this->error("No se pudo enviar la agenda al barbero {$barber->email}: " . $retryException->getMessage());
                    }
                }
                
                // Pausa de 3 segundos por seguridad
                sleep(3);
            }
        }

        // 3. Resumen final en consola
        $this->info("Proceso completado exitosamente: {$clientCount} recordatorios a clientes y {$barberCount} agendas a barberos.");
    }
}
