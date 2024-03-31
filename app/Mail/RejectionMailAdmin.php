<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectionMailAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;
    public $mailSubject;

    public function __construct($mailData, $mailSubject)
    {
        $this->mailData = $mailData;
        $this->mailSubject = $mailSubject;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject:$this->mailSubject
        );
    }
    public function content(): Content
    {
        return new Content(
            view: 'emails.offreRejectedEmail',
        );
    }
    public function attachments(): array
    {
        return [];
    }
}
