

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
					<strong class="card-title">Sheet List</strong>
				</div>
				<div class="card-body">

					<!--begin: Datatable -->
					<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
									<th title="Field #1">#</th>
									<th title="Field #2">
										 Sheet Name
									</th>
									<th title="Field #2">
										User Name
									</th>
									<th title="Field #7">
										Actions
									</th>
							</tr>
						</thead>
						<tbody>
							<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td><?php echo e($loop->iteration); ?></td>
								<td>
									<?php echo e(($row->sheet!=null)?$row->sheet->name:''); ?>

                                </td>
                                <td>
									<?php echo e(($row->user!=null)?$row->user->name:''); ?>

								</td>
								<td nowrap>
									<div style="display: flex;">
										<?php echo e(Form::open([ 'method'  => 'delete', 'route' => [ 'allocation.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])); ?>

										<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
											<i class="fa fa-trash"></i>
										</button>
									</form>
									

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
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/allocation/list.blade.php ENDPATH**/ ?>