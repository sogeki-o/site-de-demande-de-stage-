<?php

namespace App\Mail;

use App\Models\CahierCharge;
use App\Models\DemandeStage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class CahierChargePartageMail extends Mailable
{
    use Queueable, SerializesModels;

    public DemandeStage $demande;
    public CahierCharge $cahierCharge;
    public string $pdfUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(DemandeStage $demande, CahierCharge $cahierCharge)
    {
        $this->demande = $demande->loadMissing('user', 'service');
        $this->cahierCharge = $cahierCharge;
        $this->pdfUrl = URL::temporarySignedRoute(
            'demandeur.demandes.cahier.public',
            now()->addDays(7),
            ['demandeStage' => $this->demande->id]
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cahier des charges partage - Sujet: ' . $this->cahierCharge->sujet_stage,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cahier_charge_partage',
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
