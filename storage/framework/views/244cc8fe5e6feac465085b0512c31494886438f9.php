

<?php $__env->startSection('sh-title'); ?>
QM - Sheet
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
Edit
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-lg-12">
  <div class="card">
      <div class="card-header">
          <strong>Edit Sheet</strong>
      </div>
      <div class="card-body card-block">

      <!--begin::Form-->
      
        <?php echo Form::model($data,
                  array(
                  'method' => 'PATCH',
                    'url' =>'qm_sheet/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator")
                  ); ?>

        

        <div class="row">
          <div class="col-md-3 form-group">
            <label>Lob Name*</label>
            <select name="lob" class="form-control">
              <option value="">Choose Lob Name</option>
              <option value="collection" <?php echo e(($data->type=='collection')?'selected':''); ?>>Collection</option>
              <option value="commercial_vehicle" <?php echo e(($data->type=='commercial_vehicle')?'selected':''); ?>>Commercial Vehicle</option>
              <option value="rural" <?php echo e(($data->type=='rural')?'selected':''); ?>>Rural</option>
              <option value="alliance" <?php echo e(($data->type=='alliance')?'selected':''); ?>>Alliance</option>
            </select>
          </div>
          <div class="col-md-3 form-group">
            <label>Sheet Name*</label>
            <input type="text" name="name" class="form-control" required value="<?php echo e($data->name); ?>">
          </div>
          <div class="col-md-3 form-group">
            <label>Sheet Type*</label>
            <select name="type" class="form-control">
              <option value="">Choose Sheet Type</option>
              <option value="branch" <?php echo e(($data->type=='branch')?'selected':''); ?>>Branch</option>
              <option value="agency" <?php echo e(($data->type=='agency')?'selected':''); ?>>Agency</option>
              <option value="branch_repo" <?php echo e(($data->type=='branch_repo')?'selected':''); ?>>Branch Repo</option>
              <option value="agency_repo" <?php echo e(($data->type=='agency_repo')?'selected':''); ?>>Agency Repo</option>
              <option value="repo_yard" <?php echo e(($data->type=='repo_yard')?'selected':''); ?>>Repo and Yard</option>
            </select>
          </div>
          
          <!-- <div class="col-md-3 form-group">
            <label>Sheet Banner*</label>
            <input type="file" name="banner" class="form-control">
          </div> -->
          <div class="col-md-3 form-group">
            <label>Details</label>
            <textarea class="form-control" name="details"><?php echo e($data->details); ?></textarea>
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
<?php echo $__env->make('shared.form_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/qm_sheet/edit.blade.php ENDPATH**/ ?>