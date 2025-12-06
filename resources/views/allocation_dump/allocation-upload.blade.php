@extends('layouts.master')
@section('content')
    <div class="container py-4">

        {{-- PAGE HEADER --}}
        <div class="mb-4">
            <h2 class="fw-bold">Automated Report – Dump Upload Center</h2>
            <p class="text-muted">Upload Allocation Dump and DAC Dump for monthly automated analysis</p>
        </div>

        {{-- SUCCESS / ERROR ALERTS --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        <div class="row g-4">

            {{-- ================= Allocation Dump Upload ================= --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-dark text-white d-flex align-items-center">
                        <i class="bi bi-upload me-2 fs-4"></i>
                        <h5 class="mb-0">Upload Allocation Dump</h5>
                    </div>

                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Upload the latest Allocation dump (.xlsx, .xls, .csv).
                            System will automatically process & sync with DAC data.
                        </p>

                        <form action="{{ route('allocation.upload.file') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Excel File</label>
                                <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-cloud-arrow-up"></i> Upload Allocation Dump
                            </button>
                        </form>
                    </div>
                </div>
            </div>


            {{-- ================= DAC Dump Upload ================= --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-secondary text-white d-flex align-items-center">
                        <i class="bi bi-file-earmark-arrow-up me-2 fs-4"></i>
                        <h5 class="mb-0">Upload DAC Dump</h5>
                    </div>

                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Upload the DAC dump received from system.
                            Ensure correct format before uploading.
                        </p>

                        <form action="{{ route('dac.upload.file') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Select File</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="bi bi-cloud-arrow-up-fill"></i> Upload DAC Dump
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- ================= Holiday Upload ================= --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-info text-white d-flex align-items-center">
                        <i class="bi bi-calendar-week me-2 fs-4"></i>
                        <h5 class="mb-0">Upload Holiday Calendar</h5>
                    </div>

                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Upload the yearly holiday calendar used for automated business rules.
                            Only Excel files are supported.
                        </p>

                        {{-- Sample Download Button --}}
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ asset('sample_files/holiday_sample.xlsx') }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-download"></i> Download Sample
                            </a>
                        </div>

                        {{-- Upload Form --}}
                        <form method="POST" action="{{ route('holidays.upload') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Excel File</label>
                                <input type="file" name="file" class="form-control" required>
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button class="btn btn-info w-100 text-white">
                                <i class="bi bi-cloud-arrow-up"></i> Upload Holiday File
                            </button>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-12 mt-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="mt-4">
                            <h4 class="fw-bold mb-3">View Uploaded Dump Data</h4>

                            <a href="{{ route('allocationdump.index') }}" class="btn btn-outline-primary mb-2">
                                View Allocation Dump
                            </a>

                            <a href="{{ route('dacdump.index') }}" class="btn btn-outline-success mb-2">
                                View DAC Dump
                            </a>

                            <a href="{{ route('allocationdac.index') }}" class="btn btn-outline-info mb-2">
                                View Merged Allocation & DAC Data
                            </a>
                            <a href="{{ route('holidays.index') }}" class="btn btn-outline-info mb-2">
                                View Holidays
                            </a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    @endsection
