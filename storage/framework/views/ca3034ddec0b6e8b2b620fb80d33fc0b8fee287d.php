

<?php $__env->startSection('sh-title'); ?>
<?php echo e($qm_sheet_data->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
Parameters
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-toolbar'); ?>
							<div class="kt-subheader__toolbar">
								<div class="kt-subheader__wrapper">

								<a href="<?php echo e(url('qm_sheet/'.Crypt::encrypt($qm_sheet_data->id).'/add_parameter')); ?>" class="btn btn-label-success btn-bold">
									Create New Parameter
								</a>
								
								</div>
							</div> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
	<div class="col-lg-12" style="margin-top:10x">
	</div>
</div>
<div class="animated fadeIn">
	<div class="row">
		<div class="col-lg-12">
			<div class="text-right" style="margin-bottom:10px;">
				<div class="kt-subheader__wrapper">

				<a href="<?php echo e(url('qm_sheet/'.Crypt::encrypt($qm_sheet_data->id).'/add_parameter')); ?>" class="btn btn-success btn-bold">
					Create New Parameter
				</a>
				
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<strong class="card-title">Sheet parameter List</strong>
				</div>
				<div class="card-body">		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #2">
							Parameter
						</th>
						<th title="Field #2">
							Sub Parameter - Weightage
						</th>
						<th title="Field #2">
							Weightage
						</th>
						<th title="Field #2">
							Type
						</th>
						<th title="Field #7">
							Action
						</th>
				</tr>
			</thead>
			<tbody>
				<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><?php echo e($loop->iteration); ?></td>
											<td>
												<?php echo e($row->parameter); ?>

											</td>
											<td>
												<ol>
												<?php $total_weightage=0; ?>
												<?php $__currentLoopData = $row->qm_sheet_sub_parameter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ksp=>$vsp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<li><?php echo e($vsp->sub_parameter); ?> - <strong><?php echo e($vsp->weight); ?></strong></li>
												<?php $total_weightage += $vsp->weight;?>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												</ul>
											</td>
											<td>
												<?php echo e($total_weightage); ?>

											</td>
											<td>
												<?php echo e(($row->is_non_scoring)?"Non-Scoring":"Scoring"); ?>

											</td>
											
					<td nowrap>
                        <div style="display: flex;">
                        	<?php echo e(Form::open([ 'method'  => 'delete', 'route' => [ 'delete_parameter', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])); ?>

                        	<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        		<i class="fa fa-trash"></i>
                        	</button>
                        </form>
                        <a href="<?php echo e(url('parameter/'.Crypt::encrypt($row->id).'/edit')); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/qm_sheet/list_parameter.blade.php ENDPATH**/ ?>