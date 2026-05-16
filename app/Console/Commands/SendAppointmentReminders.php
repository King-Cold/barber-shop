<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\Barber;
use App\Mail\AppointmentReminder;
use App\Mail\BarberDailyAgenda;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barber:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to clients and daily agenda to barbers for tomorrow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        $this->info("Searching for appointments for date: {$tomorrow}");

        $appointments = Appointment::with(['client', 'barber', 'service'])
            ->where('date', $tomorrow)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        if ($appointments->isEmpty()) {
            $this->info("No appointments found for tomorrow.");
            return;
        }

        // 1. Send reminders to clients
        $clientCount = 0;
        foreach ($appointments as $appointment) {
            if ($appointment->client && $appointment->client->email) {
                try {
                    Mail::to($appointment->client->email)->send(new AppointmentReminder($appointment));
                    $this->line("Sent reminder to client: {$appointment->client->email}");
                    $clientCount++;
                } catch (\Exception $e) {
                    $this->warn("Mailtrap limit hit for client {$appointment->client->email}. Retrying in 6 seconds...");
                    sleep(6);
                    try {
                        Mail::to($appointment->client->email)->send(new AppointmentReminder($appointment));
                        $this->line("Sent reminder to client (after retry): {$appointment->client->email}");
                        $clientCount++;
                    } catch (\Exception $retryException) {
                        $this->error("Could not send email to client {$appointment->client->email}: " . $retryException->getMessage());
                    }
                }
                
                // General safety pause
                sleep(2);
            }
        }

        // 2. Group appointments by barber to send daily agendas
        $barberAppointments = $appointments->groupBy('barber_id');
        $barberCount = 0;

        foreach ($barberAppointments as $barberId => $appointmentsList) {
            $barber = Barber::find($barberId);
            if ($barber && $barber->email) {
                try {
                    Mail::to($barber->email)->send(new BarberDailyAgenda($barber, $appointmentsList));
                    $this->line("Sent daily agenda to barber: {$barber->email} (Total appointments: {$appointmentsList->count()})");
                    $barberCount++;
                } catch (\Exception $e) {
                    $this->warn("Mailtrap limit hit for barber {$barber->email}. Retrying in 6 seconds...");
                    sleep(6);
                    try {
                        Mail::to($barber->email)->send(new BarberDailyAgenda($barber, $appointmentsList));
                        $this->line("Sent daily agenda to barber (after retry): {$barber->email}");
                        $barberCount++;
                    } catch (\Exception $retryException) {
                        $this->error("Could not send daily agenda to barber {$barber->email}: " . $retryException->getMessage());
                    }
                }
                
                // General safety pause
                sleep(2);
            }
        }

        $this->info("Successfully processed {$clientCount} client reminders and {$barberCount} barber agendas.");
    }
}
