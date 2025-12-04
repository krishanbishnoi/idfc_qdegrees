@extends('layouts.master')

@section('content')
<div class="container">
    <h3>Edit Dac Dump Record</h3>

    <form action="{{ route('dacdump.update', $record->id) }}" method="POST">
        @csrf

        <div class="row">

            <div class="col-md-4 mb-3">
                <label>Sl#</label>
                <input type="text" class="form-control" name="sl_no" value="{{ $record->sl_no }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>PaymentId</label>
                <input type="text" class="form-control" name="PaymentId" value="{{ $record->PaymentId }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Location</label>
                <input type="text" class="form-control" name="Location" value="{{ $record->Location }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>State</label>
                <input type="text" class="form-control" name="State" value="{{ $record->State }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Branch Name</label>
                <input type="text" class="form-control" name="BranchName" value="{{ $record->BranchName }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agency Id</label>
                <input type="text" class="form-control" name="AgencyId" value="{{ $record->AgencyId }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agency Name</label>
                <input type="text" class="form-control" name="AgencyName" value="{{ $record->AgencyName }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agent Email</label>
                <input type="email" class="form-control" name="AgentEmail" value="{{ $record->AgentEmail }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agent Name</label>
                <input type="text" class="form-control" name="AgentName" value="{{ $record->AgentName }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agent Id</label>
                <input type="text" class="form-control" name="AgentId" value="{{ $record->AgentId }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Receipt No</label>
                <input type="text" class="form-control" name="ReceiptNo" value="{{ $record->ReceiptNo }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Receipt Date</label>
                <input type="date" class="form-control" name="ReceiptDate" value="{{ $record->ReceiptDate }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Receipt Time</label>
                <input type="time" class="form-control" name="ReceiptTime" value="{{ $record->ReceiptTime }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Month</label>
                <input type="text" class="form-control" name="Month" value="{{ $record->Month }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Reference No</label>
                <input type="text" class="form-control" name="ReferenceNo" value="{{ $record->ReferenceNo }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Customer Name</label>
                <input type="text" class="form-control" name="CustomerName" value="{{ $record->CustomerName }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Product 1</label>
                <input type="text" class="form-control" name="Product_1" value="{{ $record->Product_1 }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Current Bucket 1</label>
                <input type="text" class="form-control" name="Current_Bucket_1" value="{{ $record->Current_Bucket_1 }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Combo</label>
                <input type="text" class="form-control" name="Combo" value="{{ $record->Combo }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Collection Manager</label>
                <input type="text" class="form-control" name="CollectionManager" value="{{ $record->CollectionManager }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Total Receipt Amount</label>
                <input type="text" class="form-control" name="TotalReceiptAmount" value="{{ $record->TotalReceiptAmount }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Payment Mode</label>
                <input type="text" class="form-control" name="PaymentMode" value="{{ $record->PaymentMode }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>PAN Card No</label>
                <input type="text" class="form-control" name="PANCardNo" value="{{ $record->PANCardNo }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Batch ID</label>
                <input type="text" class="form-control" name="BatchID" value="{{ $record->BatchID }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Batch ID Created Date</label>
                <input type="datetime-local" class="form-control" name="BatchIDCreatedDate" 
                       value="{{ str_replace(' ', 'T', $record->BatchIDCreatedDate) }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Deposit Date</label>
                <input type="date" class="form-control" name="DepositDate" value="{{ $record->DepositDate }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>ENCollect Pay-in-Slip ID</label>
                <input type="text" class="form-control" name="ENCollect_PayInSlip_ID" value="{{ $record->ENCollect_PayInSlip_ID }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>CMS Pay-in-Slip ID</label>
                <input type="text" class="form-control" name="CMS_PayInSlip_ID" value="{{ $record->CMS_PayInSlip_ID }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Deposit Amount</label>
                <input type="text" class="form-control" name="DepositAmount" value="{{ $record->DepositAmount }}">
            </div>

        </div>

        <button class="btn btn-success mt-3">Update</button>
        <a href="{{ route('dacdump.index') }}" class="btn btn-secondary mt-3">Back</a>

    </form>
</div>
@endsection
