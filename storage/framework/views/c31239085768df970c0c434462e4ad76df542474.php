

<?php $__env->startSection('title', '| Yards'); ?>

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
					<strong class="card-title">Yard List</strong>
				</div>
				<div class="card-body">
					<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
									<th scope="col">#</th>
									<th scope="col">
										Yard Name
									</th>
									<th scope="col">
										Branch Name
									</th>
									<th scope="col">
										Agency Name
									</th>
									<th scope="col">
										Yard Id
                                    </th>
                                    <th scope="col">
										Agency Manager
									</th>
                                    <th scope="col">
										Location
									</th>
                                    <th scope="col">
										Address
									</th>
                                    
									<th scope="col">
										Actions
									</th>
							</tr>
						</thead>
						<tbody>
							<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr scope="row">
								<td><?php echo e($k); ?></td>
								<td>
									<?php echo e($row->name); ?>

								</td>
								<td>
									<?php echo e(isset($row->branch)?$row->branch->name:''); ?>

                                </td>
                                <td>
									<?php echo e(isset($row->agency)?$row->agency->name:''); ?>

								</td>
								<td><?php echo e($row->yard_id); ?></td>
								<td><?php echo e(isset($row->user)?$row->user->name:''); ?></td>
								<td><?php echo e($row->location); ?></td>
								<td><?php echo e($row->addresss); ?></td>

								<td nowrap>
									<!-- <div style="display: flex;">
										<?php echo e(Form::open([ 'method'  => 'delete', 'route' => [ 'user.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])); ?>

										<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
											<i class="la la-trash"></i>
										</button>
									</form> -->
									<a href="<?php echo e(url('yard/'.Crypt::encrypt($row->id).'/edit')); ?>" class="btn btn-xs btn-info" title="View">
										<i class="fa fa-edit"></i>
                                    </a>
                                    <a href="<?php echo e(url('yard/'.Crypt::encrypt($row->id))); ?>" class="btn btn-xs btn-danger" title="View">
										<i class="fa fa-trash"></i>
									</a>

									<!-- </div> -->
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/yard/list.blade.php ENDPATH**/ ?>