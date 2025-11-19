<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RoleAssignment extends Mailable
{
    use Queueable, SerializesModels;

    public $isImpostor;
    public $assignedWord;

    /**
     * Create a new message instance.
     */
    public function __construct(bool $isImpostor, string $assignedWord)
    {
        $this->isImpostor = $isImpostor;
        $this->assignedWord = $assignedWord;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isImpostor
            ? 'SECRET: Your Role Assignment'
            : 'Game Role Assignment';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.role',
        );
    }
}
