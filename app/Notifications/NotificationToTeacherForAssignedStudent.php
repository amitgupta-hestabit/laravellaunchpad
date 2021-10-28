<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationToTeacherForAssignedStudent extends Notification implements ShouldQueue
{
    use Queueable;

    public $tuser;
    public $suser;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tuser,$suser)
    {
        //
        $this->tuser=$tuser[0];
        $this->suser=$suser[0];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Dear Teacher '.$this->tuser->name.',')
                    ->line('You have assigned a new student:- '.$this->suser->name)
                    ->action('Visit', url('/'))
                    ->line('Thanks');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
