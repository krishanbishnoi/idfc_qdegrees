@extends('layouts.master')

@section('content')

<div class="container mt-4">

    <h3 class="mb-4">
        Monthly Analysis - Branch: 
        <strong>{{ $branch }}</strong>
    </h3>

    <div class="card shadow p-4">

        {{-- PRODUCT DROPDOWN --}}
        <div class="form-group mt-3">
            <label><strong>Select Product</strong></label>
            <select id="product" class="form-control" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $p)
                    <option value="{{ $p->Product_1 }}">{{ $p->Product_1 }}</option>
                @endforeach
            </select>
        </div>

        {{-- AGENCY DROPDOWN --}}
        <div class="form-group mt-4">
            <label><strong>Select Agency</strong></label>
            <select id="agency" class="form-control" disabled>
                <option value="">-- Select Product First --</option>
            </select>
        </div>

        {{-- PAYMENT MODE DROPDOWN --}}
        <div class="form-group mt-4">
            <label><strong>Select Payment Mode</strong></label>
            <select id="payment_mode" class="form-control" disabled>
                <option value="">-- Select Product First --</option>
            </select>
        </div>

        {{-- SEARCH BUTTON --}}
        <div class="form-group mt-4">
            <button id="searchBtn" class="btn btn-primary w-100" type="button">
                Search
            </button>
        </div>

    </div>

    {{-- RESULT SECTION --}}
    <div id="resultSection" class="mt-4"></div>

</div>


{{-- AJAX SCRIPT --}}
<script>
document.getElementById('product').addEventListener('change', function () {

    let product = this.value;
    let branch = "{{ $branch }}";

    let agencyDropdown = document.getElementById('agency');
    let paymentDropdown = document.getElementById('payment_mode');

    agencyDropdown.innerHTML = '<option>Loading...</option>';
    paymentDropdown.innerHTML = '<option>Loading...</option>';

    agencyDropdown.disabled = true;
    paymentDropdown.disabled = true;

    let baseUrl = "{{ url('/') }}";

    if(product !== "") {

        // ---- Load Agencies ----
        fetch(`${baseUrl}/get-agencies/${branch}/${product}`)
        .then(response => response.json())
        .then(data => {

            agencyDropdown.innerHTML = '<option value="">-- Select Agency --</option>';

            data.forEach(function(item){
                agencyDropdown.innerHTML += 
                    `<option value="${item.AgencyName}">${item.AgencyName}</option>`;
            });

            agencyDropdown.disabled = false;
        });

        // ---- Load Payment Modes ----
        fetch(`${baseUrl}/get-payment-modes/${branch}/${product}`)
        .then(response => response.json())
        .then(data => {

            paymentDropdown.innerHTML = '<option value="">-- Select Payment Mode --</option>';

            data.forEach(function(item){
                paymentDropdown.innerHTML += 
                    `<option value="${item.PaymentMode}">${item.PaymentMode}</option>`;
            });

            paymentDropdown.disabled = false;
        });

    } else {
        agencyDropdown.innerHTML = '<option value="">-- Select Product First --</option>';
        paymentDropdown.innerHTML = '<option value="">-- Select Product First --</option>';

        agencyDropdown.disabled = true;
        paymentDropdown.disabled = true;
    }
});


// --- HIDDEN INPUT UPDATES ---
document.getElementById('agency').addEventListener('change', function () {
    this.setAttribute("data-value", this.value);
});
document.getElementById('payment_mode').addEventListener('change', function () {
    this.setAttribute("data-value", this.value);
});
document.getElementById('product').addEventListener('change', function () {
    this.setAttribute("data-value", this.value);
});


// --- SEARCH BUTTON CLICK ---
document.getElementById('searchBtn').addEventListener('click', function () {

    let baseUrl = "{{ url('/') }}";

    let branch  = "{{ $branch }}";
    let product = document.querySelector('#product').value;
    let agency  = document.querySelector('#agency').value;
    let payment = document.querySelector('#payment_mode').value;

    if (!product || !agency || !payment) {
        alert("Please select Product, Agency and Payment Mode.");
        return;
    }

    fetch(`${baseUrl}/monthly-search?branch=${branch}&product=${product}&agency=${agency}&payment_mode=${payment}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('resultSection').innerHTML = html;
        });
});
</script>

@endsection
