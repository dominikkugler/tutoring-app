<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Models\TutorAvailability;
use Carbon\Carbon;
use App\Models\Booking;


class DashboardController extends Controller
{
    // For any logged-in user
    public function index()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->get();  // Get all posts for this user
        $messages = Message::where('recipient_id', $user->id)->get();  // Get all messages for this user

        if ($user->role == 'student') {
            return view('student.dashboard', compact('user', 'posts', 'messages'));  // Pass messages here
        } elseif ($user->role == 'tutor') {
            return view('tutor.dashboard', compact('user', 'posts', 'messages'));  // Pass messages here
        }

        return redirect('/');  // Redirect if role doesn't match
    }

    public function studentDashboard()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->get();  // Get posts for this student

        // Get the list of unique users for chats (as sender or recipient)
        $chats = Message::where('recipient_id', $user->id)
                        ->orWhere('sender_id', $user->id)
                        ->get()
                        ->map(function ($message) use ($user) {
                            return $message->sender_id == $user->id ? $message->recipient : $message->sender;
                        })
                        ->unique('id');  // Get unique users to display chats

        // Get the bookings made by the student (user_id is the student)
        $bookings = Booking::where('student_id', $user->id)->get();

        return view('student.dashboard', compact('user', 'posts', 'chats', 'bookings'));
    }


    // Tutor-specific dashboard
    public function tutorDashboard()
    {
        $user = Auth::user();
        $posts = $user->posts()->with('category')->get();

        // Chats for tutor
        $chats = Message::where('sender_id', $user->id)
                        ->orWhere('recipient_id', $user->id)
                        ->get()
                        ->map(function ($message) use ($user) {
                            return $message->sender_id == $user->id ? $message->recipient : $message->sender;
                        })
                        ->unique('id');  // Ensure each chat user is unique

        // Fetch teachings for the tutor
        $teachings = $user->teachings()->with('category')->get();

        $bookings = Booking::where('tutor_id', $user->id)->get();

        // Fetch tutor's availabilities for the current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $availabilities = TutorAvailability::where('user_id', $user->id)
                                            ->whereBetween('date', [$startOfWeek, $endOfWeek])
                                            ->orderBy('date')
                                            ->orderBy('start_hour')
                                            ->get();

                                            

        return view('tutor.dashboard', compact('user', 'posts', 'chats', 'teachings', 'availabilities', 'bookings'));
    }

    public function adminDashboard()
    {
        $user = Auth::user();

        $users = User::paginate(10);

        return view('admin.dashboard', compact('users'));
    }

    public function manageUsers()
    {
        $user = Auth::user();

        if ($user->role != 'admin') {
            return redirect('/');  // Only admins can manage users
        }

        // Get all users
        $users = User::all();

        return view('admin.users', compact('user', 'users'));
    }

    public function destroy(User $user)
    {
        $logged_user = Auth::user();
        if ($logged_user->role != 'admin') {
            return redirect('/');  // Only admins can delete users
        }

        try {
            // Ensure the admin cannot delete themselves
            if (auth()->id() === $user->id) {
                return redirect()->route('admin.dashboard')->with('error', 'You cannot delete your own account.');
            }

            // Delete the user
            $user->delete();

            return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'An error occurred while deleting the user.');
        }
    }

    // Show chat for students
    public function showChat($chatUserId)
    {
        $user = Auth::user();
        
        $messages = Message::where(function ($query) use ($user, $chatUserId) {
                            $query->where('sender_id', $user->id)
                                  ->where('recipient_id', $chatUserId);
                        })
                        ->orWhere(function ($query) use ($user, $chatUserId) {
                            $query->where('sender_id', $chatUserId)
                                  ->where('recipient_id', $user->id);
                        })
                        ->orderBy('created_at', 'asc')
                        ->get();

        $chatUser = User::find($chatUserId);

        return view('student.chat', compact('messages', 'chatUser'));
    }

    // Show chat for tutors
    public function showChatTutor($chatUserId)
    {
        $user = Auth::user();
        
        $messages = Message::where(function ($query) use ($user, $chatUserId) {
                            $query->where('sender_id', $user->id)
                                  ->where('recipient_id', $chatUserId);
                        })
                        ->orWhere(function ($query) use ($user, $chatUserId) {
                            $query->where('sender_id', $chatUserId)
                                  ->where('recipient_id', $user->id);
                        })
                        ->orderBy('created_at', 'asc')
                        ->get();

        $chatUser = User::find($chatUserId);

        return view('tutor.chat', compact('messages', 'chatUser'));
    }

    // Send message for students
    public function sendMessage(Request $request, $chatUserId)
    {
        $user = Auth::user();

        $message = new Message();
        $message->sender_id = $user->id;
        $message->recipient_id = $chatUserId;
        $message->content = $request->input('message');
        $message->save();

        return redirect()->route('chat.show', $chatUserId);
    }

    // Send message for tutors
    public function sendMessageTutor(Request $request, $chatUserId)
    {
        $user = Auth::user();

        $message = new Message();
        $message->sender_id = $user->id;
        $message->recipient_id = $chatUserId;
        $message->content = $request->input('message');
        $message->save();

        return redirect()->route('chat.show.tutor', $chatUserId);
    }

}
