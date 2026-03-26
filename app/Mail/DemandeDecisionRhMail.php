<?php

namespace App\Mail;

use App\Models\DemandeStage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeDecisionRhMail extends Mailable
{
    use Queueable, SerializesModels;

    public DemandeStage $demande;
    public string $decision;

    public function __construct(DemandeStage $demande, string $decision)
    {
        $this->demande = $demande->loadMissing('user', 'service');
        $this->decision = $decision;
    }

    public function envelope(): Envelope
    {
        $subject = $this->decision === 'acceptee'
            ? 'Votre demande de stage a ete acceptee par RH'
            : 'Votre demande de stage a ete refusee par RH';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demande_decision_rh',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
