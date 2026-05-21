import './bootstrap';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Swal = Swal;

document.addEventListener('livewire:init', () => {
    // Robust helper to extract data from multiple possible event formats in Livewire 3
    const getEventData = (event) => {
        if (!event) return null;
        
        // If it's a CustomEvent (or custom details object)
        if (event.detail) {
            return event.detail;
        }
        
        // If it's an array
        if (Array.isArray(event)) {
            return event[0];
        }
        
        // If it's an array-like object with numeric index
        if (typeof event === 'object') {
            if (event[0] !== undefined) {
                return event[0];
            }
            // If it's already the flat payload containing key properties
            if (event.title || event.text || event.icon || event.id) {
                return event;
            }
        }
        
        return event;
    };

    const handleSwal = (event) => {
        const data = getEventData(event);
        
        // Safety check: Avoid showing blank info modals
        if (!data || (!data.title && !data.text)) {
            console.warn("SweetAlert ignored empty or invalid data:", data);
            return;
        }

        Swal.fire({
            title: data.title || '',
            text: data.text || '',
            icon: data.icon || 'info',
            timer: data.timer || null,
            showConfirmButton: data.showConfirmButton ?? true,
        });
    };

    Livewire.on('swal', (event) => {
        handleSwal(event);
    });

    window.addEventListener('swal', (event) => {
        handleSwal(event.detail);
    });

    Livewire.on('swal:confirm', (event) => {
        const data = getEventData(event);
        if (!data) return;

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
                Livewire.dispatch('deleteConfirmed', [data.id]);
            }
        });
    });
});
