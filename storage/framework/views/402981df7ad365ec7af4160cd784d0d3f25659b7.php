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
<style>
    .report-table-container {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    table.report-table {
        width: 100%;
        border-collapse: separate !important;
        border-spacing: 0;
        font-size: 14px;
    }

    table.report-table thead th {
        padding: 12px 10px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    /* Month header stripes */
    .month-head-1 {
        background: #1e3a8a;
        color: #fff;
    }

    .month-head-2 {
        background: #9a3412;
        color: #fff;
    }

    .month-head-3 {
        background: #4b5563;
        color: #fff;
    }

    /* Sub header */
    .sub-head {
        background: #f1f5f9;
        font-weight: 600;
        font-size: 13px;
    }

    /* Body styling */
    table.report-table tbody tr {
        transition: .2s;
    }

    table.report-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    table.report-table tbody tr:hover {
        background: #eef6ff;
    }

    .text-money {
        color: #059669;
        font-weight: 600;
    }

    .text-count {
        color: #2563eb;
        font-weight: 600;
    }

    td,
    th {
        vertical-align: middle !important;
        text-align: center !important;
        padding: 10px !important;
    }
</style>




<?php $__env->startSection('content'); ?>
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">
                Monthly Analysis - Branch:
                <strong><?php echo e($branch); ?></strong>
            </h3>

            <a href="<?php echo e(route('select.branch')); ?>" class="btn btn-outline-primary">
                ← Back
            </a>
        </div>
        <div class="card shadow p-4">
            <div class="row">

                <div class="col-md-6">
                    <label class="fw-bold">From Month</label>
                    <select id="fromMonth" class="form-control">
                        <option value="">-- Select Start Month --</option>

                        <?php $__currentLoopData = $cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($c)): ?>
                                <option value="<?php echo e($c); ?>"><?php echo e($c); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">To Month</label>
                    <select id="toMonth" class="form-control" disabled>
                        <option value="">-- Select End Month --</option>

                        <?php $__currentLoopData = $cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($c)): ?>
                                <option value="<?php echo e($c); ?>"><?php echo e($c); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

            </div>

            <div id="monthRangeError" class="text-danger mt-2" style="display:none;">
                Invalid range: End month cannot be before Start month.
            </div>

            <div class="row">
                
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Product</strong></label>
                        <select id="product" class="form-control" required>
                            <option value="">-- Select Product --</option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->Product_1); ?>"><?php echo e($p->Product_1); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Agency</strong></label>
                        <select id="agency" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Payment Mode</strong></label>
                        <select id="payment_mode" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group mt-3">
                        <label><strong>Select Delay Deposit Bucket</strong></label>
                        <select id="delay_bucket" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>

                
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
                        <label><strong>Select Pan Required</strong></label>
                        <select id="pan_required" class="form-control" disabled>
                            <option value="">-- Select Product First --</option>
                        </select>
                    </div>
                </div>

            </div>
            
            <div class="col-md-4">
                <div class="form-group mt-3">
                    <label><strong>&nbsp;</strong></label>
                    <button id="searchBtn" class="btn btn-primary w-100" type="button">
                        Search
                    </button>
                </div>
            </div>

        </div>

        
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

    <script>
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
    </script>
    
    <script>
        document.getElementById('product').addEventListener('change', function() {

            let product = this.value;
            let branch = "<?php echo e($branch); ?>";

            let agencyDropdown = document.getElementById('agency');
            let paymentDropdown = document.getElementById('payment_mode');

            agencyDropdown.innerHTML = '<option>Loading...</option>';
            paymentDropdown.innerHTML = '<option>Loading...</option>';

            agencyDropdown.disabled = true;
            paymentDropdown.disabled = true;

            let baseUrl = "<?php echo e(url('/')); ?>";

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

                fetch(`${baseUrl}/get-pan-required/${branch}/${product}`)
                    .then(response => response.json())
                    .then(data => {

                        let delayDropdown = document.getElementById('pan_required');
                        delayDropdown.innerHTML = '<option value="">All</option>';

                        data.forEach(function(item) {
                            delayDropdown.innerHTML +=
                                `<option value="${item.pan_required}">${item.pan_required}</option>`;
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
        document.getElementById('pan_required').addEventListener('change', function() {
            this.setAttribute("data-value", this.value);
        });



        // --- SEARCH BUTTON CLICK ---
        document.getElementById('searchBtn').addEventListener('click', function() {

            let baseUrl = "<?php echo e(url('/')); ?>";

            let branch = "<?php echo e($branch); ?>";
            let product = document.querySelector('#product').value;
            let agency = document.querySelector('#agency').value;
            let payment = document.querySelector('#payment_mode').value;
            let delayBucket = document.querySelector('#delay_bucket').value;
            let location = document.querySelector('#location').value;
            let panRequired = document.querySelector('#pan_required').value;
            let months = getSelectedMonths().join(',');


            // ❌ VALIDATION REMOVED COMPLETELY

            fetch(
                    `${baseUrl}/monthly-search?branch=${branch}&product=${product}&months=${months}&agency=${agency}&payment_mode=${payment}&delay_bucket=${delayBucket}&location=${location}&pan_required=${panRequired}`
                )
                .then(response => response.text())
                .then(html => {
                    document.getElementById('resultSection').innerHTML = html;
                });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/report/monthly_analysis.blade.php ENDPATH**/ ?>