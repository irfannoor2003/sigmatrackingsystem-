<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class AttendanceClockInVerificationMail extends Mailable
{
    public string $link;

    public function __construct(string $link)
    {
        $this->link = $link;
    }

    public function build()
    {
        return $this
            ->subject('Confirm Your Clock-In')
            ->view('emails.attendance-verify');
    }
}
