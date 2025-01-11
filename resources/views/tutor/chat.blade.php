@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px; margin: 0 auto;">
    <h1 class="text-center">Chat with {{ $chatUser->name }}</h1>

    <div class="chat-box" style="
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        background-color: #f9f9f9;
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 20px;
    ">
        @foreach($messages as $message)
            <div class="message @if($message->sender_id == Auth::id()) sender @else recipient @endif" style="
                padding: 10px;
                margin: 10px 0;
                border-radius: 15px;
                max-width: 70%;
                word-wrap: break-word;
                @if($message->sender_id == Auth::id())
                    background-color: #007bff;
                    color: white;
                    align-self: flex-end;
                    margin-left: auto;
                @else
                    background-color: #e9ecef;
                    color: #333;
                    align-self: flex-start;
                    margin-right: auto;
                @endif
            ">
                <p>{{ $message->content }}</p>
                <span style="
                    display: block;
                    font-size: 0.8rem;
                    text-align: right;
                    margin-top: 5px;
                    @if($message->sender_id == Auth::id())
                        color: white;
                    @else
                        color: #666;
                    @endif
                ">{{ $message->created_at->format('Y-m-d H:i') }}</span>
            </div>
        @endforeach
    </div>

    <form action="{{ route('chat.send.tutor', $chatUser->id) }}" method="POST" class="mt-3">
        @csrf
        <textarea name="message" class="form-control mb-2" rows="3" placeholder="Type your message..." required></textarea>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>
@endsection
