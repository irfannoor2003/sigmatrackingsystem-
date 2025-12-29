<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveReasonMail extends Mailable
{
    public function __construct(
        public $user,
        public $reason
    ) {}

    public function build()
    {
        return $this->subject('🤒 Staff Leave Request')
            ->view('emails.leave_reason');
    }
}

