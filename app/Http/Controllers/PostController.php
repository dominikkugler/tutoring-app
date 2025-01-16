<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserTeaching;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter'); // Role filter (tutor/student)
        $categoryId = $request->get('category'); // Category filter

        $posts = Post::query();

        // Filter by user role
        if ($filter == 'tutor') {
            $posts->whereHas('user', function ($query) {
                $query->where('role', 'tutor');
            });
        } elseif ($filter == 'student') {
            $posts->whereHas('user', function ($query) {
                $query->where('role', 'student');
            });
        }

        // Filter by category
        if ($categoryId) {
            $posts->where('category_id', $categoryId);
        }

        $posts = $posts->with(['user', 'category'])->paginate(10);

        // Attach hourly rate to each post
        foreach ($posts as $post) {
            $teaching = UserTeaching::where('user_id', $post->user_id)
                ->where('category_id', $post->category_id)
                ->first();
            $post->hourly_rate = $teaching ? $teaching->rate : null;
        }

        // Pass categories to the view
        $categories = Category::all();

        return view('posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(), // Automatically set user_id to the logged-in user
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }


    /**
     * Display the specified post.
     */
    public function show($id)
    {
        $post = Post::with(['user', 'category'])->findOrFail($id);

        // Fetch the hourly rate for the tutor (if available)
        $teaching = UserTeaching::where('user_id', $post->user_id)
            ->where('category_id', $post->category_id)
            ->first();
        $post->hourly_rate = $teaching ? $teaching->rate : null;

        return view('posts.show', compact('post'));
    }



    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        // Check if the user is authenticated and is the owner of the post or an admin
        if (auth()->guest() || (auth()->user()->id !== $post->user_id && !auth()->user()->isAdmin())) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to edit this post.');
        }

        $categories = Category::all(); // Get all categories for the dropdown
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        // Check if the user is authenticated and is the owner of the post or an admin
        if (auth()->guest() || (auth()->user()->id !== $post->user_id && !auth()->user()->isAdmin())) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to update this post.');
        }

        // Validate the incoming data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Update the post with the validated data
        $post->update($validated);

        // Redirect to the post's detail page or another location
        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        // Check if the user is authenticated and is the owner of the post or an admin
        if (auth()->guest() || (auth()->user()->id !== $post->user_id && !auth()->user()->isAdmin())) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to delete this post.');
        }

        // Delete the post
        $post->delete();

        // Redirect to the posts index with success message
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
}

