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

/**
 * Componente Livewire: AppointmentForm
 * 
 * Este componente maneja toda la lógica del formulario de citas, tanto para 
 * CREAR una nueva cita como para EDITAR una existente. Incluye validaciones 
 * de disponibilidad, choques de horarios, días laborales y recesos.
 */
#[Layout('layouts.app')]
class AppointmentForm extends Component
{
    // Variables principales del componente
    public $appointment;
    public $isEditing = false; // Bandera para saber si estamos creando o editando

    // Campos del formulario (bindeados con wire:model en la vista)
    public $client_id;
    public $barber_id;
    public $service_id;
    public $date;
    public $time;
    public $status = 'pending';

    /**
     * Método Mount: Se ejecuta una sola vez cuando el componente se carga.
     * Sirve para inicializar las variables.
     */
    public function mount(Appointment $appointment = null)
    {
        // Si recibimos una cita existente, significa que estamos EDITANDO
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
            // Si no recibimos cita, estamos CREANDO. Ponemos valores por defecto.
            $this->date = Carbon::today()->format('Y-m-d');
            // Sugiere una hora por defecto (la siguiente hora en punto)
            $this->time = Carbon::now()->addHour()->startOfHour()->format('H:i');
        }
    }

    /**
     * Reglas de validación base requeridas para guardar el formulario.
     */
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

    /**
     * Método principal que guarda la cita en la base de datos al presionar "Guardar".
     */
    public function save()
    {
        // 1. Valida los campos básicos contra las reglas en rules()
        $this->validate();

        // 2. Valida la lógica de negocio (si el barbero trabaja, no es hora de comida, etc.)
        if (!$this->validateSelectedTime()) {
            return; // Si no pasa la validación, detiene el guardado
        }

        $timeString = Carbon::parse($this->time)->format('H:i:s');

        // 3. Verifica si el barbero ya tiene una cita ocupando ese mismo horario exacto
        $conflictQuery = Appointment::where('barber_id', $this->barber_id)
            ->where('date', $this->date)
            ->where('time', $timeString)
            ->where('status', '!=', 'canceled');

        // Si estamos editando, excluimos la cita actual de la validación de choques
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

        // 4. Si todo está correcto, guardamos la información
        if ($this->isEditing) {
            // Modo Edición: Actualiza el registro existente
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
            // Modo Creación: Crea un registro nuevo
            $newAppointment = Appointment::create([
                'client_id' => $this->client_id,
                'barber_id' => $this->barber_id,
                'service_id' => $this->service_id,
                'date' => $this->date,
                'time' => $timeString,
                'status' => $this->status,
            ]);

            // 5. Intenta enviar el correo de confirmación al cliente
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

        // 6. Redirige a la tabla principal
        return redirect()->route('appointments.index');
    }

    /**
     * Valida que el horario elegido coincida con el horario de trabajo del barbero.
     */
    public function validateSelectedTime()
    {
        // Si falta algún dato vital, no podemos validar
        if (!$this->barber_id || !$this->date || !$this->time) {
            return true;
        }

        $barber = Barber::find($this->barber_id);
        if (!$barber) {
            return false;
        }

        // Determina qué día de la semana es la fecha seleccionada (1 = Lunes, 7 = Domingo)
        $carbonDate = Carbon::parse($this->date);
        $dayOfWeek = $carbonDate->dayOfWeekIso;

        // Busca el horario del barbero para ese día específico
        $schedule = $barber->schedules()->where('day_of_week', $dayOfWeek)->first();
        if (!$schedule || !$schedule->is_working) {
            $this->addError('time', 'El barbero no trabaja este día de la semana.');
            return false;
        }

        $selectedTime = Carbon::parse($this->time)->format('H:i');
        $start = Carbon::parse($schedule->start_time)->format('H:i');
        $end = Carbon::parse($schedule->end_time)->format('H:i');

        // Valida que no esté pidiendo cita antes o después de la jornada
        if ($selectedTime < $start || $selectedTime >= $end) {
            $this->addError('time', "El horario seleccionado ({$this->time}) está fuera de la jornada laboral del barbero ({$start} - {$end}).");
            return false;
        }

        // Valida que no esté pidiendo cita durante la hora de almuerzo
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

    /**
     * Calcula dinámicamente los espacios de tiempo ("slots") disponibles para la vista.
     * Genera botones de horas disponibles excluyendo las horas ocupadas o recesos.
     */
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

        // Si el barbero no trabaja, regresa un status especial
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

        // Obtiene todas las horas ya ocupadas en base de datos para este barbero y fecha
        $bookedTimes = Appointment::where('barber_id', $this->barber_id)
            ->where('date', $this->date)
            ->where('status', '!=', 'canceled');

        if ($this->isEditing) {
            $bookedTimes->where('id', '!=', $this->appointment->id);
        }

        $bookedTimes = $bookedTimes->pluck('time')
            ->map(fn($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        // Recorre la jornada laboral en intervalos de 30 minutos
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

            // Si no es hora de almuerzo, añade el espacio a la lista disponible o lo marca como ocupado
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

    /**
     * Helper para traducir números de días a español.
     */
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

    /**
     * Renderiza la vista del componente y le inyecta las listas maestras (Selects).
     */
    public function render()
    {
        return view($this->isEditing ? 'livewire.admin.appointments.edit' : 'livewire.admin.appointments.create', [
            'clientsList' => Client::orderBy('name')->get(),
            'barbersList' => Barber::orderBy('name')->get(),
            'servicesList' => Service::orderBy('name')->get(),
        ]);
    }
}
