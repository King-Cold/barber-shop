<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Barber;
use App\Models\Service;
use Carbon\Carbon;

#[Layout('layouts.app')]
class AppointmentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';
    
    // Modal state
    public $isModalOpen = false;
    public $isEditing = false;
    
    // Form fields
    public $appointmentId;
    public $client_id;
    public $barber_id;
    public $service_id;
    public $date;
    public $time;
    public $status = 'pending';

    protected $listeners = ['deleteConfirmed' => 'delete'];

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
            $this->sortField = $field;
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->date = Carbon::today()->format('Y-m-d');
        // Default time to nearest next hour
        $this->time = Carbon::now()->addHour()->startOfHour()->format('H:i');
        
        $this->isEditing = false;
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $this->appointmentId = $appointment->id;
        $this->client_id = $appointment->client_id;
        $this->barber_id = $appointment->barber_id;
        $this->service_id = $appointment->service_id;
        $this->date = Carbon::parse($appointment->date)->format('Y-m-d');
        $this->time = Carbon::parse($appointment->time)->format('H:i');
        $this->status = $appointment->status;
        
        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        // Format time properly just in case it includes seconds
        $timeString = Carbon::parse($this->time)->format('H:i:s');

        // CRITICAL BUSINESS LOGIC: Check for overlapping appointments
        $conflictQuery = Appointment::where('barber_id', $this->barber_id)
            ->where('date', $this->date)
            ->where('time', $timeString)
            ->where('status', '!=', 'canceled');

        if ($this->isEditing) {
            $conflictQuery->where('id', '!=', $this->appointmentId);
        }

        if ($conflictQuery->exists()) {
            $this->dispatch('swal', [
                'title' => 'Cita Duplicada',
                'text' => 'El barbero seleccionado ya tiene una cita asignada en esta fecha y hora. Por favor, selecciona otro horario.',
                'icon' => 'error',
                'showConfirmButton' => true
            ]);
            return; // Stop execution
        }

        if ($this->isEditing) {
            $appointment = Appointment::find($this->appointmentId);
            $appointment->update([
                'client_id' => $this->client_id,
                'barber_id' => $this->barber_id,
                'service_id' => $this->service_id,
                'date' => $this->date,
                'time' => $timeString,
                'status' => $this->status,
            ]);
            $this->dispatch('swal', [
                'title' => 'Cita Actualizada',
                'text' => 'La cita ha sido actualizada con éxito.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        } else {
            Appointment::create([
                'client_id' => $this->client_id,
                'barber_id' => $this->barber_id,
                'service_id' => $this->service_id,
                'date' => $this->date,
                'time' => $timeString,
                'status' => $this->status,
            ]);
            $this->dispatch('swal', [
                'title' => 'Cita Registrada',
                'text' => 'La cita se agendó correctamente.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => '¡No podrás revertir esto!',
            'icon' => 'warning',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            // Soft delete is handled automatically by the model's SoftDeletes trait
            $appointment->delete();
            $this->dispatch('swal', [
                'title' => '¡Eliminada!',
                'text' => 'La cita ha sido cancelada/eliminada exitosamente.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->appointmentId = null;
        $this->client_id = null;
        $this->barber_id = null;
        $this->service_id = null;
        $this->date = '';
        $this->time = '';
        $this->status = 'pending';
        $this->resetValidation();
    }

    public function render()
    {
        $appointments = Appointment::with(['client', 'barber', 'service'])
            ->whereHas('client', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('barber', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.appointments.index', [
            'appointments' => $appointments,
            'clientsList' => Client::orderBy('name')->get(),
            'barbersList' => Barber::orderBy('name')->get(),
            'servicesList' => Service::orderBy('name')->get(),
        ]);
    }
}
