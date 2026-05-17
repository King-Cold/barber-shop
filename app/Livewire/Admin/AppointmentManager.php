<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Appointment;

#[Layout('layouts.app')]
class AppointmentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'date';
    public $sortDirection = 'desc';

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
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
        $appointment = Appointment::with('client')->find($id);
        if ($appointment) {
            $clientName = $appointment->client->name;
            $appointment->delete();
            $this->dispatch('swal', [
                'title' => '¡Eliminada!',
                'text' => "La cita de {$clientName} ha sido eliminada correctamente.",
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        }
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
            ->paginate($this->perPage);

        return view('livewire.admin.appointments.index', [
            'appointments' => $appointments,
        ]);
    }
}
