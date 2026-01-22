<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoClockOutCommand extends Command
{
    protected $signature = 'attendance:auto-clockout';
    protected $description = 'Automatically clock out users who forgot to clock out';

    public function handle()
    {
        $today = today()->toDateString();
        $clockOutTime = Carbon::today()->setTime(20, 0); // 8:00 PM

        $attendances = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in')     // MUST be clocked in
            ->whereNull('clock_out')       // Not clocked out
            ->where('status', 'present')   // Real attendance only
            ->where('auto_clock_out', 0)   // Not already auto-clocked
            ->get();

        if ($attendances->isEmpty()) {
            $this->info('No records eligible for auto clock-out.');
            return;
        }

        foreach ($attendances as $attendance) {

            // Calculate total minutes safely
            $totalMinutes = $attendance->clock_in
                ? Carbon::parse($attendance->clock_in)->diffInMinutes($clockOutTime)
                : 0;

            $attendance->update([
                'clock_out'        => $clockOutTime,
                'total_minutes'    => max(0, $totalMinutes),
                'auto_clock_out'   => 1,
            ]);
        }

        $this->info('Auto clock-out completed successfully.');
    }
}
