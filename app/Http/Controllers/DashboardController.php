<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class DashboardController extends Controller
{
    // For any logged-in user
    public function index()
    {
        $user = Auth::user();
        $posts = Post::all();  // Get all posts

        if ($user->role == 'student') {
            return view('student.dashboard', compact('user', 'posts'));
        } elseif ($user->role == 'tutor') {
            return view('tutor.dashboard', compact('user', 'posts'));
        }

        return redirect('/');  // Redirect if role doesn't match
    }

    // For student dashboard
    public function studentDashboard()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->get();  // Get all posts created by this student

        return view('student.dashboard', compact('user', 'posts'));
    }

    // For tutor dashboard
    public function tutorDashboard()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->get();  // Get all posts created by this tutor
        $messages = Message::where('recipient_id', $user->id)->get();  // Get all messages sent to this tutor

        return view('tutor.dashboard', compact('user', 'posts', 'messages'));
    }


}
