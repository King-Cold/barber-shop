<div>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow duration-300">
            <div class="w-14 h-14 rounded-full bg-bronze-gold/10 flex items-center justify-center text-bronze-gold">
                <i class="fa-solid fa-sack-dollar text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Ingresos Totales</p>
                <h3 class="text-2xl font-bold text-slate-dark">${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>

        <!-- Today's Appointments Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow duration-300">
            <div class="w-14 h-14 rounded-full bg-slate-dark/10 flex items-center justify-center text-slate-dark">
                <i class="fa-regular fa-calendar-check text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Citas Hoy</p>
                <h3 class="text-2xl font-bold text-slate-dark">{{ $todayAppointmentsCount }}</h3>
            </div>
        </div>

        <!-- Total Clients Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow duration-300">
            <div class="w-14 h-14 rounded-full bg-bronze-gold/10 flex items-center justify-center text-bronze-gold">
                <i class="fa-solid fa-users text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Clientes</p>
                <h3 class="text-2xl font-bold text-slate-dark">{{ $totalClients }}</h3>
            </div>
        </div>

        <!-- Total Barbers Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow duration-300">
            <div class="w-14 h-14 rounded-full bg-slate-dark/10 flex items-center justify-center text-slate-dark">
                <i class="fa-solid fa-scissors text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Barberos</p>
                <h3 class="text-2xl font-bold text-slate-dark">{{ $totalBarbers }}</h3>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-slate-dark">Próximas 5 Citas (Hoy)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hora</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Servicio</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Barbero</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($upcomingAppointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-slate-dark font-medium">
                                    <i class="fa-regular fa-clock mr-2 text-bronze-gold"></i>
                                    {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-dark font-bold mr-3 border border-slate-200 overflow-hidden">
                                        @if($appointment->client->photo)
                                            <img src="{{ asset($appointment->client->photo) }}" class="w-full h-full object-cover rounded-full" loading="lazy">
                                        @else
                                            {{ substr($appointment->client->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $appointment->client->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $appointment->client->phone ?? 'Sin teléfono' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">
                                    {{ $appointment->service->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-bronze-gold text-white flex items-center justify-center text-xs font-bold mr-2 overflow-hidden">
                                        @if($appointment->barber->photo)
                                            <img src="{{ asset($appointment->barber->photo) }}" class="w-full h-full object-cover rounded-full" loading="lazy">
                                        @else
                                            {{ substr($appointment->barber->name, 0, 1) }}
                                        @endif
                                    </div>
                                    {{ $appointment->barber->name }}
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
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-gray-100 text-gray-800">{{ ucfirst($appointment->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium text-gray-600">No hay citas programadas para hoy.</p>
                                    <p class="text-sm">Disfruta de tu descanso o promociona tus servicios.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
