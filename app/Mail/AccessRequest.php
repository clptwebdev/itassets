<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class AccessRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $admin, User $user)
    {
        $this->admin = $admin;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.user.access-request');
    }
}
