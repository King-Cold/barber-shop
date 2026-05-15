<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Service;

#[Layout('layouts.app')]
class ServiceManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    
    // Modal state
    public $isModalOpen = false;
    public $isEditing = false;
    
    // Form fields
    public $serviceId;
    public $name;
    public $description;
    public $price;
    public $duration;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
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
        $service = Service::findOrFail($id);
        $this->serviceId = $service->id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->price = $service->price;
        $this->duration = $service->duration;
        
        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $service = Service::find($this->serviceId);
            $service->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'duration' => $this->duration,
            ]);
            $this->dispatch('swal', [
                'title' => 'Servicio Actualizado',
                'text' => 'Los datos se actualizaron correctamente.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        } else {
            Service::create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'duration' => $this->duration,
            ]);
            $this->dispatch('swal', [
                'title' => 'Servicio Creado',
                'text' => 'El servicio fue registrado exitosamente.',
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
        $service = Service::find($id);
        if ($service) {
            $service->delete();
            $this->dispatch('swal', [
                'title' => '¡Eliminado!',
                'text' => 'El servicio ha sido eliminado.',
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
        $this->serviceId = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->duration = '';
        $this->resetValidation();
    }

    public function render()
    {
        $services = Service::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.service-manager', [
            'services' => $services
        ]);
    }
}
