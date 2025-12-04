@extends('layouts.master')

@section('content')

<style>
    .upload-card {
        max-width: 650px;
        margin: auto;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: linear-gradient(145deg, #ffffff, #f6f6f6);
    }
    .upload-title {
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: .5px;
    }
    .btn-sample {
        background: #16a085;
        color: white;
        font-weight: 600;
    }
    .btn-sample:hover {
        background: #13856f;
        color: white;
    }
</style>


<div class="container mt-5">

    <div class="upload-card">

        {{-- Header with Sample Download Button --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="upload-title mb-0">Upload Holidays</h3>

            <a href="{{ asset('sample_files/holiday_sample.xlsx') }}" class="btn btn-sample">
                ⬇ Sample
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('holidays.upload') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label class="fw-bold">Select Excel File</label>
                <input type="file" name="file" class="form-control" required>
            </div>

            <button class="btn btn-primary mt-2 w-100">Upload</button>
        </form>

    </div>

</div>

@endsection
