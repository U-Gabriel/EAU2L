<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationRendezVous extends Mailable
{
    use Queueable, SerializesModels;

    // Cette variable doit correspondre à ce que tu utilises dans ta vue Blade ($info)
    public $info;

    public function __construct($info) {
        $this->info = $info;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre rendez-vous - EAU2L',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmation', // C'est ici qu'était l'erreur "view.name"
        );
    }

    public function attachments(): array
    {
        return [];
    }
}