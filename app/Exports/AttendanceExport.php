<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $salesmanId;
    protected $month;
    protected $year;

    public function __construct($salesmanId = null, $month = null, $year = null)
    {
        $this->salesmanId = $salesmanId;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $query = Attendance::with('salesman');

        if ($this->salesmanId) {
            $query->where('salesman_id', $this->salesmanId);
        }

        if ($this->month && $this->year) {
            $query->whereMonth('date', $this->month)
                  ->whereYear('date', $this->year);
        }

        return $query
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($a) {

                $date = Carbon::parse($a->date);

                return [
                    'Date'       => $date->format('d M Y'),
                    'Day'        => $date->format('l'), // 👈 NEW (Monday, Tuesday)
                    'Name'       => $a->salesman->name,
                    'Role'       => ucfirst($a->salesman->role),
                    'Status'     => ucfirst($a->status),
                    'Clock In'   => $a->clock_in
                        ? Carbon::parse($a->clock_in)->format('h:i A')
                        : '-',
                    'Clock Out'  => $a->clock_out
                        ? Carbon::parse($a->clock_out)->format('h:i A')
                        : '-',
                    'Work (min)' => $a->total_minutes ?? 0,
                    'Note'       => $a->note ?: '--',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Day',
            'Name',
            'Role',
            'Status',
            'Clock In',
            'Clock Out',
            'Work Hours',
            'Reason / Note',
        ];
    }
}
