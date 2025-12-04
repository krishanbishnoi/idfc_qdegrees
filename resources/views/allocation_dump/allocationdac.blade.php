@extends('layouts.master')

@section('content')
    <div class="container">
        <h2 class="mb-3">Allocation Dac List</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form method="GET" action="{{ route('dacdump.index') }}" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" 
                   placeholder="Search Receipt No / Branch / Location / Agent..."
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
                    <th>ReceiptNo</th>
                    <th>Branch</th>

                    <th>Location</th>
                    <th>Agency</th>
                    <th>Product</th>

                    <th>Agent</th>

                    <th>Amount</th>
                    <th>Edit</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($records as $r)
                    <tr>
                        <td>{{ $r->ReceiptNo }}</td>
                        <td>{{ $r->BranchName }}</td>

                        <td>{{ $r->Location }}</td>
                        <td>{{ $r->AgencyName }}</td>
                        <td>{{ $r->Product_1 }}</td>

                        <td>{{ $r->AgentName }}</td>
                        <td>{{ $r->TotalReceiptAmount }}</td>

                        <td>
                            <a href="{{ route('allocationdac.edit', $r->id) }}" class="btn btn-sm btn-primary">
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
