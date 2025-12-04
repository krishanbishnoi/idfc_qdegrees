@extends('layouts.master')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Record</h3>

    <form action="{{ route('allocationdump.update', $record->id) }}" method="POST">
        @csrf

        <div class="row">

            <div class="col-md-4 mb-3">
                <label>Month</label>
                <input type="text" class="form-control" name="month" value="{{ $record->month }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Loan Number</label>
                <input type="text" class="form-control" name="loan_number" value="{{ $record->loan_number }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Product Flag 1</label>
                <input type="text" class="form-control" name="productflag_1" value="{{ $record->productflag_1 }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Product</label>
                <input type="text" class="form-control" name="product" value="{{ $record->product }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Branch</label>
                <input type="text" class="form-control" name="branch" value="{{ $record->branch }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>State</label>
                <input type="text" class="form-control" name="state" value="{{ $record->state }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agency Name</label>
                <input type="text" class="form-control" name="agency_name" value="{{ $record->agency_name }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agency Yard Code</label>
                <input type="text" class="form-control" name="agency_yard_code" value="{{ $record->agency_yard_code }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Collection Manager</label>
                <input type="text" class="form-control" name="collection_manager" value="{{ $record->collection_manager }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Collection Manager Emp Code</label>
                <input type="text" class="form-control" name="collection_manager_emp_code" value="{{ $record->collection_manager_emp_code }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agent Name</label>
                <input type="text" class="form-control" name="agent_name" value="{{ $record->agent_name }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agent SFDC Code</label>
                <input type="text" class="form-control" name="agent_sfdc_code" value="{{ $record->agent_sfdc_code }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Bucket</label>
                <input type="text" class="form-control" name="bucket" value="{{ $record->bucket }}">
            </div>

        </div>


        <button class="btn btn-success mt-3">Update</button>
        <a href="{{ route('allocationdump.index') }}" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>
@endsection
