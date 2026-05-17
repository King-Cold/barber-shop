<div>
    <!-- Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <x-breadcrumbs :links="[
                ['label' => 'Barberos', 'url' => route('barbers')],
                ['label' => 'Historial de Citas']
            ]" />
            <h1 class="text-2xl font-bold text-slate-dark mt-1">Historial de Citas</h1>
            <p class="text-gray-500 text-sm">Consulta todos los registros y servicios realizados por este profesional.</p>
        </div>
        <div>
            <a href="{{ route('barbers') }}" class="inline-flex items-center bg-barber-black hover:bg-slate-800 text-white font-medium rounded-lg text-sm px-4 py-2.5 shadow-sm transition-colors font-sans" wire:navigate>
                <i class="fa-solid fa-arrow-left mr-2 text-vintage-gold"></i> Volver a Barberos
            </a>
        </div>
    </div>

    <!-- Professional Profile Banner -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
        <div class="absolute left-0 top-0 h-full w-1.5 bg-vintage-gold"></div>
        
        <!-- Large Avatar -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-tr from-vintage-gold to-yellow-600 rounded-full blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
            <div class="relative w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center text-vintage-gold font-bold text-3xl border-2 border-vintage-gold/30 overflow-hidden shadow-md">
                @if($barber->photo)
                    <img src="{{ asset($barber->photo) }}" class="w-full h-full object-cover">
                @else
                    {{ substr($barber->name, 0, 1) }}
                @endif
            </div>
        </div>

        <!-- Professional Details -->
        <div class="flex-1 text-center md:text-left">
            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-md bg-vintage-gold/15 text-vintage-gold border border-vintage-gold/20 uppercase tracking-widest font-barber">
                {{ $barber->specialty ?? 'Especialista General' }}
            </span>
            <h2 class="text-2xl font-bold text-slate-dark mt-2 font-cinzel tracking-wide">{{ $barber->name }}</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4 text-sm text-gray-500 font-sans">
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <i class="fa-solid fa-envelope text-vintage-gold"></i>
                    <span>{{ $barber->email ?? 'Sin correo registrado' }}</span>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <i class="fa-solid fa-phone text-vintage-gold"></i>
                    <span>{{ $barber->phone ?? 'Sin teléfono registrado' }}</span>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <i class="fa-solid fa-id-card text-vintage-gold"></i>
                    <span>ID de Empleado: #{{ $barber->id }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top DataTable Filter Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-2 w-full md:w-auto">
            <span class="text-sm text-gray-500 font-sans">Mostrar</span>
            <select wire:model.live="perPage" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-vintage-gold focus:border-vintage-gold block p-2 shadow-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-500 font-sans">registros</span>
        </div>
        
        <div class="relative w-full md:w-80">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fa-solid fa-search text-gray-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-vintage-gold focus:border-vintage-gold block w-full pl-10 p-2.5 shadow-sm" placeholder="Buscar por cliente, fecha, estado...">
        </div>
    </div>

    <!-- Appointments History Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-150">
                        <th wire:click="sortBy('date')" class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs cursor-pointer hover:bg-slate-100 transition-colors">
                            Fecha y Hora
                            @if($sortField === 'date')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('client_name')" class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs cursor-pointer hover:bg-slate-100 transition-colors">
                            Cliente
                            @if($sortField === 'client_name')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('service_name')" class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs cursor-pointer hover:bg-slate-100 transition-colors">
                            Servicio contratado
                            @if($sortField === 'service_name')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs">
                            Costo total
                        </th>
                        <th wire:click="sortBy('status')" class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-xs cursor-pointer hover:bg-slate-100 transition-colors">
                            Estado
                            @if($sortField === 'status')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-dark">
                                {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} <br>
                                <span class="text-[11px] text-gray-400 font-normal"><i class="fa-regular fa-clock text-vintage-gold mr-1"></i> {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-vintage-gold font-bold mr-3 border border-vintage-gold/20 overflow-hidden shadow-sm">
                                        @if($appointment->client->photo)
                                            <img src="{{ asset($appointment->client->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($appointment->client->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-dark">{{ $appointment->client->name }}</div>
                                        <span class="text-xs text-gray-400"><i class="fa-solid fa-phone text-[10px] mr-1"></i> {{ $appointment->client->phone ?? 'Sin teléfono' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $appointment->service->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-dark">
                                ${{ number_format($appointment->service->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($appointment->status === 'pending')
                                    <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-md bg-yellow-50 text-yellow-700 border border-yellow-150">Pendiente</span>
                                @elseif($appointment->status === 'confirmed')
                                    <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-md bg-blue-50 text-blue-700 border border-blue-150">Confirmada</span>
                                @elseif($appointment->status === 'completed')
                                    <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-md bg-green-50 text-green-700 border border-green-150">Completada</span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-md bg-gray-50 text-gray-700 border border-gray-150">{{ ucfirst($appointment->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center py-6">
                                    <i class="fa-regular fa-calendar-xmark text-5xl text-vintage-gold/60 mb-3.5"></i>
                                    <p class="text-base font-bold text-gray-700">No se encontraron citas en el historial.</p>
                                    <p class="text-xs text-gray-400 mt-1">Este profesional no tiene citas registradas o que coincidan con la búsqueda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- DataTable Pagination Summary -->
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="text-sm text-gray-500 font-sans">
                Mostrando {{ $appointments->firstItem() ?? 0 }} a {{ $appointments->lastItem() ?? 0 }} de {{ $appointments->total() }} registros
            </div>
            @if($appointments->hasPages())
                <div class="w-full md:w-auto">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
