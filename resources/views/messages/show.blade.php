<!-- resources/views/messages/show.blade.php -->
@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Auth;
@endphp

@section('content')
<div class="container">
    <h1>Chat with {{ $message->sender->name }}</h1>

    <!-- Display the conversation -->
    <div class="chat-box" style="max-height: 400px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px;">
        @foreach($messages as $msg)
            <div class="message {{ $msg->sender_id == Auth::id() ? 'sent' : 'received' }}">
                <strong>{{ $msg->sender->name }}:</strong>
                <p>{{ $msg->content }}</p>
                <small>{{ $msg->created_at->format('Y-m-d H:i') }}</small>
            </div>
        @endforeach
    </div>

    <!-- Message Input -->
    <form action="{{ route('messages.store') }}" method="POST">
        @csrf
        <input type="hidden" name="recipient_id" value="{{ $message->sender_id }}">
        <div class="input-group mt-3">
            <input type="text" name="content" class="form-control" placeholder="Type a message..." required>
            <button type="submit" class="btn btn-primary">Send</button>
        </div>
    </form>

    <a href="{{ route('messages.index') }}" class="btn btn-secondary mt-3">Back to Messages</a>
</div>

<style>
    .sent {
        text-align: right;
        background-color: #f1f1f1;
        padding: 10px;
        margin: 5px;
        border-radius: 5px;
    }

    .received {
        text-align: left;
        background-color: #e0e0e0;
        padding: 10px;
        margin: 5px;
        border-radius: 5px;
    }
</style>

@endsection
