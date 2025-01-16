@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Student Dashboard</h1>

    <h3 class="mb-4">Welcome, {{ $user->name }}</h3>

    <!-- Display chats -->
    <h4>Your Chats:</h4>
    @if($chats->isEmpty())
        <p>You have no chats yet.</p>
    @else
        <ul class="list-group mb-4">
            @foreach($chats as $chat)
                <li class="list-group-item d-flex justify-content-between align-items-center">
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
        <div class="table-responsive mb-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->category->name }}</td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
