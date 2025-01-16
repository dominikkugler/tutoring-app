@extends('layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container">
    <h1>All Posts</h1>

    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create New Post</a>

    @if($posts->isEmpty())
        <p>No posts available.</p>
    @else
        <div class="container">
        
            @foreach ($posts as $post)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ $post->content }}</p>
                        <p class="card-text"><strong>Category:</strong> {{ $post->category->name }}</p>
                        <p class="card-text"><strong>Created by:</strong> {{ $post->user->name }}</p>
                        @if ($post->hourly_rate !== null)
                            <p class="card-text"><strong>Hourly Rate:</strong> ${{ $post->hourly_rate }}</p>
                        @else
                            <p class="card-text text-muted">Hourly rate not available</p>
                        @endif
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            @endforeach
            
        </div>

        <!-- Pagination links -->
        <div class="d-flex justify-content-between">
            <div>
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @endif
</div>
@endsection
