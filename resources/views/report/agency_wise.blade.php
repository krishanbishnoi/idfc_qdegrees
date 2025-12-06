@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">
            Agency Wise Delay Deposition - Branch:
            <strong>{{ $branch }}</strong>
        </h3>
        <div class="card shadow p-4">
            <div class="row">
                {{-- PRODUCT DROPDOWN --}}
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Product</strong></label>
                        <select id="product" class="form-control" required>
                            <option value="">-- Select Product --</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->Product_1 }}">{{ $p->Product_1 }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- AGENCY DROPDOWN --}}
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Agency</strong></label>
                        <select id="agency" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>
                {{-- PAYMENT MODE DROPDOWN --}}
                {{-- <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Payment Mode</strong></label>
                        <select id="payment_mode" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div> --}}
                {{-- DELAY DEPOSIT BUCKET DROPDOWN --}}
                {{-- <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Delay Deposit Bucket</strong></label>
                        <select id="delay_bucket" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div> --}}

                {{-- location drowdown --}}
                {{-- <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Location</strong></label>
                        <select id="location" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div> --}}

                {{-- <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Collection Manager</strong></label>
                        <select id="collection_manager" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div> --}}

                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Time Bkt</strong></label>
                        <select id="time_bkt" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>
            </div>
            {{-- SEARCH BUTTON --}}
            {{-- <div class="col-md-4">
                <div class="form-group mt-3">
                    <label><strong>&nbsp;</strong></label>
                    <button id="searchBtn" class="btn btn-primary w-100" type="button">
                        Search
                    </button>
                </div>
            </div> --}}

        </div>

        {{-- RESULT SECTION --}}
        <div id="resultSection" class="mt-4"></div>
    </div>


    {{-- AJAX SCRIPT --}}
    <script>
        document.getElementById('product').addEventListener('change', function() {

            let product = this.value;
            let branch = "{{ $branch }}";

            let agencyDropdown = document.getElementById('agency');
            let paymentDropdown = document.getElementById('payment_mode');

            agencyDropdown.innerHTML = '<option>Loading...</option>';
            paymentDropdown.innerHTML = '<option>Loading...</option>';

            agencyDropdown.disabled = true;
            paymentDropdown.disabled = true;

            let baseUrl = "{{ url('/') }}";

            if (product !== "") {

                // ---- Load Agencies ----
                fetch(`${baseUrl}/get-agencies/${branch}/${product}`)
                    .then(response => response.json())
                    .then(data => {

                        agencyDropdown.innerHTML = '<option value="">All</option>';

                        data.forEach(function(item) {
                            agencyDropdown.innerHTML +=
                                `<option value="${item.AgencyName}">${item.AgencyName}</option>`;
                        });

                        agencyDropdown.disabled = false;
                    });

                // ---- Load Payment Modes ----
                fetch(`${baseUrl}/get-payment-modes/${branch}/${product}`)
                    .then(response => response.json())
                    .then(data => {

                        paymentDropdown.innerHTML = '<option value="">All</option>';

                        data.forEach(function(item) {
                            paymentDropdown.innerHTML +=
                                `<option value="${item.PaymentMode}">${item.PaymentMode}</option>`;
                        });

                        paymentDropdown.disabled = false;
                    });

                // ---- Load Delay Deposit Buckets ----
                fetch(`${baseUrl}/get-delay-buckets/${branch}/${product}`)
                    .then(response => response.json())
                    .then(data => {

                        let delayDropdown = document.getElementById('delay_bucket');
                        delayDropdown.innerHTML = '<option value="">All</option>';

                        data.forEach(function(item) {
                            delayDropdown.innerHTML +=
                                `<option value="${item.delay_deposit_bucket}">${item.delay_deposit_bucket}</option>`;
                        });

                        delayDropdown.disabled = false;
                    });

                // load location
                // load location
                fetch(`${baseUrl}/get-location/${branch}/${product}`)
                    .then(response => response.json())
                    .then(data => {

                        let locationDropdown = document.getElementById('location');
                        locationDropdown.innerHTML = '<option value="">All</option>';

                        data.forEach(function(item) {
                            locationDropdown.innerHTML +=
                                `<option value="${item.Location}">${item.Location}</option>`;
                        });

                        locationDropdown.disabled = false;
                    });

                fetch(`${baseUrl}/get-collection-manager/${branch}/${product}`)
                    .then(response => response.json())
                    .then(data => {

                        let delayDropdown = document.getElementById('collection_manager');
                        delayDropdown.innerHTML = '<option value="">All</option>';

                        data.forEach(function(item) {
                            delayDropdown.innerHTML +=
                                `<option value="${item.CollectionManager}">${item.CollectionManager}</option>`;
                        });

                        delayDropdown.disabled = false;
                    });


                    fetch(`${baseUrl}/get-time-bkt/${branch}/${product}`)
                    .then(response => response.json())
                    .then(data => {

                        let delayDropdown = document.getElementById('time_bkt');
                        delayDropdown.innerHTML = '<option value="">All</option>';

                        data.forEach(function(item) {
                            delayDropdown.innerHTML +=
                                `<option value="${item.time_bkt}">${item.time_bkt}</option>`;
                        });

                        delayDropdown.disabled = false;
                    });

            } else {
                agencyDropdown.innerHTML = '<option value="">-- Select Product --</option>';
                paymentDropdown.innerHTML = '<option value="">-- Select Product --</option>';

                agencyDropdown.disabled = true;
                paymentDropdown.disabled = true;
            }
        });


        // --- HIDDEN INPUT UPDATES ---
        document.getElementById('agency').addEventListener('change', function() {
            this.setAttribute("data-value", this.value);
        });
        document.getElementById('payment_mode').addEventListener('change', function() {
            this.setAttribute("data-value", this.value);
        });
        document.getElementById('product').addEventListener('change', function() {
            this.setAttribute("data-value", this.value);
        });
        document.getElementById('delay_bucket').addEventListener('change', function() {
            this.setAttribute("data-value", this.value);
        });
        document.getElementById('location').addEventListener('change', function() {
            this.setAttribute("data-value", this.value);
        });
        document.getElementById('collection_manager').addEventListener('change', function() {
            this.setAttribute("data-value", this.value);
        });
        document.getElementById('time_bkt').addEventListener('change', function() {
            this.setAttribute("data-value", this.value);
        });
        



        // --- SEARCH BUTTON CLICK ---
  document.getElementById('searchBtn').addEventListener('click', function() {

    let baseUrl = "{{ url('/') }}";

    let branch      = "{{ $branch }}";
    let product     = document.querySelector('#product').value;
    let agency      = document.querySelector('#agency').value;
    let payment     = document.querySelector('#payment_mode').value;
    let delayBucket = document.querySelector('#delay_bucket').value;
    let location    = document.querySelector('#location').value;
    let collection_manager = document.querySelector('#collection_manager').value;
    let time_bkt = document.querySelector('#time_bkt').vlaue;

    
    // ❌ VALIDATION REMOVED COMPLETELY

    fetch(
        `${baseUrl}/agent-wise-search?branch=${branch}&product=${product}&agency=${agency}&payment_mode=${payment}&delay_bucket=${delayBucket}&location=${location}&collection_manager=${collection_manager}&time_bkt=${time_bkt}`
    )
    .then(response => response.text())
    .then(html => {
        document.getElementById('resultSection').innerHTML = html;
    });

});

    </script>
@endsection
