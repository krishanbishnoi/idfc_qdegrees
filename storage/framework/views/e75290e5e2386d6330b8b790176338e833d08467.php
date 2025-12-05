<?php $__env->startSection('content'); ?>
    <div class="container">
        <h2 class="mb-3">Allocation Dac List</h2>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <form method="GET" action="<?php echo e(route('dacdump.index')); ?>" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" 
                   placeholder="Search Receipt No / Branch / Location / Agent..."
                   value="<?php echo e(request('search')); ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Search</button>
        </div>
    </div>
</form>


        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ReceiptNo</th>
                    <th>Branch</th>

                    <th>Location</th>
                    <th>Agency</th>
                    <th>Product</th>

                    <th>Agent</th>

                    <th>Amount</th>
                    <th>Edit</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($r->ReceiptNo); ?></td>
                        <td><?php echo e($r->BranchName); ?></td>

                        <td><?php echo e($r->Location); ?></td>
                        <td><?php echo e($r->AgencyName); ?></td>
                        <td><?php echo e($r->Product_1); ?></td>

                        <td><?php echo e($r->AgentName); ?></td>
                        <td><?php echo e($r->TotalReceiptAmount); ?></td>

                        <td>
                            <a href="<?php echo e(route('allocationdac.edit', $r->id)); ?>" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <?php echo e($records->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/allocation_dump/allocationdac.blade.php ENDPATH**/ ?>