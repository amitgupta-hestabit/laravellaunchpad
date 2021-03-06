<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnapprovedUserMail extends Mailable
{
    use Queueable, SerializesModels;
    public $users;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($users)
    {
        //
        $this->users = $users;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name',$this->users);
        return $this->subject("Unapproved user List")->view('emails.unapproveduserlistmail');
      
    }
}
