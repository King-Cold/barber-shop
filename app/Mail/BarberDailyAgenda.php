<?php

namespace App\Mail;

use App\Models\Barber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;

class BarberDailyAgenda extends Mailable
{
    use Queueable, SerializesModels;

    public $barber;
    public $appointments;

    /**
     * Create a new message instance.
     */
    public function __construct(Barber $barber, $appointments)
    {
        $this->barber = $barber;
        $this->appointments = $appointments;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Agenda de Trabajo para Mañana - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.barbers.agenda',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $tomorrow = \Carbon\Carbon::tomorrow()->toDateString();
        
        $pdf = Pdf::loadView('pdf.barber_agenda', [
            'barber' => $this->barber,
            'appointments' => $this->appointments,
            'date' => $tomorrow
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), "Agenda_{$tomorrow}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
