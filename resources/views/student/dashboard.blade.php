@extends('layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container">
    <h1>Student Dashboard</h1>
    
    <h3>Welcome, {{ $user->name }} ({{ $user->email }})</h3>

    <h4>Your Posts:</h4>
    @if($posts->isEmpty())
        <p>You have no posts yet.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
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
    @endif

    <h4>Your Messages:</h4>
    @if($messages->isEmpty())
        <p>You have no messages.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Message</th>
                    <th>Sent At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                    <tr>
                        <td>{{ $message->sender->name }}</td>
                        <td>{{ Str::limit($message->content, 50) }}</td>
                        <td>{{ $message->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('messages.show', $message->id) }}" class="btn btn-info btn-sm">View Chat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
