<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Carbon\Carbon;

class HolidayController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'title'      => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
    ]);

    $start = Carbon::parse($request->start_date);
    $end   = Carbon::parse($request->end_date ?? $request->start_date);

    while ($start->lte($end)) {
        Holiday::updateOrCreate(
            ['date' => $start->toDateString()],
            ['title' => $request->title]
        );

        $start->addDay();
    }

    return back()->with('success', 'Holiday saved successfully.');
}

}
