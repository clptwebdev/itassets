<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use \App\Models\User;
use \App\Models\Location;
use \App\Models\Asset;
use \App\Models\Accessory;

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
    public function __construct(User $user, $type, $id, $from, $to, $date, $comment)
    {
        $this->user = $user;
        $this->type = $comment;;
        $this->model = ucfirst($type)::find($id);
        return dd($this->model);
        $this->from = Location::find($from);
        $this->to = Location::find($to);
        $this->date = $date;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.admin.alert-request');
    }
}
