<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class DashboardAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $dashboardUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->dashboardUrl = $this->getDashboardUrl();
    }

    private function getDashboardUrl()
    {
        return route('demandeur.dashboard');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Accès à votre tableau de bord',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dashboard_access',
        );
    }

    
    public function attachments(): array
    {
        return [];
    }
}