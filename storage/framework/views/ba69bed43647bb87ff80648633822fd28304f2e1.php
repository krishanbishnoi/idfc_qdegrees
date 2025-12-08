<?php $__env->startSection('title', '| Users'); ?>



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

					<strong class="card-title">User List</strong>

					<a class="btn btn-primary btn-sm float-right" href="<?php echo e(route('bulkDeactivate')); ?>">De-Activate user(Bulk)</a>
					<a class="btn btn-primary btn-sm float-right" style="margin-right: 5px" href="<?php echo e(route('userUpload')); ?>">Import Users (Create bulk user)</a>

					<a class="btn btn-primary btn-sm float-right" style="margin-right: 5px" href="<?php echo e(route('excelDownloadUser')); ?>" target="_blank">Export Users</a>

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

										Role

									</th>

									<th scope="col">

										Email

									</th>

									<th scope="col">

										Phone

									</th><th scope="col">

										Status

									</th>



									<th scope="col">

										Actions

									</th>

									<th scope="col">
										Disable User
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

									<?php $__currentLoopData = $row->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rrs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

									<?php echo e($rrs->name.","); ?>


									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								</td>

								<td><?php echo e($row->email); ?></td>

								<td><?php echo e($row->mobile); ?></td>

								

								<td>
									<?php if($row->active_status == 0): ?>
										Activated
									<?php else: ?> 
										De-Activated
									<?php endif; ?>
								
								</td>

								<td nowrap>

									<!-- <div style="display: flex;">

										<?php echo e(Form::open([ 'method'  => 'delete', 'route' => [ 'user.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])); ?>


										<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">

											<i class="la la-trash"></i>

										</button>

									</form> -->

									<a href="<?php echo e(url('user/'.Crypt::encrypt($row->id).'/edit')); ?>" class="btn btn-xs btn-info" title="View">

										<i class="fa fa-edit"></i>

									</a>



									<!-- </div> -->

								</td>

								<td nowrap>
								<a  class="btn btn-xs btn-info" onclick="block_user('<?php echo e(Crypt::encrypt($row->id)); ?>')" title="View">
								<i class="fa fa-ban"></i>
								</a> 
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

<script type="text/javascript">
   function block_user(id) {
		if (confirm("Are you sure you want to block?")) {

			var link  = '/user/'+id+'/disable'
			location.href = link;
		}
	}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/acl/users/list.blade.php ENDPATH**/ ?>