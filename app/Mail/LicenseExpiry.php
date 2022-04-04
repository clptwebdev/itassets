<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LicenseExpiry extends Mailable {

    use Queueable, SerializesModels;

    public $days;
    public $license;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($days, $license)
    {
        $this->days = $days;
        $this->license = $license;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('licenses.mailExpiry');
    }

}
