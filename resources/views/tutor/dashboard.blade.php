@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Tutor Dashboard</h1>

    <h3 class="mb-3">Welcome, {{ $user->name }}</h3>

    <!-- Display chats -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Your Chats</h4>
        </div>
        <div class="card-body">
            @if($chats->isEmpty())
                <p>No conversations.</p>
            @else
                <ul class="list-group">
                @foreach($chats as $chatUser)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $chatUser->name }}</strong>
                        </div>
                        <div>
                            <a href="{{ route('chat.show.tutor', $chatUser->id) }}" class="btn btn-info btn-sm">View Chat</a>
                        </div>
                    </li>
                @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Display tutor's posts -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Your Posts</h4>
        </div>
        <div class="card-body">
            @if($posts->isEmpty())
                <p>You have not created any posts yet.</p>
            @else
                <ul class="list-group">
                    @foreach($posts as $post)
                        <li class="list-group-item">
                            <strong>Title:</strong> {{ $post->title }}
                            <br>
                            <strong>Category:</strong> {{ $post->category->name }}
                            <br>
                            <strong>Created At:</strong> {{ $post->created_at->format('Y-m-d') }}
                            <br>
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm mt-2">Edit</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Add Availability Button -->
    <a href="{{ route('availabilities.create') }}" class="btn btn-primary mb-3">Add Availability</a>

    <!-- Display tutor's availabilities -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Your Availabilities for This Week</h4>
        </div>
        <div class="card-body">
            @if($availabilities->isEmpty())
                <p>You have no availabilities scheduled for this week.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availabilities as $availability)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($availability->date)->format('l, F d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($availability->start_hour)->format('h:i A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($availability->end_hour)->format('h:i A') }}</td>
                                <td>
                                    <form action="{{ route('availabilities.destroy', $availability->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this availability?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Display tutor's teachings -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Your Teachings</h4>
        </div>
        <div class="card-body">
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
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>
@endsection
