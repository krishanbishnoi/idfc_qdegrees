

<?php $__env->startSection('title', '| Agency'); ?>

<?php $__env->startSection('sh-detail'); ?>
    Create New
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <strong>Create Agency</strong> form
        </div>
        <div class="card-body card-block">
            <?php echo Form::open(
                     array(
                       'route' => 'agency.store',
                       'class' => 'form-horizontal',
                       'role'=>'form',
                       'data-toggle'=>"validator")
                     ); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">Agency Name</label>
                        <input type="text" id="text-input" name="name" placeholder="Agency Name" class="form-control" value="<?php echo e(old('name')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">Branch Name</label>
                        <select name="branch_name" data-placeholder="Choose a Branch..." class="standardSelect form-control" tabindex="1">
                            <option value="" label="Branch Name"></option>
                            <?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class=" form-group">
                        <label for="agency_id" class=" form-control-label">Agency id</label>
                        <input type="text" id="agency_id" name="agency_id" placeholder="Agency id" class="form-control" value="<?php echo e(old('agency_id')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class=" form-group">
                        <label for="agency_manager" class=" form-control-label">Agency Manger</label>
                        <!-- <input type="text" id="agency_manager" name="agency_manager" placeholder="Agency Manger" class="form-control" value="<?php echo e(old('agency_manager')); ?>"> -->
                        <select name="agency_manager" data-placeholder="Choose a Agency Manager..." class="standardSelect form-control" tabindex="3">
                            <option value="" label="Agency Manger"></option>
                            <?php $__currentLoopData = $user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class=" form-group">
                        <label for="location" class=" form-control-label">Location</label>
                        <input type="text" id="location" name="location" placeholder="Location" class="form-control" value="<?php echo e(old('location')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class=" form-group">
                        <label for="address" class=" form-control-label">Address</label>
                        <input type="text" id="address" name="address" placeholder="Address" class="form-control" value="<?php echo e(old('address')); ?>">
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
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>

        $(function () {
            $(".sizes").select2();
            
        });
        jQuery(document).ready(function() {
        jQuery(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "Oops, nothing found!",
            width: "100%"
        });
    })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/agency/create.blade.php ENDPATH**/ ?>