{{-- @extends('layouts.master')

@section('content')
<div class="container">
    <h3 class="mb-3">Allocation-Dac Report</h3>

   

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

        <div class="row">

            <div class="col-md-4 mb-3">
                <label>Sl#</label>
                <input type="text" class="form-control" name="sl_no" value="{{ $dac->sl_no }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>PaymentId</label>
                <input type="text" class="form-control" name="PaymentId" value="{{ $dac->PaymentId }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Location</label>
                <input type="text" class="form-control" name="Location" value="{{ $dac->Location }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>State</label>
                <input type="text" class="form-control" name="State" value="{{ $dac->State }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Branch Name</label>
                <input type="text" class="form-control" name="BranchName" value="{{ $dac->BranchName }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agency Id</label>
                <input type="text" class="form-control" name="AgencyId" value="{{ $dac->AgencyId }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agency Name</label>
                <input type="text" class="form-control" name="AgencyName" value="{{ $dac->AgencyName }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agent Email</label>
                <input type="email" class="form-control" name="AgentEmail" value="{{ $dac->AgentEmail }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agent Name</label>
                <input type="text" class="form-control" name="AgentName" value="{{ $dac->AgentName }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Agent Id</label>
                <input type="text" class="form-control" name="AgentId" value="{{ $dac->AgentId }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Receipt No</label>
                <input type="text" class="form-control" name="ReceiptNo" value="{{ $dac->ReceiptNo }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Receipt Date</label>
                <input type="text" class="form-control" name="ReceiptDate" value="{{ $dac->ReceiptDate }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Receipt Time</label>
                <input type="text" class="form-control" name="ReceiptTime" value="{{ $dac->ReceiptTime }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Month</label>
                <input type="text" class="form-control" name="Month" value="{{ $dac->Month }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Reference No</label>
                <input type="text" class="form-control" name="ReferenceNo" value="{{ $dac->ReferenceNo }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Customer Name</label>
                <input type="text" class="form-control" name="CustomerName" value="{{ $dac->CustomerName }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Product 1</label>
                <input type="text" class="form-control" name="Product_1" value="{{ $dac->Product_1 }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Current Bucket 1</label>
                <input type="text" class="form-control" name="Current_Bucket_1" value="{{ $dac->Current_Bucket_1 }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Combo</label>
                <input type="text" class="form-control" name="Combo" value="{{ $dac->Combo }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Collection Manager</label>
                <input type="text" class="form-control" name="CollectionManager" value="{{ $dac->CollectionManager }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Total Receipt Amount</label>
                <input type="text" class="form-control" name="TotalReceiptAmount" value="{{ $dac->TotalReceiptAmount }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Payment Mode</label>
                <input type="text" class="form-control" name="PaymentMode" value="{{ $dac->PaymentMode }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>PAN Card No</label>
                <input type="text" class="form-control" name="PANCardNo" value="{{ $dac->PANCardNo }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Batch ID</label>
                <input type="text" class="form-control" name="BatchID" value="{{ $dac->BatchID }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Batch ID Created Date</label>
                <input type="text" class="form-control" name="BatchIDCreatedDate" 
                       value="{{ str_replace(' ', 'T', $dac->BatchIDCreatedDate) }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Deposit Date</label>
                <input type="text" class="form-control" name="DepositDate" value="{{ $dac->DepositDate }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>ENCollect Pay-in-Slip ID</label>
                <input type="text" class="form-control" name="ENCollect_PayInSlip_ID" value="{{ $dac->ENCollect_PayInSlip_ID }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>CMS Pay-in-Slip ID</label>
                <input type="text" class="form-control" name="CMS_PayInSlip_ID" value="{{ $dac->CMS_PayInSlip_ID }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Deposit Amount</label>
                <input type="text" class="form-control" name="DepositAmount" value="{{ $dac->DepositAmount }}">
            </div>

        </div>

       
</div>
@endsection --}}

@extends('layouts.master')

@section('content')
<div class="container" style="max-width: 1100px;">

    <h2 class="mb-4 text-center fw-bold" style="color:#233044">
        Consolidated Allocation – Collection Report
    </h2>

    {{-- ====================== ALLOCATION REPORT ====================== --}}
    <div class="card shadow-lg border-0 mb-5" style="border-radius: 16px;">
        
        <div class="card-header text-white"
            style="background: linear-gradient(135deg, #00a6fb, #0582ca, #006494); 
                   border-radius: 16px 16px 0 0;">
            <h5 class="mb-0 fw-bold">📘 Allocation Dump Details</h5>
        </div>

        <div class="card-body p-4" style="background:#eef7ff;">

            <div class="row">
                @php
                    $allocationFields = [
                        'month' => $record->month,
                        'loan_number' => $record->loan_number,
                        'productflag_1' => $record->productflag_1,
                        'product' => $record->product,
                        'branch' => $record->branch,
                        'state' => $record->state,
                        'agency_name' => $record->agency_name,
                        'agency_yard_code' => $record->agency_yard_code,
                        'collection_manager' => $record->collection_manager,
                        'collection_manager_emp_code' => $record->collection_manager_emp_code,
                        'agent_name' => $record->agent_name,
                        'agent_sfdc_code' => $record->agent_sfdc_code,
                        'bucket' => $record->bucket,
                    ];
                @endphp

                @foreach ($allocationFields as $field => $value)
                <div class="col-md-6 mb-3">
                    <div class="p-3 bg-white shadow-sm rounded border"
                        style="border-left: 6px solid #0582ca;">
                        <div class="fw-bold text-dark text-capitalize" style="font-size: 14px;">
                            {{ str_replace('_',' ', $field) }}
                        </div>
                        <div class="text-secondary" style="font-size: 15px;">
                            {{ $value ?? '-' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>


    {{-- ====================== DAC REPORT ====================== --}}
    <div class="card shadow-lg border-0 mb-5" style="border-radius: 16px;">
        
        <div class="card-header text-white"
            style="background: linear-gradient(135deg, #7f5af0, #6245d7, #3e2ba9); 
                   border-radius: 16px 16px 0 0;">
            <h5 class="mb-0 fw-bold">📗 DAC Dump Details</h5>
        </div>

        <div class="card-body p-4" style="background:#f5f2ff;">

            <div class="row">
                @php
                    $dacFields = [
                        'sl_no' => $dac->sl_no,
                        'PaymentId' => $dac->PaymentId,
                        'Location' => $dac->Location,
                        'State' => $dac->State,
                        'BranchName' => $dac->BranchName,
                        'AgencyId' => $dac->AgencyId,
                        'AgencyName' => $dac->AgencyName,
                        'AgentEmail' => $dac->AgentEmail,
                        'AgentName' => $dac->AgentName,
                        'AgentId' => $dac->AgentId,
                        'ReceiptNo' => $dac->ReceiptNo,
                        'ReceiptDate' => $dac->ReceiptDate,
                        'ReceiptTime' => $dac->ReceiptTime,
                        'Month' => $dac->Month,
                        'ReferenceNo' => $dac->ReferenceNo,
                        'CustomerName' => $dac->CustomerName,
                        'Product_1' => $dac->Product_1,
                        'Current_Bucket_1' => $dac->Current_Bucket_1,
                        'Combo' => $dac->Combo,
                        'CollectionManager' => $dac->CollectionManager,
                        'TotalReceiptAmount' => $dac->TotalReceiptAmount,
                        'PaymentMode' => $dac->PaymentMode,
                        'PANCardNo' => $dac->PANCardNo,
                        'BatchID' => $dac->BatchID,
                        'BatchIDCreatedDate' => $dac->BatchIDCreatedDate,
                        'DepositDate' => $dac->DepositDate,
                        'ENCollect_PayInSlip_ID' => $dac->ENCollect_PayInSlip_ID,
                        'CMS_PayInSlip_ID' => $dac->CMS_PayInSlip_ID,
                        'DepositAmount' => $dac->DepositAmount,
                    ];
                @endphp

                @foreach ($dacFields as $field => $value)
                <div class="col-md-6 mb-3">
                    <div class="p-3 bg-white shadow-sm rounded border"
                        style="border-left: 6px solid #7f5af0;">
                        <div class="fw-bold text-dark text-capitalize" style="font-size: 14px;">
                            {{ str_replace('_',' ', $field) }}
                        </div>
                        <div class="text-secondary" style="font-size: 15px;">
                            {{ $value ?? '-' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

</div>
@endsection


