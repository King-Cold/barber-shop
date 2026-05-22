<x-app-layout>
    <div class="mb-6">
        <x-breadcrumbs :links="[
            ['label' => 'Usuarios']
        ]" />
        <h1 class="text-2xl font-bold text-slate-dark">Gestión de Usuarios</h1>
        <p class="text-gray-500 text-sm mt-1">Administra los accesos y roles de todo el personal y clientes.</p>
    </div>

    <!-- Top Action Bar (GET Form) -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0 bg-white p-4 rounded-xl shadow-sm border border-gray-100 font-sans">
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
                <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-bronze-gold focus:border-bronze-gold block w-full pl-10 p-2.5 shadow-sm" placeholder="Buscar usuarios por nombre, email o rol...">
            </div>
            
            <input type="hidden" name="sort" value="{{ $sortField }}">
            <input type="hidden" name="direction" value="{{ $sortDirection }}">

            <div class="flex space-x-2 w-full md:w-auto">
                <button type="submit" class="bg-bronze-gold hover:bg-bronze-gold/90 text-white font-medium rounded-lg text-sm px-4 py-2.5 text-center transition-colors shadow-sm whitespace-nowrap">
                    Buscar
                </button>
                <a href="{{ route('admin.users.create') }}" class="w-full md:w-auto bg-slate-dark hover:bg-slate-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center justify-center transition-colors shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-plus mr-2"></i> Agregar Usuario
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
                            <a href="{{ route('admin.users.index', ['sort' => 'id', 'direction' => ($sortField === 'id' && $sortDirection === 'asc') ? 'desc' : 'asc', 'search' => $search, 'per_page' => $perPage]) }}" class="flex items-center">
                                ID
                                @if($sortField === 'id')
                                    <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1.5"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1.5 text-gray-300"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            <a href="{{ route('admin.users.index', ['sort' => 'name', 'direction' => ($sortField === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc', 'search' => $search, 'per_page' => $perPage]) }}" class="flex items-center">
                                Usuario
                                @if($sortField === 'name')
                                    <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1.5"></i>
                                @else
                                    <i class="fa-solid fa-sort ml-1.5 text-gray-300"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Rol
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                #{{ $user->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-dark font-bold mr-3 border border-slate-200 overflow-hidden">
                                        @if($user->photo)
                                            <img src="{{ asset($user->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($user->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-dark">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-dark">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role_id == 1)
                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fa-solid fa-user-shield fa-fw mr-1"></i> {{ $user->role->name ?? 'Administrador' }}
                                    </span>
                                @elseif($user->role_id == 2)
                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <i class="fa-solid fa-file-shield fa-fw mr-1"></i> {{ $user->role->name ?? 'Super Administrador' }}
                                    </span>
                                @elseif($user->role_id == 3)
                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                        <i class="fa-solid fa-scissors fa-fw mr-1"></i> {{ $user->role->name ?? 'Barbero' }}
                                    </span>
                                @elseif($user->role_id == 4)
                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-user fa-fw mr-1"></i> {{ $user->role->name ?? 'Cliente' }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fa-solid fa-question fa-fw mr-1"></i> Sin Rol
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center justify-center text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-md transition-colors mr-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                @if(auth()->id() !== $user->id)
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}', {{ $user->id === 1 ? 'true' : 'false' }})" class="inline-flex items-center justify-center text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors" title="Eliminar Usuario">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="inline-flex items-center justify-center text-gray-400 bg-gray-50 p-2 rounded-md cursor-not-allowed" title="No puedes eliminarte a ti mismo">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-users-slash text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium text-gray-600">No se encontraron usuarios.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="text-sm text-gray-500">
                Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} registros
            </div>
            @if($users->hasPages())
                <div class="w-full md:w-auto">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(id, name, isProtected) {
            if (isProtected) {
                Swal.fire({
                    title: 'Acción denegada',
                    text: 'La cuenta del Super Administrador Principal (ID 1) está protegida y no puede ser eliminada.',
                    icon: 'error',
                    showConfirmButton: true
                });
                return;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: `Estás a punto de eliminar a ${name}. ¡No podrás revertir esto!`,
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
    </script>
    @endpush
</x-app-layout>
