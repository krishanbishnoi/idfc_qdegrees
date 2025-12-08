<?php $__env->startSection('title', '| View'); ?>

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
					<strong class="card-title">Product view</strong>
					<a class="btn btn-primary btn-sm float-right" style="margin-right: 5px" href="<?php echo e(route('excelDownloadProduct')); ?>" target="_blank">Export Product</a>
				</div>
				<div class="card-body">
					<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
									<th scope="col">#</th>
									<th scope="col">
										Branch Name
                                    </th>
									<th scope="col">
										Product Name
                                    </th>
                                    <?php $__currentLoopData = $productUserType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<th scope="col">
										<?php echo e(str_replace('_'," ",$item)); ?>
									</th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									
									<th scope="col">
										Actions
									</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; ?>
							<?php $__currentLoopData = $productUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php $__currentLoopData = $val; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<tr scope="row">
										<td><?php echo e($i++); ?></td>
										<td>
											<?php echo e($k ?? ''); ?>
										</td>
										<td>
											<?php echo e($key ?? ''); ?>
										</td>
										<td>
											<?php echo e($row['Area_Collection_Manager']->user->name ?? ''); ?>
										</td>
										
										<td>
											<?php echo e($row['Regional_Collection_Manager']->user->name ?? ''); ?>
										</td>
										
										<td>
											<?php echo e($row['National_Collection_Manager']->user->name ?? ''); ?>
										</td>
										
										<td>
											<?php echo e($row['Group_Product_Head']->user->name ?? ''); ?>
										</td>
										<td>
											<?php echo e($row['Zonal_Collection_Manager']->user->name ?? ''); ?>
										</td>
										<td>
											<?php echo e($row['Head_of_the_Collections']->user->name ?? ''); ?>
										</td>
										<td nowrap>
											 
											<a href="<?php echo e(url('product/hierarchy/'.$k.'/'.$key.'/edit')); ?>" class="btn btn-xs btn-info" title="Edit">
												<i class="fa fa-edit"></i>
											</a>
											

											 
										</td>
									</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script>
	jQuery(document).on('ready',function(){
		jQuery('#kt_table_1').DataTable();
		// {
		// 	dom: 'Bfrtip',
        // buttons: [
        //     'excelHtml5',
        // ]
    	// }
	})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/product/view.blade.php ENDPATH**/ ?>