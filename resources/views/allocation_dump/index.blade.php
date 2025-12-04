@extends('layouts.master')

@section('content')
    <div class="container">
        <h2 class="mb-3">Allocation Dump List</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form method="GET" action="{{ route('allocationdump.index') }}" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" 
                   placeholder="Search Loan No / Branch / Agency Name / Product..."
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Search</button>
        </div>
    </div>
</form>


        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Loan Number</th>
                    <th>Product</th>

                    <th>Product Flag</th>
                    <th>Branch</th>
                    <th>Agency Name</th>

                    <th>State</th>

                    <th>Edit</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($records as $r)
                    <tr>
                        <td>{{ $r->loan_number }}</td>
                        <td>{{ $r->product }}</td>

                        <td>{{ $r->productflag_1 }}</td>
                        <td>{{ $r->branch }}</td>
                        <td>{{ $r->agency_name }}</td>

                        <td>{{ $r->state }}</td>

                        <td>
                            <a href="{{ route('allocationdump.edit', $r->id) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $records->links() }}
    </div>
@endsection
