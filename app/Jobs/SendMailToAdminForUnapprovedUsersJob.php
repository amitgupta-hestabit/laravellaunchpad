<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\UnapprovedUserMail;
use App\Models\User;
class SendMailToAdminForUnapprovedUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $users;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users)
    {
        //
        $this->users = $users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $admin = User::where('user_type','ADMIN')->first();
        Mail::to($admin->email)->send(new UnapprovedUserMail($this->users));
    }
}
