import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;

document.addEventListener('livewire:init', () => {
    Livewire.on('swal', (event) => {
        const data = event[0];
        Swal.fire({
            title: data.title || '',
            text: data.text || '',
            icon: data.icon || 'info',
            timer: data.timer || null,
            showConfirmButton: data.showConfirmButton ?? true,
        });
    });

    Livewire.on('swal:confirm', (event) => {
        const data = event[0];
        Swal.fire({
            title: data.title || '¿Estás seguro?',
            text: data.text || "¡No podrás revertir esto!",
            icon: data.icon || 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteConfirmed', { id: data.id });
            }
        });
    });
});
