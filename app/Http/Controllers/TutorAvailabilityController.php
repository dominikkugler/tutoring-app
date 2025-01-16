<?php

namespace App\Http\Controllers;

use App\Models\TutorAvailability;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TutorAvailabilityController extends Controller
{
    /**
     * Display a listing of the tutor's availabilities.
     */
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Start of the current week
        $endOfWeek = Carbon::now()->endOfWeek(); // End of the current week

        $availabilities = TutorAvailability::where('user_id', Auth::id())
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date')
            ->orderBy('start_hour')
            ->get();

        return view('tutor.dashboard', compact('availabilities'));
    }

    /**
     * Show the form for creating a new availability.
     */
    public function create()
    {
        return view('availabilities.create');
    }

    /**
     * Store a newly created availability in storage.
     */
    public function store(Request $request)
    {

        // Combine hour and minute for start and end times
        $start_time = sprintf('%02d:%s', $request->start_hour, $request->start_minute);
        $end_time = sprintf('%02d:%s', $request->end_hour, $request->end_minute);

        // Validate that end time is after start time
        if ($start_time >= $end_time) {
            return back()->withErrors(['end_time' => 'End time must be after start time.'])->withInput();
        }

        // Check if the tutor already has an availability for this day
        $existingAvailability = TutorAvailability::where('user_id', Auth::id())
                                                ->where('date', $request->date)
                                                ->exists();

        if ($existingAvailability) {
            return back()->withErrors(['date' => 'You already have an availability set for this day.'])->withInput();
        }

        // Save availability
        TutorAvailability::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'start_hour' => $start_time,
            'end_hour' => $end_time,
        ]);

        return redirect()->route('tutor.dashboard')->with('success', 'Availability added successfully.');
    }


    /**
     * Remove the specified availability.
     */
    public function destroy(TutorAvailability $availability)
    {
        $availability->delete();
        return redirect()->route('tutor.dashboard')->with('success', 'Availability deleted successfully!');
    }
}
