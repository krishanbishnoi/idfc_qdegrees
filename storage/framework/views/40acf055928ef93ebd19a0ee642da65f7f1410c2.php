<?php $__env->startSection('title', '| Agency'); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">

        <div class="col-lg-12" style="margin-top:10x">

        </div>

    </div>

    <div class="animated fadeIn">

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-header">

                        <strong class="card-title">Allocation Dump List</strong>

                    </div>

                    <div class="card-body">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>
                        <form method="GET" action="<?php echo e(route('allocationdump.index')); ?>" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search Loan No / Branch / Agency Name / Product..."
                                        value="<?php echo e(request('search')); ?>">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                        <table class="table table-striped- table-bordered table-hover table-checkable">
                            <thead>
                                <tr>
                                    <th>Loan Number</th>
                                    <th>Product</th>

                                    <th>Product Flag</th>
                                    <th>Branch</th>
                                    <th>Agency Name</th>

                                    <th>State</th>

                                    <th>Edit</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($r->loan_number); ?></td>
                                        <td><?php echo e($r->product); ?></td>

                                        <td><?php echo e($r->productflag_1); ?></td>
                                        <td><?php echo e($r->branch); ?></td>
                                        <td><?php echo e($r->agency_name); ?></td>

                                        <td><?php echo e($r->state); ?></td>

                                        <td>
                                            <a href="<?php echo e(route('allocationdump.edit', $r->id)); ?>"
                                                class="btn btn-sm btn-primary">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

                        <?php echo e($records->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/allocation_dump/index.blade.php ENDPATH**/ ?>