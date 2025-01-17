<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings for the logged-in user.
     */
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::where('student_id', $user->id)
                            ->orWhere('tutor_id', $user->id)
                            ->with(['student', 'tutor', 'category'])
                            ->orderBy('date', 'asc')
                            ->get();

        return view('student.dashboard', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request)
    {
        $tutor = User::findOrFail($request->tutor_id);
        $category = Category::findOrFail($request->category_id);

        return view('bookings.create', compact('tutor', 'category'));
    }


    /**
     * Store a newly created booking in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date|after_or_equal:today',
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
        ]);

        $booking = new Booking();
        $booking->student_id = Auth::id();
        $booking->tutor_id = $request->tutor_id;
        $booking->category_id = $request->category_id;
        $booking->date = $request->date;
        $booking->start_hour = $request->start_hour;
        $booking->end_hour = $request->end_hour;
        $booking->status = 'pending';
        $booking->save();

        return redirect()->route('student.dashboard')->with('success', 'Booking created successfully.');
    }

    /**
     * Display the details of a specific booking.
     */
    public function show($id)
    {
        $booking = Booking::with(['student', 'tutor', 'category'])->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Update the status of a booking (accept/reject).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $booking = Booking::findOrFail($id);

        if (Auth::id() != $booking->tutor_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized action.');
        }

        $booking->status = $request->status;
        $booking->save();

        return redirect()->route('tutor.dashboard')->with('success', 'Booking status updated.');
    }

    /**
     * Delete a booking.
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::id() != $booking->student_id) {
            return redirect()->route('bookings.index')->with('error', 'Unauthorized action.');
        }

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
