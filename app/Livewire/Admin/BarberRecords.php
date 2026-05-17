<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Barber;
use App\Models\Appointment;

#[Layout('layouts.app')]
class BarberRecords extends Component
{
    use WithPagination;

    public $barber;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'date';
    public $sortDirection = 'desc';

    public function mount(Barber $barber)
    {
        $this->barber = $barber;
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

    public function render()
    {
        // Query appointments for this specific barber with search and sorting
        $appointments = Appointment::where('barber_id', $this->barber->id)
            ->where(function($query) {
                $query->whereHas('client', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('service', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('date', 'like', '%' . $this->search . '%')
                ->orWhere('status', 'like', '%' . $this->search . '%');
            });

        // Apply sorting based on field
        if ($this->sortField === 'client_name') {
            $appointments = $appointments->join('clients', 'appointments.client_id', '=', 'clients.id')
                ->select('appointments.*')
                ->orderBy('clients.name', $this->sortDirection);
        } elseif ($this->sortField === 'service_name') {
            $appointments = $appointments->join('services', 'appointments.service_id', '=', 'services.id')
                ->select('appointments.*')
                ->orderBy('services.name', $this->sortDirection);
        } else {
            $appointments = $appointments->orderBy($this->sortField, $this->sortDirection);
        }

        $appointments = $appointments->with(['client', 'service'])->paginate($this->perPage);

        return view('livewire.admin.barbers.records', [
            'appointments' => $appointments
        ]);
    }
}
