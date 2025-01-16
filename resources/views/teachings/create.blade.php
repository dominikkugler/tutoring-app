@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Teaching</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teachings.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Select a Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="rate" class="form-label">Hourly Rate</label>
            <input type="number" class="form-control" id="rate" name="rate" step="0.01" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Teaching</button>
    </form>
</div>
@endsection
