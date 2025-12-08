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
<?php $__env->startSection('content'); ?>
    <div class="container mt-4">
        <h3 class="mb-4">
            Agent Wise Delay Deposition - Branch:
            <strong><?php echo e($branch); ?></strong>
        </h3>
        <div class="card shadow p-4">
            <div>
                <label class="fw-bold">Select Months (Min 1, Max 3)</label>

                <div class="month-tabs" id="monthTabs">
                    <?php $__currentLoopData = $cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($c)): ?>
                            <div class="month-tab" data-value="<?php echo e($c); ?>"><?php echo e($c); ?></div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div id="monthError" class="error-msg">You can select maximum 3 months.</div>
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

            let baseUrl = "<?php echo e(url('/')); ?>";

            let branch = "<?php echo e($branch); ?>";
            let product = document.querySelector('#product').value;
            let agency = document.querySelector('#agency').value;
            let payment = document.querySelector('#payment_mode').value;
            let delayBucket = document.querySelector('#delay_bucket').value;
            let location = document.querySelector('#location').value;
            let collection_manager = document.querySelector('#collection_manager').value;
            let time_bkt = document.querySelector('#time_bkt').value;
            let months = getSelectedMonths().join(',');


            // ❌ VALIDATION REMOVED COMPLETELY

            fetch(
                    `${baseUrl}/agent-wise-search?branch=${branch}&product=${product}&months=${months}&agency=${agency}&payment_mode=${payment}&delay_bucket=${delayBucket}&location=${location}&collection_manager=${collection_manager}&time_bkt=${time_bkt}`
                )
                .then(response => response.text())
                .then(html => {
                    document.getElementById('resultSection').innerHTML = html;
                });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/report/agent_wise_delay_deposition.blade.php ENDPATH**/ ?>