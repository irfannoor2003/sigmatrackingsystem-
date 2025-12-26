<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\CompanyOffDay;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ClockOutReminderNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClockOutReminder;

class SendClockOutReminder extends Command
{
    protected $signature = 'send:clockout-reminder';
    protected $description = 'Send clock-out reminder after 8 hours of work';

  public function handle(): void
{
    $today = now()->toDateString();
    $now = now();

    // Skip company off day safely
    if (\Schema::hasTable('company_off_days')) {
        if (\App\Models\CompanyOffDay::where('off_date', $today)->exists()) {
            return;
        }
    }

    \App\Models\Attendance::whereDate('date', $today)
        ->whereNotNull('clock_in')
        ->whereNull('clock_out')
        ->where('status', 'present')
        ->get()
        ->each(function ($attendance) use ($now) {

            // ✅ SAFE for datetime
            $clockInDateTime = \Carbon\Carbon::parse($attendance->clock_in);

            if ($clockInDateTime->diffInMinutes($now) >= 480) {

                if ($attendance->reminder_sent) {
                    return;
                }



Mail::to($attendance->salesman->email)
    ->send(new ClockOutReminder());


                $attendance->update(['reminder_sent' => true]);
            }
        });
}


}
