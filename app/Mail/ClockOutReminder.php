<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClockOutReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('⏰ Clock Out Reminder')
            ->view('emails.clockout_reminder');
    }
}
