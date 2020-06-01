

<?php $__env->startSection('sh-title'); ?>
Audit Alert Box
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
Messages
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
					<strong class="card-title">Beat plan List</strong>
				</div>
				<div class="card-body">

					<!--begin: Datatable -->
					<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
									<th title="Field #1">#</th>
									<th title="Field #2">
										Name
									</th>
									<th title="Field #2">
										Visit List
									</th>
									<th title="Field #7">
										Actions
									</th>
							</tr>
						</thead>
						<tbody>
							<?php $__currentLoopData = $beatplan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td><?php echo e($loop->iteration); ?></td>
								<td>
									<?php echo e($row->name); ?>

								</td>
								<td nowrap>
									<ul stylw="padding-left:10px">
										<?php $__currentLoopData = $row->sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<li><?php echo e(($value->branch!=null)?$value->branch->name:''); ?>(<?php echo e($value->date); ?>)</li>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</ul>
								</td>
								<td nowrap>
									<div style="display: flex;">
										<?php echo e(Form::open([ 'method'  => 'delete', 'route' => [ 'beat_plan.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])); ?>

										<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
											<i class="fa fa-trash"></i>
										</button>
									</form>
									<a href="<?php echo e(url('beat_plan/'.Crypt::encrypt($row->id).'/edit')); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
										<i class="fa fa-edit"></i>
									</a>

								</div>

							</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

					</tbody>
				</table>
    			<!--end: Datatable -->
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/beat_plan/list.blade.php ENDPATH**/ ?>