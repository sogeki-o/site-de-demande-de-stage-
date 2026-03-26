<?php

namespace App\Mail;

use App\Models\DemandeStage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeRefuseeServiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public DemandeStage $demande;

    public function __construct(DemandeStage $demande)
    {
        $this->demande = $demande->loadMissing('user', 'service');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre demande a ete refusee par le service',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demande_refusee_service',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
