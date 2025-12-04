@extends('layouts.master')

@section('content')
<style>
    .scroll-box {
        max-height: 330px;
        overflow-y: auto;
        padding-right: 5px;
    }
    .scroll-box::-webkit-scrollbar {
        width: 6px;
    }
    .scroll-box::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    .scroll-box::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<div class="container-fluid mt-4">

    <h2 class="mb-4 fw-bold text-center" style="color:#2c3e50;">
        Allocation Summary Dashboard
    </h2>

    <div class="row">

        <!-- Agency Table -->
        <div class="col-md-3 col-lg-3 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Agency Wise Counts
                </div>

                <div class="p-2">
                    <input type="text" id="search-agency" class="form-control" placeholder="Search Agency...">
                </div>

                <div class="card-body p-0 scroll-box">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Name</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($counts as $item)
                                <tr class="agency-row" 
                                    onclick="loadDetails('agency', '{{ $item->agency_name }}')" 
                                    style="cursor:pointer;">
                                    <td class="agency-name">{{ $item->agency_name }}</td>
                                    <td class="fw-bold">{{ $item->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- CM Table -->
        <div class="col-md-3 col-lg-3 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white fw-bold">
                    Collection Manager Counts
                </div>

                <div class="p-2">
                    <input type="text" id="search-cm" class="form-control" placeholder="Search CM...">
                </div>

                <div class="card-body p-0 scroll-box">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Name</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cm as $c)
                                <tr class="cm-row"
                                    onclick="loadDetails('cm', '{{ $c->collection_manager }}')"
                                    style="cursor:pointer;">
                                    <td class="cm-name">{{ $c->collection_manager }}</td>
                                    <td class="fw-bold">{{ $c->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Product Table -->
        <div class="col-md-3 col-lg-3 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning fw-bold">
                    Product Wise Counts
                </div>

                <div class="p-2">
                    <input type="text" id="search-product" class="form-control" placeholder="Search Product...">
                </div>

                <div class="card-body p-0 scroll-box">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Name</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product as $p)
                                <tr class="product-row"
                                    onclick="loadDetails('product', '{{ $p->product }}')"
                                    style="cursor:pointer;">
                                    <td class="product-name">{{ $p->product }}</td>
                                    <td class="fw-bold">{{ $p->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Branch Table -->
        <div class="col-md-3 col-lg-3 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-info text-white fw-bold">
                    Branch Wise Counts
                </div>

                <div class="p-2">
                    <input type="text" id="search-branch" class="form-control" placeholder="Search Branch...">
                </div>

                <div class="card-body p-0 scroll-box">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Name</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($branch as $b)
                                <tr class="branch-row"
                                    onclick="loadDetails('branch', '{{ $b->branch }}')"
                                    style="cursor:pointer;">
                                    <td class="branch-name">{{ $b->branch }}</td>
                                    <td class="fw-bold">{{ $b->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</div>

<hr>

<h4 class="mt-4 fw-bold text-center">Details</h4>

<div id="agency-details-box" class="mt-3" style="display:none;">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white">
            Details for: <span id="selected-agency"></span>
        </div>

        <div class="card-body p-0">
            <div class="scroll-box" style="max-height: 400px;">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-dark sticky-top text-white">
                        <tr>
                            <th>State</th>
                            <th>Branch</th>
                            <th>Product</th>
                            <th>Collection Manager</th>
                        </tr>
                    </thead>
                    <tbody id="details-body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
/* SEARCH FILTER */
const searchConfigs = [
    { id: "search-agency", row: ".agency-row", col: ".agency-name" },
    { id: "search-cm", row: ".cm-row", col: ".cm-name" },
    { id: "search-product", row: ".product-row", col: ".product-name" },
    { id: "search-branch", row: ".branch-row", col: ".branch-name" }
];

searchConfigs.forEach(({ id, row, col }) => {
    document.getElementById(id).addEventListener("keyup", function() {
        let q = this.value.toLowerCase();
        document.querySelectorAll(row).forEach(r => {
            r.style.display = r.querySelector(col).textContent.toLowerCase().includes(q)
                ? ""
                : "none";
        });
    });
});


/* UNIVERSAL DETAIL LOADER */
function loadDetails(type, value) {

    document.getElementById("selected-agency").innerText = value;
    document.getElementById("agency-details-box").style.display = "block";

    let url = "";

    if (type === 'agency') url = `/allocation/by-agency/${value}`;
    if (type === 'cm') url = `/allocation/by-cm/${value}`;
    if (type === 'product') url = `/allocation/by-product/${value}`;
    if (type === 'branch') url = `/allocation/by-branch/${value}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            let rows = "";

            data.forEach(row => {
                rows += `
                    <tr>
                        <td>${row.state ?? ''}</td>
                        <td>${row.branch ?? ''}</td>
                        <td>${row.product ?? ''}</td>
                        <td>${row.collection_manager ?? ''}</td>
                    </tr>
                `;
            });

            document.getElementById("details-body").innerHTML = rows;
        });
}
</script>

@endsection
