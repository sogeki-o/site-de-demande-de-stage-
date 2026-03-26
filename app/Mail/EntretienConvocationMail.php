<?php

namespace App\Mail;

use App\Models\DemandeStage;
use App\Models\Entretien;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EntretienConvocationMail extends Mailable
{
    use Queueable, SerializesModels;

    public DemandeStage $demande;
    public Entretien $entretien;

    /**
     * Create a new message instance.
     */
    public function __construct(DemandeStage $demande, Entretien $entretien)
    {
        $this->demande = $demande->loadMissing('user', 'service');
        $this->entretien = $entretien;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convocation a un entretien de stage',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.entretien_convocation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
