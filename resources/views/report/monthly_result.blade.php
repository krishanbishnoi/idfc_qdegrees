@extends('layouts.master')

@section('content')

<div class="container mt-4">
    
    <h3 class="mb-3">Monthly Analysis Result</h3>

    <div class="card shadow p-4">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Agency Name</th>
                    <th>Agent ID</th>        {{-- updated ✔ --}}
                    <th>Count</th>
                    <th>Total Receipt Amount</th>
                </tr>
            </thead>

            <tbody>
                @foreach($results as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->AgencyName }}</td>
                    <td>{{ $row->AgentId }}</td>  {{-- updated ✔ --}}
                    <td>{{ $row->total_count }}</td>
                    <td>{{ number_format($row->total_receipt, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($results) == 0)
            <p class="text-center text-danger">No data found.</p>
        @endif

    </div>

</div>

@endsection
