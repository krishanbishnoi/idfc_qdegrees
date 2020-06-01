

<?php $__env->startSection('title', '| Upload'); ?>

<?php $__env->startSection('sh-detail'); ?>
    Create New
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
    <div class="card ">
        <div class="card-header">
            <strong>Users Upload</strong>
            <a class="btn btn-primary btn-sm float-right" href="<?php echo e(route('downloadUser')); ?>">Download Sample</a>
        </div>
        <div class="card-body card-block">
            <?php echo Form::open(
                     array(
                       'route' => 'userImport',
                       'class' => 'form-horizontal',
                       'role'=>'form',
                       'data-toggle'=>"validator",
                       'files' => true)
                     ); ?>

            <div class="row">
                <div class="col-sm-6">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">Role</label>
                        <select name="role[]" multiple="" class="form-control">
                            
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class=" form-group">
                        <label for="user_excel" class=" form-control-label">User Excel</label>
                        <input type="file" id="user_excel" name="user_excel" class="form-control-file">
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
        </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Submit
                </button>
                <button type="reset" class="btn btn-danger btn-sm">
                    <i class="fa fa-ban"></i> Reset
                </button>
            </div>
            </form>
        
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
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/acl/users/upload.blade.php ENDPATH**/ ?>