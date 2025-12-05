<?php $__env->startSection('content'); ?>
<div class="container mt-4">

    <h3 class="mb-3">Select Branch</h3>

    <form action="<?php echo e(route('show.branch.data')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div class="form-group mb-3">
            <label for="branch">Choose Branch:</label>
            <select name="branch" class="form-control" required>
                <option value="">-- Select Branch --</option>

                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->BranchName); ?>"><?php echo e($b->BranchName); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <button class="btn btn-primary">Submit</button>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/report/select_branch.blade.php ENDPATH**/ ?>