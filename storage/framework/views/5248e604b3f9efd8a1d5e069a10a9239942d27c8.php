<?php $__env->startSection('sh-title'); ?>
QM - Sheet
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
Create New
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-lg-12">
  <div class="card">
      <div class="card-header">
          <strong>Create Sheet</strong>
      </div>
      <div class="card-body card-block">

      <!--begin::Form-->
      
        <?php echo Form::open(
                  array(
                    'route' => 'qm_sheet.store', 
                    'class' => 'form-horizontal',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ); ?>

        <div class="row">
          <div class="col-md-3 form-group">
            <label>Lob Name*</label>
            <select name="lob" class="form-control">
              <option value="">Choose Lob Name</option>
              <option value="collection">Collection</option>
              <option value="commercial_vehicle">Commercial Vehicle</option>
              <option value="rural">Rural</option>
              <option value="alliance">Alliance</option>
            </select>
          </div>
          <div class="col-md-3 form-group">
            <label>Sheet Name*</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-md-3 form-group">
            <label>Sheet Type*</label>
            <select name="type" class="form-control">
              <option value="">Choose Sheet Type</option>
              <option value="branch">Branch</option>
              <option value="agency">Agency</option>
	      <option value="branch_repo">Branch Repo</option>
              <option value="agency_repo">Agency Repo</option>
              <option value="repo_yard">Repo and Yard</option>
            </select>
          </div>
          
          <!-- <div class="col-md-3 form-group">
            <label>Sheet Banner*</label>
            <input type="file" name="banner" class="form-control">
          </div> -->
          <div class="col-md-3 form-group">
            <label>Details</label>
            <textarea class="form-control" name="details"></textarea>
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

      <!--end::Form-->
    </div>

    <!--end::Portlet-->
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/qm_sheet/create.blade.php ENDPATH**/ ?>