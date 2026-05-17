<div>
    <div class="mb-6">
        <x-breadcrumbs :links="[
            ['label' => 'Citas']
        ]" />
        <h1 class="text-2xl font-bold text-slate-dark">Gestión de Citas</h1>
        <p class="text-gray-500 text-sm mt-1">Controla la agenda y el estado de las reservas.</p>
    </div>

    <!-- Top Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-2 w-full md:w-auto">
            <span class="text-sm text-gray-500">Mostrar</span>
            <select wire:model.live="perPage" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-bronze-gold focus:border-bronze-gold block p-2 shadow-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-500">registros</span>
        </div>
        
        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
            <div class="relative w-full md:w-80">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-bronze-gold focus:border-bronze-gold block w-full pl-10 p-2.5 shadow-sm" placeholder="Buscar por cliente o barbero...">
            </div>
            <a href="{{ route('appointments.create') }}" class="w-full md:w-auto bg-slate-dark hover:bg-slate-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center justify-center transition-colors shadow-sm whitespace-nowrap" wire:navigate>
                <i class="fa-solid fa-calendar-plus mr-2"></i> Nueva Cita
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th wire:click="sortBy('id')" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            ID
                            @if($sortField === 'id')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('date')" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            Fecha y Hora
                            @if($sortField === 'date')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Barbero / Servicio
                        </th>
                        <th wire:click="sortBy('status')" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            Estado
                            @if($sortField === 'status')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                #{{ $appointment->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-dark"><i class="fa-regular fa-calendar mr-1 text-gray-400"></i> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500 mt-1"><i class="fa-regular fa-clock mr-1 text-bronze-gold"></i> {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-dark font-bold mr-3 border border-slate-200 overflow-hidden">
                                        @if($appointment->client->photo)
                                            <img src="{{ asset($appointment->client->photo) }}" class="w-full h-full object-cover rounded-full" loading="lazy">
                                        @else
                                            {{ substr($appointment->client->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-dark">{{ $appointment->client->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $appointment->client->phone ?? 'Sin teléfono' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-bronze-gold/10 flex items-center justify-center text-bronze-gold font-bold mr-3 border border-bronze-gold/20 overflow-hidden">
                                        @if($appointment->barber->photo)
                                            <img src="{{ asset($appointment->barber->photo) }}" class="w-full h-full object-cover rounded-full" loading="lazy">
                                        @else
                                            {{ substr($appointment->barber->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-dark">
                                            {{ $appointment->barber->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <span class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200">{{ $appointment->service->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($appointment->status === 'pending')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-yellow-100 text-yellow-800">Pendiente</span>
                                @elseif($appointment->status === 'confirmed')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-blue-100 text-blue-800">Confirmada</span>
                                @elseif($appointment->status === 'completed')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">Completada</span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-gray-100 text-gray-800">Cancelada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('appointments.ticket', $appointment->id) }}" target="_blank" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-md transition-colors mr-2 inline-block" title="Descargar Ticket">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-md transition-colors mr-2 inline-block" title="Editar Cita" wire:navigate>
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button wire:click="confirmDelete({{ $appointment->id }})" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium text-gray-600">No se encontraron citas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="text-sm text-gray-500">
                Mostrando {{ $appointments->firstItem() ?? 0 }} a {{ $appointments->lastItem() ?? 0 }} de {{ $appointments->total() }} registros
            </div>
            @if($appointments->hasPages())
                <div class="w-full md:w-auto">
                    {{ $appointments->links() }}
                </div>
            @endif
    </div>
</div>
