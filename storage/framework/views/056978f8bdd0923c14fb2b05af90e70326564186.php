<?php $__env->startSection('sh-title'); ?>
QM - Sheet
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
All Client
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
										Name
									</th>
									<th title="Field #2">
										Lob
									</th>
									
									<th title="Field #2">
										Type
									</th>
									<th title="Field #2">
										Total Parameters
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
															<?php echo e($row->name); ?>

														</td>
														<td>
															<?php echo e($row->lob); ?>

														</td>
														

														<td>
															<?php echo e(ucfirst($row->type)); ?>

														</td>
														<td><?php echo e($row->parameter->count()); ?></td>
								<td nowrap>
									<div style="display: flex;">
										<?php echo e(Form::open([ 'method'  => 'delete', 'route' => [ 'qm_sheet.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])); ?>

										<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete">
											<i class="fa fa-trash"></i>
										</button>
									</form>
									<a href="<?php echo e(url('qm_sheet/'.Crypt::encrypt($row->id).'/edit')); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
										<i class="fa fa-edit"></i>
									</a>
									<a href="<?php echo e(url('qm_sheet/'.Crypt::encrypt($row->id).'/parameter')); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Manage Parameters">
										<i class="fa fa-list"></i>
									</a>
									<a href="<?php echo e(url('audit_sheet/'.Crypt::encrypt($row->id))); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Audit Sheet">
										<i class="fa fa-eye"></i>
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script>
function delete_confirm() {
	var x = confirm("Are you sure you want to delete?");
	if (x) {
		return true;
	}
	else {
		event.preventDefault();
		return false;
	}
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/qm_sheet/list.blade.php ENDPATH**/ ?>