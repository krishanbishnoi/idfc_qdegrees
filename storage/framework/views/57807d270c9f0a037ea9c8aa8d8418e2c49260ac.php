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

                        <strong class="card-title">Holidays List</strong>

                    </div>

                    <div class="card-body">

                        <table class="table table-striped- table-bordered table-hover table-checkable">

                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Date</th>
                                    <th>Holiday</th>
                                    <th>Working Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__currentLoopData = $holidays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($h->day_name); ?></td>
                                        <td><?php echo e($h->date); ?></td>
                                        <td><?php echo e($h->holiday_name); ?></td>
                                        <td><?php echo e($h->working_date); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('holidays.edit', $h->id)); ?>"
                                                class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/holidays/index.blade.php ENDPATH**/ ?>