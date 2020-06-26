<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('sender@adrenalads.com')
            ->subject(sprintf(config('mail.contact_us.subject'), array_get($this->data, 'name')))
            ->replyTo(array_get($this->data, 'email'))
            ->view('emails.contact_us')->with($this->data);
    }


}
