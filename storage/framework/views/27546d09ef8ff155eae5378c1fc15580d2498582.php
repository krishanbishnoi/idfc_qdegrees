
<?php if($type=='yard'): ?>

    <div class="col-md-3 form-group">

        <label>Yard name</label>

        <input type="text" name="agency_name" class="form-control" value="<?php echo e($yard->name ?? ''); ?>" disabled>

    </div>

    <div class="col-md-3 form-group">

        <label>Yard Manager</label>

        <input list="yard_manager" type="text" name="yard_manager" class="form-control" value="<?php echo e($yard->agency_manager ?? ''); ?>">

        <!-- <input type="hidden" name="yard_manager_email" value="">

        <span id="yard_error" style="display:none">Not found</span>

        <datalist id="yard_manager">

        </datalist> -->

    </div>

    <div class="col-md-3 form-group">

        <label>Yard Address</label>

        <input type="text" name="agency_address" class="form-control" value="<?php echo e($yard->addresss ?? ''); ?>" disabled>

    </div>

<?php endif; ?>

<?php if($type=='agency'): ?>

    <div class="col-md-3 form-group">

        <label>Agency name</label>

        <input type="text" name="agency_name" class="form-control" value="<?php echo e($agency->name ?? ''); ?>" disabled>

    </div>

    

    <div class="col-md-3 form-group">

        <label>Agency Manager</label>

        <input type="text" name="agency_manager" class="form-control" value="<?php echo e($agency->agency_manager ?? ''); ?>">

    </div>

    <div class="col-md-3 form-group">

        <label>Agency Phone</label>

        <input type="text" name="agency_phone" class="form-control" value="<?php echo e($agency->agency_phone ?? ''); ?>">

    </div>

    <div class="col-md-3 form-group">

        <label>Agency Address</label>

        <input type="text" name="agency_address" class="form-control" value="<?php echo e($agency->addresss ?? ''); ?>" disabled>

    </div>

<?php endif; ?>

<?php if($type=='agency_repo'): ?>

    <div class="col-md-3 form-group">

        <label>Agency repo name</label>

        <input type="text" name="agency_repo_name" class="form-control" value="<?php echo e($AgencyRepo->name ?? ''); ?>" disabled>

    </div>

    <div class="col-md-3 form-group">

        <label>Agency repo Address</label>

        <input type="text" name="agency_repo_address" class="form-control" value="<?php echo e($AgencyRepo->location ?? ''); ?>" disabled>

    </div>

<?php endif; ?>

<?php if($type=='branch_repo'): ?>

    <div class="col-md-3 form-group">

        <label>Branch repo name</label>

        <input type="text" name="branch_repo_name" class="form-control" value="<?php echo e($BranchRepo->name ?? ''); ?>" disabled>

    </div>

    <div class="col-md-3 form-group">

        <label>Branch repo Address</label>

        <input type="text" name="branch_repo_address" class="form-control" value="<?php echo e($BranchRepo->location ?? ''); ?>" disabled>

    </div>

<?php endif; ?>

<div class="col-md-3 form-group">

    <label>Branch name</label>

    <input type="text" name="branch" class="form-control" value="<?php echo e($branchable->name ?? ''); ?>" disabled>

</div>

<div class="col-md-3 form-group">

    <label>City</label>

    <input type="text" name="city" class="form-control" value="<?php echo e($branchable->city->name ?? ''); ?>" disabled>

</div>

<div class="col-md-3 form-group">

    <label>location</label>

    <input type="text" name="location" class="form-control" value="<?php echo e($branchable->location ?? ''); ?>" disabled>

</div>


<?php

    $myData=[];

    $myType=[];

    $code='';

    $bucket='';

    $i=0;
    
    foreach($branchable->branchable as $item){ 

        if($item->type=='Collection_Manager'){

            $myData[$item->type][]=$item;

        }
        elseif($item->type=='Area_Collection_Manager'){

            $myData[$item->type][]=$item;

        }elseif($item->type=='Regional_Collection_Manager'){



            $myData[$item->type][]=$item;



        }
        elseif($item->type=='Zonal_Collection_Manager'){



            $myData[$item->type][]=$item;



        }
         elseif($item->type=='National_Collection_Manager'){



            $myData[$item->type][]=$item;



        }
        else{

            $myData[$item->type]=$item;

        }  

    }

    $myType=array_keys($myData);

?>


<?php //echo "<pre>"; print_r($myType); ?>

<?php $__currentLoopData = $myType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <?php if($item=='Collection_Manager'): ?>
    <div class="col-md-3 form-group">

        <label><?php echo e(str_replace('_',' ',$item)); ?></label>

        <select class="form-control" name="<?php echo e($item); ?>" id="collection_manager-select">

            <?php $__currentLoopData = $myData[$item]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            
                <?php 
                    $ncmname=($value->ncm_id) ? $value->ncm->name : '';
                    $rcmname=($value->rcm_id) ? $value->rcm->name : '';
                    $acmname=($value->acm_id) ? $value->acm->name : '';
                    $zcmname=($value->zcm_id) ? $value->zcm->name : '';
                    $ghname=($value->gph_id) ? $value->ghead->name : '';
                
                ?>
                <?php if(isset($value->user->employee_id)): ?>
                <option data-code="<?php echo e($value->user->employee_id); ?>" data-ncmname = "<?php echo e($ncmname); ?>" data-rcmname = "<?php echo e($rcmname); ?>" data-zcmname = "<?php echo e($zcmname); ?>" data-ghname = "<?php echo e($ghname); ?>" data-bucket="<?php echo e($value->bucket ?? ''); ?>" data-acm = "<?php echo e($value->acm_id ?? ''); ?>" data-zcm = "<?php echo e($value->zcm_id ?? ''); ?>" data-rcm = "<?php echo e($value->rcm_id ?? ''); ?>" data-ncm = "<?php echo e($value->ncm_id ?? ''); ?>"  data-nph = "<?php echo e($value->nph_id ?? ''); ?>" value="<?php echo e($value->user->id); ?>"><?php echo e($value->user->name ?? ''); ?></option>

                <?php

                    if(count($myData[$item])==1){

                        $code=$value->user->employee_id ?? '';

                        $bucket=$value->bucket ?? '';

                    }

                ?>
                <?php endif; ?>
            

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </select>

    </div>

    <div class="col-md-3 form-group">

        <label><?php echo e(str_replace('_',' ',$item)); ?> Emp Code</label>

        <input type="text" name="<?php echo e($item); ?>code" class="form-control" value="<?php echo e($code ?? ''); ?>" disabled>

    </div>

    <div class="col-md-3 form-group">

        <label><?php echo e(str_replace('_',' ',$item)); ?> bucket</label>

        <input type="text" name="<?php echo e($item); ?>_bucket" class="form-control" value="<?php echo e($bucket ?? ''); ?>" disabled>

    </div>

    <?php elseif($item=='Area_Collection_Manager'): ?>
    <div class="col-md-3 form-group">

        <label><?php echo e(str_replace('_',' ',$item)); ?></label>
        <select class="form-control" name="<?php echo e($item); ?>" id="area_collection_manager-select" disabled="true">

            <?php $__currentLoopData = $myData[$item]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <option  value="<?php echo e($value->user->id); ?>"><?php echo e($value->user->name ?? ''); ?></option>

                

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </select>
        

    </div>
     <?php elseif($item=='Regional_Collection_Manager'): ?>
    <div class="col-md-3 form-group" id="forsingle_rcm">

        <label><?php echo e(str_replace('_',' ',$item)); ?></label>

        <input list="adventure" type="text" name="<?php echo e($item); ?>" id="regional_collection_manager-select" class="form-control" value="<?php echo e($myData[$item][0]->user->name ?? ''); ?>" disabled>

    </div>

  <?php elseif($item=='Zonal_Collection_Manager'): ?>
    <div class="col-md-3 form-group" id="forsingle_rcm">

        <label><?php echo e(str_replace('_',' ',$item)); ?></label>

        <input list="adventure" type="text" name="<?php echo e($item); ?>" id="zonal_collection_manager-select" class="form-control" value="<?php echo e($myData[$item][0]->user->name ?? ''); ?>" disabled>

    </div>
     <?php elseif($item=='National_Collection_Manager'): ?>
    <div class="col-md-3 form-group" id="forsingle_rcm">

        <label><?php echo e(str_replace('_',' ',$item)); ?></label>

        <input list="adventure" type="text" name="<?php echo e($item); ?>" id="national_collection_manager-select" class="form-control" value="<?php echo e($myData[$item][0]->user->name ?? ''); ?>" disabled>

    </div>

    <?php else: ?>
    <div class="col-md-3 form-group">

        <label><?php echo e(str_replace('_',' ',$item)); ?></label>

        <input list="adventure" type="text" name="<?php echo e($item); ?>" class="form-control" value="<?php echo e($myData[$item]->user->name ?? ''); ?>" disabled>

    </div>

    <?php endif; ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div class="col-md-3 form-group">

    <label>Geo tag</label>

    <textarea type="text" name="geotag" id="demogeo" class="form-control" value="" disabled></textarea>

</div>

<script>

/*function getSub(val) {
    $('input[name=Collection_Manager_bucket]').val('');
    $('input[name=Collection_Managercode]').val('');
    $('input[name=Area_Collection_Manager]').val('');
    $('#regional_collection_manager-select').val('');
    $('#national_collection_manager-select').val('');
    $('#zonal_collection_manager-select').val('');
    $('input[name=Group_Product_Head]').val('');
    
    $('#collection_manager-select option[value='+val+']').attr('selected','selected');
    var code = $('#collection_manager-select').find('option:selected').data('code');
     //alert(code);
     
    var bucket = $('#collection_manager-select').find('option:selected').data('bucket');
    var acmid = $('#collection_manager-select').find('option:selected').data('acm');
    $('#area_collection_manager-select option[value='+acmid+']').removeAttr("selected");
    //added by abhilasha
    var zcmid = $('#collection_manager-select').find('option:selected').data('zcm');
    var ncmid = $('#collection_manager-select').find('option:selected').data('ncm');
    
    var ncmname=$('#collection_manager-select').find('option:selected').data('ncmname');
    var zcmname=$('#collection_manager-select').find('option:selected').data('zcmname');
    var ghname=$('#collection_manager-select').find('option:selected').data('ghname');
    var rcmname=$('#collection_manager-select').find('option:selected').data('rcmname');
    
    var rcmid = $('#collection_manager-select').find('option:selected').data('rcm');
    if(bucket != '')
    $('input[name=Collection_Manager_bucket]').val(bucket);
    if(code != '')
    $('input[name=Collection_Managercode]').val(code);
    if(acmid != ''){
    $('input[name=Area_Collection_Manager]').val(acmid);
    $('#area_collection_manager-select option[value='+acmid+']').attr('selected','selected');
    }
    if(rcmid != ''){

    $('#regional_collection_manager-select').val(rcmname);

    }
    if(ncmid != ''){

    $('#national_collection_manager-select').val(ncmname);

    }
    if(zcmid != ''){

    $('#zonal_collection_manager-select').val(zcmname);

    }
    
    if(ghname != ''){

    $('input[name=Group_Product_Head]').val(ghname);

    }
}*/

//Commented due to version issue
jQuery('#collection_manager-select').on('change',function(e){
    //jQuery('#collection_manager-select').find("option").removeAttr("selected");
    //jQuery('#area_collection_manager-select').find("option").removeAttr("selected");
    jQuery('input[name=Collection_Manager_bucket]').val('');
    jQuery('input[name=Collection_Managercode]').val('');
    jQuery('input[name=Area_Collection_Manager]').val('');
    jQuery('#regional_collection_manager-select').val('');
    jQuery('#national_collection_manager-select').val('');
    jQuery('#zonal_collection_manager-select').val('');
    jQuery('input[name=Group_Product_Head]').val('');
    
    var optval = this.value;
    jQuery('#collection_manager-select option[value='+optval+']').attr('selected','selected');
    //console.log(code,bucket);
    var code = jQuery(this).find('option:selected').data('code');
    var bucket = jQuery(this).find('option:selected').data('bucket');
    var acmid = jQuery(this).find('option:selected').data('acm');

    //added by kratika jain
    var zcmid = jQuery(this).find('option:selected').data('zcm');
    var ncmid = jQuery(this).find('option:selected').data('ncm');
    var rcmid = jQuery(this).find('option:selected').data('rcm');
    
    var ncmname=jQuery(this).find('option:selected').data('ncmname');
    var zcmname=jQuery(this).find('option:selected').data('zcmname');
    var ghname=jQuery(this).find('option:selected').data('ghname');
    var rcmname=jQuery(this).find('option:selected').data('rcmname');
    if(bucket != '')
    jQuery('input[name=Collection_Manager_bucket]').val(bucket);
    if(code != '')
    jQuery('input[name=Collection_Managercode]').val(code);
    if(acmid != ''){
    jQuery('input[name=Area_Collection_Manager]').val(acmid);
    jQuery('#area_collection_manager-select option[value='+acmid+']').attr('selected','selected');
    }
    if(rcmid != ''){

    jQuery('#regional_collection_manager-select').val(rcmname);

    }
    if(ncmid != ''){

    jQuery('#national_collection_manager-select').val(ncmname);

    }
    if(zcmid != ''){

    jQuery('#zonal_collection_manager-select').val(zcmname);

    }
    
    if(ghname != ''){

    jQuery('input[name=Group_Product_Head]').val(ghname);

    }

});

</script><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/audit/branch.blade.php ENDPATH**/ ?>