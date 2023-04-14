<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreateAPasswordResetToken extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $token;

    /**
     * @var string
     */
    private string $title;

    /**
     * Create a new message instance.
     */
    public function __construct(string $email, string $token, string $title)
    {
        $this->email = $email;
        $this->token = $token;
        $this->title = $title;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->email),
            subject: $this->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $createPassword = $this->title == 'Criar nova senha' ? 'define-password' : 'redefine-password';

        $url = env('FRONT_URL') . "/auth/$createPassword?email={$this->email}&password_reset_token={$this->token}";

        return new Content(
            view: 'email.create-password-reset-token',
            with: [
                'url' => $url,
                'title' => $this->title,
            ]
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
