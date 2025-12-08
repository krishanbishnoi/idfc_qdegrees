<?php $__env->startSection('content'); ?>
    <style>
        .report-card {
            transition: 0.3s;
            cursor: pointer;
            border-radius: 14px !important;
            color: #fff !important;
        }

        .report-card * {
            color: #fff !important;
            text-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4);
        }

        .report-card:hover {
            transform: translateY(-6px);
            box-shadow: 0px 12px 28px rgba(0, 0, 0, 0.20);
        }

        .report-icon {
            font-size: 42px;
            opacity: 0.9;
        }

        .card-monthly {
            background: linear-gradient(135deg, #5a84ff, #3456d1);
        }

        .card-agency {
            background: linear-gradient(135deg, #25d597, #0f8b5f);
        }

        .card-agent {
            background: linear-gradient(135deg, #ffcf4d, #d9a200);
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            border-radius: 8px;
            background: #e2e8f0;
            color: #1e293b;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .back-btn:hover {
            background: #cbd5e1;
            color: #000;
        }
    </style>
    <div class="row">
        <div class="col-lg-12" style="margin-top:10x">
        </div>
    </div>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">DAC Audit Report</strong>
                    </div>
                    <div class="card-body">
                        <label for="text-input" class=" form-control-label">Choose Branch</label>
                        <form action="<?php echo e(route('select.branch')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="form-group mb-3">
                                <select name="branch" class="form-control" required>
                                    <option value="">-- Select Branch --</option>

                                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($b->BranchName); ?>"
                                            <?php if(!empty($branch) && $branch == $b->BranchName): ?> selected <?php endif; ?>>
                                            <?php echo e($b->BranchName); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <button class="btn btn-primary">Submit</button>
                        </form>
                        <div class="mt-5" id="branch_report_types"
                            <?php if(empty($branch)): ?> style="display:none" <?php endif; ?>>
                            <div class="mb-3">
                                <h2 class="fw-bold" style="font-size: 28px; color:#1e293b;">
                                    📊 Dac Audit Reports for Branch:
                                    <span style="color:#3b82f6"><?php echo e($branch); ?></span>
                                </h2>
                                <hr>
                                <div
                                    style="width: 80px; height: 4px; background: #3b82f6; margin: 10px auto 0; border-radius: 8px;">
                                </div>
                            </div>

                            <div class="row g-4 justify-content-center">

                                <!-- Monthly Analysis -->
                                <div class="col-md-4 h-100">
                                    <a href="<?php echo e(route('monthly.analysis', ['branch' => $branch])); ?>" target="_blank"
                                        class="text-decoration-none">
                                        <div class="card report-card border-0 shadow-lg"
                                            style="background: linear-gradient(135deg, #4f80ff, #3554d1); padding: 32px;">

                                            <div class="text-center">
                                                <i class="bi bi-calendar-check report-icon mb-3"></i>
                                                <h4 class="fw-bold mb-1">Monthly Analysis</h4>
                                                <p style="opacity: .9; font-size:14px;">Review month-wise performance
                                                    insights</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Agency Wise Delay -->
                                <div class="col-md-4 h-100">
                                    <a href="<?php echo e(route('agency.wise.delay.deposition', ['branch' => $branch])); ?>"
                                        target="_blank" class="text-decoration-none">
                                        <div class="card report-card border-0 shadow-lg"
                                            style="background: linear-gradient(135deg, #20d6a2, #0f8b5f); padding: 32px;">

                                            <div class="text-center">
                                                <i class="bi bi-building report-icon mb-3"></i>
                                                <h4 class="fw-bold mb-1">Agency Delay Deposition</h4>
                                                <p style="opacity: .9; font-size:14px;">Monitor delays across various
                                                    agencies</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Agent Wise Delay -->
                                <div class="col-md-4 h-100">
                                    <a href="<?php echo e(route('agent.wise.delay.deposition', ['branch' => $branch])); ?>"
                                        target="_blank" class="text-decoration-none">
                                        <div class="card report-card border-0 shadow-lg"
                                            style="background: linear-gradient(135deg, #ffc94d, #c89a00); padding: 32px;">

                                            <div class="text-center">
                                                <i class="bi bi-person-badge report-icon mb-3"></i>
                                                <h4 class="fw-bold mb-1">Agent Delay Deposition</h4>
                                                <p style="opacity: .9; font-size:14px;">Analyze delays across individual
                                                    agents</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/report/select_branch.blade.php ENDPATH**/ ?>