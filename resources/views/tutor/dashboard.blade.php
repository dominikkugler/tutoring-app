@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tutor Dashboard</h1>

    <h3>Welcome, {{ $user->name }}</h3>

    <!-- Display chats -->
    <h4>Your Chats:</h4>
    @if($chats->isEmpty())
        <p>No conversations.</p>
    @else
        <ul>
            @foreach($chats as $chatUserId => $chatUser)
                <li>
                    <strong>{{ $chatUser->name }}</strong>
                    <a href="{{ route('chat.show.tutor', $chatUserId) }}" class="btn btn-info btn-sm">View Chat</a>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Display tutor's posts -->
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

    <!-- Display tutor's teachings -->
    <h4>Your Teachings:</h4>
    <a href="{{ route('teachings.create') }}" class="btn btn-success mb-3">Add New Teaching</a>

    @if($teachings->isEmpty())
        <p>You are not teaching any subjects yet.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Hourly Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachings as $teaching)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $teaching->category->name }}</td>
                        <td>${{ number_format($teaching->rate, 2) }}</td>
                        <td>
                            <form action="{{ route('teachings.destroy', $teaching->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this teaching?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
