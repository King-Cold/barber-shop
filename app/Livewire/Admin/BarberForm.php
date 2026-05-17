<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Barber;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

#[Layout('layouts.app')]
class BarberForm extends Component
{
    use WithFileUploads;

    public $barber;
    public $isEditing = false;

    // Barber fields
    public $name;
    public $specialty;
    public $phone;
    public $email;
    public $address;
    public $photo; // For uploading
    public $currentPhoto; // For displaying existing

    public function mount(Barber $barber = null)
    {
        if ($barber && $barber->exists) {
            $this->barber = $barber;
            $this->isEditing = true;
            $this->name = $barber->name;
            $this->specialty = $barber->specialty;
            $this->phone = $barber->phone;
            $this->email = $barber->email;
            $this->address = $barber->address;
            $this->currentPhoto = $barber->photo;
        } else {
            $this->specialty = 'General';
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => ['required', 'email', Rule::unique('barbers', 'email')->ignore($this->barber?->id)],
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    public function save()
    {
        $this->validate();

        $photoPath = $this->currentPhoto;
        if ($this->photo) {
            // Delete old photo if exists in public/images/barbers
            if ($this->currentPhoto && File::exists(public_path($this->currentPhoto))) {
                File::delete(public_path($this->currentPhoto));
            }
            
            $photoName = time() . '_' . $this->photo->getClientOriginalName();
            $this->photo->storeAs('barbers', $photoName, 'real_public');
            $photoPath = 'images/barbers/' . $photoName;
        }

        if ($this->isEditing) {
            $this->barber->update([
                'name' => $this->name,
                'specialty' => $this->specialty,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'photo' => $photoPath,
            ]);

            session()->flash('swal', [
                'title' => 'Barbero Actualizado',
                'text' => 'Los datos profesionales se guardaron con éxito.',
                'icon' => 'success',
            ]);
        } else {
            Barber::create([
                'name' => $this->name,
                'specialty' => $this->specialty,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'photo' => $photoPath,
            ]);

            session()->flash('swal', [
                'title' => 'Barbero Registrado',
                'text' => 'El nuevo barbero ha sido dado de alta.',
                'icon' => 'success',
            ]);
        }

        return redirect()->route('barbers');
    }

    public function render()
    {
        return view($this->isEditing ? 'livewire.admin.barbers.edit' : 'livewire.admin.barbers.create');
    }
}
