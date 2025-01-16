@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Teaching</h1>

    {{-- General Errors --}}
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

        {{-- Category Field --}}
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                <option value="">Select a Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            {{-- Inline Error for Category --}}
            @error('category_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Rate Field --}}
        <div class="mb-3">
            <label for="rate" class="form-label">Hourly Rate</label>
            <input type="number" class="form-control @error('rate') is-invalid @enderror" id="rate" name="rate" step="0.01" min="0" value="{{ old('rate') }}" required>

            {{-- Inline Error for Rate --}}
            @error('rate')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Add Teaching</button>
    </form>
</div>
@endsection
