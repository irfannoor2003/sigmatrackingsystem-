<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    protected $fillable = ['date', 'title'];

    protected $casts = [
        'date' => 'date',
    ];

    /* 🔹 Check if date (or today) is a company holiday */
    public static function isHoliday($date = null): bool
    {
        $date = $date
            ? Carbon::parse($date)->toDateString()
            : Carbon::today()->toDateString();

        return self::whereDate('date', $date)->exists();
    }

    /* 🔹 Get holiday title */
    public static function title($date = null): ?string
    {
        $date = $date
            ? Carbon::parse($date)->toDateString()
            : Carbon::today()->toDateString();

        return self::whereDate('date', $date)->value('title');
    }
}
