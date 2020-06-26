<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEmailIsSent()
    {
        $message = [
            'name' => 'Bob the Builder',
            'email' => 'bob@builders.com',
            'content' => 'Can I fix it?',
        ];


        Mail::fake();

        Mail::to(config('mail.contact_us.recipient'))
            ->send(new ContactMessage($message));

        Mail::assertSent(ContactMessage::class, function ($mail) use ($message) {
            return $mail->hasFrom('sender@adrenalads.com') &&
                $mail->hasSubject(sprintf(config('mail.contact_us.subject'), array_get($message, 'name'))) &&
                $mail->hasTo(config('mail.contact_us.recipient')) &&
                $mail->hasReplyTo(array_get($message, 'email'));
        });
    }
}
