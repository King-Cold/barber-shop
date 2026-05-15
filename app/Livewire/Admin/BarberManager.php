<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Barber;

#[Layout('layouts.app')]
class BarberManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    
    // Modal state
    public $isModalOpen = false;
    public $isEditing = false;
    
    // Form fields
    public $barberId;
    public $name;
    public $specialty;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
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
        $barber = Barber::findOrFail($id);
        $this->barberId = $barber->id;
        $this->name = $barber->name;
        $this->specialty = $barber->specialty;
        
        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $barber = Barber::find($this->barberId);
            $barber->update([
                'name' => $this->name,
                'specialty' => $this->specialty,
            ]);
            $this->dispatch('swal', [
                'title' => 'Barbero Actualizado',
                'text' => 'Los datos se actualizaron correctamente.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        } else {
            Barber::create([
                'name' => $this->name,
                'specialty' => $this->specialty,
            ]);
            $this->dispatch('swal', [
                'title' => 'Barbero Creado',
                'text' => 'El barbero fue registrado exitosamente.',
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
        $barber = Barber::find($id);
        if ($barber) {
            $barber->delete();
            $this->dispatch('swal', [
                'title' => '¡Eliminado!',
                'text' => 'El barbero ha sido eliminado.',
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
        $this->barberId = null;
        $this->name = '';
        $this->specialty = '';
        $this->resetValidation();
    }

    public function render()
    {
        $barbers = Barber::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('specialty', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.barber-manager', [
            'barbers' => $barbers
        ]);
    }
}
