<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentTicketController extends Controller
{
    public function download(Appointment $appointment)
    {
        $appointment->load(['client', 'barber', 'service']);
        $pdf = Pdf::loadView('pdf.appointment-ticket', compact('appointment'));
        return $pdf->stream('ticket-cita-'.$appointment->id.'.pdf');
    }

    public function preview(Appointment $appointment)
    {
        $appointment->load(['client', 'barber', 'service']);
        $pdf = Pdf::loadView('pdf.appointment-ticket', compact('appointment'));
        return $pdf->stream('preview.pdf');
    }
}
