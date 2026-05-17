<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
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
        
        // Security Check: Cannot delete self
        if ($user && $user->id === auth()->id()) {
            $this->dispatch('swal', [
                'title' => 'Acción denegada',
                'text' => 'No puedes eliminar tu propia cuenta.',
                'icon' => 'error',
                'showConfirmButton' => true
            ]);
            return;
        }

        // Security Check: Only SuperAdmin can delete other SuperAdmins (or prevent it entirely)
        if ($user && $user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            $this->dispatch('swal', [
                'title' => 'Acceso Denegado',
                'text' => 'Solo un Super Administrador puede gestionar a otros Super Administradores.',
                'icon' => 'error',
                'showConfirmButton' => true
            ]);
            return;
        }

        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => "Estás a punto de eliminar a {$user->name}. ¡No podrás revertir esto!",
            'icon' => 'warning',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $userName = $user->name;
            
            // Final security check
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
        $users = User::with('role')
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.users.index', compact('users'));
    }
}
