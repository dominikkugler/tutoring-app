@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Posts</h1>

    <!-- Filter Form -->
    <form action="{{ route('posts.index') }}" method="GET" class="mb-4">
        <div class="row align-items-end">
            <!-- Search Query -->
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input 
                    type="text" 
                    name="search" 
                    id="search" 
                    class="form-control" 
                    value="{{ request('search') }}" 
                    placeholder="Search by title or content"
                    onchange="this.form.submit()">
            </div>

            <!-- Role Filter -->
            <div class="col-md-3">
                <label for="filter" class="form-label">Filter by Author Role</label>
                <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="tutor" {{ request('filter') === 'tutor' ? 'selected' : '' }}>Tutor</option>
                    <option value="student" {{ request('filter') === 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div class="col-md-3">
                <label for="category" class="form-label">Filter by Category</label>
                <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Create Post Button -->
            @auth
                <div class="col-md-2 text-end">
                    <a href="{{ route('posts.create') }}" class="btn btn-success w-100">
                        <i class="fas fa-plus"></i> Create Post
                    </a>
                </div>
            @endauth
        </div>
    </form>

    <!-- Display Posts -->
    @if($posts->isEmpty())
        <p>No posts available.</p>
    @else
        @foreach ($posts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ \Illuminate\Support\Str::limit($post->content, 100) }}</p>
                    <p class="card-text"><strong>Category:</strong> {{ $post->category->name }}</p>
                    <p class="card-text">
                        <strong>Created by:</strong> {{ $post->user->name }} ({{ ucfirst($post->user->role) }})
                    </p>
                    @if ($post->hourly_rate !== null)
                        <p class="card-text"><strong>Hourly Rate:</strong> ${{ $post->hourly_rate }}</p>
                    @else
                        <p class="card-text text-muted">Hourly rate not available</p>
                    @endif
                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">View Details</a>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-between">
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
