<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Barber;
use App\Models\Service;
use Carbon\Carbon;
use App\Mail\AppointmentConfirmed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort', 'date');
        $sortDirection = $request->input('direction', 'desc');

        // Whitelist sort fields
        if (!in_array($sortField, ['id', 'date', 'status'])) {
            $sortField = 'date';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $appointments = Appointment::with(['client', 'barber', 'service'])
            ->where(function($query) use ($search) {
                $query->whereHas('client', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('barber', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage)
            ->appends($request->all());

        return view('admin.appointments.index', compact('appointments', 'search', 'perPage', 'sortField', 'sortDirection'));
    }

    public function complete(Appointment $appointment)
    {
        if ($appointment->status !== 'completed') {
            $appointment->update(['status' => 'completed']);
            return redirect()->route('admin.appointments.index')->with('swal', [
                'title' => '¡Actualizado!',
                'text' => "La cita #{$appointment->id} se marcó como completada.",
                'icon' => 'success',
            ]);
        }
        return redirect()->route('admin.appointments.index');
    }

    public function create()
    {
        $clientsList = Client::orderBy('name')->get();
        $barbersList = Barber::orderBy('name')->get();
        $servicesList = Service::orderBy('name')->get();
        
        return view('admin.appointments.create', compact('clientsList', 'barbersList', 'servicesList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'barber_id' => 'required|exists:barbers,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        $errorMessage = '';
        if (!$this->validateSelectedTime($validated['barber_id'], $validated['date'], $validated['time'], $errorMessage)) {
            return back()->withInput()->withErrors(['time' => $errorMessage]);
        }

        $timeString = Carbon::parse($validated['time'])->format('H:i:s');

        // Check conflicts
        $conflictExists = Appointment::where('barber_id', $validated['barber_id'])
            ->where('date', $validated['date'])
            ->where('time', $timeString)
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($conflictExists) {
            return back()->withInput()->withErrors([
                'time' => 'El barbero seleccionado ya tiene una cita asignada en esta fecha y hora. Por favor, selecciona otro horario.'
            ]);
        }

        $appointment = Appointment::create([
            'client_id' => $validated['client_id'],
            'barber_id' => $validated['barber_id'],
            'service_id' => $validated['service_id'],
            'date' => $validated['date'],
            'time' => $timeString,
            'status' => $validated['status'],
        ]);

        // Send email
        try {
            if ($appointment->client && $appointment->client->email) {
                Mail::to($appointment->client->email)->send(new AppointmentConfirmed($appointment));
            }
        } catch (\Exception $e) {
            Log::error("Error sending appointment email: " . $e->getMessage());
        }

        return redirect()->route('admin.appointments.index')->with('swal', [
            'title' => 'Cita Agendada',
            'text' => 'La nueva cita se registró correctamente y se notificó al cliente.',
            'icon' => 'success',
        ]);
    }

    public function edit(Appointment $appointment)
    {
        $clientsList = Client::orderBy('name')->get();
        $barbersList = Barber::orderBy('name')->get();
        $servicesList = Service::orderBy('name')->get();

        // format date and time as needed by traditional inputs
        $appointment->date = Carbon::parse($appointment->date)->format('Y-m-d');
        $appointment->time = Carbon::parse($appointment->time)->format('H:i');

        return view('admin.appointments.edit', compact('appointment', 'clientsList', 'barbersList', 'servicesList'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'barber_id' => 'required|exists:barbers,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        $errorMessage = '';
        if (!$this->validateSelectedTime($validated['barber_id'], $validated['date'], $validated['time'], $errorMessage)) {
            return back()->withInput()->withErrors(['time' => $errorMessage]);
        }

        $timeString = Carbon::parse($validated['time'])->format('H:i:s');

        // Check conflicts, excluding current appointment
        $conflictExists = Appointment::where('barber_id', $validated['barber_id'])
            ->where('date', $validated['date'])
            ->where('time', $timeString)
            ->where('status', '!=', 'canceled')
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($conflictExists) {
            return back()->withInput()->withErrors([
                'time' => 'El barbero seleccionado ya tiene una cita asignada en esta fecha y hora. Por favor, selecciona otro horario.'
            ]);
        }

        $appointment->update([
            'client_id' => $validated['client_id'],
            'barber_id' => $validated['barber_id'],
            'service_id' => $validated['service_id'],
            'date' => $validated['date'],
            'time' => $timeString,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.appointments.index')->with('swal', [
            'title' => 'Cita Actualizada',
            'text' => 'La cita ha sido modificada con éxito.',
            'icon' => 'success',
        ]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('swal', [
            'title' => 'Cita Eliminada',
            'text' => 'La cita ha sido eliminada con éxito.',
            'icon' => 'success',
        ]);
    }

    /**
     * Endpoint asíncrono para retornar slots libres/ocupados para un barbero y fecha.
     */
    public function getSlots(Request $request)
    {
        $barberId = $request->query('barber_id');
        $date = $request->query('date');
        $appointmentId = $request->query('appointment_id');

        if (!$barberId || !$date) {
            return response()->json([]);
        }

        $barber = Barber::find($barberId);
        if (!$barber) {
            return response()->json([]);
        }

        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->dayOfWeekIso;

        $schedule = $barber->schedules()->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule || !$schedule->is_working) {
            return response()->json([
                'status' => 'not_working',
                'message' => 'El barbero seleccionado no trabaja este día de la semana (' . $this->getDayName($dayOfWeek) . ').',
                'slots' => []
            ]);
        }

        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);

        $slots = [];
        $current = $start->copy();

        $bookedTimesQuery = Appointment::where('barber_id', $barberId)
            ->where('date', $date)
            ->where('status', '!=', 'canceled');

        if ($appointmentId) {
            $bookedTimesQuery->where('id', '!=', $appointmentId);
        }

        $bookedTimes = $bookedTimesQuery->pluck('time')
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

        return response()->json([
            'status' => 'working',
            'slots' => $slots
        ]);
    }

    private function validateSelectedTime($barberId, $date, $time, &$errorMessage)
    {
        $barber = Barber::find($barberId);
        if (!$barber) {
            $errorMessage = 'El barbero seleccionado no existe.';
            return false;
        }

        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->dayOfWeekIso;

        $schedule = $barber->schedules()->where('day_of_week', $dayOfWeek)->first();
        if (!$schedule || !$schedule->is_working) {
            $errorMessage = 'El barbero no trabaja este día de la semana.';
            return false;
        }

        $selectedTime = Carbon::parse($time)->format('H:i');
        $start = Carbon::parse($schedule->start_time)->format('H:i');
        $end = Carbon::parse($schedule->end_time)->format('H:i');

        if ($selectedTime < $start || $selectedTime >= $end) {
            $errorMessage = "El horario seleccionado ({$time}) está fuera de la jornada laboral del barbero ({$start} - {$end}).";
            return false;
        }

        if ($schedule->lunch_start_time && $schedule->lunch_end_time) {
            $lunchStart = Carbon::parse($schedule->lunch_start_time)->format('H:i');
            $lunchEnd = Carbon::parse($schedule->lunch_end_time)->format('H:i');
            if ($selectedTime >= $lunchStart && $selectedTime < $lunchEnd) {
                $errorMessage = 'El horario seleccionado coincide con el receso de almuerzo del barbero.';
                return false;
            }
        }

        return true;
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
}
