<div>
    <div class="mb-6">
        <x-breadcrumbs :links="[
            ['label' => 'Clientes']
        ]" />
        <h1 class="text-2xl font-bold text-slate-dark">Base de Clientes</h1>
        <p class="text-gray-500 text-sm mt-1">Gestiona la información de contacto y fidelidad de tus clientes.</p>
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
                <input wire:model.live.debounce.300ms="search" type="text" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-bronze-gold focus:border-bronze-gold block w-full pl-10 p-2.5 shadow-sm" placeholder="Buscar por nombre o email...">
            </div>
            <a href="{{ route('admin.clients.create') }}" class="w-full md:w-auto bg-slate-dark hover:bg-slate-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center justify-center transition-colors shadow-sm whitespace-nowrap">
                <i class="fa-solid fa-plus mr-2"></i> Nuevo Cliente
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
                            <div class="flex items-center">
                                ID
                                @if($sortField === 'id')
                                    <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1.5"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1.5 text-gray-300"></i>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('name')" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                Cliente
                                @if($sortField === 'name')
                                    <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1.5"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1.5 text-gray-300"></i>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Teléfono
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Dirección
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($clients as $client)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                #{{ $client->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-dark font-bold mr-3 border border-slate-200 overflow-hidden">
                                        @if($client->photo)
                                            <img src="{{ asset($client->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($client->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="font-medium text-slate-dark">{{ $client->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-dark">
                                {{ $client->email ?? 'Sin correo' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $client->phone ?? 'Sin teléfono' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $client->address }}">
                                {{ $client->address ?? 'Sin dirección' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.clients.records', $client->id) }}" class="inline-flex items-center justify-center text-vintage-gold hover:text-yellow-600 bg-vintage-gold/10 hover:bg-vintage-gold/20 p-2 rounded-md transition-colors mr-2" title="Ver Historial de Citas" wire:navigate>
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.clients.edit', $client->id) }}" class="inline-flex items-center justify-center text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-md transition-colors mr-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button wire:click="confirmDelete({{ $client->id }})" class="inline-flex items-center justify-center text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-user-tag text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium text-gray-600">No se encontraron clientes.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="text-sm text-gray-500">
                Mostrando {{ $clients->firstItem() ?? 0 }} a {{ $clients->lastItem() ?? 0 }} de {{ $clients->total() }} registros
            </div>
            @if($clients->hasPages())
                <div class="w-full md:w-auto">
                    {{ $clients->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
