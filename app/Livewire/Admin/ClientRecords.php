<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Client;
use App\Models\Appointment;

#[Layout('layouts.app')]
class ClientRecords extends Component
{
    use WithPagination;

    public $client;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'date';
    public $sortDirection = 'desc';

    public function mount(Client $client)
    {
        $this->client = $client;
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
        // Query appointments for this specific client with search and sorting
        $appointments = Appointment::where('client_id', $this->client->id)
            ->where(function($query) {
                $query->whereHas('barber', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('service', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('date', 'like', '%' . $this->search . '%')
                ->orWhere('status', 'like', '%' . $this->search . '%');
            });

        // Apply sorting based on field
        if ($this->sortField === 'barber_name') {
            $appointments = $appointments->join('barbers', 'appointments.barber_id', '=', 'barbers.id')
                ->select('appointments.*')
                ->orderBy('barbers.name', $this->sortDirection);
        } elseif ($this->sortField === 'service_name') {
            $appointments = $appointments->join('services', 'appointments.service_id', '=', 'services.id')
                ->select('appointments.*')
                ->orderBy('services.name', $this->sortDirection);
        } else {
            $appointments = $appointments->orderBy($this->sortField, $this->sortDirection);
        }

        $appointments = $appointments->with(['barber', 'service'])->paginate($this->perPage);

        return view('livewire.admin.clients.records', [
            'appointments' => $appointments
        ]);
    }
}
