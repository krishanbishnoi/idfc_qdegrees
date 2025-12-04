@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <h3>Edit Holiday</h3>

        <form method="POST" action="{{ route('holidays.update', $holiday->id) }}">
            @csrf

            <div class="form-group">
                <label>Day</label>
                <input type="text" name="day_name" value="{{ $holiday->day_name }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" value="{{ $holiday->date }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Holiday</label>
                <input type="text" name="holiday_name" value="{{ $holiday->holiday_name }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Working Date</label>
                <input type="date" name="working_date" value="{{ $holiday->working_date }}" class="form-control">
            </div>

            <button class="btn btn-primary mt-2">Update</button>
        </form>
    </div>
@endsection
