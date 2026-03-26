<?php

namespace App\Mail;

use App\Models\DemandeStage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeAffecteeServiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public function __construct(DemandeStage $demande)
    {
        $this->demande = $demande;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande stage affecte',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demande_affectee_service',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
