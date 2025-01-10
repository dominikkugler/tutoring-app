@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mt-5 text-center">
        <h1>Welcome to the App!</h1>
        <p>We're glad to have you here. Please log in or register to get started.</p>

        <!-- Wyświetlanie nazwy użytkownika, jeśli jest zalogowany -->
        @auth
            <p>Welcome, {{ auth()->user()->name }}!</p>
        @endauth

        <!-- Przycisk do przeglądania wszystkich postów -->
        <a href="{{ route('posts.index') }}" class="btn btn-primary">View All Posts</a>
    </div>
</div>
@endsection