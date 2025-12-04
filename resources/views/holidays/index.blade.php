@extends('layouts.master')

@section('content')

<div class="container mt-4">
    
    <h3>Holiday List</h3>

    <a href="{{ route('holidays.upload') }}" class="btn btn-success mb-3">Upload File</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Day</th>
                <th>Date</th>
                <th>Holiday</th>
                <th>Working Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($holidays as $h)
            <tr>
                <td>{{ $h->day_name }}</td>
                <td>{{ $h->date }}</td>
                <td>{{ $h->holiday_name }}</td>
                <td>{{ $h->working_date }}</td>
                <td>
                    <a href="{{ route('holidays.edit', $h->id) }}" class="btn btn-primary btn-sm">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
