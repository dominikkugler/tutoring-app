@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Student Dashboard</h1>

    <h3>Welcome, {{ $user->name }}</h3>

    <!-- Display chats -->
    <h4>Your Chats:</h4>
    @if($chats->isEmpty())
        <p>You have no chats yet.</p>
    @else
        <ul>
            @foreach($chats as $chat)
                <li>
                    <strong>Chat with:</strong> {{ $chat->name }}
                    <a href="{{ route('chat.show', $chat->id) }}" class="btn btn-info btn-sm">View</a>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Display user's posts -->
    <h4>Your Posts:</h4>
    @if($posts->isEmpty())
        <p>You have not created any posts yet.</p>
    @else
        <ul>
            @foreach($posts as $post)
                <li>
                    <strong>Title:</strong> {{ $post->title }}
                    <br>
                    <strong>Category:</strong> {{ $post->category->name }}
                    <br>
                    <strong>Created At:</strong> {{ $post->created_at->format('Y-m-d') }}
                    <br>
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
