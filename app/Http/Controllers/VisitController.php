<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Customer;
use Auth;

class VisitController extends Controller
{
    // Show create visit form
    public function create()
    {
        $customers = Customer::where('salesman_id', Auth::id())->get();
        return view('salesman.visits.create', compact('customers'));
    }

    // Start a visit
    public function store(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'customer_id' => 'required|exists:customers,id',
            'purpose' => 'required|string|max:255',
        ]);

        Visit::create([
            'customer_id' => $request->customer_id,
            'salesman_id' => Auth::id(),
            'purpose' => $request->purpose,
            'status' => 'started',
            'started_at' => now(),
        ]);

        return redirect()->route('salesman.visits.index')
                         ->with('success', 'Visit started successfully!');
    }

    // List visits for logged-in salesman
    public function index()
    {
        $visits = Visit::where('salesman_id', Auth::id())->get();
        return view('salesman.visits.index', compact('visits'));
    }

    // Complete visit
   public function complete(Request $request, $id)
{
    $visit = Visit::findOrFail($id);

    $images = [];

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $img) {
            $path = $img->store('visit_images', 'public');
            $images[] = $path;
        }
    }

    // Merge new images with old ones
    $existingImages = $visit->images ?? [];
    $mergedImages = array_merge($existingImages, $images);

    $visit->notes = $request->notes ?? $visit->notes;
    $visit->images = $mergedImages;
    $visit->status = 'completed';
    $visit->completed_at = now();
    $visit->save();

    return back()->with('success', 'Visit completed successfully!');
}
public function show($id)
{
    $visit = Visit::where('id', $id)
                  ->where('salesman_id', Auth::id())
                  ->firstOrFail();

    return view('salesman.visits.show', compact('visit'));
}

}
