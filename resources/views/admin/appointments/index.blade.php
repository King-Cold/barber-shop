<x-app-layout>
    <div class="mb-6">
        <x-breadcrumbs :links="[
            ['label' => 'Citas']
        ]" />
        <h1 class="text-2xl font-bold text-slate-dark">Gestión de Citas</h1>
        <p class="text-gray-500 text-sm mt-1">Controla la agenda y el estado de las reservas.</p>
    </div>

    <!-- Top Action Bar (GET Form) -->
    <form method="GET" action="{{ route('admin.appointments.index') }}" class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0 bg-white p-4 rounded-xl shadow-sm border border-gray-100 font-sans">
        <div class="flex items-center space-x-2 w-full md:w-auto">
            <span class="text-sm text-gray-500">Mostrar</span>
            <select name="per_page" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-bronze-gold focus:border-bronze-gold block p-2 shadow-sm">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-sm text-gray-500">registros</span>
        </div>
        
        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
            <div class="relative w-full md:w-80">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-bronze-gold focus:border-bronze-gold block w-full pl-10 p-2.5 shadow-sm" placeholder="Buscar por cliente o barbero...">
            </div>
            
            <input type="hidden" name="sort" value="{{ $sortField }}">
            <input type="hidden" name="direction" value="{{ $sortDirection }}">

            <div class="flex space-x-2 w-full md:w-auto">
                <button type="submit" class="bg-bronze-gold hover:bg-bronze-gold/90 text-white font-medium rounded-lg text-sm px-4 py-2.5 text-center transition-colors shadow-sm whitespace-nowrap">
                    Buscar
                </button>
                <a href="{{ route('admin.appointments.create') }}" class="w-full md:w-auto bg-slate-dark hover:bg-slate-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center justify-center transition-colors shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-calendar-plus mr-2"></i> Nueva Cita
                </a>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            <a href="{{ route('admin.appointments.index', ['sort' => 'id', 'direction' => ($sortField === 'id' && $sortDirection === 'asc') ? 'desc' : 'asc', 'search' => $search, 'per_page' => $perPage]) }}" class="flex items-center">
                                ID
                                @if($sortField === 'id')
                                    <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1.5"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1.5 text-gray-300"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            <a href="{{ route('admin.appointments.index', ['sort' => 'date', 'direction' => ($sortField === 'date' && $sortDirection === 'asc') ? 'desc' : 'asc', 'search' => $search, 'per_page' => $perPage]) }}" class="flex items-center">
                                Fecha y Hora
                                @if($sortField === 'date')
                                    <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1.5"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1.5 text-gray-300"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Barbero / Servicio
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            <a href="{{ route('admin.appointments.index', ['sort' => 'status', 'direction' => ($sortField === 'status' && $sortDirection === 'asc') ? 'desc' : 'asc', 'search' => $search, 'per_page' => $perPage]) }}" class="flex items-center">
                                Estado
                                @if($sortField === 'status')
                                    <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1.5"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1.5 text-gray-300"></i>
                                @endif
                            </a>
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
                                    @php $client = $appointment->client; @endphp
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-dark font-bold mr-3 border border-slate-200 overflow-hidden">
                                        @if($client && $client->photo)
                                            <img src="{{ asset($client->photo) }}" class="w-full h-full object-cover rounded-full" loading="lazy">
                                        @elseif($client)
                                            {{ substr($client->name, 0, 1) }}
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-dark">{{ optional($client)->name ?? 'Sin cliente' }}</div>
                                        <div class="text-xs text-gray-500">{{ optional($client)->phone ?? 'Sin teléfono' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-bronze-gold/10 flex items-center justify-center text-bronze-gold font-bold mr-3 border border-bronze-gold/20 overflow-hidden">
                                        @if($appointment->barber && $appointment->barber->photo)
                                            <img src="{{ asset($appointment->barber->photo) }}" class="w-full h-full object-cover rounded-full" loading="lazy">
                                        @elseif($appointment->barber)
                                            {{ substr($appointment->barber->name, 0, 1) }}
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-dark">
                                            {{ optional($appointment->barber)->name ?? 'Sin barbero' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <span class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200">{{ optional($appointment->service)->name ?? 'Sin servicio' }}</span>
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
                                <a href="{{ route('admin.appointments.ticket', $appointment->id) }}" target="_blank" class="inline-flex items-center justify-center text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-md transition-colors mr-2" title="Descargar Ticket">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="inline-flex items-center justify-center text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-md transition-colors mr-2" title="Editar Cita">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                @if($appointment->status !== 'completed')
                                    <form id="complete-form-{{ $appointment->id }}" action="{{ route('admin.appointments.complete', $appointment->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="confirmComplete('{{ $appointment->id }}')" class="inline-flex items-center justify-center text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-2 rounded-md transition-colors mr-2" title="Marcar como completada">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <form id="delete-form-{{ $appointment->id }}" action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $appointment->id }}')" class="inline-flex items-center justify-center text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors" title="Eliminar Cita">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
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

    @push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function confirmComplete(id) {
            Swal.fire({
                title: '¿Marcar como completada?',
                text: `Estás a punto de marcar la cita #${id} como completada.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, completar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('complete-form-' + id).submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
