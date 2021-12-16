<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class ApproveRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $requests_descision;
    public $requests_type;
    public $requests_title;
    public $requests_message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $descision, $type, $title, $message)
    {
        $this->user = $user;
        $this->requests_descision = $descision;
        $this->requests_type = $type;
        $this->requests_title = $title;
        $this->requests_message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.user.approval-request');
    }
}
