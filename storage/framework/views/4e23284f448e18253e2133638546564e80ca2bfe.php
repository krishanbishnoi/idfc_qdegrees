<?php $__env->startSection('css'); ?>



<style>

.sp-row .row {

    margin-bottom: 15px;

}





.sp-row .row {

    margin-bottom: 15px;

}



.flex-container {

    display: flex;

    align-items: center;

}



.flex-container {

    display: flex;

    align-items: center;

}

.kt-font-bolder {

    font-weight: 600 !important;

}

#seprator {

    margin: 2.5rem 0 0 0;

}

#seprator {

    margin: 2.5rem 0 0 0;

}

.kt-separator.kt-separator--space-lg {

    margin: 2.5rem 0;

}

.kt-separator.kt-separator--border-dashed {

    border-bottom: 1px dashed #ebedf2;

}

.kt-separator {

    height: 0;

    margin: 20px 0;

    border-bottom: 1px solid #ebedf2;

}

.kt-font-primary {

    color: #5867dd !important;

}



.kt-font-bolder {

    font-weight: 600 !important;

}

.kt-font-bold {

    font-weight: 500 !important;

}

.centerparameter{

	display: flex;

    justify-content: center;

    align-items: center

}

</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>

Audit 

<?php $__env->stopSection(); ?>



<?php $__env->startSection('sh-detail'); ?>

Messages

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>



<div class="row">

	<div class="col-lg-12" style="margin-top:10x">

	</div>

</div>

<div class="animated fadeIn">

	<div class="row">

		<div class="col-lg-12">

			<div class="card"> 

				<div class="card-header" style="background-image: linear-gradient(to right, rgb(132, 94, 194), rgb(144, 109, 198), rgb(156, 125, 201), rgb(168, 140, 205), rgb(179, 156, 208));color:#fff">

					<strong class="card-title"><?php echo e(($data->lob=='commercial_vehicle')?'Commercial Vehicle':ucfirst($data->lob)); ?> | <?php echo e(ucfirst(str_replace('_',' ',$data->type))); ?></strong>

				</div>

				<div class="card-body">

					<div class="row">

						<?php if($data->type=='branch'): ?>

							<div class="col-md-3 form-group">

								<label>Branch*</label>

								<select name="branch" class="form-control branch">

								<option value="">Choose Branch</option>

								<?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  

									<option value="<?php echo e($item->id); ?>" <?php echo e(($item->id==$result->branch_id)?'selected':''); ?>><?php echo e($item->name); ?></option>

								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								</select>

							</div>

						<?php elseif($data->type=='agency'): ?>

							<div class="col-md-3 form-group">

								<label>Agency*</label>

								<select name="agency" class="form-control agency">

								<option value="">Choose Agency</option>

								<?php $__currentLoopData = $agency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  

									<option value="<?php echo e($item->id); ?>" <?php echo e(($item->id==$result->agency_id)?'selected':''); ?>><?php echo e($item->name); ?></option>

								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								</select>

							</div>

						<?php elseif($data->type=='repo_yard'): ?>

							<div class="col-md-3 form-group">

								<label>Yard*</label>

								<select name="yard" class="form-control yard">

								<option value="">Choose Yard</option>

								<?php $__currentLoopData = $yard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  

									<option value="<?php echo e($item->id); ?>" <?php echo e(($item->id==$result->yard_id)?'selected':''); ?>><?php echo e($item->name); ?></option>

								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								</select>

							</div>

							<?php elseif($data->type=='branch_repo'): ?>

							<div class="col-md-3 form-group">

								<label>Branch Repo*</label>

								<select name="branch_repo" class="form-control branch_repo">

								<option value="">Choose Branch Repo</option>

								<?php $__currentLoopData = $branchRepo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  

									<option value="<?php echo e($item->id); ?>" <?php echo e(($item->id==$result->branch_repo_id)?'selected':''); ?>><?php echo e($item->name); ?></option>

								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								</select>

							</div>

							<?php elseif($data->type=='agency_repo'): ?>

							<div class="col-md-3 form-group">

								<label>Agency Repo*</label>

								<select name="agency_repo" class="form-control agency_repo">

								<option value="">Choose Yard</option>

								<?php $__currentLoopData = $agencyRepo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  

									<option value="<?php echo e($item->id); ?>" <?php echo e(($item->id==$result->agency_repo_id)?'selected':''); ?>><?php echo e($item->name); ?></option>

								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								</select>

							</div>

						<?php endif; ?>
						
						<div class="col-md-3 form-group">
								<label>Audit Cycle*</label>
								<select name="audit_cycle" class="form-control audit_cycle js-example-basic-single" id="audit_cycle" required="true">
								<?php $__currentLoopData = $cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
									<option value="<?php echo e($item->id); ?>" <?php if($result->audit_cycle_id == $item->id) { echo "selected"; } ?>><?php echo e($item->name); ?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
						</div>
						
						<div class="col-md-3 form-group">
								<label>Audit Date*</label>
								<input type="date" name="audit_date" class="form-control audit_date js-example-basic-single" id="audit_date" required="true" <?php if(!is_null($result->audit_date_by_aud)) { ?> value="<?=$result->audit_date_by_aud; ?>" <?php } ?>>
						</div>

						<div class="col-md-3 form-group" id="product" style="display:none;">

							<label>Product*</label>

							<select name="product" class="form-control product" id="productSelect">

							<option value="">Choose Product</option>

							</select>

						</div>

					</div>

					<div class="row" id="data">

					</div>

				</div>

			</div>

			<div class="card">

				<div class="card-header">

					<strong class="card-title">Audit</strong>

				</div>

				<div class="card-body">

					

					<div class="row">

						<div class="col-md-2 kt-font-bolder">

							Parameter

						</div>

						<div class="col-md-10 kt-font-bolder">

							<div class="row">

								<div class="col-md-2 kt-font-bolder">Sub Parameter</div> 

								<div class="col-md-2 kt-font-bolder">Observation</div> 

								<div class="col-md-2 kt-font-bolder">Scored</div> 

								<div class="col-md-2 kt-font-bolder">Remarks</div>

								<div class="col-md-2 kt-font-bolder">Action</div>

							</div>

						</div>

					</div>

					<div id="seprator" class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>

					<?php

						$total=0;

					?>
					<div id="accordion">
					<?php $__currentLoopData = $data->parameter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<div class="card">

							<div class="card-header" id="<?php echo 'heading'.$item->id ?>" data-toggle="collapse" data-target="<?php echo '#collapse'.$item->id ?>"  aria-controls="<?php echo '#collapse'.$item->id ?>" aria-expanded="false">
						      <h5 class="mb-0">
						        <button class="btn btn-link" data-toggle="collapse" data-target="<?php echo '#collapse'.$item->id ?>"  aria-controls="<?php echo '#collapse'.$item->id ?>" aria-expanded="false">
						          <?php echo e($item->parameter); ?>

						        </button>
						        <?php if(isset($resultSubPar[$item->qm_sheet_sub_parameter[0]->id])): ?>
						        	<button class="btn btn-link" style="float:right;">Filled</button>
						        <?php else: ?>
						        <button class="btn btn-link" style="float:right;">Not Filled</button>
						        <?php endif; ?>
						        	
						      </h5>
							</div>
							<div class="row flex-container collapse" style="border-bottom: 1px solid rgb(204, 204, 204); padding: 20px 0px; height: 100%;" id="<?php echo 'collapse'.$item->id ?>"  aria-labelledby="<?php echo 'heading'.$item->id ?>" data-parent="#accordion">

								<div class="col-md-2 kt-font-bolder kt-font-primary flex-item centerparameter">

									<?php echo e($item->parameter); ?>


								</div>

								<div class="col-md-10 sp-row">

									<?php $__currentLoopData = $item->qm_sheet_sub_parameter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

									<div class="row flex-container mb-2">

										<div class="col-md-2 kt-font-bold">

											<?php echo e($value->sub_parameter); ?> <i title="sdfdf" class="la la-info-circle kt-font-warning sp-details-top"></i>

										</div>

										<div class="col-md-2">

										<select class="form-control 0bervation" id="obs<?php echo e($value->id); ?>" data-id="<?php echo e($value->id); ?>" data-parameterId="<?php echo e($item->id); ?>" data-point="<?php echo e($value->weight); ?>">

												<option value="">Choose type</option>

												<?php if(isset($resultSubPar[$value->id]) && $resultSubPar[$value->id]->option_selected==null): ?>

												<?php if($value->pass==1): ?><option value="<?php echo e($value->weight); ?>" <?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->selected_option==$value->weight))?'selected':''); ?>>Pass</option><?php endif; ?>

												<?php if($value->fail==1): ?><option value="0"  <?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_critical==0 && $resultSubPar[$value->id]->selected_option==0))?'selected':''); ?>>Fail</option><?php endif; ?>

											<?php else: ?>

												<?php if($value->pass==1): ?><option value="<?php echo e($value->weight); ?>" <?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->option_selected=='Pass'))?'selected':''); ?>>Pass</option><?php endif; ?>

												<?php if($value->fail==1): ?><option value="0"  <?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_critical==0 && $resultSubPar[$value->id]->option_selected=='Fail'))?'selected':''); ?>>Fail</option><?php endif; ?>

											<?php endif; ?>

												<?php if($value->critical==1): ?><option value="Critical"  <?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_critical==1))?'selected':''); ?>>Critical</option><?php endif; ?>

												<?php if($value->na==1): ?><option value="N/A"  <?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_critical==0 && $resultSubPar[$value->id]->selected_option=='N/A'))?'selected':''); ?>>N/A</option><?php endif; ?>

												<?php if($value->pwd==1): ?><option value="<?php echo e(round(($value->weight)/2,2)); ?>"  <?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->selected_option==round(($value->weight)/2,2)))?'selected':''); ?>>PWD</option><?php endif; ?>

												<?php if($value->per==1): ?><option value="<?php echo e(round(($value->weight))); ?>" data-type="rating" <?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_percentage==1))?'selected':''); ?>>Percentage</option><?php endif; ?>

											</select>

											<span style="display:none" id="org<?php echo e($value->id); ?>"><?php echo e($value->weight); ?></span>

										</div>

										<div class="col-md-2">

											<select class="form-control ratingSelect" name="ratingSelect" id="ratingSelect<?php echo e($value->id); ?>"  style="<?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_percentage!=1))?'display:none':'display:block'); ?>" data-id="<?php echo e($value->id); ?>" data-parameterId="<?php echo e($item->id); ?>">

											<option>select percentage</option>

											<?php for($counting=0;$counting<=100;$counting=$counting+5): ?>

											<option value="<?php echo e($counting); ?>" <?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->selected_per==$counting))?'selected':''); ?>><?php echo e($counting); ?>%</option>

											<?php endfor; ?>

										</select>

										<?php if(isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_percentage!=1)): ?>

										<input type="text" id="<?php echo e($value->id); ?>" readonly="readonly" class="form-control" value="<?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_critical==1))?'Critical':($resultSubPar[$value->id]->score ?? '')); ?>"  style="<?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_percentage==1))?'display:none':'display:block'); ?>">

										<?php else: ?>

										<input type="text" id="<?php echo e($value->id); ?>" readonly="readonly" class="form-control" value="rating"  style="<?php echo e((isset($resultSubPar[$value->id]) && ($resultSubPar[$value->id]->is_percentage==1))?'display:none':'display:block'); ?>">

										<?php endif; ?>

										</div>

										<div class="col-md-2">

										

										</div>

										<div class="col-md-2">

											<button class="btn btn-danger btn-sm alertModal" data-parameterid="<?php echo e($item->id); ?>" data-id="<?php echo e($value->id); ?>">Alert</button>

											<button class="btn btn-info btn-sm artifactModal mr-1" data-parameterid="<?php echo e($item->id); ?>" data-id="<?php echo e($value->id); ?>">Artifact</button>
											
											<input type="checkbox" id="ackalert<?php echo e($value->id); ?>" data-id="<?php echo e($value->id); ?>" data-parameterId="<?php echo e($item->id); ?>" />

										</div>

									</div>

									<div class="col-md-12 row">

										<!-- <div class="col-md-2">

											Remark

										</div> -->

										<div class="col-md-10">

										<textarea class="form-control" id="remark<?php echo e($value->id); ?>" value="<?php echo e($resultSubPar[$value->id]->remark ?? ''); ?>"><?php echo e($resultSubPar[$value->id]->remark ?? ''); ?></textarea>

										</div>

									</div>

									<div class="col-md-12 row">

										<div class="col-md-10 preview<?php echo e($value->id); ?>">

										<?php $__currentLoopData = $value->artifact; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

											<?php if(in_array($art->id,$artifactIds)): ?>

												<div class="img-wrap art<?php echo e($art->id); ?>" style="position: relative;display: inline-block;font-size: 0;">

													<span class="close">&times;</span>

													<img src="<?php echo e(URL::asset('storage/app/'.$art->file)); ?>" style="width:100px;height:100px;" data-id="<?php echo e($art->id); ?>">

												</div>

											<?php endif; ?>

										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

										</div>

									</div>

									<div id="seprator" class="kt-separator kt-separator--border-dashed "></div>

									<?php

										$total=$total+$value->weight;

									?>

									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								<span style="display:none" id="total<?php echo e($item->id); ?>"><?php echo e($total); ?></span>	

								</div>

							</div>
					</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>

					

					<div>

						

				</div>

				

			</div>

		</div>



		<div class="card">

			<div class="card-header">

				<strong class="card-title">Result</strong>

			</div>

			<div class="card-body">

				

			<div class="row" style="border-bottom: 1px solid rgb(204, 204, 204);">

			<!-- <div class="col-lg-4 kt-font-bolder">&nbsp;</div> -->

			<div class="col-lg-3 kt-font-bolder">Parameter</div>

			<div class="col-lg-3 kt-font-bolder">Scorable</div>

			<div class="col-lg-3 kt-font-bolder">Scored</div>

			<div class="col-lg-3 kt-font-bolder">Scores%</div>

		</div>

		

		<?php $__currentLoopData = $data->parameter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

			<!-- <div class="row" style="border-bottom: 1px solid rgb(204, 204, 204); padding: 20px 0px; height: 100%;">

				<div class="col-lg-2 kt-font-bold kt-font-primary"><?php echo e($item->parameter); ?></div>

			<div class="col-lg-2" id="scroable<?php echo e($item->id); ?>">0</div>

				<div class="col-lg-2 kt-font-danger" id="wfatal<?php echo e($item->id); ?>">0</div>

				<div class="col-lg-2" id="wnfatal<?php echo e($item->id); ?>">0</div>

				<div class="col-lg-2 kt-font-danger" id="wfatalper<?php echo e($item->id); ?>">0 %</div>

				<div class="col-lg-2" id="wnfatalper<?php echo e($item->id); ?>">0 %</div>

			</div> -->

			<div class="row" style="border-bottom: 1px solid rgb(204, 204, 204); padding: 20px 0px; height: 100%;">

				<div class="col-lg-3 kt-font-bold kt-font-primary"><?php echo e($item->parameter); ?></div>

				<div class="col-lg-3" id="scroable<?php echo e($item->id); ?>">0</div>

				<div class="col-lg-3 kt-font-danger" id="wfatal<?php echo e($item->id); ?>">0</div>

				<div class="col-lg-3" id="wfatalper<?php echo e($item->id); ?>">0</div>

			</div>

		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



		<!-- <div class="row" style="padding: 20px 0px; height: 100%;">

			<div class="col-lg-2 kt-font-bold kt-font-success">Over All</div>

			<div class="col-lg-2 kt-font-bold" id="scroable">0</div>

			<div class="col-lg-2 kt-font-bold kt-font-danger" id="wfatal">0</div>

			<div class="col-lg-2 kt-font-bold" id="wnfatal">0</div>

			<div class="col-lg-2 kt-font-bold kt-font-danger" id="wfatalper">0%</div>

			<div class="col-lg-2 kt-font-bold"  id="wnfatalper">0%</div>

		</div> -->

		<div class="row" style="padding: 20px 0px; height: 100%;">

			<div class="col-lg-3 kt-font-bold kt-font-success">Over All</div>

			<div class="col-lg-3 kt-font-bold" id="scroable">0</div>

			<div class="col-lg-3 kt-font-bold kt-font-danger" id="wfatal">0</div>

			<div class="col-lg-3 kt-font-bold" id="wfatalper">0</div>

		</div>

	</div>

</div>

	</div>



	</div>

	<button type="submit" class="btn btn-primary btn-sm savebutton">

		<i class="fa fa-dot-circle-o"></i> Save

	</button>

	<button type="submit" class="btn btn-primary btn-sm submit">

		<i class="fa fa-dot-circle-o"></i> Submit

	</button>

	<button type="reset" class="btn btn-danger btn-sm">

		<i class="fa fa-ban"></i> Reset

	</button>

</div>





<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

	<div class="modal-dialog" role="document">

	  <div class="modal-content">

		<div class="modal-header">

		  <h5 class="modal-title" id="exampleModalLabel">Alert</h5>

		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">

			<span aria-hidden="true">&times;</span>

		  </button>

		</div>

		<div class="modal-body">

			<div class="row">

				<div class="col-md-12 form-group">

					<label>files</label>

					<input type="file" id="file" name="file" class="form-control-file">

				</div>

				<div class="col-md-12 form-group">

					<label>Messages*</label>

					<input type="hidden" name="alertParameterId" id="alertParameterId" value=""/>

					<input type="hidden" name="alertSubParameterId" id="alertSubParameterId" value=""/>

					<textarea name="msg" id="msg" class="form-control" placeholder="Enter message" required></textarea>

				</div>

			</div>

		</div>

		<div class="modal-footer">

		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

		  <button type="button" class="btn btn-primary" id="saveAlert">Save changes</button>

		</div>

	  </div>

	</div>

  </div>

  <div class="modal fade" id="artifactModal" tabindex="-1" role="dialog" aria-labelledby="artifactModalLabel" aria-hidden="true">

	<div class="modal-dialog" role="document">

	  <div class="modal-content">

		<div class="modal-header">

		  <h5 class="modal-title" id="artifactModalLabel">Artifact</h5>

		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">

			<span aria-hidden="true">&times;</span>

		  </button>

		</div>

		<div class="modal-body">

			<div class="row">

				<div class="col-md-12 form-group">

					<label>files</label>

					<input type="hidden" name="artifactParameterId" id="artifactParameterId" value=""/>

					<input type="hidden" name="artifactSubParameterId" id="artifactSubParameterId" value=""/>

					<input type="file" id="file" name="artifactfile[]" class="form-control-file artifact file">

					<div id="moreArtifact"></div>

					<div id="progress-bar">

						<span id="ProgressContaint" style="display:none">0% Complete</span>

					</div>

				</div>

			</div>

		</div>

		<div class="modal-footer">

		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

		  <button type="button" class="btn btn-primary" id="artifactAlert">Save changes</button>

		</div>

	  </div>

	</div>

  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>

<?php echo $__env->make('shared.table_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;

<style>

	.img-wrap .close {

		position: absolute;

		top: 2px;

		right: 2px;

		z-index: 100;

		background-color: #FFF;

		padding: 5px 2px 2px;

		color: #000;

		font-weight: bold;

		cursor: pointer;

		opacity: .2;

		text-align: center;

		font-size: 22px;

		line-height: 10px;

		border-radius: 50%;

	}

	.img-wrap:hover .close {

		opacity: 1;

	}

</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script>

jQuery('#collection_manager-select').on('change',function(e){

	var code =jQuery(this).data('code');

	var bucket =jQuery(this).data('bucket');

	jQuery('input[name=Collection_Manager_bucket]').val(bucket)

	jQuery('input[name=Collection_Managercode]').val(code)

})

	jQuery(document).on('change', '.artifact', function(e){

	console.log(e)

	if(e.target.files.length>0){

		jQuery('#moreArtifact').append('<input type="file" id="file" name="artifactfile[]" class="form-control-file artifact file">')

	}

})



jQuery(document).on('click', '.close', function() {

		var id = jQuery(this).closest('.img-wrap').find('img').data('id');

		var data={'id':id,'_token':'<?php echo e(csrf_token()); ?>'}

		var saveData = jQuery.ajax({

				type: 'DELETE',

				url: "<?php echo e(url('artifact')); ?>/"+id,

				data: data,

				success: function(resultData) { 

					console.log(resultData)

					jQuery('.art'+id).remove();

				}

			});

		saveData.error(function() { alert("Something went wrong"); });

	});

var redalertData={};

	var result={};

	var par={};

	var subpar={};

	<?php

	foreach($data->parameter as $item)

	{

		?>

		result[<?php echo e($item->id); ?>]={};

		<?php

	}

	

	foreach($resultSubPar as $k=>$v){

		$subValue=($v->is_critical==1)?"Critical":$v->score;

		?>	

		subpar[<?php echo e($k); ?>]=<?php echo e($v->id); ?>


		resultFun('<?php echo e($subValue); ?>', <?php echo e($k); ?>,<?php echo e($v->parameter_id); ?>)

	<?php

	}

	foreach($resultPar as $k=>$v){

	?>

		par[<?php echo e($k); ?>]=<?php echo e($v->id); ?>


	<?php

	}

	?>

	function sum( obj ) {

		var sum = 0;

		for( var el in obj ) {

			if( obj.hasOwnProperty( el ) ) {

				sum += parseFloat( obj[el] );

			}

		}

		return sum;

	}

	function totalfun( obj ){

		var total=0;

		var parmeterTotal=0;

		for( var el in obj ) {

			if( obj.hasOwnProperty( el ) ) {

				var subtotal=0

				for( var item in obj[el] ) {

				if( obj[el].hasOwnProperty( item ) ) {

					if(obj[el][item]!='N/A'){

						paramterValue=jQuery('#org'+item).html();

					}

					else{

						paramterValue=0;

					}

					if(obj[el][item]!='Critical'){

						subtotal=parseFloat(subtotal)+parseFloat((obj[el][item]=='N/A'?0:obj[el][item]));

						parmeterTotal=parmeterTotal+parseFloat(paramterValue);

					}

					else{

						subtotal=0;

						parmeterTotal=parmeterTotal+parseFloat(paramterValue);

						break;

					}

				}

			}

			total=parseFloat(subtotal)+parseFloat(total);

				// total +=sum(obj[el])

			}

		}

		jQuery('#scroable').text(parmeterTotal)

		jQuery('#wfatal').text(total)

		jQuery('#wnfatal').text(total)

		var wfatalper=(total!=0)?(total/parmeterTotal)*100:0;

		// var wnfatalper=(total!=0)?(total/total)*100:0;

		jQuery('#wfatalper').text(wfatalper.toFixed(2)+'%')

		// jQuery('#wnfatalper').text(wnfatalper+'%')

	}

	var total=0;

	function resultFun(value, id,parameterId){

		console.log(value, id,parameterId)

		result[parameterId][id]=value;

		var total=0;

		var parmeterTotal=0;

			for( var el in result[parameterId] ) {

				if( result[parameterId].hasOwnProperty( el ) ) {

					var paramterValue=0;

					if(result[parameterId][el]!='N/A'){

						paramterValue=jQuery('#org'+el).html();

					}

					else{

							paramterValue=0;

						}

					if(result[parameterId][el]!='Critical'){

						total=parseFloat(total)+parseFloat((result[parameterId][el]=='N/A'?0:result[parameterId][el]));

						parmeterTotal=parmeterTotal+parseFloat(paramterValue);

					}

					else{

						total=0;

						parmeterTotal=parmeterTotal+parseFloat(paramterValue);

						break;

					}

				}

			}

			console.log(total)

		jQuery('#scroable'+parameterId).text(parmeterTotal)

		jQuery('#wfatal'+parameterId).text(total)

		// jQuery('#wnfatal'+parameterId).text(total)

		var wfatalper=(total!=0)?(total/parmeterTotal)*100:0;

		// var wnfatalper=(total!=0)?(total/total)*100:0;

		jQuery('#wfatalper'+parameterId).text(wfatalper.toFixed(2)+'%')

		// jQuery('#wnfatalper'+parameterId).text(wnfatalper+'%')

		totalfun(result)

		

	}

	jQuery('.ratingSelect').on('change',function(e){

		var id =jQuery(this).data('id');

		console.log(id)

		var parameterId =jQuery(this).data('parameterid');



		var value=parseInt(jQuery('#org'+id).html());

		var finalValue=value*(e.target.value/100);

		console.log(finalValue,value)

		jQuery('#'+id).val('rating')

		// jQuery('#ratingSelect'+id).hide();

		// 	jQuery('#'+id).show()

		resultFun(finalValue, id,parameterId)

	});

	jQuery('.0bervation').on('change',function(e){

		var id =jQuery(this).data('id');

		var parameterId =jQuery(this).data('parameterid');

		var type = jQuery(this).find(':selected').data('type');

        // alert(ids);

		if(type=='rating'){

			jQuery('#ratingSelect'+id).show();

			jQuery('#'+id).hide()

			jQuery('#'+id).val(e.target.value)

			jQuery('#ratingSelect'+id).attr('data-id',id)

			jQuery('#ratingSelect'+id).attr('data-parameterid',parameterId)



		}

		else{

		jQuery('#ratingSelect'+id).hide();

		jQuery('#'+id).show()

		jQuery('#'+id).val(e.target.value)

		resultFun(e.target.value, id,parameterId)

		}

	})

	jQuery(".submit").on("click",function(e){

		   var observation_rs=true;
          jQuery('.0bervation').each(function(){ 
          	var id=jQuery(this).val();
          	var txt1=jQuery(this).children("option").filter(":selected").text();
          	console.log(id);
			    
          	if(id == null || typeof(id) == 'undefined' || txt1.trim() == 'Choose type')
          	{
			    observation_rs= false;	
			    //console.log(id);
			    
          	}
          	});
          	if(observation_rs == true){
          	if(remarkIsFilled(result)){
			  if(confirm("Are you sure to submit audit. after submit you dont be able to edit audit")){
				jQuery(".submit").prop('disabled', true);
				jQuery(".savebutton").prop('disabled', true);
				submitDataFun('submit');
			 }
			}
			else{
				alert('please filled remarks');
				return false;
			}
		}else
		{
			alert('please choose Observation');
			return false;
		}

		// if(remarkIsFilled(result)){

		// 	if(confirm("Are you sure to submit audit. after submit you dont be able to edit audit")){

		// 		submitDataFun('submit');

		// 	}

		// }

		// else{

		// 	alert('please filled remarks')

		// }

		

	})

	jQuery(".savebutton").on("click",function(e){

		//if(remarkIsFilled(result)){

			submitDataFun('save');

		//}

		/*else{

			alert('please filled remarks')

		}*/

		

	})

	jQuery(document).on('ready',function(e){

		var type="<?php echo e($data->type); ?>"

		if(type=='branch'){

			gerProduct('<?php echo e($result->branch_id); ?>','branch')

			editBranch('<?php echo e($result->branch_id); ?>','<?php echo e($result->product_id); ?>','branch')

		}

		else if(type=='agency'){

			gerProduct('<?php echo e($result->agency_id); ?>','agency')

			editBranch('<?php echo e($result->agency_id); ?>','<?php echo e($result->product_id); ?>','agency')

		}

		else if(type=='branch_repo'){

			gerProduct('<?php echo e($result->branch_repo_id); ?>','branch_repo')

			editBranch('<?php echo e($result->branch_repo_id); ?>','<?php echo e($result->product_id); ?>','branch_repo')

		}

		else if(type=='agency_repo'){

			gerProduct('<?php echo e($result->agency_repo_id); ?>','agency_repo')

			editBranch('<?php echo e($result->agency_repo_id); ?>','<?php echo e($result->product_id); ?>','agency_repo')

		}

		else{

			gerProduct('<?php echo e($result->yard_id); ?>','yard')

			editBranch('<?php echo e($result->yard_id); ?>','<?php echo e($result->product_id); ?>','yard')

		}
		
		getLocation();

	})

	jQuery('.branch').on('change',function(e){

		gerProduct(e.target.value,'branch')

	})

	jQuery('.agency').on('change',function(e){

		gerProduct(e.target.value,'agency')

	})

	jQuery('.yard').on('change',function(e){

		gerProduct(e.target.value,'yard')

	})

	jQuery('.branch_repo').on('change',function(e){

		gerProduct(e.target.value,'branch_repo')

	})

	jQuery('.agency_repo').on('change',function(e){

		gerProduct(e.target.value,'agency_repo')

	})

	jQuery('.product').on('change',function(e){

		var type=jQuery('#productSelect').attr("data-type")

		var id=jQuery('#productSelect').attr("data-id")

		editBranch(id,e.target.value,type)

	})

	function gerProduct(id,type){

		var saveData = jQuery.ajax({

			type: 'get',

			url: "<?php echo e(url('get_product')); ?>/"+id+'/'+type,

			dataType: "text",

			success: function(resultData) { 

				var data='';

				var obj=JSON.parse(resultData)

				obj.data.forEach(function(item, index){

					data=data+'<option value="'+item.id+'"'+(item.id=="<?php echo e($result->product_id); ?>"?'selected':'')+' >'+item.name+'</option>'

					// data=data+'<option value="'+item.id+'">'+item.name+'</option>'

					// data=dadata=data+'<option value="'+item.product.id+'">'+item.product.name+'</option>'

				});

				jQuery('#product').show();

				jQuery('#productSelect').attr('data-type',type)

				jQuery('#productSelect').attr('data-id',id)

				jQuery('#productSelect').html(data)

				// window.location='<?php echo e(url("audited_list")); ?>'

			}

		});

		saveData.error(function() { alert("Something went wrong"); });

	}

	function editBranch(id,product_id,type){

		var saveData = jQuery.ajax({

			type: 'get',

			url: "<?php echo e(url('get_branch_detail')); ?>/"+id+'/'+type+'/'+product_id,

			dataType: "text",

			success: function(resultData) { 

				console.log(resultData)

				jQuery('#data').html(resultData)

				jQuery('#collection_manager-select').val("<?php echo e($result->collection_manager_id); ?>")

				var code =jQuery('#collection_manager-select').find(':selected').data('code');

				var bucket =jQuery('#collection_manager-select').find(':selected').data('bucket');

				jQuery('input[name=Collection_Manager_bucket]').val(bucket)

				jQuery('input[name=Collection_Managercode]').val(code)

				<?php if($result->collection_manager_email != ''): ?>

					var html='<div>\

						<div>\

						<?php echo e($result->collectionuser->name); ?> (<?php echo e($result->collection_manager_email); ?>) change by <?php echo e($result->qa_qtl_detail->name); ?>\

						</div>\

						<div>\

							<button class="btn btn-sm btn-success" onclick="SaveData(`<?php echo e($result->collection_manager_email); ?>`,<?php echo e($result->id); ?>,`collection`)">Accept</button>\

							<button class="btn btn-sm btn-danger" onclick="RejectData(`<?php echo e($result->collection_manager_email); ?>`,<?php echo e($result->id); ?>,`collection`)">Reject</button>\

						</div>\

					</div>'

					jQuery('#error').show();

					jQuery('.error').show();

					jQuery('#error').html(html)

				<?php endif; ?>

				<?php if($result->agency_manager_email != ''): ?>

				var htmla='<div>\

						<div>\

							<?php echo e($result->agencyuser->name); ?> (<?php echo e(($result->agency_manager_email)); ?>) change by <?php echo e($result->qa_qtl_detail->name); ?>\

						</div>\

						<div>\

							<button class="btn btn-sm btn-success"  onclick="SaveData(`<?php echo e($result->agency_manager_email); ?>`,<?php echo e($result->id); ?>,`agency`);">Accept</button>\

							<button class="btn btn-sm btn-danger" onclick="RejectData(`<?php echo e($result->agency_manager_email); ?>`,<?php echo e($result->id); ?>,`agency`);">Reject</button>\

						</div>\

					</div>'

					jQuery('#agency_error').show();

					jQuery('.agency_error').show();

					jQuery('#agency_error').html(htmla)

				<?php endif; ?>

				<?php if($result->yard_manager_email != ''): ?>

				var htmlb='<div>\

						<div>\

							<?php echo e($result->yarduser->name); ?> (<?php echo e(($result->yard_manager_email)); ?>) change by <?php echo e($result->qa_qtl_detail->name); ?>\

						</div>\

						<div>\

							<button class="btn btn-sm btn-success"  onclick="SaveData(`<?php echo e($result->yard_manager_email); ?>`,<?php echo e($result->id); ?>,`yard`)">Accept</button>\

							<button class="btn btn-sm btn-danger" onclick="RejectData(`<?php echo e($result->yard_manager_email); ?>`,<?php echo e($result->id); ?>,`yard`)">Reject</button>\

						</div>\

					</div>'

					jQuery('#yard_error').show();

					jQuery('.yard_error').show();

					jQuery('#yard_error').html(htmlb)

				<?php endif; ?>

			}

		});

		saveData.error(function() { alert("Something went wrong"); });

	}

	jQuery('.alertModal').on('click',function(e){

		var subparameterId =jQuery(this).data('id')

		var parameterId =jQuery(this).data('parameterid')

		jQuery('#alertParameterId').val(parameterId)

		jQuery('#alertSubParameterId').val(subparameterId)

		console.log(parameterId)

		jQuery('#exampleModal').modal('show');

	})

	jQuery('.artifactModal').on('click',function(e){

		var subparameterId =jQuery(this).data('id')

		var parameterId =jQuery(this).data('parameterid')

		jQuery('#artifactParameterId').val(parameterId)

		jQuery('#artifactSubParameterId').val(subparameterId)

		jQuery('#moreArtifact').empty()

		jQuery('#artifactModal').modal('show');

	})

	jQuery('#saveAlert').on('click',function(e){

		var parid=jQuery('#alertParameterId').val()

		var subid=jQuery('#alertSubParameterId').val()

		var msg=jQuery('#msg').val()

		var lob=jQuery('#alertlob').val()

		var type=jQuery('#alerttype').val()

		var typeid=jQuery('#alerttypeid').val()

		var sheetID="<?php echo e($data->id); ?>"

		jQuery('#alertParameterId').val('')

		jQuery('#alertSubParameterId').val('')

		jQuery('#msg').val('')

		jQuery('#exampleModal').modal('hide');

		var fileUpload = jQuery("#file").get(0);

		var files = fileUpload.files;

		var data = new FormData();

            // data.append('id', subid);

            // data.append('parameter_id', parid);

            // data.append('sheet_id', sheetID);

            // data.append('msg', msg);

			// data.append('lob', lob);

            // data.append('type', type);

            // data.append('typeid', typeid);

            // data.append('_token', "<?php echo e(csrf_token()); ?>");

			redalertData[subid]={'id':subid

			,'parameter_id':parid,'sheet_id':sheetID,'msg':msg,'lob':lob,'type':type,'typeid':typeid

			}

            for (var i = 0; i < files.length; i++) {

                // data.append('file', files[i]);

				redalertData[subid]['file']=files[i]

            }

			console.log(redalertData)

			jQuery('#file').val('')

		// var saveData = jQuery.ajax({

		// 	type: 'post',

		// 	url: "<?php echo e(url('red-alert')); ?>",

		// 	data: data,

		// 	processData: false,

		// 	contentType: false,

		// 	success: function(resultData) { 

		// 		console.log(resultData)

		// 		// window.location='<?php echo e(url("audited_list")); ?>'

		// 	}

		// });

		// saveData.error(function() { alert("Something went wrong"); });

	})

	jQuery('#artifactAlert').on('click',function(e){
		

		var parid=jQuery('#artifactParameterId').val()

		var subid=jQuery('#artifactSubParameterId').val()

		var msg=jQuery('#msg').val()

		var sheetID="<?php echo e($data->id); ?>"

		jQuery('#artifactParameterId').val('')

		jQuery('#artifactSubParameterId').val('')

		jQuery('#msg').val('')

		// jQuery('#artifactModal').modal('hide');

		var fileUpload = jQuery(".file").get();

		console.log(fileUpload)

		var files = fileUpload;

		var totalFile=files.length;

		var images='';

		var data = new FormData();

            data.append('id', subid);

			data.append('audit_id', "<?php echo e($result->id); ?>");

            data.append('parameter_id', parid);

            data.append('sheet_id', sheetID);

            data.append('_token', "<?php echo e(csrf_token()); ?>");

            data.append('totalFile', totalFile);

            for (var i = 0; i < files.length; i++) {

				if(files[i].files.length>0){

					data.append('file'+i, files[i].files[0]);

				}

            }

			

		var saveData = jQuery.ajax({

			xhr: function(){

				var xhr = new window.XMLHttpRequest();

			xhr.upload.addEventListener("progress", function(evt) {

			if (evt.lengthComputable) {

				var percentComplete = evt.loaded / evt.total;

				percentComplete = parseInt(percentComplete * 100);

				// jQuery('#progress-bar').attr('aria-valuenow',percentComplete)

				// jQuery('#progress-bar').attr('style','width:'+percentComplete+'%')

				jQuery('#ProgressContaint').html(percentComplete+'% Complete')

				if (percentComplete === 100) {

					// jQuery('#progress-bar').addClass('progress-bar-success')

					jQuery('#ProgressContaint').html(percentComplete+'% Complete')

				}

			}

			}, false)

			return xhr;

			},

			type: 'post',

			url: "<?php echo e(url('artifact')); ?>",

			data: data,

			processData: false,

			contentType: false,

			success: function(resultData) { 

				console.log(resultData)

				jQuery('#moreArtifact').empty()

				jQuery('.file').val('')	

				ImgPreview(resultData.data,'.preview'+subid)

				// window.location='<?php echo e(url("audited_list")); ?>'

				jQuery('#artifactModal').modal('hide');

			}

		});

		saveData.error(function() { alert("Something went wrong"); });

	})

	function ImgPreview(input, placeToInsertImagePreview) {

		// if (input) {

			var filesAmount = input.length;

			var image=''

			input.map(function(item){

			image=image+`<div class="img-wrap preview${item.id}" style="position: relative;display: inline-block;font-size: 0;">

					<span class="close">&times;</span>

					<img src="${item.file}" style="width:100px;height:100px;" data-id="${item.id}">

				</div>`;

			})

			jQuery(placeToInsertImagePreview).html(image)	

		// }

}

	function submitDataFun(type){

	var submitData=[];

		var parameters={}

		var sub={}

		var alertData=redalertData;

		console.log(result)

		for( var el in result ) {

			if( result.hasOwnProperty( el ) ) {

				for( var row in result[el] ) {

					if( result[el].hasOwnProperty( row ) ) {
					    
					    var ck=0;
                        if(jQuery('#ackalert'+row).prop('checked') == true){
                             ck=1;
                        }

						sub[row]={

							'id':subpar[row],

							'remark':jQuery('#remark'+row).val(),

							'orignal_weight':jQuery('#org'+row).text(),

							'temp_weight':result[el][row],

							'score':jQuery('#'+row).val(),

							'is_percentage':(jQuery('#'+row).val()=='rating')?1:0,

							'selected_per':jQuery('#ratingSelect'+row).val(),
                            'ackalert':ck,
							'option':jQuery('#obs'+row+' option:selected').text(),

						}

					}

				}

				

				parameters[el]={

				'id':par[el],

				'score':jQuery('#scroable'+el).text(),

				'score_with_fatal':jQuery('#wfatal'+el).text(),

				'score_without_fatal':jQuery('#wnfatal'+el).text(),

				'temp_total_weightage':jQuery('#scroable').text(),

				'parameter_weight':jQuery('#total'+el).text(),

				'subs':sub

			}

			sub={}

			}

		}
		
		if(jQuery('#demogeo').val() == ''){
			alert("Please Allow Geo Tagging");
			return false;
		}

		submitData.push({

			'id':"<?php echo e($result->id); ?>",

			'qm_sheet_id':"<?php echo e($data->id); ?>",
			
			'geotag':jQuery('#demogeo').val(),

			// 'overall_score':jQuery('#scroable').text(),

			'overall_score':jQuery('#wfatal').text(),

			'with_fatal_score_per':jQuery('#wfatalper').text(),

			'branch_id':jQuery('.branch').val(),
			
			'audit_cycle':jQuery('.audit_cycle').val(),

			'agency_id':jQuery('.agency').val(),

			'yard_id':jQuery('.yard').val(),

			'branch_repo_id':jQuery('.branch_repo').val(),

			'agency_repo_id':jQuery('.agency_repo').val(),

			'product_id':jQuery('.product').val(),

			'collection_manager_email':jQuery('input[name=Collection_Manager_email]').val(),

			'agency_manager_email':jQuery('input[name=agency_manager_email]').val(),

			'yard_manager_email':jQuery('input[name=yard_manager_email]').val(),

			'collection_manager_id':jQuery('#collection_manager-select').val(),
			
			'agency_manager':jQuery('input[name=agency_manager]').val(),
			
			'agency_phone':jQuery('input[name=agency_phone]').val(),

			'status':type

		})

		var ids=[];

		var saveData = jQuery.ajax({

			type: 'POST',

			url: "<?php echo e(url('allocation/update_audit')); ?>",

			data: {'submission_data':submitData,'parameters':parameters,

			"_token":"<?php echo e(csrf_token()); ?>"

			},

			dataType: "text",

			success: function(result) { 

				

				console.log(result)

				var data = new FormData();

				if(jQuery.isEmptyObject(alertData)==false){

				for( var el in alertData ) {

					if( alertData.hasOwnProperty( el ) ) {

							data.append('id'+el, alertData[el].id);

							data.append('parameter_id'+el,  alertData[el].parameter_id);

							data.append('sheet_id'+el,  alertData[el].sheet_id);

							data.append('msg'+el,  alertData[el].msg);

							data.append('lob'+el,  alertData[el].lob);

							data.append('file'+el,  alertData[el].file);

							data.append('_token', "<?php echo e(csrf_token()); ?>");

							data.append('type',  alertData[el].type);

							data.append('typeid',  alertData[el].typeid);

							ids.push(alertData[el].id);

					}

				}

				data.append('ids', JSON.stringify(ids));

				data.append('audit_id', JSON.parse(result).audit_id);

				var saveAlert = jQuery.ajax({

					type: 'post',

					url: "<?php echo e(url('red-alert')); ?>",

					data: data,

					dataType: "text",

					processData: false,

					contentType: false,

					success: function(resultData) { 

						console.log(resultData)

						// window.location='<?php echo e(url("audited_list")); ?>'

					}

				});

				saveAlert.error(function() { alert("Something went wrong");console.log('red-alert-error'); });

			}

				// window.location='<?php echo e(url("audited_list")); ?>'

				window.location='<?php echo e(url("auditor_list")); ?>'

			}

		});

		saveData.error(function() { alert("Something went wrong");console.log('audit save-error'); });

		console.log(parameters)

}

   function remarkIsFilled(result){

	var remark=true;

	for( var el in result ) {

		if( result.hasOwnProperty( el ) ) {

			if(jQuery.isEmptyObject(result[el])){

					remark=false;

			}

			for( var row in result[el] ) {

				if( result[el].hasOwnProperty( row ) ) {

					var value=jQuery('#remark'+row).val();

					if(value.trim().length==0){

						remark=false;

						break;

					}

				}

			}

		}

	}

	return remark;

}

function getLocationWhenReady() {
  const checkInterval = setInterval(() => {
    const textarea = document.getElementById("demogeo");
    if (textarea) {
      clearInterval(checkInterval);
      getLocation();
    }
  }, 200); // checks every 200ms
}

var x = document.getElementById("demogeo");
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  var value =  position.coords.latitude + 
  " " + position.coords.longitude;
  console.log(value)
  jQuery('#demogeo').val(value)
}

function showError(error) {
  var innerHTML=''
	switch(error.code) {
    case error.PERMISSION_DENIED:
      innerHTML = "User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      innerHTML = "Location information is unavailable."
      break;
    case error.TIMEOUT:
      innerHTML = "The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      innerHTML = "An unknown error occurred."
      break;
  }
  jQuery('#demogeo').val(value)
}

getLocationWhenReady();

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/audit/render_sheet_edit.blade.php ENDPATH**/ ?>