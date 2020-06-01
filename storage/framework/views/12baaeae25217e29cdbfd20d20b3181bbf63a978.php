<?php $__env->startSection('title', '| Users'); ?>

<?php $__env->startSection('sh-detail'); ?>
Edit
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <div class="card">

    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="card-header">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
            Details
          </h3>
        </div>
      </div>

      <!--begin::Form-->
      
        <?php echo Form::model($data,
                  array(
                  'method' => 'PATCH',
                    'url' =>'user/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator")
                  ); ?>



        <div class="card-body card-block">

          <div class="form-group row">
            <div class="col-lg-6">
            <label>Name*</label>
            <input type="text" name="name" class="form-control" required value="<?php echo e($data->name); ?>">
          </div>
          <div class="col-lg-6">
            <label>Primary Email (as username)*</label>
            <input type="text" readonly name="email" class="form-control" required value="<?php echo e($data->email); ?>">
          </div>
         </div>
          <div class="form-group row">
            <div class="col-lg-6">
            <label>Mobile No.*</label>
            <input type="text" name="mobile" class="form-control" required value="<?php echo e($data->mobile); ?>">
          </div>
          <div class="col-lg-6">
            <label>Roles*</label>
            <?php echo e(Form::select('role[]',$roles,$rdata,['class'=>'form-control m-select2','id'=>'kt_select2_1','multiple'=>'multiple','required'=>'required'])); ?>

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

      <!--end::Form-->
    </div>

    <!--end::Portlet-->
  </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/acl/users/edit.blade.php ENDPATH**/ ?>