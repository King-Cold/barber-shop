<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Service;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ServiceForm extends Component
{
    public $service;
    public $isEditing = false;

    public $name;
    public $description;
    public $price;
    public $duration;

    public function mount(Service $service = null)
    {
        if ($service && $service->exists) {
            $this->service = $service;
            $this->isEditing = true;
            $this->name = $service->name;
            $this->description = $service->description;
            $this->price = $service->price;
            $this->duration = $service->duration;
        } else {
            $this->duration = 30;
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $this->service->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'duration' => $this->duration,
            ]);

            session()->flash('swal', [
                'title' => 'Servicio Actualizado',
                'text' => 'El servicio se modificó correctamente.',
                'icon' => 'success',
            ]);
        } else {
            Service::create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'duration' => $this->duration,
            ]);

            session()->flash('swal', [
                'title' => 'Servicio Creado',
                'text' => 'El nuevo servicio ha sido agregado al catálogo.',
                'icon' => 'success',
            ]);
        }

        return redirect()->route('services');
    }

    public function render()
    {
        return view($this->isEditing ? 'livewire.admin.services.edit' : 'livewire.admin.services.create');
    }
}
