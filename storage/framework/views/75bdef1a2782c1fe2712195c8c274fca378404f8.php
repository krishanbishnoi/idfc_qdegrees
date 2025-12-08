<?php if(count($results) == 0): ?>

<div class="alert alert-danger text-center fw-bold">
    <i class="bi bi-exclamation-circle"></i> No data found.
</div>

<?php else: ?>

<div class="report-table-container mt-3">
    <table class="table report-table mb-0">

        <thead>
            <tr>
                <th>#</th>
                <th>Agency Name</th>
                <th>Agent ID</th>

                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php 
                        $colorClass = 'month-head-' . (($i % 3) + 1); 
                    ?>
                    <th colspan="2" class="<?php echo e($colorClass); ?>"><?php echo e($m); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>

            <tr class="sub-head">
                <th></th>
                <th></th>
                <th></th>

                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th>Count</th>
                    <th>Receipt</th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>

        <tbody>
            <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="fw-bold"><?php echo e($loop->iteration); ?></td>
                <td class="text-start"><?php echo e($row->AgencyName); ?></td>
                <td><?php echo e($row->AgentId); ?></td>

                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $safe = preg_replace('/[^A-Za-z0-9_]/', '_', $m); ?>

                    <td class="text-count">
                        <?php echo e($row->{'count_'.$safe} ?? 0); ?>

                    </td>

                    <td class="text-money">
                        ₹ <?php echo e(number_format($row->{'receipt_'.$safe} ?? 0, 2)); ?>

                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>

    </table>
</div>

<?php endif; ?>
<?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/report/partials/monthly_table.blade.php ENDPATH**/ ?>