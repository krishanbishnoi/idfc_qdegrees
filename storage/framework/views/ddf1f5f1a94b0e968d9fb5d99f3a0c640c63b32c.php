<?php if($type=='yard'): ?>
    <div class="col-md-3 form-group">
        <label>Yard name</label>
        <input type="text" name="agency_name" class="form-control" value="<?php echo e($yard->name ?? ''); ?>" disabled>
    </div>
    <div class="col-md-3 form-group">
        <label>Yard Manager</label>
        <input list="yard_manager" type="text" name="yard_manager" class="form-control" value="<?php echo e($yard->user->name ?? ''); ?>" onkeyup="changeYardManager(this.value)" onchange="selectYardManager(this.value)">
        <input type="hidden" name="yard_manager_email" value="">
        <span id="yard_error" style="display:none">Not found</span>
        <datalist id="yard_manager">
        </datalist>
    </div>
    <div class="col-md-3 form-group">
        <label>Yard Address</label>
        <input type="text" name="agency_address" class="form-control" value="<?php echo e($yard->addresss ?? ''); ?>" disabled>
    </div>
<?php endif; ?>
<?php if($type=='agency' || $type=='yard'): ?>
    <div class="col-md-3 form-group">
        <label>Agency name</label>
        <input type="text" name="agency_name" class="form-control" value="<?php echo e($agency->name ?? ''); ?>" disabled>
    </div>
    <div class="col-md-3 form-group">
        <label>Agency Manager</label>
        <input list="agency_manager" type="text" name="agency_manager" class="form-control" value="<?php echo e($agency->user->name ?? ''); ?>" onkeyup="changeAgencyManager(this.value)" onchange="selectAgencyManager(this.value)">
        <input type="hidden" name="agency_manager_email" value="">
        <span id="agency_error" style="display:none">Not found</span>
        <datalist id="agency_manager">
        </datalist>
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
        else{
            $myData[$item->type]=$item;
        }  
    }
    $myType=array_keys($myData);
?>

<?php $__currentLoopData = $myType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($item=='Collection_Manager'): ?>
    <div class="col-md-3 form-group">
        <label><?php echo e(str_replace('_',' ',$item)); ?></label>
        <select class="form-control" name="<?php echo e($item); ?>" id="collection_manager-select">
            <?php $__currentLoopData = $myData[$item]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option data-code="<?php echo e($value->user->employee_id); ?>" data-bucket="<?php echo e($value->buckrt ?? ''); ?>" value="<?php echo e($value->user->id); ?>"><?php echo e($value->user->name ?? ''); ?></option>
                <?php
                    if(count($myData[$item])==1){
                        $code=$value->user->employee_id ?? '';
                        $bucket=$value->buckrt ?? '';
                    }
                ?>
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
    <?php else: ?>
    <div class="col-md-3 form-group">
        <label><?php echo e(str_replace('_',' ',$item)); ?></label>
        <input list="adventure" type="text" name="<?php echo e($item); ?>" class="form-control" value="<?php echo e($myData[$item]->user->name ?? ''); ?>" disabled>
    </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /var/www/html/audit/resources/views/audit/branch.blade.php ENDPATH**/ ?>