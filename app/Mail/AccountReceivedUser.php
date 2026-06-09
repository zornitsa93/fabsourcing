<?php
namespace App\Mail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountReceivedUser extends Mailable {
    use Queueable, SerializesModels;
    public function __construct(public User $user) {}
    public function envelope(): Envelope {
        $fr = ($this->user->locale ?? 'fr') === 'fr';
        return new Envelope(subject: $fr ? 'Votre demande de compte est bien reçue' : 'Your account request has been received');
    }
    public function content(): Content {
        return new Content(view: 'emails.account-received-user', with: ['user' => $this->user]);
    }
}
