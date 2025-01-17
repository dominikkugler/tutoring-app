@extends('layouts.app')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('content')
<div class="container">
    <h1>{{ $post->title }}</h1>
    <p>
        <strong>Category:</strong> {{ $post->category->name }}
    </p>
    <p>
        <strong>Author:</strong> {{ $post->user->name }}

        <!-- Check if the logged-in user is not the author of the post -->
        @if(Auth::id() !== $post->user_id)
            <button class="btn btn-sm btn-outline-primary ms-2">
                <a href="{{ route('messages.create', $post->id) }}">
                    <i class="fas fa-envelope"></i> Send a Message
                </a>
            </button>
        @else
            <!-- If the logged-in user is the author, show a button to go to their dashboard -->
            <button class="btn btn-sm btn-outline-primary ms-2">
                <a href="{{ Auth::user()->isTutor() ? route('tutor.dashboard') : route('student.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Go to Your Dashboard
                </a>
            </button>
        @endif
    </p>
    <p><strong>Phone number:</strong> {{ $post->user->phone }}</p>
    <p><strong>Posted On:</strong> {{ $post->created_at->format('F d, Y') }}</p>
    <hr>
    <p>{{ $post->content }}</p>

    <!-- Display Hourly Rate if available -->
    @php
        $hourlyRate = \App\Models\UserTeaching::where('user_id', $post->user_id)
            ->where('category_id', $post->category_id)
            ->value('rate');
    @endphp
    @if ($hourlyRate !== null)
        <p><strong>Hourly Rate:</strong> ${{ number_format($hourlyRate, 2) }}</p>
    @else
        <p class="text-muted"><strong>Hourly Rate:</strong> Not available</p>
    @endif

    <!-- Book Now button -->
    @auth
        @if ($post->user->isTutor() && Auth::user()->isStudent() && Auth::id() !== $post->user_id)
            <div class="mt-4">
                <a href="{{ route('bookings.create', ['tutor_id' => $post->user_id, 'category_id' => $post->category_id]) }}" class="btn btn-primary">
                    Book Now
                </a>
            </div>
        @endif
    @endauth

    <div class="mt-4">
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to All Posts</a>
        @auth
            @if (Auth::id() == $post->user_id)
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                </form>
            @endif
        @endauth
    </div>
</div>
@endsection
