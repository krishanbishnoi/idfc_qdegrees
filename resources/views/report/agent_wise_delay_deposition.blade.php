@extends('layouts.master')

<style>
    .month-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 8px;
    }

    .month-tab {
        padding: 8px 18px;
        border: 1px solid #d0d7e2;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        background: #f8fafc;
        transition: .2s;
        user-select: none;
    }

    .month-tab:hover {
        background: #eef2ff;
    }

    .month-tab.active {
        background: #2563eb;
        color: white;
        border-color: #1e40af;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
    }

    .error-msg {
        color: red;
        font-size: 13px;
        display: none;
    }
</style>
@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">
                Agent Wise Delay Deposition - Branch:
                <strong>{{ $branch }}</strong>
            </h3>

            <a href="{{ route('select.branch', ['branch' => $branch]) }}" class="btn btn-outline-primary">
                ← Back
            </a>
        </div>
        <div class="card shadow p-4">

            {{-- month tab - raghav --}}
            {{-- <div>
                <label class="fw-bold">Select Months (Min 1, Max 3)</label>

                <div class="month-tabs" id="monthTabs">
                    @foreach ($cycle as $c)
                        @if (!empty($c))
                            <div class="month-tab" data-value="{{ $c }}">{{ $c }}</div>
                        @endif
                    @endforeach
                </div>

                <div id="monthError" class="error-msg">You can select maximum 3 months.</div>
            </div> --}}
            <div class="row">

                <div class="col-md-6">
                    <label class="fw-bold">From Month</label>
                    <select id="fromMonth" class="form-control">
                        <option value="">-- Select Start Month --</option>

                        @foreach ($cycle as $c)
                            @if (!empty($c))
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">To Month</label>
                    <select id="toMonth" class="form-control" disabled>
                        <option value="">-- Select End Month --</option>

                        @foreach ($cycle as $c)
                            @if (!empty($c))
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>

            <div id="monthRangeError" class="text-danger mt-2" style="display:none;">
                Invalid range: End month cannot be before Start month.
            </div>
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
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Payment Mode</strong></label>
                        <select id="payment_mode" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>
                {{-- DELAY DEPOSIT BUCKET DROPDOWN --}}
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Delay Deposit Bucket</strong></label>
                        <select id="delay_bucket" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>

                {{-- location drowdown --}}
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Location</strong></label>
                        <select id="location" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Collection Manager</strong></label>
                        <select id="collection_manager" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Time Bkt</strong></label>
                        <select id="time_bkt" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Pan Status</strong></label>
                        <select id="pan_status" class="form-control">
                            <option value="" selected>-- Select Pan Status --</option>
                            <option value="Not Required">Not Required</option>
                            <option value="Pan Available">Pan Available</option>
                        </select>
                    </div>
                </div>
            </div>
            {{-- SEARCH BUTTON --}}
            <div class="col-md-4">
                <div class="form-group mt-3">
                    <label><strong>&nbsp;</strong></label>
                    <button id="searchBtn" class="btn btn-primary w-100" type="button">
                        Search
                    </button>
                </div>
            </div>

        </div>

        {{-- RESULT SECTION --}}
        <div id="resultSection" class="mt-4"></div>
    </div>

    <script>
        const fromMonth = document.getElementById("fromMonth");
        const toMonth = document.getElementById("toMonth");
        const errorMsg = document.getElementById("monthRangeError");

        fromMonth.addEventListener("change", function() {
            toMonth.disabled = false;
            errorMsg.style.display = "none";
        });

        toMonth.addEventListener("change", function() {
            const fromValue = fromMonth.selectedIndex;
            const toValue = toMonth.selectedIndex;

            if (toValue < fromValue) {
                errorMsg.style.display = "block";
            } else {
                errorMsg.style.display = "none";
            }
        });
    </script>

    {{-- month tab script - raghav --}}
    {{-- <script>
        const tabs = document.querySelectorAll('.month-tab');
        const error = document.getElementById('monthError');

        function getSelectedMonths() {
            return Array.from(document.querySelectorAll('.month-tab.active'))
                .map(tab => tab.getAttribute('data-value'));
        }

        // Default: Select the first tab so minimum 1 rule is satisfied
        if (tabs.length > 0) {
            tabs[0].classList.add('active');
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const selected = getSelectedMonths();

                // If clicking an already selected tab → allow removal only if more than 1
                if (tab.classList.contains('active')) {
                    if (selected.length === 1) {
                        // must keep at least one
                        return;
                    }
                    tab.classList.remove('active');
                    error.style.display = 'none';
                    return;
                }

                // If selecting a new one → enforce max 3
                if (selected.length >= 3) {
                    error.style.display = 'block';
                    setTimeout(() => {
                        error.style.display = 'none';
                    }, 1500);
                    return;
                }

                // Activate this tab
                tab.classList.add('active');
            });
        });
    </script> --}}
    {{-- AJAX SCRIPT --}}
    <script>
        document.getElementById('product').addEventListener('change', function() {

            let product = this.value;
            let branch = "{{ $branch }}";
            let baseUrl = "{{ url('/') }}";

            let agencyDropdown = document.getElementById('agency');
            let paymentDropdown = document.getElementById('payment_mode');
            let delayDropdown = document.getElementById('delay_bucket');
            let locationDropdown = document.getElementById('location');
            let cmDropdown = document.getElementById('collection_manager');
            let timeBktDropdown = document.getElementById('time_bkt');

            // Show loading for all dropdowns together
            agencyDropdown.innerHTML =
                paymentDropdown.innerHTML =
                delayDropdown.innerHTML =
                locationDropdown.innerHTML =
                cmDropdown.innerHTML =
                timeBktDropdown.innerHTML =
                "<option>Loading...</option>";

            agencyDropdown.disabled =
                paymentDropdown.disabled =
                delayDropdown.disabled =
                locationDropdown.disabled =
                cmDropdown.disabled =
                timeBktDropdown.disabled = true;

            if (product === "") return;

            // Load ALL dropdown values in parallel
            Promise.all([
                    fetch(`${baseUrl}/get-agencies/${branch}/${product}`).then(r => r.json()),
                    fetch(`${baseUrl}/get-payment-modes/${branch}/${product}`).then(r => r.json()),
                    fetch(`${baseUrl}/get-delay-buckets/${branch}/${product}`).then(r => r.json()),
                    fetch(`${baseUrl}/get-location/${branch}/${product}`).then(r => r.json()),
                    fetch(`${baseUrl}/get-collection-manager/${branch}/${product}`).then(r => r.json()),
                    fetch(`${baseUrl}/get-time-bkt/${branch}/${product}`).then(r => r.json())
                ])
                .then(([agencies, paymentModes, delayBuckets, locations, cms, timebkt]) => {

                    // AGENCY
                    agencyDropdown.innerHTML = '<option value="">All</option>';
                    agencies.forEach(item => {
                        agencyDropdown.innerHTML +=
                            `<option value="${item.AgencyName}">${item.AgencyName}</option>`;
                    });
                    agencyDropdown.disabled = false;

                    // PAYMENT MODE
                    paymentDropdown.innerHTML = '<option value="">All</option>';
                    paymentModes.forEach(item => {
                        paymentDropdown.innerHTML +=
                            `<option value="${item.PaymentMode}">${item.PaymentMode}</option>`;
                    });
                    paymentDropdown.disabled = false;

                    // DELAY BUCKET
                    delayDropdown.innerHTML = '<option value="">All</option>';
                    delayBuckets.forEach(item => {
                        delayDropdown.innerHTML +=
                            `<option value="${item.delay_deposit_bucket}">${item.delay_deposit_bucket}</option>`;
                    });
                    delayDropdown.disabled = false;

                    // LOCATION
                    locationDropdown.innerHTML = '<option value="">All</option>';
                    locations.forEach(item => {
                        locationDropdown.innerHTML +=
                            `<option value="${item.Location}">${item.Location}</option>`;
                    });
                    locationDropdown.disabled = false;

                    // COLLECTION MANAGER
                    cmDropdown.innerHTML = '<option value="">All</option>';
                    cms.forEach(item => {
                        cmDropdown.innerHTML +=
                            `<option value="${item.CollectionManager}">${item.CollectionManager}</option>`;
                    });
                    cmDropdown.disabled = false;

                    // TIME BKT
                    timeBktDropdown.innerHTML = '<option value="">All</option>';
                    timebkt.forEach(item => {
                        timeBktDropdown.innerHTML +=
                            `<option value="${item.time_bkt}">${item.time_bkt}</option>`;
                    });
                    timeBktDropdown.disabled = false;

                });
        });


        // Hidden input updates
        ["agency", "payment_mode", "product", "delay_bucket", "location", "collection_manager", "time_bkt", "pan_status"]
        .forEach(id => {
            document.getElementById(id).addEventListener("change", function() {
                this.setAttribute("data-value", this.value);
            });
        });


        // SEARCH BUTTON
        document.getElementById('searchBtn').addEventListener('click', function() {

            let baseUrl = "{{ url('/') }}";
            let branch = "{{ $branch }}";

            let product = document.querySelector('#product').value;
            let agency = document.querySelector('#agency').value;
            let payment = document.querySelector('#payment_mode').value;
            let delayBucket = document.querySelector('#delay_bucket').value;
            let location = document.querySelector('#location').value;
            let collection_manager = document.querySelector('#collection_manager').value;
            let time_bkt = document.querySelector('#time_bkt').value;
            let pan_status = document.querySelector('#pan_status').value;

            // Month range
            let fromValue = document.getElementById("fromMonth").value;
            let toValue = document.getElementById("toMonth").value;
            let allMonths = @json($cycle);

            let startIndex = allMonths.indexOf(fromValue);
            let endIndex = allMonths.indexOf(toValue);

            let selectedMonths = [];
            if (startIndex !== -1 && endIndex !== -1 && endIndex >= startIndex) {
                selectedMonths = allMonths.slice(startIndex, endIndex + 1);
            }

            let months = selectedMonths.join(',');

            // Search request
            fetch(
                    `${baseUrl}/agent-wise-search?branch=${branch}&product=${product}&months=${months}&agency=${agency}&payment_mode=${payment}&delay_bucket=${delayBucket}&location=${location}&collection_manager=${collection_manager}&time_bkt=${time_bkt}&pan_status=${pan_status}`
                    )
                .then(response => response.text())
                .then(html => {
                    document.getElementById('resultSection').innerHTML = html;
                });

        });
    </script>
@endsection
