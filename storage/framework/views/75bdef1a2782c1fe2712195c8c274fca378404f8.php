<?php if(count($results) == 0): ?>
    <p class="text-danger text-center">No data found.</p>
<?php else: ?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Agency Name</th>
            <th>Agent ID</th>
            <th>Count</th>
            <th>Total Receipt Amount</th>
        </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($loop->iteration); ?></td>
            <td><?php echo e($row->AgencyName); ?></td>
            <td><?php echo e($row->AgentId); ?></td>
            <td><?php echo e($row->total_count); ?></td>
            <td><?php echo e(number_format($row->total_receipt, 2)); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/report/partials/monthly_table.blade.php ENDPATH**/ ?>