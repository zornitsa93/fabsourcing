<?php
namespace App\Mail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountPendingAdmin extends Mailable {
    use Queueable, SerializesModels;
    public function __construct(public User $user) {}
    public function envelope(): Envelope {
        $fr = ($this->user->locale ?? 'fr') === 'fr';
        return new Envelope(subject: $fr ? 'Nouveau compte en attente de validation' : 'New account awaiting approval');
    }
    public function content(): Content {
        return new Content(view: 'emails.account-pending-admin', with: ['user' => $this->user]);
    }
}
