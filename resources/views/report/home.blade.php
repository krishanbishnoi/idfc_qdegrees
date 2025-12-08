@extends('layouts.master')

@section('content')

<style>
    .report-card {
        transition: 0.3s;
        cursor: pointer;
        border-radius: 14px !important;
        color: #fff !important;
    }
    .report-card * {
        color: #fff !important;
        text-shadow: 0px 1px 3px rgba(0,0,0,0.4);
    }
    .report-card:hover {
        transform: translateY(-6px);
        box-shadow: 0px 12px 28px rgba(0,0,0,0.20);
    }
    .report-icon {
        font-size: 42px;
        opacity: 0.9;
    }

    .card-monthly { background: linear-gradient(135deg, #5a84ff, #3456d1); }
    .card-agency { background: linear-gradient(135deg, #25d597, #0f8b5f); }
    .card-agent  { background: linear-gradient(135deg, #ffcf4d, #d9a200); }

    .back-btn {
        display: inline-block;
        margin-bottom: 20px;
        padding: 10px 18px;
        border-radius: 8px;
        background: #e2e8f0;
        color: #1e293b;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }
    .back-btn:hover {
        background: #cbd5e1;
        color: #000;
    }
</style>

<div class="container mt-5">

    <!-- BACK BUTTON -->
    <a href="{{ route('select.branch') }}" class="back-btn">
    ← Back
</a>


    <h2 class="text-center mb-4 fw-bold">
        📊 Reports for Branch: <span class="text-primary">{{ $branch }}</span>
    </h2>

    <div class="row justify-content-center">

        <!-- Monthly Analysis -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('monthly.analysis', $branch) }}" target="_blank" class="text-decoration-none">
                <div class="card report-card card-monthly text-center p-4 border-0">
                    <div class="report-icon mb-2">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h4 class="fw-bold">Monthly Analysis</h4>
                    <p class="small">View month-wise performance</p>
                </div>
            </a>
        </div>

        <!-- Agency Wise Delay -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('agency.wise.delay.deposition', $branch) }}" target="_blank" class="text-decoration-none">
                <div class="card report-card card-agency text-center p-4 border-0">
                    <div class="report-icon mb-2">
                        <i class="bi bi-building"></i>
                    </div>
                    <h4 class="fw-bold">Agency Wise Delay</h4>
                    <p class="small">Track delays by agencies</p>
                </div>
            </a>
        </div>

        <!-- Agent Wise Delay -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('agent.wise.delay.deposition', $branch) }}" target="_blank" class="text-decoration-none">
                <div class="card report-card card-agent text-center p-4 border-0">
                    <div class="report-icon mb-2">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h4 class="fw-bold">Agent Wise Delay</h4>
                    <p class="small">Track delays by individual agents</p>
                </div>
            </a>
        </div>

    </div>

</div>

@endsection
