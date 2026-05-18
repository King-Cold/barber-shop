<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <x-breadcrumbs :links="[
        ['label' => 'Citas', 'url' => route('appointments')],
        ['label' => 'Agendar Nueva Cita']
    ]" />

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-xl font-bold text-slate-dark">
                <i class="fa-solid fa-calendar-plus mr-2 text-bronze-gold"></i>
                Agendar Nueva Cita
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
                    <select wire:model="client_id" id="client_id" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('client_id') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
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
                    <select wire:model.live="barber_id" id="barber_id" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('barber_id') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
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
                    <select wire:model="service_id" id="service_id" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('service_id') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
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
                    <input wire:model.live="date" type="date" id="date" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('date') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                    @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Time Selection (Reactive Slot Grid) -->
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hora de la Cita <span class="text-red-500">*</span></label>
                    
                    @php
                        $scheduleData = $this->getAvailableSlots();
                    @endphp

                    @if(empty($scheduleData))
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-amber-500"></i>
                            Por favor, selecciona un barbero y una fecha para ver las horas disponibles.
                        </div>
                    @elseif($scheduleData['status'] === 'not_working')
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                            {{ $scheduleData['message'] }}
                        </div>
                    @else
                        <div class="p-4 rounded-lg mb-3 border transition-all @error('time') bg-red-50/50 border-red-300 ring-1 ring-red-500/20 @else bg-gray-50 border-gray-200 @enderror">
                            <p class="text-xs text-gray-500 font-medium mb-3 uppercase tracking-wider">Bloques de tiempo disponibles (30 min)</p>
                            
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                                @foreach($scheduleData['slots'] as $slot)
                                    @if($slot['is_booked'])
                                        <button type="button" disabled class="px-3 py-2 text-xs font-semibold text-gray-400 bg-gray-200 border border-gray-300 rounded-lg cursor-not-allowed flex flex-col items-center justify-center gap-1 shadow-inner">
                                            <span>{{ $slot['formatted'] }}</span>
                                            <span class="text-[9px] text-red-500 uppercase tracking-widest font-bold"><i class="fa-solid fa-lock text-[8px]"></i> Ocupado</span>
                                        </button>
                                    @else
                                        @php
                                            $isSelected = ($time === $slot['time']);
                                        @endphp
                                        <button type="button" 
                                            wire:click="$set('time', '{{ $slot['time'] }}')"
                                            class="px-3 py-2 text-xs font-bold rounded-lg border transition-all flex flex-col items-center justify-center gap-1 shadow-sm {{ $isSelected ? 'bg-bronze-gold text-white border-bronze-gold ring-2 ring-bronze-gold/30' : 'bg-white text-slate-dark border-gray-300 hover:border-bronze-gold hover:text-bronze-gold hover:bg-bronze-gold/5' }}">
                                            <span>{{ $slot['formatted'] }}</span>
                                            @if($isSelected)
                                                <span class="text-[9px] text-white font-bold uppercase tracking-widest"><i class="fa-solid fa-circle-check"></i> Elegido</span>
                                            @else
                                                <span class="text-[9px] text-gray-400 font-normal uppercase tracking-widest">Disponible</span>
                                            @endif
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @error('time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Status Selection -->
                <div class="col-span-2">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Estado de la Cita <span class="text-red-500">*</span></label>
                    <select wire:model="status" id="status" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('status') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
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
                    <i class="fa-solid fa-save mr-2 text-white"></i> Guardar Cita
                </button>
            </div>
        </form>
    </div>
</div>
