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
    
    // Modal state
    public $isModalOpen = false;
    public $isEditing = false;
    
    // Form fields
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role = 'client';

    protected $listeners = ['deleteConfirmed' => 'delete'];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required|string|in:client,barber,admin,super_admin',
        ];

        if (!$this->isEditing) {
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
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
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; // Don't show password
        
        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $user = User::find($this->userId);
            
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $user->update($data);

            $this->dispatch('swal', [
                'title' => 'Usuario Actualizado',
                'text' => 'Los datos se actualizaron correctamente.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);

            // Sync to clients or barbers table
            if ($this->role === 'client') {
                Client::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => null, // Phone can be updated later in ClientManager
                ]);
            } elseif ($this->role === 'barber') {
                Barber::create([
                    'name' => $this->name,
                    'specialty' => 'General', // Default specialty
                ]);
            }

            $this->dispatch('swal', [
                'title' => 'Usuario Creado',
                'text' => 'El usuario fue registrado exitosamente.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        }

        $this->closeModal();
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
            // Extra security check
            if ($user->id === auth()->id()) {
                return;
            }
            
            $user->delete();
            $this->dispatch('swal', [
                'title' => '¡Eliminado!',
                'text' => 'El usuario ha sido eliminado.',
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
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'client';
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('role', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.user-manager', [
            'users' => $users
        ]);
    }
}
