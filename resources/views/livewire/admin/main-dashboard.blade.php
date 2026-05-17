<div>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute left-0 top-0 h-full w-1.5 bg-vintage-gold"></div>
            <div class="w-14 h-14 rounded-full bg-vintage-gold/10 flex items-center justify-center text-vintage-gold shadow-inner group-hover:bg-vintage-gold group-hover:text-barber-black transition-colors duration-300">
                <i class="fa-solid fa-sack-dollar text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider font-sans">Ingresos Totales</p>
                <h3 class="text-2xl font-bold text-slate-dark mt-1">${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>

        <!-- Today's Appointments Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute left-0 top-0 h-full w-1.5 bg-slate-dark"></div>
            <div class="w-14 h-14 rounded-full bg-slate-dark/10 flex items-center justify-center text-slate-dark shadow-inner group-hover:bg-slate-dark group-hover:text-white transition-colors duration-300">
                <i class="fa-regular fa-calendar-check text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider font-sans">Citas Hoy</p>
                <h3 class="text-2xl font-bold text-slate-dark mt-1">{{ $todayAppointmentsCount }}</h3>
            </div>
        </div>

        <!-- Total Clients Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute left-0 top-0 h-full w-1.5 bg-vintage-gold"></div>
            <div class="w-14 h-14 rounded-full bg-vintage-gold/10 flex items-center justify-center text-vintage-gold shadow-inner group-hover:bg-vintage-gold group-hover:text-barber-black transition-colors duration-300">
                <i class="fa-solid fa-users text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider font-sans">Total Clientes</p>
                <h3 class="text-2xl font-bold text-slate-dark mt-1">{{ $totalClients }}</h3>
            </div>
        </div>

        <!-- Total Barbers Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute left-0 top-0 h-full w-1.5 bg-slate-dark"></div>
            <div class="w-14 h-14 rounded-full bg-slate-dark/10 flex items-center justify-center text-slate-dark shadow-inner group-hover:bg-slate-dark group-hover:text-white transition-colors duration-300">
                <i class="fa-solid fa-scissors text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider font-sans">Total Barberos</p>
                <h3 class="text-2xl font-bold text-slate-dark mt-1">{{ $totalBarbers }}</h3>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5 bg-barber-black text-white flex items-center justify-between">
            <h3 class="text-base sm:text-lg font-bold font-cinzel tracking-wider flex items-center">
                <i class="fa-solid fa-calendar-check text-vintage-gold mr-2.5"></i> Próximas Citas de Hoy
            </h3>
            <span class="px-3 py-1 bg-vintage-gold/20 text-vintage-gold border border-vintage-gold/30 font-bold font-barber text-xs uppercase tracking-wider rounded-full">
                Agenda Activa
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider font-sans">Hora</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider font-sans">Cliente</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider font-sans">Servicio</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider font-sans">Barbero</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider font-sans">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($upcomingAppointments as $appointment)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-slate-dark font-semibold text-sm">
                                    <i class="fa-regular fa-clock mr-2 text-vintage-gold"></i>
                                    {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-vintage-gold font-bold mr-3 border border-vintage-gold/20 overflow-hidden shadow-sm">
                                        @if($appointment->client->photo)
                                            <img src="{{ asset($appointment->client->photo) }}" class="w-full h-full object-cover rounded-full" loading="lazy">
                                        @else
                                            {{ substr($appointment->client->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $appointment->client->name }}</div>
                                        <div class="text-xs text-gray-500"><i class="fa-solid fa-phone text-gray-400 mr-1 text-[10px]"></i> {{ $appointment->client->phone ?? 'Sin teléfono' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $appointment->service->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-barber-black text-vintage-gold flex items-center justify-center text-xs font-bold mr-2 overflow-hidden shadow-sm border border-vintage-gold/20">
                                        @if($appointment->barber->photo)
                                            <img src="{{ asset($appointment->barber->photo) }}" class="w-full h-full object-cover rounded-full" loading="lazy">
                                        @else
                                            {{ substr($appointment->barber->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $appointment->barber->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($appointment->status === 'pending')
                                    <span class="px-2.5 py-1 inline-flex text-[11px] leading-5 font-bold rounded-md bg-yellow-50 text-yellow-700 border border-yellow-150">Pendiente</span>
                                @elseif($appointment->status === 'confirmed')
                                    <span class="px-2.5 py-1 inline-flex text-[11px] leading-5 font-bold rounded-md bg-blue-50 text-blue-700 border border-blue-150">Confirmada</span>
                                @elseif($appointment->status === 'completed')
                                    <span class="px-2.5 py-1 inline-flex text-[11px] leading-5 font-bold rounded-md bg-green-50 text-green-700 border border-green-150">Completada</span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-[11px] leading-5 font-bold rounded-md bg-gray-50 text-gray-700 border border-gray-150">{{ ucfirst($appointment->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center py-6">
                                    <i class="fa-regular fa-calendar-xmark text-5xl text-vintage-gold/60 mb-3.5"></i>
                                    <p class="text-base font-bold text-gray-700">No hay citas programadas para hoy.</p>
                                    <p class="text-xs text-gray-400 mt-1">Disfruta de tu descanso o promociona tus servicios.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
