<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <x-breadcrumbs :links="[
        ['label' => 'Citas', 'url' => route('appointments')],
        ['label' => 'Editar Cita']
    ]" />

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-xl font-bold text-slate-dark">
                <i class="fa-solid fa-pen-to-square mr-2 text-bronze-gold"></i>
                Editar Cita #{{ $appointment->id }}
            </h2>
            <a href="{{ route('appointments') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors" wire:navigate>
                <i class="fa-solid fa-arrow-left mr-1"></i> Cancelar
            </a>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="save" class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Client Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-2">Cliente <span class="text-red-500">*</span></label>
                    <select wire:model="client_id" id="client_id" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                        <option value="">Selecciona un cliente</option>
                        @foreach($clientsList as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->phone ?? 'Sin teléfono' }})</option>
                        @endforeach
                    </select>
                    @error('client_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Barber Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label for="barber_id" class="block text-sm font-semibold text-gray-700 mb-2">Barbero <span class="text-red-500">*</span></label>
                    <select wire:model="barber_id" id="barber_id" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                        <option value="">Selecciona un barbero</option>
                        @foreach($barbersList as $barber)
                            <option value="{{ $barber->id }}">{{ $barber->name }} - {{ $barber->specialty ?? 'General' }}</option>
                        @endforeach
                    </select>
                    @error('barber_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Service Selection -->
                <div class="col-span-2">
                    <label for="service_id" class="block text-sm font-semibold text-gray-700 mb-2">Servicio <span class="text-red-500">*</span></label>
                    <select wire:model="service_id" id="service_id" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                        <option value="">Selecciona un servicio</option>
                        @foreach($servicesList as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} - ${{ number_format($service->price, 2) }} ({{ $service->duration }} min)</option>
                        @endforeach
                    </select>
                    @error('service_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Date Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">Fecha <span class="text-red-500">*</span></label>
                    <input wire:model="date" type="date" id="date" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                    @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Time Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label for="time" class="block text-sm font-semibold text-gray-700 mb-2">Hora <span class="text-red-500">*</span></label>
                    <input wire:model="time" type="time" id="time" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                    @error('time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Status Selection -->
                <div class="col-span-2">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Estado de la Cita <span class="text-red-500">*</span></label>
                    <select wire:model="status" id="status" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                        <option value="pending">Pendiente</option>
                        <option value="confirmed">Confirmada</option>
                        <option value="completed">Completada</option>
                        <option value="canceled">Cancelada</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="mt-12 flex items-center justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('appointments') }}" class="px-8 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-55 transition-all shadow-sm" wire:navigate>
                    Cancelar
                </a>
                <button type="submit" class="px-10 py-3 text-sm font-bold text-white bg-bronze-gold hover:bg-yellow-600 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-save mr-2 text-white"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
