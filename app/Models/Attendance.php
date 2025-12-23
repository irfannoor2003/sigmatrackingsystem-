<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'salesman_id',
        'date',
        'status',
        'clock_in',
        'clock_out',
        'total_minutes',
        'lat',
        'lng',
        'office_verified',
        'note',
        'leave_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'office_verified' => 'boolean',
    ];

    /* =========================
        RELATIONSHIPS
    ==========================*/

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    /* =========================
        ACCESSORS (UI HELPERS)
    ==========================*/

    /**
     * Human-readable status for UI
     */
    public function getDisplayStatusAttribute()
    {
        if ($this->status === 'leave') {
            return 'On Leave';
        }

        if (!$this->clock_in) {
            return 'Not Clocked';
        }

        if ($this->clock_in && !$this->clock_out) {
            return 'Working';
        }

        return 'Completed';
    }

    /**
     * Calculate working minutes (live or stored)
     */
    public function getWorkingMinutesAttribute()
    {
        if ($this->status === 'leave' || !$this->clock_in) {
            return 0;
        }

        $clockOut = $this->clock_out ?? now();
return $this->clock_in->diffInMinutes($clockOut);

    }

    /**
     * Format working time as HH:MM
     */
    public function getWorkingDurationAttribute()
    {
        $minutes = $this->total_minutes ?? $this->working_minutes;

        if ($minutes <= 0) {
            return '--';
        }

        return sprintf(
            '%02d:%02d',
            intdiv($minutes, 60),
            $minutes % 60
        );
    }
}
