<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Barber;
use App\Models\BarberSchedule as ScheduleModel;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BarberSchedule extends Component
{
    public $barber;
    public $schedules = [];

    protected $rules = [
        'schedules.*.is_working' => 'required|boolean',
        'schedules.*.start_time' => 'required|date_format:H:i',
        'schedules.*.end_time' => 'required|date_format:H:i',
        'schedules.*.has_lunch' => 'required|boolean',
        'schedules.*.lunch_start_time' => 'nullable|required_if:schedules.*.has_lunch,true|date_format:H:i',
        'schedules.*.lunch_end_time' => 'nullable|required_if:schedules.*.has_lunch,true|date_format:H:i',
    ];

    protected $messages = [
        'schedules.*.start_time.required' => 'La hora de inicio es requerida.',
        'schedules.*.end_time.required' => 'La hora de fin es requerida.',
        'schedules.*.lunch_start_time.required_if' => 'La hora de inicio de almuerzo es requerida si está activa.',
        'schedules.*.lunch_end_time.required_if' => 'La hora de fin de almuerzo es requerida si está activa.',
    ];

    public function mount(Barber $barber)
    {
        $this->barber = $barber;
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $dbSchedules = $this->barber->schedules()->orderBy('day_of_week')->get();

        // If for some reason the barber doesn't have schedules yet, initialize them
        if ($dbSchedules->isEmpty()) {
            for ($i = 1; $i <= 7; $i++) {
                $this->barber->schedules()->create([
                    'day_of_week' => $i,
                    'is_working' => $i <= 6, // Lunes a Sábado activo, Domingo inactivo
                    'start_time' => '09:00:00',
                    'end_time' => '18:00:00',
                    'lunch_start_time' => '13:00:00',
                    'lunch_end_time' => '14:00:00',
                ]);
            }
            $dbSchedules = $this->barber->schedules()->orderBy('day_of_week')->get();
        }

        $this->schedules = [];
        foreach ($dbSchedules as $sched) {
            $this->schedules[$sched->day_of_week] = [
                'id' => $sched->id,
                'is_working' => (bool)$sched->is_working,
                'start_time' => Carbon::parse($sched->start_time)->format('H:i'),
                'end_time' => Carbon::parse($sched->end_time)->format('H:i'),
                'lunch_start_time' => $sched->lunch_start_time ? Carbon::parse($sched->lunch_start_time)->format('H:i') : '13:00',
                'lunch_end_time' => $sched->lunch_end_time ? Carbon::parse($sched->lunch_end_time)->format('H:i') : '14:00',
                'has_lunch' => $sched->lunch_start_time !== null,
            ];
        }
    }

    public function save()
    {
        $this->validate();

        // Additional validation for ranges
        foreach ($this->schedules as $dayNum => $sched) {
            if ($sched['is_working']) {
                $start = Carbon::parse($sched['start_time']);
                $end = Carbon::parse($sched['end_time']);

                if ($end->lte($start)) {
                    $this->addError("schedules.{$dayNum}.end_time", "La hora de fin debe ser posterior al inicio.");
                    return;
                }

                if ($sched['has_lunch']) {
                    $lStart = Carbon::parse($sched['lunch_start_time']);
                    $lEnd = Carbon::parse($sched['lunch_end_time']);

                    if ($lEnd->lte($lStart)) {
                        $this->addError("schedules.{$dayNum}.lunch_end_time", "El fin de almuerzo debe ser posterior al inicio.");
                        return;
                    }

                    if ($lStart->lt($start) || $lEnd->gt($end)) {
                        $this->addError("schedules.{$dayNum}.lunch_start_time", "El receso de almuerzo debe estar dentro del horario laboral.");
                        return;
                    }
                }
            }
        }

        // Save to Database
        foreach ($this->schedules as $dayOfWeek => $data) {
            $schedModel = ScheduleModel::find($data['id']);
            if ($schedModel) {
                $schedModel->update([
                    'is_working' => $data['is_working'],
                    'start_time' => $data['start_time'] . ':00',
                    'end_time' => $data['end_time'] . ':00',
                    'lunch_start_time' => $data['has_lunch'] ? $data['lunch_start_time'] . ':00' : null,
                    'lunch_end_time' => $data['has_lunch'] ? $data['lunch_end_time'] . ':00' : null,
                ]);
            }
        }

        session()->flash('swal', [
            'title' => '¡Horario Guardado!',
            'text' => 'El horario de trabajo de ' . $this->barber->name . ' ha sido actualizado con éxito.',
            'icon' => 'success',
            'timer' => 3000,
            'showConfirmButton' => false
        ]);

        return redirect()->route('admin.barbers.index');
    }

    public function render()
    {
        return view('livewire.admin.barbers.schedule');
    }
}
