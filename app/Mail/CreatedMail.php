<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreatedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;
    public $mailSubject;
    public $clientAppLink;

    public function __construct($mailData, $mailSubject ,$clientAppLink)
    {
        $this->mailData = $mailData;
        $this->mailSubject = $mailSubject;
        $this->clientAppLink = $clientAppLink;
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
            view: 'emails.offerCreatedEmail',
        );
    }
    public function attachments(): array
    {
        return [];
    }
}
