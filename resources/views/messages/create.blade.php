@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Send a Message</h1>

    <!-- Check for any success or error messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Form to send a new message -->
    <form action="{{ route('messages.store') }}" method="POST">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}"> <!-- Pass post ID -->
        <input type="hidden" name="recipient_id" value="{{ $recipientId }}"> <!-- Pass recipient ID -->

        <div class="mb-3">
            <label for="recipient" class="form-label">Recipient</label>
            <p>{{ $post->user->name }}</p> <!-- Display the recipient (post author) -->
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Message Content</label>
            <textarea name="content" id="content" rows="5" class="form-control" required>{{ old('content') }}</textarea>
            @error('content')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>



    <div class="mt-3">
        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-secondary">Back to Post</a>
    </div>
</div>
@endsection
