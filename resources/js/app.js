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
});
