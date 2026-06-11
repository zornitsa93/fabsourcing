<?php

namespace Tests\Feature;

use App\Mail\ContactSubmissionMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_submission_is_stored_and_emailed_to_recipient_with_reply_to(): void
    {
        config(['mail.contact_to' => 'thierry@example.test']);
        Mail::fake();

        $this->post('/fr/contact', [
            'name'    => 'Jean Dupont',
            'company' => 'Acme',
            'email'   => 'jean@acme.fr',
            'phone'   => '0102030405',
            'message' => 'Bonjour, je souhaite un devis.',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_submissions', ['email' => 'jean@acme.fr']);

        Mail::assertSent(ContactSubmissionMail::class, function ($mail) {
            return $mail->hasTo('thierry@example.test')
                && $mail->hasReplyTo('jean@acme.fr');
        });
    }
}
