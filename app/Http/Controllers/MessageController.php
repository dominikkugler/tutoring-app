<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class MessageController extends Controller
{
    // Constructor to apply authentication middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show the inbox (messages received by the authenticated user)
    public function inbox()
    {
        $user = Auth::user();
        $messages = Message::where('recipient_id', $user->id)->get();

        return view('messages.inbox', compact('messages'));
    }

    // Show the sent messages (messages sent by the authenticated user)
    public function sent()
    {
        $user = Auth::user();
        $messages = Message::where('sender_id', $user->id)->get();

        return view('messages.sent', compact('messages'));
    }

    // Show a single message and allow reply
    public function show($id)
    {
        $message = Message::findOrFail($id);
        $user = Auth::user();

        // Get all messages between the sender and the recipient (chat between both parties)
        $messages = Message::where(function ($query) use ($user, $message) {
                $query->where('sender_id', $message->sender_id)
                    ->where('recipient_id', $user->id);
            })
            ->orWhere(function ($query) use ($user, $message) {
                $query->where('sender_id', $user->id)
                    ->where('recipient_id', $message->sender_id);
            })
            ->orderBy('created_at', 'asc') // Sort by date in ascending order
            ->get();

        return view('messages.show', compact('messages', 'message'));
    }



    // Store a new message
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id', // Ensure recipient exists in the users table
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id', // Ensure the post exists
        ]);

        // Check if the sender is the same as the recipient (user cannot message themselves)
        if ($validated['recipient_id'] == Auth::id()) {
            return redirect()->back()->withErrors(['error' => 'You cannot send a message to yourself.']);
        }

        // Create the new message
        $message = new Message();
        $message->sender_id = Auth::id(); // Set the sender to the logged-in user
        $message->recipient_id = $validated['recipient_id'];
        $message->content = $validated['content'];
        $message->post_id = $validated['post_id']; // Assuming post_id should be assigned as well
        $message->save(); // Save the message to the database

        // Redirect the user with a success message
        return redirect('/posts')->with('success', 'Message sent successfully!');
    }

    // Show the form to compose a new message
    public function create($postId)
    {
        $post = Post::findOrFail($postId); // Get the post by its ID
        $users = User::where('id', '!=', Auth::id())->get(); // Get users excluding the logged-in user
        $recipientId = $post->user_id; // The author of the post is the recipient

        return view('messages.create', compact('post', 'users', 'recipientId'));
    }


}
