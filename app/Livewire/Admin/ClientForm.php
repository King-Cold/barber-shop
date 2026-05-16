<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Client;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

#[Layout('layouts.app')]
class ClientForm extends Component
{
    use WithFileUploads;

    public $client;
    public $isEditing = false;

    public $name;
    public $email;
    public $phone;
    public $photo; // Upload
    public $currentPhoto; // Display

    public function mount(Client $client = null)
    {
        if ($client && $client->exists) {
            $this->client = $client;
            $this->isEditing = true;
            $this->name = $client->name;
            $this->email = $client->email;
            $this->phone = $client->phone;
            $this->currentPhoto = $client->photo;
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clients', 'email')->ignore($this->client?->id)],
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ];
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
            $this->photo->storeAs('clients', $photoName, 'real_public');
            $photoPath = 'images/clients/' . $photoName;
        }

        if ($this->isEditing) {
            $this->client->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'photo' => $photoPath,
            ]);

            session()->flash('swal', [
                'title' => 'Cliente Actualizado',
                'text' => 'Los datos del cliente se guardaron con éxito.',
                'icon' => 'success',
            ]);
        } else {
            Client::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'photo' => $photoPath,
            ]);

            session()->flash('swal', [
                'title' => 'Cliente Registrado',
                'text' => 'El nuevo cliente ha sido dado de alta.',
                'icon' => 'success',
            ]);
        }

        return redirect()->route('clients');
    }

    public function render()
    {
        return view($this->isEditing ? 'livewire.admin.clients.edit' : 'livewire.admin.clients.create');
    }
}
