<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Reservation;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReservationDeclinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reservation;

    public function __construct(User $user, Reservation $reservation)
    {
        $this->user = $user;
        $this->reservation = $reservation;
    }

    public function build()
    {
        $userName = $this->user->first_name;

        // Generate PDF
        $pdf = $this->generatePDF($this->reservation->id);

        // Attach PDF to the email
        return $this->view('emails.reservation_declined',[
            'userName' => $userName, // Pass the user's name to the view
        ])
            ->subject('Reservation Declined')
            ->attachData($pdf->output(), 'document.pdf', [
                'mime' => 'application/pdf',
            ]);
    }

    public function generatePDF($reservationId)
    {
        // Fetch the reservation data
        $reservation = Reservation::findOrFail($reservationId);

        // Load the PDF view with the reservation data
        $pdf = PDF::loadView('pdf.document', compact('reservation'));

        return $pdf;
    }
}
