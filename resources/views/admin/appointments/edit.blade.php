<x-app-layout>
<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <x-breadcrumbs :links="[
        ['label' => 'Citas', 'url' => route('admin.appointments.index')],
        ['label' => 'Editar Cita']
    ]" />

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-xl font-bold text-slate-dark">
                <i class="fa-solid fa-pen-to-square mr-2 text-bronze-gold"></i>
                Editar Cita #{{ $appointment->id }}
            </h2>
            <a href="{{ route('admin.appointments.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Cancelar
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Client Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-2">Cliente <span class="text-red-500">*</span></label>
                    <select name="client_id" id="client_id" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm bg-white @error('client_id') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                        <option value="">Selecciona un cliente</option>
                        @foreach($clientsList as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $appointment->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->phone ?? 'Sin teléfono' }})</option>
                        @endforeach
                    </select>
                    @error('client_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Barber Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label for="barber_id" class="block text-sm font-semibold text-gray-700 mb-2">Barbero <span class="text-red-500">*</span></label>
                    <select name="barber_id" id="barber_id" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm bg-white @error('barber_id') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                        <option value="">Selecciona un barbero</option>
                        @foreach($barbersList as $barber)
                            <option value="{{ $barber->id }}" {{ old('barber_id', $appointment->barber_id) == $barber->id ? 'selected' : '' }}>{{ $barber->name }} - {{ $barber->specialty ?? 'General' }}</option>
                        @endforeach
                    </select>
                    @error('barber_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Service Selection -->
                <div class="col-span-2">
                    <label for="service_id" class="block text-sm font-semibold text-gray-700 mb-2">Servicio <span class="text-red-500">*</span></label>
                    <select name="service_id" id="service_id" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm bg-white @error('service_id') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                        <option value="">Selecciona un servicio</option>
                        @foreach($servicesList as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>{{ $service->name }} - ${{ number_format($service->price, 2) }} ({{ $service->duration }} min)</option>
                        @endforeach
                    </select>
                    @error('service_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Date Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">Fecha <span class="text-red-500">*</span></label>
                    <input name="date" value="{{ old('date', $appointment->date) }}" type="date" id="date" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('date') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                    @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Time Selection (Reactive Slot Grid) -->
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hora de la Cita <span class="text-red-500">*</span></label>
                    <input type="hidden" name="time" id="selected_time" value="{{ old('time', $appointment->time) }}">
                    
                    <div id="slots-wrapper">
                        <!-- Slots grid will load dynamically via JS -->
                    </div>
                    
                    @error('time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Status Selection -->
                <div class="col-span-2">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Estado de la Cita <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm bg-white @error('status') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                        <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completada</option>
                        <option value="canceled" {{ old('status', $appointment->status) == 'canceled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="mt-12 flex items-center justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('admin.appointments.index') }}" class="px-8 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-55 transition-all shadow-sm">
                    Cancelar
                </a>
                <button type="submit" class="px-10 py-3 text-sm font-bold text-white bg-bronze-gold hover:bg-yellow-600 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-save mr-2 text-white"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const slotsUrl = "{{ route('admin.appointments.slots') }}";
    const appointmentId = "{{ $appointment->id }}";

    function loadSlots() {
        const barberId = document.getElementById('barber_id').value;
        const date = document.getElementById('date').value;
        const container = document.getElementById('slots-wrapper');
        
        if (!barberId || !date) {
            container.innerHTML = `
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-amber-500"></i>
                    Por favor, selecciona un barbero y una fecha para ver las horas disponibles.
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="p-4 text-center text-gray-500 text-sm">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i> Cargando horarios disponibles...
            </div>
        `;

        let url = `${slotsUrl}?barber_id=${barberId}&date=${date}&appointment_id=${appointmentId}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'not_working') {
                    container.innerHTML = `
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                            ${data.message}
                        </div>
                    `;
                    return;
                }

                if (!data.slots || data.slots.length === 0) {
                    container.innerHTML = `
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-gray-400"></i>
                            No hay horarios disponibles para la fecha seleccionada.
                        </div>
                    `;
                    return;
                }

                let html = `
                    <div class="p-4 rounded-lg mb-3 border bg-gray-50 border-gray-200">
                        <p class="text-xs text-gray-500 font-medium mb-3 uppercase tracking-wider">Bloques de tiempo disponibles (30 min)</p>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                `;

                const selectedTimeInput = document.getElementById('selected_time');
                const currentSelected = selectedTimeInput.value;

                data.slots.forEach(slot => {
                    if (slot.is_booked) {
                        html += `
                            <button type="button" disabled class="px-3 py-2 text-xs font-semibold text-gray-400 bg-gray-200 border border-gray-300 rounded-lg cursor-not-allowed flex flex-col items-center justify-center gap-1 shadow-inner w-full">
                                <span>${slot.formatted}</span>
                                <span class="text-[9px] text-red-500 uppercase tracking-widest font-bold"><i class="fa-solid fa-lock text-[8px]"></i> Ocupado</span>
                            </button>
                        `;
                    } else {
                        const isSelected = currentSelected === slot.time;
                        const btnClass = isSelected 
                            ? 'bg-bronze-gold text-white border-bronze-gold ring-2 ring-bronze-gold/30' 
                            : 'bg-white text-slate-dark border-gray-300 hover:border-bronze-gold hover:text-bronze-gold hover:bg-bronze-gold/5';
                        
                        const spanText = isSelected
                            ? '<span class="text-[9px] text-white font-bold uppercase tracking-widest"><i class="fa-solid fa-circle-check"></i> Elegido</span>'
                            : '<span class="text-[9px] text-gray-400 font-normal uppercase tracking-widest">Disponible</span>';

                        html += `
                            <button type="button" 
                                onclick="selectSlot(this, '${slot.time}')"
                                class="slot-btn px-3 py-2 text-xs font-bold rounded-lg border transition-all flex flex-col items-center justify-center gap-1 shadow-sm w-full ${btnClass}">
                                <span>${slot.formatted}</span>
                                ${spanText}
                            </button>
                        `;
                    }
                });

                html += `
                        </div>
                    </div>
                `;
                container.innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = `
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        Error al cargar los horarios disponibles.
                    </div>
                `;
            });
    }

    function selectSlot(button, time) {
        document.getElementById('selected_time').value = time;
        
        document.querySelectorAll('.slot-btn').forEach(btn => {
            btn.className = 'slot-btn px-3 py-2 text-xs font-bold rounded-lg border transition-all flex flex-col items-center justify-center gap-1 shadow-sm w-full bg-white text-slate-dark border-gray-300 hover:border-bronze-gold hover:text-bronze-gold hover:bg-bronze-gold/5';
            const label = btn.querySelector('span:last-child');
            if (label) {
                label.className = 'text-[9px] text-gray-400 font-normal uppercase tracking-widest';
                label.innerHTML = 'Disponible';
            }
        });

        button.className = 'slot-btn px-3 py-2 text-xs font-bold rounded-lg border transition-all flex flex-col items-center justify-center gap-1 shadow-sm w-full bg-bronze-gold text-white border-bronze-gold ring-2 ring-bronze-gold/30';
        const label = button.querySelector('span:last-child');
        if (label) {
            label.className = 'text-[9px] text-white font-bold uppercase tracking-widest';
            label.innerHTML = '<i class="fa-solid fa-circle-check"></i> Elegido';
        }
    }

    document.getElementById('barber_id').addEventListener('change', loadSlots);
    document.getElementById('date').addEventListener('change', loadSlots);
    document.addEventListener('DOMContentLoaded', loadSlots);
</script>
</x-app-layout>
