

<?php $__env->startSection('sh-title'); ?>
Audited
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
Call
<?php $__env->stopSection(); ?>

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
					<strong class="card-title">Action Plan List</strong>
				</div>
				<div class="card-body">
					
		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #7">
							Audit Date
						</th>
						<th title="Field #2">
							lob
						</th>
						<th title="Field #2">
							Audit for
						</th>
						<th title="Field #2">
							Branch Name
						</th>
						<th title="Field #2">
							Product Name
						</th>
						<th title="Field #7">
							Score With FATAL
						</th>
						<th title="Field #7">
							Score Without FATAL
						</th>
						<th>
							Qc status
						</th>
						<th>
							Checked By
						</th>
						<th title="Field #7">
							Action
						</th>
				</tr>
			</thead>
			<tbody>
				<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php
					$name='';
					switch ($row->audit->qmsheet->type) {
						case 'agency':
							$name=$row->audit->agency->name ?? '';
							break;
						case 'branch':
							$name=$row->audit->branch->name ?? '';
							break;
						case 'repo_yard':
							$name=$row->audit->yard->name ?? '';
							break;
						
					}
					$status='';
					switch ($row->status) {
						case '1':
							$status='Pass With Edit';
							break;
						case '2':
							$status='Pass';
							break;
						case '3':
							$status='Faild';
							break;
						
					}
					
				?>
				<tr>
					<td><?php echo e($loop->iteration); ?></td>
					<td><?php echo e($row->audit->created_at); ?></td>
					<td><?php echo e($row->audit->qmsheet->lob ?? ''); ?></td>
					<td><?php echo e($row->audit->qmsheet->type ?? ''); ?></td>
					<td><?php echo e($name); ?></td>
					<td><?php echo e($row->audit->product->name ?? ''); ?></td>
					
					<td><?php echo e(($row->audit->is_critical==1)?0:$row->audit->overall_score.""); ?></td>
					<td><?php echo e($row->audit->overall_score); ?> </td>
					<td><?php echo e($status); ?> </td>
					<td><?php echo e($row->user->name ?? ''); ?> </td>
					<td nowrap>
						<?php if($row->status<3): ?>
						<a href="<?php echo e(url('action/'.Crypt::encrypt($row->audit_id).'/alert')); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Rise Action Alert">
								<i class="fa fa-bell"></i>
						</a>
						<?php endif; ?>

                    </div>

                </td>
			</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php if(count($data)==0): ?>
				<tr>
					<td  colspan="9" class="text-center">No Record found</td>
				</tr>
			<?php endif; ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<?php echo $__env->make('shared.table_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<?php echo $__env->make('shared.table_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<script>
	jQuery(document).on('ready',function(){
		jQuery('input[name=start_date]').datepicker();
		jQuery('input[name=end_date]').datepicker();
	})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/Action/list.blade.php ENDPATH**/ ?>