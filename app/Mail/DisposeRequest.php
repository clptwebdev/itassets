<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Location;

class DisposeRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $admin;
    public $requests_model;
    public $requests_date;
    public $requests_type;
    public $requests_comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, User $admin, $requests_type, $requests_id, $requests_date, $requests_comment)
    {
        $this->user = $user;
        $this->admin = $admin;
        $this->requests_type = $requests_type;
        $m = "\\App\\Models\\".ucfirst($requests_type);
        $this->requests_model = $m::find($requests_id);
        $this->requests_date = $requests_date;
        $this->requests_comment = $requests_comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.user.dispose-request');
    }
}
