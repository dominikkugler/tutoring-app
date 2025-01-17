@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create a Booking</h2>

    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="tutor" class="form-label">Tutor</label>
            <input type="text" class="form-control" id="tutor" value="{{ $tutor->name }}" disabled>
            <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" id="category" value="{{ $category->name }}" disabled>
            <input type="hidden" name="category_id" value="{{ $category->id }}">
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>

        <div class="mb-3">
            <label for="start_hour" class="form-label">Start Hour</label>
            <input type="time" class="form-control" id="start_hour" name="start_hour" required>
        </div>

        <div class="mb-3">
            <label for="end_hour" class="form-label">End Hour</label>
            <input type="time" class="form-control" id="end_hour" name="end_hour" required>
        </div>

        <button type="submit" class="btn btn-primary">Book</button>
    </form>
</div>
@endsection
