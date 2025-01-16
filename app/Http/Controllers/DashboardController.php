<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;


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

        return view('student.dashboard', compact('user', 'posts', 'chats'));
    }

    // Tutor-specific dashboard
    public function tutorDashboard()
    {
        $user = Auth::user();
        $posts = $user->posts()->with('category')->get();

        // Chats for tutor
        $chats = $user->sentMessages()
                      ->with('recipient')
                      ->get()
                      ->keyBy('recipient_id');

        // Fetch teachings for the tutor
        $teachings = $user->teachings()->with('category')->get();

        return view('tutor.dashboard', compact('user', 'posts', 'chats', 'teachings'));
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
