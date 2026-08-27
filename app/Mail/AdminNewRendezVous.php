<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewRendezVous extends Mailable
{
    use Queueable, SerializesModels;

    public $info;
    public $teamsUrl;

    public function __construct($info, $teamsUrl) {
        $this->info = $info;
        $this->teamsUrl = $teamsUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau rendez-vous pris - ' . $this->info->company_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_rendez_vous', 
        );
    }

    public function attachments(): array
    {
        return [];
    }
}