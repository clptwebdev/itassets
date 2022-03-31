<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BroadbandExpiry extends Mailable {

    use Queueable, SerializesModels;

    public $days;
    public $broadband;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($days, $broadband)
    {
        $this->days = $days;
        $this->broadband = $broadband;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('broadband.mailExpiry');
    }

}
