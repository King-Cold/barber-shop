<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Appointment;

/**
 * Componente Livewire: AppointmentManager
 * 
 * Este componente controla la tabla principal donde se listan todas las citas.
 * Maneja el buscador en tiempo real, la paginación, el ordenamiento de columnas
 * y la eliminación de citas.
 */
#[Layout('layouts.app')]
class AppointmentManager extends Component
{
    // Usamos este trait para habilitar la paginación dinámica sin recargar la página
    use WithPagination;

    // Propiedades públicas conectadas (bindeadas) a los inputs de la vista con wire:model
    public $search = '';
    public $perPage = 10;
    public $sortField = 'date';
    public $sortDirection = 'desc';

    // Escuchamos el evento de confirmación de SweetAlert para ejecutar el método 'delete'
    protected $listeners = ['deleteConfirmed' => 'delete'];

    /**
     * Este método mágico de Livewire se ejecuta automáticamente cada vez que 
     * el usuario escribe algo en el input de búsqueda ($search).
     */
    public function updatingSearch()
    {
        // Reseteamos a la página 1 para que los resultados de búsqueda no queden ocultos
        $this->resetPage();
    }

    /**
     * Se ejecuta cuando el usuario cambia el número de resultados por página ($perPage).
     */
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Ordena la tabla al hacer clic en los encabezados de las columnas.
     */
    public function sortBy($field)
    {
        // Si hacemos clic en la misma columna, invertimos el orden (ASC -> DESC)
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Si es una columna nueva, el orden predeterminado es ascendente
            $this->sortDirection = 'asc';
            $this->sortField = $field;
        }
    }

    /**
     * Se activa cuando el usuario presiona el botón de la "papelera" en la vista.
     * Despacha un evento hacia JavaScript para mostrar la alerta SweetAlert.
     */
    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => '¡No podrás revertir esto!',
            'icon' => 'warning',
            'id' => $id
        ]);
    }

    /**
 * Marca la cita como completada.
 */
public function markCompleted($id)
{
    // Si recibimos un array (por Livewire), extraemos el ID
    if (is_array($id)) {
        $id = $id['id'] ?? null;
    }
    $appointment = Appointment::find($id);
    if ($appointment && $appointment->status !== 'completed') {
        $appointment->update(['status' => 'completed']);
        $this->dispatch('swal', [
            'title' => '¡Actualizado!',
            'text' => "La cita #{$appointment->id} se marcó como completada.",
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
        $this->resetPage();
    }
}

    /**
     * Elimina (soft‑delete) la cita.
     * Se invoca después de la confirmación del SweetAlert.
     */
    public function delete($id)
    {
        // Soporta recibir $id como array (por Livewire)
        if (is_array($id)) {
            $id = $id['id'] ?? null;
        }
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->delete(); // Soft delete via SoftDeletes trait
            $this->dispatch('swal', [
                'title' => '¡Eliminada!',
                'text' => "La cita #{$appointment->id} se ha borrado.",
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false,
            ]);
        }
    }

    /**
     * Renderiza la vista principal del componente.
     */
    public function render()
    {
        // Realizamos una consulta a la BD trayendo sus relaciones (Eager Loading)
        // para evitar el problema de "N+1 queries" (consultas excesivas).
        $appointments = Appointment::with(['client', 'barber', 'service'])
            // Filtro dinámico: Buscamos coincidencias en el nombre del cliente...
            ->whereHas('client', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            // ...o coincidencias en el nombre del barbero
            ->orWhereHas('barber', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.appointments.index', [
            'appointments' => $appointments,
        ]);
    }
}
