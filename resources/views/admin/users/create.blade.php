<x-app-layout>
<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <x-breadcrumbs :links="[
        ['label' => 'Usuarios', 'url' => route('admin.users.index')],
        ['label' => 'Nuevo Usuario']
    ]" />

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-xl font-bold text-slate-dark">
                <i class="fa-solid fa-user-plus mr-2 text-bronze-gold"></i>
                Registrar Nuevo Usuario
            </h2>
            <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver al listado
            </a>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Photo Column -->
                <div class="md:col-span-1 flex flex-col items-center">
                    <label class="block text-sm font-semibold text-gray-700 mb-4 w-full text-center">Foto de Perfil</label>
                    <div class="relative group">
                        <div id="photo-preview-container" class="w-48 h-48 rounded-full bg-gray-100 border-2 border-dashed flex items-center justify-center overflow-hidden transition-all @error('photo') border-red-500 @else border-gray-300 group-hover:border-bronze-gold @enderror">
                            <div class="text-center p-4">
                                <i class="fa-solid fa-camera text-3xl text-gray-400 mb-2"></i>
                                <p class="text-xs text-gray-500">Subir foto</p>
                            </div>
                        </div>
                        <input type="file" name="photo" id="photo-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('photo') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                </div>

                <!-- Fields Column -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nombre Completo <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user text-gray-400 text-xs"></i>
                            </div>
                            <input name="name" value="{{ old('name') }}" type="text" id="name" class="block w-full pl-10 pr-3 py-3 border rounded-lg text-sm transition-all shadow-sm @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror" placeholder="Ej. Administrador Principal">
                        </div>
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-gray-400 text-xs"></i>
                            </div>
                            <input name="email" value="{{ old('email') }}" type="email" id="email" class="block w-full pl-10 pr-3 py-3 border rounded-lg text-sm transition-all shadow-sm @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror" placeholder="correo@ejemplo.com">
                        </div>
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="role_id" class="block text-sm font-semibold text-gray-700 mb-2">Rol del Sistema <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user-tag text-gray-400 text-xs"></i>
                            </div>
                            <select name="role_id" id="role_id" class="block w-full pl-10 pr-3 py-3 border rounded-lg text-sm transition-all shadow-sm bg-white @error('role_id') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', '1') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2 border-t border-gray-100 pt-6 mt-2">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Seguridad de la Cuenta</h3>
                    </div>

                    <!-- Password -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Contraseña <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-xs"></i>
                            </div>
                            <input name="password" type="password" id="password" class="block w-full pl-10 pr-3 py-3 border rounded-lg text-sm transition-all shadow-sm @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror" placeholder="••••••••">
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirmar Contraseña <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-shield-check text-gray-400 text-xs"></i>
                            </div>
                            <input name="password_confirmation" type="password" id="password_confirmation" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm" placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex items-center justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('admin.users.index') }}" class="px-8 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-55 transition-all shadow-sm">
                    Cancelar
                </a>
                <button type="submit" class="px-10 py-3 text-sm font-bold text-white bg-slate-dark hover:bg-slate-800 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-save mr-2 text-white"></i>
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('photo-input').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('photo-preview-container').innerHTML = `
                    <img src="${event.target.result}" class="w-full h-full object-cover rounded-full">
                `;
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
</x-app-layout>
