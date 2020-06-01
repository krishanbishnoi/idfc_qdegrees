

<?php $__env->startSection('sh-title'); ?>
Audited
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
Call
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-lg-12" style="margin-top:10x">
        <a href="<?php echo e(url('action/list')); ?>" class="btn btn-label-success btn-bold" style="float: right;" >Go Back</a>
	</div>
</div>
<div class="animated fadeIn">
	<div class="row">
    <div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<strong class="card-title"> Summary</strong>
				</div>
				<div class="card-body">
                    <div class="row">
                        <div class="col-md-3  form-group">
                            <label>Sheet Name</label>
                            <span class="form-control"><?php echo e($action->audit->qmsheet->name ?? ''); ?></span>
                        </div>
                        <div class="col-md-3  form-group">
                            <label>Auditor Name</label>
                            <span class="form-control"><?php echo e($action->audit->qa_qtl_detail->name ?? ''); ?></span>
                        </div>
                        <div class="col-md-3  form-group">
                            <label>Auditor Email</label>
                            <span class="form-control"><?php echo e($action->audit->qa_qtl_detail->email ?? ''); ?></span>
                        </div>
                        <div class="col-md-3  form-group">
                            <label>Collection Manager Name</label>
                            <span class="form-control"><?php echo e($collection->user->name ?? ''); ?></span>
                        </div>
                        <div class="col-md-3  form-group">
                            <label>Collection Manager Email</label>
                            <span class="form-control"><?php echo e($collection->user->email ?? ''); ?></span>
                        </div>
                        <div class="col-md-3  form-group">
                            <label><?php echo e(ucfirst($action->audit->qmsheet->type)); ?> name</label>
                            <span class="form-control"><?php echo e($name ?? ''); ?></span>
                        </div>
                        <div class="col-md-3  form-group">
                            <label>Product</label>
                            <span class="form-control"><?php echo e($collection->product->name ?? ''); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<strong class="card-title"> Action Plan</strong>
				</div>
				<div class="card-body">
                            
                            <div class="row">
                            <?php $__currentLoopData = $action->sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                                <div class="col-md-6 form-group">
                                    <label><?php echo e($item->question); ?></label>
                                    <textarea name="answer<?php echo e($item->id); ?>" Required readonly class="form-control" placeholder="Enter Answer" value="<?php echo e($item->actionPlanAnswer->answer ?? ''); ?>"><?php echo e($item->actionPlanAnswer->answer ?? ''); ?></textarea>
                                </div>
                                <!-- <?php if($item->artifact!=null): ?>
                                <div class="col-md-6 form-group">
                                    <label><a href="<?php echo e(url('download-action-artifact/'.Crypt::encrypt($item->id))); ?>" target="_blank">Artifact</a></label>
                                    <input type="file" class="form-control" style="margin-bottom:5px" name="artifactAnswer<?php echo e($item->id); ?>"/>
                                    <textarea name="answer<?php echo e($item->id); ?>" Required class="form-control" placeholder="Enter Answer"></textarea>
                                </div>
                                <?php endif; ?> -->
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                                    
                        <!-- <input type="submit" value="submit">
                    </form> -->
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
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>


<script>

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.action', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/Action/view.blade.php ENDPATH**/ ?>