<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Barber;
use App\Models\Service;
use Carbon\Carbon;
use App\Mail\AppointmentConfirmed;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AppointmentForm extends Component
{
    public $appointment;
    public $isEditing = false;

    // Fields
    public $client_id;
    public $barber_id;
    public $service_id;
    public $date;
    public $time;
    public $status = 'pending';

    public function mount(Appointment $appointment = null)
    {
        if ($appointment && $appointment->exists) {
            $this->appointment = $appointment;
            $this->isEditing = true;
            $this->client_id = $appointment->client_id;
            $this->barber_id = $appointment->barber_id;
            $this->service_id = $appointment->service_id;
            $this->date = Carbon::parse($appointment->date)->format('Y-m-d');
            $this->time = Carbon::parse($appointment->time)->format('H:i');
            $this->status = $appointment->status;
        } else {
            $this->date = Carbon::today()->format('Y-m-d');
            $this->time = Carbon::now()->addHour()->startOfHour()->format('H:i');
        }
    }

    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'barber_id' => 'required|exists:barbers,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ];
    }

    public function save()
    {
        $this->validate();

        if (!$this->validateSelectedTime()) {
            return;
        }

        $timeString = Carbon::parse($this->time)->format('H:i:s');

        // Check for overlapping appointments
        $conflictQuery = Appointment::where('barber_id', $this->barber_id)
            ->where('date', $this->date)
            ->where('time', $timeString)
            ->where('status', '!=', 'canceled');

        if ($this->isEditing) {
            $conflictQuery->where('id', '!=', $this->appointment->id);
        }

        if ($conflictQuery->exists()) {
            $this->dispatch('swal', [
                'title' => 'Cita Duplicada',
                'text' => 'El barbero seleccionado ya tiene una cita asignada en esta fecha y hora. Por favor, selecciona otro horario.',
                'icon' => 'error',
                'showConfirmButton' => true
            ]);
            return;
        }

        if ($this->isEditing) {
            $this->appointment->update([
                'client_id' => $this->client_id,
                'barber_id' => $this->barber_id,
                'service_id' => $this->service_id,
                'date' => $this->date,
                'time' => $timeString,
                'status' => $this->status,
            ]);

            session()->flash('swal', [
                'title' => 'Cita Actualizada',
                'text' => 'La cita ha sido modificada con éxito.',
                'icon' => 'success',
            ]);
        } else {
            $newAppointment = Appointment::create([
                'client_id' => $this->client_id,
                'barber_id' => $this->barber_id,
                'service_id' => $this->service_id,
                'date' => $this->date,
                'time' => $timeString,
                'status' => $this->status,
            ]);

            // Send confirmation email
            try {
                if ($newAppointment->client && $newAppointment->client->email) {
                    Mail::to($newAppointment->client->email)->send(new AppointmentConfirmed($newAppointment));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error sending appointment email: " . $e->getMessage());
            }

            session()->flash('swal', [
                'title' => 'Cita Agendada',
                'text' => 'La nueva cita se registró correctamente y se notificó al cliente.',
                'icon' => 'success',
            ]);
        }

        return redirect()->route('appointments');
    }

    public function validateSelectedTime()
    {
        if (!$this->barber_id || !$this->date || !$this->time) {
            return true;
        }

        $barber = Barber::find($this->barber_id);
        if (!$barber) {
            return false;
        }

        $carbonDate = Carbon::parse($this->date);
        $dayOfWeek = $carbonDate->dayOfWeekIso;

        $schedule = $barber->schedules()->where('day_of_week', $dayOfWeek)->first();
        if (!$schedule || !$schedule->is_working) {
            $this->addError('time', 'El barbero no trabaja este día de la semana.');
            return false;
        }

        $selectedTime = Carbon::parse($this->time)->format('H:i');
        $start = Carbon::parse($schedule->start_time)->format('H:i');
        $end = Carbon::parse($schedule->end_time)->format('H:i');

        if ($selectedTime < $start || $selectedTime >= $end) {
            $this->addError('time', "El horario seleccionado ({$this->time}) está fuera de la jornada laboral del barbero ({$start} - {$end}).");
            return false;
        }

        if ($schedule->lunch_start_time && $schedule->lunch_end_time) {
            $lunchStart = Carbon::parse($schedule->lunch_start_time)->format('H:i');
            $lunchEnd = Carbon::parse($schedule->lunch_end_time)->format('H:i');
            if ($selectedTime >= $lunchStart && $selectedTime < $lunchEnd) {
                $this->addError('time', 'El horario seleccionado coincide con el receso de almuerzo del barbero.');
                return false;
            }
        }

        return true;
    }

    public function getAvailableSlots()
    {
        if (!$this->barber_id || !$this->date) {
            return [];
        }

        $barber = Barber::find($this->barber_id);
        if (!$barber) {
            return [];
        }

        $carbonDate = Carbon::parse($this->date);
        $dayOfWeek = $carbonDate->dayOfWeekIso;

        $schedule = $barber->schedules()->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule || !$schedule->is_working) {
            return [
                'status' => 'not_working',
                'message' => 'El barbero seleccionado no trabaja este día de la semana (' . $this->getDayName($dayOfWeek) . ').',
                'slots' => []
            ];
        }

        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);

        $slots = [];
        $current = $start->copy();

        $bookedTimes = Appointment::where('barber_id', $this->barber_id)
            ->where('date', $this->date)
            ->where('status', '!=', 'canceled');

        if ($this->isEditing) {
            $bookedTimes->where('id', '!=', $this->appointment->id);
        }

        $bookedTimes = $bookedTimes->pluck('time')
            ->map(fn($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        while ($current->lt($end)) {
            $timeSlot = $current->format('H:i');

            $isLunch = false;
            if ($schedule->lunch_start_time && $schedule->lunch_end_time) {
                $lunchStart = Carbon::parse($schedule->lunch_start_time);
                $lunchEnd = Carbon::parse($schedule->lunch_end_time);
                if ($current->gte($lunchStart) && $current->lt($lunchEnd)) {
                    $isLunch = true;
                }
            }

            if (!$isLunch) {
                $isBooked = in_array($timeSlot, $bookedTimes);
                $slots[] = [
                    'time' => $timeSlot,
                    'formatted' => $current->format('g:i A'),
                    'is_booked' => $isBooked,
                ];
            }
            $current->addMinutes(30);
        }

        return [
            'status' => 'working',
            'slots' => $slots
        ];
    }

    private function getDayName($dayNum)
    {
        $days = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];
        return $days[$dayNum] ?? '';
    }

    public function render()
    {
        return view($this->isEditing ? 'livewire.admin.appointments.edit' : 'livewire.admin.appointments.create', [
            'clientsList' => Client::orderBy('name')->get(),
            'barbersList' => Barber::orderBy('name')->get(),
            'servicesList' => Service::orderBy('name')->get(),
        ]);
    }
}
