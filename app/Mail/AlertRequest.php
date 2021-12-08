<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Location;

class AlertRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $model;
    public $from;
    public $to;
    public $date;
    public $type;
    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.user.alert-request');
    }
}
