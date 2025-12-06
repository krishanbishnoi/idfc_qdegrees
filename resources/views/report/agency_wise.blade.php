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

    <h3 class="mb-4">
        Agency Wise Delay Deposition - Branch:
        <strong>{{ $branch }}</strong>
    </h3>

    <div class="card shadow p-4">
        
        {{-- MONTH TABS --}}
        <div>
            <label class="fw-bold">Select Months (Min 1, Max 3)</label>
            <div class="month-tabs" id="monthTabs">
                @foreach ($cycle as $c)
                    @if (!empty($c))
                        <div class="month-tab" data-value="{{ $c }}">{{ $c }}</div>
                    @endif
                @endforeach
            </div>
            <div id="monthError" class="error-msg">You can select maximum 3 months.</div>
        </div>

        {{-- DROPDOWNS --}}
        <div class="row">

            {{-- PRODUCT --}}
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

            {{-- AGENCY --}}
            <div class="col-md-4">
                <div class="form-group mt-3">
                    <label><strong>Select Agency</strong></label>
                    <select id="agency" class="form-control" disabled>
                        <option value="">-- Select Product First --</option>
                    </select>
                </div>
            </div>

            {{-- TIME BUCKET --}}
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
        <div class="col-md-4">
            <div class="form-group mt-3">
                <label><strong>&nbsp;</strong></label>
                <button id="searchBtn" class="btn btn-primary w-100" type="button">
                    Search
                </button>
            </div>
        </div>

    </div>

    {{-- RESULT --}}
    <div id="resultSection" class="mt-4"></div>

</div>

{{-- ======================= JAVASCRIPT ======================= --}}

<script>
    /* ----------------- MONTH TABS LOGIC ----------------- */
    const tabs = document.querySelectorAll('.month-tab');
    const error = document.getElementById('monthError');

    function getSelectedMonths() {
        return Array.from(document.querySelectorAll('.month-tab.active'))
            .map(tab => tab.getAttribute('data-value'));
    }

    // select first by default
    if (tabs.length > 0) {
        tabs[0].classList.add('active');
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const selected = getSelectedMonths();

            if (tab.classList.contains('active')) {
                if (selected.length === 1) return; // must keep min 1
                tab.classList.remove('active');
                error.style.display = 'none';
                return;
            }

            if (selected.length >= 3) {
                error.style.display = 'block';
                setTimeout(() => error.style.display = 'none', 1500);
                return;
            }

            tab.classList.add('active');
        });
    });

    /* ----------------- PRODUCT CHANGE → FETCH AGENCIES + TIME BKT ----------------- */
    document.getElementById('product').addEventListener('change', function () {

        let product = this.value;
        let branch = "{{ $branch }}";
        let baseUrl = "{{ url('/') }}";

        let agencyDropdown = document.getElementById('agency');
        let timeDropdown = document.getElementById('time_bkt');

        agencyDropdown.innerHTML = '<option>Loading...</option>';
        timeDropdown.innerHTML = '<option>Loading...</option>';

        agencyDropdown.disabled = true;
        timeDropdown.disabled = true;

        if (product !== "") {

            // Load Agencies
            fetch(`${baseUrl}/get-agencies/${branch}/${product}`)
                .then(res => res.json())
                .then(data => {
                    agencyDropdown.innerHTML = '<option value="">All</option>';
                    data.forEach(item => {
                        agencyDropdown.innerHTML +=
                            `<option value="${item.AgencyName}">${item.AgencyName}</option>`;
                    });
                    agencyDropdown.disabled = false;
                });

            // Load Time Bucket
            fetch(`${baseUrl}/get-time-bkt/${branch}/${product}`)
                .then(res => res.json())
                .then(data => {
                    timeDropdown.innerHTML = '<option value="">All</option>';
                    data.forEach(item => {
                        timeDropdown.innerHTML +=
                            `<option value="${item.time_bkt}">${item.time_bkt}</option>`;
                    });
                    timeDropdown.disabled = false;
                });

        } else {
            agencyDropdown.innerHTML = '<option value="">-- Select Product --</option>';
            timeDropdown.innerHTML = '<option value="">-- Select Product --</option>';
        }
    });

    /* ----------------- SEARCH BUTTON → AJAX CALL ----------------- */
    document.getElementById('searchBtn').addEventListener('click', function () {

        let baseUrl  = "{{ url('/') }}";

        let branch   = "{{ $branch }}";
        let product  = document.querySelector('#product').value;
        let agency   = document.querySelector('#agency').value;
        let time_bkt = document.querySelector('#time_bkt').value;

        // Selected months
        let months = getSelectedMonths().join(',');

        fetch(
            `${baseUrl}/agency-wise-search?branch=${branch}&product=${product}&agency=${agency}&time_bkt=${time_bkt}&months=${months}`
        )
        .then(response => response.text())
        .then(html => {
            document.getElementById('resultSection').innerHTML = html;
        });

    });
</script>

@endsection
