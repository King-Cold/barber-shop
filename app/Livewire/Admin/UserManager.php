<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

/**
 * Componente Livewire: UserManager
 * 
 * Este componente administra la tabla de usuarios del sistema (Admins y SuperAdmins).
 * Incluye lógica vital de seguridad para evitar que los usuarios eliminen
 * su propia cuenta o la del administrador principal.
 */
#[Layout('layouts.app')]
class UserManager extends Component
{
    use WithPagination;

    // Variables de la tabla (Buscador, resultados por página, ordenamiento)
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';

    // Escucha el evento de confirmación de SweetAlert
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

    // Redirige al formulario de creación
    public function create()
    {
        return redirect()->route('admin.users.create');
    }

    // Redirige al formulario de edición del usuario seleccionado
    public function edit($id)
    {
        return redirect()->route('admin.users.edit', $id);
    }

    /**
     * Valida si se puede eliminar a un usuario ANTES de mostrar la alerta.
     * Aquí se aplican reglas de negocio críticas de seguridad.
     */
    public function confirmDelete($id)
    {
        $user = User::find($id);
        
        // REGLA 1: No puedes eliminar tu propia cuenta mientras estás logueado.
        if ($user && $user->id === auth()->id()) {
            $this->dispatch('swal', [
                'title' => 'Acción denegada',
                'text' => 'No puedes eliminar tu propia cuenta.',
                'icon' => 'error',
                'showConfirmButton' => true
            ]);
            return;
        }

        // REGLA 2: No se puede eliminar al primer usuario creado (Generalmente el dueño o creador).
        if ($user && $user->id === 1) {
            $this->dispatch('swal', [
                'title' => 'Acción denegada',
                'text' => 'La cuenta del Super Administrador Principal (ID 1) está protegida y no puede ser eliminada.',
                'icon' => 'error',
                'showConfirmButton' => true
            ]);
            return;
        }

        // REGLA 3: Un Administrador normal no puede eliminar a un Super Administrador.
        if ($user && $user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            $this->dispatch('swal', [
                'title' => 'Acceso Denegado',
                'text' => 'Solo un Super Administrador puede gestionar a otros Super Administradores.',
                'icon' => 'error',
                'showConfirmButton' => true
            ]);
            return;
        }

        // Si pasa todas las pruebas, muestra la alerta preguntando si está seguro.
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => "Estás a punto de eliminar a {$user->name}. ¡No podrás revertir esto!",
            'icon' => 'warning',
            'id' => $id
        ]);
    }

    /**
     * Método final que elimina al usuario.
     */
    public function delete($id)
    {
        if (is_array($id)) {
            $id = $id['id'] ?? null;
        }
        $user = User::find($id);
        if ($user) {
            $userName = $user->name;
            
            // Verificación doble de seguridad justo antes de borrar (backend).
            if ($user->id === auth()->id()) {
                return;
            }

            if ($user->id === 1) {
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

    /**
     * Renderiza la tabla de usuarios buscando por nombre o correo.
     */
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
