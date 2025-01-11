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

        // Get received messages, grouped by sender (using aggregate functions like MAX())
        $receivedMessages = Message::selectRaw('MAX(id) as id, sender_id, MAX(created_at) as created_at')
                                    ->where('recipient_id', $user->id)
                                    ->groupBy('sender_id') // Group by sender
                                    ->orderBy('created_at', 'asc') // Order by created_at to show latest conversations
                                    ->with('sender')  // Load sender data
                                    ->get();

        // Get sent messages, grouped by recipient (using aggregate functions like MAX())
        $sentMessages = Message::selectRaw('MAX(id) as id, recipient_id, MAX(created_at) as created_at')
                                ->where('sender_id', $user->id)
                                ->groupBy('recipient_id') // Group by recipient
                                ->orderBy('created_at', 'asc') // Order by created_at to show latest conversations
                                ->with('recipient')  // Load recipient data
                                ->get();

        return view('student.dashboard', compact('user', 'posts', 'receivedMessages', 'sentMessages'));
    }

    public function showChat($chatUserId)
    {
        $user = Auth::user();
        
        // Get all messages between logged-in user and the chat user
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

        $chatUser = User::find($chatUserId); // Get the user you're chatting with

        return view('student.chat', compact('messages', 'chatUser'));
    }

    public function sendMessage(Request $request, $chatUserId)
    {
        $user = Auth::user();
        
        $message = new Message();
        $message->sender_id = $user->id;
        $message->recipient_id = $chatUserId;
        $message->content = $request->input('message');
        $message->save();

        return redirect()->route('chat.show', $chatUserId); // Redirect to the chat window
    }

    public function tutorDashboard()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->get(); // Get posts for the tutor

        // Get all messages where the tutor is either the sender or recipient
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Group messages by the other participant
        $chats = $messages->mapToGroups(function ($message) use ($user) {
            $otherUser = $message->sender_id === $user->id ? $message->recipient : $message->sender;
            return [$otherUser->id => $otherUser];
        })->map(function ($users) {
            return $users->first(); // Ensure only one entry per user
        });

        return view('tutor.dashboard', compact('user', 'posts', 'chats'));
    }


    public function showChatTutor($chatUserId)
    {
        $user = Auth::user();
        
        // Get all messages between logged-in tutor and the chat user
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

        $chatUser = User::find($chatUserId); // Get the user you're chatting with

        return view('tutor.chat', compact('messages', 'chatUser'));
    }

    public function sendMessageTutor(Request $request, $chatUserId)
    {
        $user = Auth::user();
        
        $message = new Message();
        $message->sender_id = $user->id;
        $message->recipient_id = $chatUserId;
        $message->content = $request->input('message');
        $message->save();

        return redirect()->route('chat.show.tutor', $chatUserId); // Redirect to the chat window
    }


}
