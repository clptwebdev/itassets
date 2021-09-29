<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class DeletedUser extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $name)
    {
            $this->user = $user;
            $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        //return $this->from('Example@email.com')->view('emails.orders.shipped'); 
        return $this->view('admin.user.delete-user');
    }
}
