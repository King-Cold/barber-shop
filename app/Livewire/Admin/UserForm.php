<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

#[Layout('layouts.app')]
class UserForm extends Component
{
    use WithFileUploads;

    public $user;
    public $isEditing = false;

    // Form fields
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'admin';
    public $photo; // Upload
    public $currentPhoto; // Display

    public function mount(User $user = null)
    {
        if ($user && $user->exists) {
            $this->user = $user;
            $this->isEditing = true;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->currentPhoto = $user->photo;
        }
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user?->id)],
            'role' => 'required|string|in:admin,super_admin',
            'photo' => 'nullable|image|max:2048',
        ];

        if (!$this->isEditing) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        $photoPath = $this->currentPhoto;
        if ($this->photo) {
            if ($this->currentPhoto && File::exists(public_path($this->currentPhoto))) {
                File::delete(public_path($this->currentPhoto));
            }
            
            $photoName = time() . '_' . $this->photo->getClientOriginalName();
            $this->photo->storeAs('users', $photoName, 'real_public');
            $photoPath = 'images/users/' . $photoName;
        }

        if ($this->isEditing) {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'photo' => $photoPath,
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $this->user->update($data);

            session()->flash('swal', [
                'title' => 'Usuario Actualizado',
                'text' => 'Los datos se actualizaron correctamente.',
                'icon' => 'success',
            ]);
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'photo' => $photoPath,
            ]);

            session()->flash('swal', [
                'title' => 'Usuario Creado',
                'text' => 'El usuario fue registrado exitosamente.',
                'icon' => 'success',
            ]);
        }

        return redirect()->route('users');
    }

    public function render()
    {
        return view($this->isEditing ? 'livewire.admin.users.edit' : 'livewire.admin.users.create');
    }
}
