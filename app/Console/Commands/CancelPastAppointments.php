<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class CancelPastAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barber:cancel-past-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel pending or confirmed appointments that are from past days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $appointmentsToCancel = Appointment::where('date', '<', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        if ($appointmentsToCancel->isEmpty()) {
            $this->info("No past appointments found to cancel.");
            return;
        }

        $count = 0;
        foreach ($appointmentsToCancel as $appointment) {
            $appointment->update(['status' => 'canceled']);
            $count++;
        }

        $this->info("Successfully canceled {$count} past appointments.");
    }
}
