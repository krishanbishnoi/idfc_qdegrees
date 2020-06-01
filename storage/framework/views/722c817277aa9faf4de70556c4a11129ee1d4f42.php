

<?php $__env->startSection('title', '| Bulk Upload'); ?>

<?php $__env->startSection('sh-detail'); ?>
    Create New
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
    <div class="card ">
        <div class="card-header">
            <strong>Bulk Upload</strong> 
            <a class="btn btn-primary btn-sm float-right" href="<?php echo e(route('downloadBranch')); ?>">Download Excel Format</a>
            <div style="font-size:13px">Click to download excel sheet and re-upload sheet again with required data</div>
        </div>
        <div class="card-body card-block">
            <?php echo Form::open(
                     array(
                       'route' => 'bulkUpload.store',
                       'class' => 'form-horizontal',
                       'role'=>'form',
                       'data-toggle'=>"validator",
                       'files' => true)
                     ); ?>

            
            <div class="row">
                <div class="col-sm-3">
                    <div class=" form-group">
                        <label for="branch" class=" form-control-label">Upload Sheet</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class=" form-group">
                        <input type="file" id="branch" name="file" class="form-control-file">
                        <?php if(is_array(session()->get('error'))): ?>
                            <?php $__currentLoopData = session()->get('error'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(is_array($error)): ?>
                                    <?php $__currentLoopData = $error; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="text-danger"><?php echo e($err); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <span class="text-danger"><?php echo e(session()->get('error')); ?></span>
                        <?php endif; ?>
                                    
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if($errors->any()): ?>
                    <div class="text-danger">
                        
                        Errors
                    </div>
                    <div>
                        <ol class="ml-5">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="text-danger"><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    </div>    
                    <?php endif; ?>
                </div>
            </div>
            
<div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Save Sheet
                </button>
                <button type="reset" class="btn btn-danger btn-sm">
                    <i class="fa fa-ban"></i> Reset
                </button>
            </div>
            </form>
        </div>

    </div>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery('.datepicker').datepicker({
                format: "mm-yyyy",
                viewMode: "months", 
                minViewMode: "months"
            });
    })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/bulkUpload/branch.blade.php ENDPATH**/ ?>