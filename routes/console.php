<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// 1. Clock-in Reminder at 11:00 AM
Schedule::command('attendance:reminders')
    ->dailyAt('11:00')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

// 2. Clock-out Reminder at 6:00 PM (18:00)
Schedule::command('attendance:reminders')
    ->dailyAt('18:00')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

// 3. Auto-Clockout at 8:00 PM (20:00)
Schedule::command('attendance:auto-clockout')
    ->dailyAt('20:00')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();
