

<?php $__env->startSection('sh-title'); ?>
QM - Allocation
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
Allocation New
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-lg-12">
  <div class="card">
      <div class="card-header">
          <strong>Sheet Allocation</strong>
      </div>
      <div class="card-body card-block">

      <!--begin::Form-->
      
        <?php echo Form::open(
                  array(
                    'route' => 'allocation.store', 
                    'class' => 'form-horizontal',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ); ?>

        <div class="row">

          <div class="col-md-6 form-group">
            <label>Sheet*</label>
            <select class="form-control" name="sheet_id" id="sheet_id" required>
                <option selected>--Choose sheet--</option>
               <?php $__currentLoopData = $sheet; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <option value="<?php echo e($data->id); ?>"><?php echo e($data->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="col-md-6 form-group">
            <label>User*</label>
            <select class="form-control" name="user_id" id="user_id" required>
                <option selected>--Choose user--</option>
               <?php $__currentLoopData = $user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <option value="<?php echo e($data->id); ?>"><?php echo e($data->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
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

      <!--end::Form-->
    </div>

    <!--end::Portlet-->
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/allocation/create.blade.php ENDPATH**/ ?>