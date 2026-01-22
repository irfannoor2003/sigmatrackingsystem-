<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClockInReminderMail;
use App\Mail\ClockOutReminderMail;
use Carbon\Carbon;

class AttendanceReminderCommand extends Command
{
    protected $signature = 'attendance:reminders';
    protected $description = 'Send clock-in and clock-out reminder emails safely';

    public function handle()
    {
        $now   = now();
        $today = now()->toDateString();

        /* =========================================================
         | CLOCK-IN REMINDER (11:00 AM – 3:00 PM)
         | ❗ DOES NOT CREATE ATTENDANCE
         ========================================================= */
        if ($now->between(
            Carbon::today()->setTime(11, 0),
            Carbon::today()->setTime(15, 0)
        )) {

            User::whereNotNull('role')
                ->where('role', '!=', 'admin')
                ->each(function ($user) use ($today) {

                    $attendance = Attendance::where('salesman_id', $user->id)
                        ->where('date', $today)
                        ->first();

                    // ❌ If already clocked in or leave → do nothing
                    if (
                        $attendance &&
                        ($attendance->clock_in || $attendance->status === 'leave')
                    ) {
                        return;
                    }

                    // ❌ Reminder already sent
                    if ($attendance && $attendance->clock_in_reminder_sent) {
                        return;
                    }

                    Mail::to($user->email)
                        ->send(new ClockInReminderMail($user));

                    // ✅ Mark reminder sent ONLY if record exists
                    if ($attendance) {
                        $attendance->update([
                            'clock_in_reminder_sent' => 1,
                        ]);
                    }
                });
        }

        /* =========================================================
         | CLOCK-OUT REMINDER (AFTER 6:00 PM)
         | ❗ ONLY FOR USERS WHO CLOCKED IN
         ========================================================= */
        if ($now->hour >= 18) {

            Attendance::whereDate('date', $today)
                ->whereNotNull('clock_in')          // must have clocked in
                ->whereNull('clock_out')
                ->where('auto_clock_out', 0)
                ->where('clock_out_reminder_sent', 0)
                ->with('salesman')
                ->each(function ($attendance) {

                    if (!$attendance->salesman) return;

                    Mail::to($attendance->salesman->email)
                        ->send(new ClockOutReminderMail($attendance->salesman));

                    $attendance->update([
                        'clock_out_reminder_sent' => 1,
                    ]);
                });
        }

        $this->info('Attendance reminders processed safely.');
    }
}
