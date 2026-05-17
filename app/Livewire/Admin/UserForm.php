<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
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
    public $role_id = 1; // Default to Admin
    public $photo; // Upload
    public $currentPhoto; // Display

    public function mount(User $user = null)
    {
        if ($user && $user->exists) {
            $this->user = $user;
            $this->isEditing = true;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role_id = $user->role_id;
            $this->currentPhoto = $user->photo;
        }
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user?->id)],
            'role_id' => 'required|integer|in:1,2,3,4',
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

        $user = null;

        if ($this->isEditing) {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'photo' => $photoPath,
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $this->user->update($data);
            $user = $this->user;

            session()->flash('swal', [
                'title' => 'Usuario Actualizado',
                'text' => 'Los datos se actualizaron correctamente.',
                'icon' => 'success',
            ]);
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role_id,
                'photo' => $photoPath,
            ]);

            session()->flash('swal', [
                'title' => 'Usuario Creado',
                'text' => 'El nuevo usuario ha sido registrado.',
                'icon' => 'success',
            ]);
        }

        // --- AUTOMATIC SYNC TO BARBERS AND CLIENTS ---
        if ($user) {
            if ($user->role_id == 3) {
                // Unlink this user from any existing Client record
                \App\Models\Client::where('user_id', $user->id)->update(['user_id' => null]);
                
                // Find or create the barber linked to this user or matching email
                $barber = \App\Models\Barber::where('user_id', $user->id)
                    ->orWhere('email', $user->email)
                    ->first();
                    
                if ($barber) {
                    $barber->update([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo,
                    ]);
                } else {
                    \App\Models\Barber::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'specialty' => 'General',
                        'photo' => $user->photo,
                    ]);
                }
            } elseif ($user->role_id == 4) {
                // Unlink this user from any existing Barber record
                \App\Models\Barber::where('user_id', $user->id)->update(['user_id' => null]);
                
                // Find or create the client linked to this user or matching email
                $client = \App\Models\Client::where('user_id', $user->id)
                    ->orWhere('email', $user->email)
                    ->first();
                    
                if ($client) {
                    $client->update([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo,
                    ]);
                } else {
                    \App\Models\Client::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo,
                    ]);
                }
            } else {
                // Unlink this user from both Barbers and Clients
                \App\Models\Barber::where('user_id', $user->id)->update(['user_id' => null]);
                \App\Models\Client::where('user_id', $user->id)->update(['user_id' => null]);
            }
        }

        return redirect()->route('users');
    }

    public function render()
    {
        return view($this->isEditing ? 'livewire.admin.users.edit' : 'livewire.admin.users.create');
    }
}
