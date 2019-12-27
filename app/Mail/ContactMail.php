<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    /** Create a new message instance. ...*/
    public function __construct($request)
    {
        $this->request = $request;
    }

    /** Build the message. ...*/
    public function build()
    {
        $contact = $this->request->contact;
        return $this->from($contact.'@thevinylshop.com', 'The Vinyl Shop - ' . ucfirst($contact))
            ->cc($contact.'@thevinylshop.com', 'The Vinyl Shop - ' . ucfirst($contact))
            ->subject('The Vinyl Shop - Contact Form')
            ->markdown('email.contact');
    }
}
