<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Barber;
use Carbon\Carbon;

class MainDashboard extends Component
{
    public $totalRevenue = 0;
    public $todayAppointmentsCount = 0;
    public $totalClients = 0;
    public $totalBarbers = 0;
    public $upcomingAppointments = [];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        // Total Revenue: sum of prices of all completed appointments
        $this->totalRevenue = Appointment::where('appointments.status', 'completed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        // Today's Appointments
        $this->todayAppointmentsCount = Appointment::whereDate('date', Carbon::today())->count();

        // Total Clients
        $this->totalClients = Client::count();

        // Total Barbers
        $this->totalBarbers = Barber::count();

        // Upcoming 5 appointments for today
        $this->upcomingAppointments = Appointment::with(['client', 'barber', 'service'])
            ->whereDate('date', Carbon::today())
            ->where('status', '!=', 'canceled')
            ->orderBy('time', 'asc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.main-dashboard');
    }
}
