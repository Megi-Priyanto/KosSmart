<?php

namespace App\Mail;

use App\Models\AdminRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminRegistrationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public AdminRegistration $registration;

    public function __construct(AdminRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Selamat! Pendaftaran Kos Anda Disetujui - KosSmart');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-registration.approved');
    }

    public function attachments(): array { return []; }
}