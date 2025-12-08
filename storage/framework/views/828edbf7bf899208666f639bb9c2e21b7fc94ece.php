<?php $__env->startSection('content'); ?>
    <div class="container mt-4">

        <h3 class="text-center mb-4">
            Reports for Branch: <strong><?php echo e($branch); ?></strong>
        </h3>

        <div class="row">

            <div class="col-md-6 mb-3">
                <a href="<?php echo e(route('monthly.analysis', $branch)); ?>" target="_blank" class="btn btn-primary w-100 p-3"
                    style="font-size:18px;">
                    Monthly Analysis
                </a>

            </div>

            <div class="col-md-6 mb-3">
                <a href="<?php echo e(route('agency.wise.delay.deposition', $branch)); ?>" target="_blank" class="btn btn-success w-100 p-3"
                    style="font-size:18px;">
                    Agency wise Delay Deposition
                </a>

            </div>

            <div class="col-md-6 mb-3">
                <a href="<?php echo e(route('agent.wise.delay.deposition', $branch)); ?>" target="_blank" class="btn btn-warning w-100 p-3"
                    style="font-size:18px;">
                    Agent Wise Delay Deposition
                </a>

            </div>

            <div class="col-md-6 mb-3">
                <a href="#" class="btn btn-info w-100 p-3" style="font-size:18px;">
                    PAN Status
                </a>
            </div>

        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/report/home.blade.php ENDPATH**/ ?>