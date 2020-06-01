

<?php $__env->startSection('sh-title'); ?>
<?php echo e($qm_sheet_data->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
Update Parameter
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-toolbar'); ?>
							<div class="kt-subheader__toolbar">
								<div class="kt-subheader__wrapper">

								<a href="/qm_sheet/<?php echo e(Crypt::encrypt($qm_sheet_data->id)); ?>/list_parameter" class="btn btn-label-success btn-bold">
									List All Parameter
								</a>
								
								</div>
							</div> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
	<div class="col-md-12">
		<div class="text-right" style="margin-bottom:10px;">
			<div class="kt-subheader__wrapper">
				<a href="<?php echo e(url('/qm_sheet/'.Crypt::encrypt($qm_sheet_data->id).'/list_parameter')); ?>" class="btn btn-success btn-bold">
					List All Parameter
				</a>
			</div>
		</div>
	  <!--begin::Portlet-->
	  <div class="card">
		  <div class="card-header">
			  <strong>Edit Parameter</strong>
		  </div>
		  <div class="card-body card-block">
  

      <!--begin::Form-->
      
        <?php echo Form::open(
                  array(
                    'route' => 'update_parameter', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ); ?>

        
        <input type="hidden" name="qm_sheet_id" value="<?php echo e($qm_sheet_data->id); ?>">
        <input type="hidden" name="parameter_id" value="<?php echo e($param_data->id); ?>">
        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Parameter*</label>
            <input type="text" name="parameter" class="form-control col-lg-6" required value="<?php echo e($param_data->parameter); ?>">
          </div>
          

          <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
          <h6>Add Sub - Parameters</h6>
          <br/>
          <div id="kt_repeater_qm_sheet">
												<div class="form-group  row" id="kt_repeater_qm_sheet">
													<div data-repeater-list="subs" class="col-lg-12">

														<?php $__currentLoopData = $param_data->qm_sheet_sub_parameter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kksp=>$vvsp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<div data-repeater-item class="form-group align-items-center">
															<input type="hidden" name="sp_pm_id" value="<?php echo e($vvsp->id); ?>">
															<div class="row">
															<div class="col-md-4">
																<div class="kt-form__group--inline">
																	<div class="kt-form__control">
																		<input type="text" class="form-control" placeholder="Sub - Parameter name" name="sub_parameter" value="<?php echo e($vvsp->sub_parameter); ?>">
																	</div>
																</div>
																<div class="d-md-none kt-margin-b-10"></div>
															</div>

															<div class="col-md-2">
																<div class="kt-form__group--inline">
																	<div class="kt-form__control">
																		<input type="number" class="form-control" placeholder="Weightage" name="weight" value="<?php echo e($vvsp->weight); ?>">
																	</div>
																</div>
																<div class="d-md-none kt-margin-b-10"></div>
															</div>

															<div class="col-md-4">
																<div class="kt-form__group--inline">
																	<div class="kt-form__control">
																		<textarea class="form-control" placeholder="Sub - Parameter details" name="details"><?php echo e($vvsp->details); ?></textarea>
																	</div>
																</div>
																<div class="d-md-none kt-margin-b-10"></div>
															</div>
															
															<div class="col-md-2">
																<div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill" @click="onQmSheetSubParameterDelete(<?php echo e($vvsp->id); ?>)">
																	<span>
																		<i class="la la-trash-o"></i>
																		<span>Delete</span>
																	</span>
																</div>
															</div>
															</div>

															<br/>
															
															<div class="row">
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_pass" value="1" <?php echo e(($vvsp->pass)?'checked':''); ?>> Pass
																		<span></span>
																	</label>
																</div>
																
															

															
															
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_fail" value="1" <?php echo e(($vvsp->fail)?'checked':''); ?>> Fail
																		<span></span>
																	</label>
																</div>
																
															

															
															
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_critical" value="1" <?php echo e(($vvsp->critical)?'checked':''); ?>> Critical
																		<span></span>
																	</label>
																</div>
																
																
															

															
															
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_na" value="1" <?php echo e(($vvsp->na)?'checked':''); ?>> N/A
																		<span></span>
																	</label>
																</div>
																
																
															

															
															
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_pwd" value="1" <?php echo e(($vvsp->pwd)?'checked':''); ?>> PWD
																		<span></span>
																	</label>
																</div>
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_per" value="1" <?php echo e(($vvsp->per)?'checked':''); ?>> Percentage
																		<span></span>
																	</label>
																</div>
																
															</div>

															

												          <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
														</div>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

														<div data-repeater-item class="form-group align-items-center">
															<input type="hidden" name="sp_pm_id">
															<div class="row">
															<div class="col-md-4">
																<div class="kt-form__group--inline">
																	<div class="kt-form__control">
																		<input type="text" class="form-control" placeholder="Sub - Parameter name" name="sub_parameter" >
																	</div>
																</div>
																<div class="d-md-none kt-margin-b-10"></div>
															</div>

															<div class="col-md-2">
																<div class="kt-form__group--inline">
																	<div class="kt-form__control">
																		<input type="number" class="form-control" placeholder="Weightage" name="weight" >
																	</div>
																</div>
																<div class="d-md-none kt-margin-b-10"></div>
															</div>

															<div class="col-md-4">
																<div class="kt-form__group--inline">
																	<div class="kt-form__control">
																		<textarea class="form-control" placeholder="Sub - Parameter details" name="details"></textarea>
																	</div>
																</div>
																<div class="d-md-none kt-margin-b-10"></div>
															</div>
															
															<div class="col-md-2">
																<div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill">
																	<span>
																		<i class="la la-trash-o"></i>
																		<span>Delete</span>
																	</span>
																</div>
															</div>
															</div>

															<br/>
															
															<div class="row">
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_pass" value="1"> Pass
																		<span></span>
																	</label>
																</div>
																

															
															
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_fail" value="1"> Fail
																		<span></span>
																	</label>
																</div>
																
															
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_critical" value="1"> Critical
																		<span></span>
																	</label>
																</div>
																
															

															
															
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_na" value="1"> N/A
																		<span></span>
																	</label>
																</div>
																
																
															

															
															
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_pwd" value="1"> PWD
																		<span></span>
																	</label>
																</div>
																<div class="col-md-2">
																	<label class="kt-checkbox kt-checkbox--state-success">
																		<input type="checkbox" name="s_per" value="1"> Percentage
																		<span></span>
																	</label>
																</div>
																
															</div>

															

												          <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
														</div>

													</div>

												</div>
												<div class="form-group row">
													<div class="col-lg-4">
														<div data-repeater-create="" class="btn btn-primary btn-sm">
															<span>
																<i class="fa fa-plus"></i>
																<span>Add</span>
															</span>
														</div>
													</div>
												</div>
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
<?php echo $__env->make('shared.form_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/qm_sheet/edit_parameter.blade.php ENDPATH**/ ?>