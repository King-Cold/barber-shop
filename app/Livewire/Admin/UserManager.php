<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Client;
use App\Models\Barber;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';

    protected $listeners = ['deleteConfirmed' => 'delete'];

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
        return redirect()->route('users.create');
    }

    public function edit($id)
    {
        return redirect()->route('users.edit', $id);
    }

    public function confirmDelete($id)
    {
        $user = User::find($id);
        
        // Validation: Super admin cannot delete themselves
        if ($user && $user->id === auth()->id()) {
            $this->dispatch('swal', [
                'title' => 'Acción denegada',
                'text' => 'No puedes eliminar tu propia cuenta mientras estás logueado.',
                'icon' => 'error',
                'showConfirmButton' => true
            ]);
            return;
        }

        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => '¡No podrás revertir esto!',
            'icon' => 'warning',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $userName = $user->name;
            // Extra security check
            if ($user->id === auth()->id()) {
                return;
            }
            
            $user->delete();
            $this->dispatch('swal', [
                'title' => '¡Eliminado!',
                'text' => "El usuario {$userName} ha sido eliminado correctamente.",
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        }
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('role', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.users.index', [
            'users' => $users
        ]);
    }
}
