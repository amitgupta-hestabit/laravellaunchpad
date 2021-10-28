<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $usertype;
    public $username;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usertype,$username)
    {
        //
        $this->usertype = $usertype;
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your account have been approved.")->view('emails.approvedmail');
    }
}
