<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Client;

#[Layout('layouts.app')]
class ClientManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $selectedClient = null;
    public $viewingClientAppointments = null;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function viewAppointments($clientId)
    {
        $this->selectedClient = Client::find($clientId);
        if ($this->selectedClient) {
            $this->viewingClientAppointments = $this->selectedClient->appointments()
                ->with(['barber', 'service'])
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->get();
        }
    }

    public function closeAppointmentsModal()
    {
        $this->selectedClient = null;
        $this->viewingClientAppointments = null;
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
        return redirect()->route('clients.create');
    }

    public function edit($id)
    {
        return redirect()->route('clients.edit', $id);
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
        $client = Client::find($id);
        if ($client) {
            $clientName = $client->name;
            $client->delete();
            $this->dispatch('swal', [
                'title' => '¡Eliminado!',
                'text' => "El cliente {$clientName} ha sido eliminado correctamente.",
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        }
    }

    public function render()
    {
        $clients = Client::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.clients.index', [
            'clients' => $clients
        ]);
    }
}
