<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Barber;

#[Layout('layouts.app')]
class BarberManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $selectedBarber = null;
    public $viewingBarberAppointments = null;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function viewAppointments($barberId)
    {
        $this->selectedBarber = Barber::find($barberId);
        if ($this->selectedBarber) {
            $this->viewingBarberAppointments = $this->selectedBarber->appointments()
                ->with(['client', 'service'])
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->get();
        }
    }

    public function closeAppointmentsModal()
    {
        $this->selectedBarber = null;
        $this->viewingBarberAppointments = null;
    }

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

    public function create()
    {
        return redirect()->route('barbers.create');
    }

    public function edit($id)
    {
        return redirect()->route('barbers.edit', $id);
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
        if (is_array($id)) {
            $id = $id['id'] ?? null;
        }
        $barber = Barber::find($id);
        if ($barber) {
            $barberName = $barber->name;
            $barber->delete();
            $this->dispatch('swal', [
                'title' => '¡Eliminado!',
                'text' => "El barbero {$barberName} ha sido eliminado correctamente.",
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        }
    }

    public function render()
    {
        $barbers = Barber::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('specialty', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.barbers.index', [
            'barbers' => $barbers
        ]);
    }
}
