

<?php $__env->startSection('title', '| Users'); ?>

<?php $__env->startSection('sh-detail'); ?>
    Create New
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <strong>Create User</strong>
        </div>
        <div class="card-body card-block">
            <?php echo Form::open(
                     array(
                       'route' => 'user.store',
                       'class' => 'form-horizontal',
                       'role'=>'form',
                       'data-toggle'=>"validator")
                     ); ?>


            <div class="row form-group">
                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Name</label></div>
                <div class="col-12 col-md-9"><input type="text" id="text-input" name="name" placeholder="User Name" class="form-control" value="<?php echo e(old('name')); ?>">
                </div>
            </div>
            <div class="row form-group">
                <div class="col col-md-3"><label for="email-input" class=" form-control-label">Email</label></div>
                <div class="col-12 col-md-9"><input type="email" id="email-input" value="<?php echo e(old('email')); ?>" name="email"
                                                    placeholder="Enter Email" class="form-control">
                </div>
            </div>

            <div class="row form-group">
                <div class="col col-md-3"><label for="email-input" class=" form-control-label">Mobile</label></div>
                <div class="col-12 col-md-9"><input type="text" id="email-input" name="mobile"
                                                    placeholder="Enter Mobile" class="form-control" value="<?php echo e(old('mobile')); ?>">
                </div>
            </div>
            <div class="row form-group">
                <div class="col col-md-3"><label for="check-input" class=" form-control-label">Password Type</label></div>
                <div class="col-12 col-md-9">
                    <input type="radio" id="check-input" name="auto" value="automatic"><label for="check-input">Automatic</label>
                    <input type="radio" id="check-input2" name="auto" value="manual" checked><label for="check-input2">Manual</label>
                </div>
            </div>
            <div id="passwordDiv">
                <div class="row form-group">
                    <div class="col col-md-3"><label for="email-input" class=" form-control-label">Password</label></div>
                    <div class="col-12 col-md-9"><input type="password" id="email-input" name="password"
                                                        placeholder="Enter password" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col col-md-3"><label for="email-input" class=" form-control-label">Confirm Password</label></div>
                    <div class="col-12 col-md-9"><input type="password" id="email-input" name="password_confirmation"
                                                        placeholder="Confirm Password" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row form-group">
                <div class="col col-md-3"><label for="multiple-select" class=" form-control-label">Multiple
                        select</label></div>
                <div class="col col-md-9">
                    <select name="role[]" id="multiple-select" multiple="" class="form-control">
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v->id); ?>"><?php echo e($v->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Create
                </button>
                <button type="reset" class="btn btn-danger btn-sm">
                    <i class="fa fa-ban"></i> Reset
                </button>
            </div>
            </form>
        </div>

    </div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>

        jQuery(function () {
            jQuery(".sizes").select2();
        });
        jQuery('#check-input2').on('click',function(){
            jQuery('#passwordDiv').show();
        })
        jQuery('#check-input').on('click',function(){
            jQuery('#passwordDiv').hide();
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/acl/users/create.blade.php ENDPATH**/ ?>