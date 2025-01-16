@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Availability</h2>

    <!-- Display validation errors -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Availability form -->
    <form action="{{ route('availabilities.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>

        <div class="mb-3">
            <label for="start_hour" class="form-label">Start Hour</label>
            <select class="form-control" id="start_hour" name="start_hour" required>
                @for ($hour = 0; $hour < 24; $hour++)
                    @foreach (['00', '15', '30', '45'] as $minute)
                        <option value="{{ sprintf('%02d:%s', $hour, $minute) }}">
                            {{ sprintf('%02d:%s', $hour, $minute) }}
                        </option>
                    @endforeach
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label for="end_hour" class="form-label">End Hour</label>
            <select class="form-control" id="end_hour" name="end_hour" required>
                @for ($hour = 0; $hour < 24; $hour++)
                    @foreach (['00', '15', '30', '45'] as $minute)
                        <option value="{{ sprintf('%02d:%s', $hour, $minute) }}">
                            {{ sprintf('%02d:%s', $hour, $minute) }}
                        </option>
                    @endforeach
                @endfor
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Availability</button>
    </form>
</div>
@endsection
