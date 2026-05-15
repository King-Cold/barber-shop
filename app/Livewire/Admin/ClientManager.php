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
    public $sortField = 'id';
    public $sortDirection = 'desc';
    
    // Modal state
    public $isModalOpen = false;
    public $isEditing = false;
    
    // Form fields
    public $clientId;
    public $name;
    public $email;
    public $phone;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email,' . $this->clientId,
            'phone' => 'nullable|string|max:20',
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
        $this->isEditing = false;
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->email = $client->email;
        $this->phone = $client->phone;
        
        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $client = Client::find($this->clientId);
            $client->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ]);
            $this->dispatch('swal', [
                'title' => 'Cliente Actualizado',
                'text' => 'Los datos se actualizaron correctamente.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        } else {
            Client::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ]);
            $this->dispatch('swal', [
                'title' => 'Cliente Creado',
                'text' => 'El cliente fue registrado exitosamente.',
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
        $client = Client::find($id);
        if ($client) {
            $client->delete();
            $this->dispatch('swal', [
                'title' => '¡Eliminado!',
                'text' => 'El cliente ha sido eliminado.',
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
        $this->clientId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->resetValidation();
    }

    public function render()
    {
        $clients = Client::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.client-manager', [
            'clients' => $clients
        ]);
    }
}
