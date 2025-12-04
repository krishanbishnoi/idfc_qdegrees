@extends('layouts.master')

@section('content')
    <div class="container mt-4">

        <h3 class="text-center mb-4">
            Reports for Branch: <strong>{{ $branch }}</strong>
        </h3>

        <div class="row">

            <div class="col-md-6 mb-3">
                <a href="{{ route('monthly.analysis', $branch) }}" target="_blank" class="btn btn-primary w-100 p-3"
                    style="font-size:18px;">
                    Monthly Analysis
                </a>

            </div>

            <div class="col-md-6 mb-3">
                <a href="#" class="btn btn-success w-100 p-3" style="font-size:18px;">
                    Agency wise Delay Deposition
                </a>
            </div>

            <div class="col-md-6 mb-3">
                <a href="#" class="btn btn-warning w-100 p-3" style="font-size:18px;">
                    Agent wise Delay Deposition
                </a>
            </div>

            <div class="col-md-6 mb-3">
                <a href="#" class="btn btn-info w-100 p-3" style="font-size:18px;">
                    PAN Status
                </a>
            </div>

        </div>

    </div>
@endsection
