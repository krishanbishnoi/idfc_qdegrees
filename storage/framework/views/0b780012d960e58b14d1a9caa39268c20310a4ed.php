<?php $__env->startSection('title', '| Audit Cycle'); ?>



<!-- <?php $__env->startSection('sh-detail'); ?>

Users

<?php $__env->stopSection(); ?> -->



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

					<strong class="card-title">Audit Cycle List</strong>

					<a class="btn btn-primary btn-sm float-right" href="<?php echo e(url('create-audit-cycle')); ?>">Create Audit Cycle</a>

				</div>

				<div class="card-body">

					<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">

						<thead>

							<tr>

									<th scope="col">#</th>

									<th scope="col">

										Name

									</th>

									<th scope="col">

										Created At

									</th>

									
							</tr>

						</thead>

						<tbody>

							<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

							<tr scope="row">

								<td><?php echo e($loop->iteration); ?></td>

								<td>

									<?php echo e($row->name); ?>


								</td>

								<td>

								<?php echo e($row->created_at->format("Y-m-d h:i:s")); ?>


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

<?php $__env->startSection('css'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>

	jQuery(document).on('ready',function(){

		jQuery('#kt_table_1').DataTable();

	})

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/audit/audit_cycle_list.blade.php ENDPATH**/ ?>