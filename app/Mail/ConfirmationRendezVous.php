<?php

namespace App\Mail;

use App\Models\PageBlock;
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
    public $teamsUrl;

    public function __construct($info) {
        $this->info = $info;

        $teamsBlock = PageBlock::where('type', 'teams_link')->first();

        // Si $teamsBlock existe ET qu'il a un lien rempli dans la colonne 'link'
        if ($teamsBlock && !empty($teamsBlock->link)) {
            $this->teamsUrl = $teamsBlock->link;
        } else {
            // Fallback de secours uniquement si non trouvé en BDD
            $this->teamsUrl = 'https://teams.live.com/meet/93469201237491?p=WBX9LmrsbYkHqLKMT6';
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre rendez-vous - Armature Business',
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