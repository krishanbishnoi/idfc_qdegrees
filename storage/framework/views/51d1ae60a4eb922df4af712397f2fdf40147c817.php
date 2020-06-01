

<?php $__env->startSection('title', '| Users'); ?>

<?php $__env->startSection('sh-detail'); ?>
    Create New
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <strong>Create Branch</strong> 
        </div>
        <div class="card-body card-block">
            <?php echo Form::open(
                     array(
                       'route' => 'branch.store',
                       'class' => 'form-horizontal',
                       'role'=>'form',
                       'data-toggle'=>"validator")
                     ); ?>


            <div class="row">
            
            <div class="col col-md-4">
                <div class="form-group">
                    <label>Lob Name*</label>
                    <select name="lob" class="form-control">
                        <option value="">Choose Lob Name</option>
                        <option value="collection">Collection</option>
                        <option value="commercial_vehicle">Commercial Vehicle</option>
                        <option value="rural">Rural</option>
                        <option value="alliance">Alliance</option>
                    </select>
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="multiple-select" class=" form-control-label">Regions
                            </label>
                    <select class="form-control" name="region_id" id="country">
                        <option value="">Choose Region</option>
                        <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($country->id); ?>">
                                <?php echo e($country->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="multiple-select" class=" form-control-label">State
                            </label>
                        <select class="form-control" name="state" id="state">
                        </select>

                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="multiple-select" class=" form-control-label">City
                            </label>

                        <select class="form-control" name="city_id" id="city">
                        </select>

                </div>
            </div>

            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Branch Name</label>
                    <input type="text" id="text-input" name="name" placeholder="Branch Name"
                                                        class="form-control" value="<?php echo e(old('name')); ?>">
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Location</label>
                    <input type="text" id="text-input" name="location" placeholder="Location"
                                                        class="form-control" value="<?php echo e(old('name')); ?>">
                    
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Branch Id</label>
                    <input type="text" id="text-input" name="uuid" placeholder="Branch Id"
                                                        class="form-control" readonly value="<?php echo e(getBranchUuid()); ?>">
                    
                </div>
            </div>

            
            <!-- branch manager working -->
            
                
                
                <?php
                $roles=['Collection_Manager','Area_Collection_Manager','Regional_Collection_Manager','Zonal_Collection_Manager','National_Collection_Manager','Group_Product_Head'];
            ?>
            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="multiple-select" class=" form-control-label"><?php echo e(str_replace('_',' ',$item)); ?>

                            </label>
                    <select class="form-control" name="<?php echo e($item); ?>" id="users">
                            <option value="">Choose <?php echo e(str_replace('_',' ',$item)); ?></option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($user->hasRole(str_replace('_',' ',$item))): ?>
                                <option value="<?php echo e($user->id); ?>">
                                    <?php echo e($user->name); ?>

                                </option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="col col-md-3">
                    <div class="form-group">
                        <label for="multiple-select" class=" form-control-label">Product
                                </label>
                            <select class="form-control" name="product_id" id="owner_id">
                                <option selected>--Choose Product--</option>
                               <?php $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               <option value="<?php echo e($data->id); ?>"><?php echo e($data->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                    </div>
                </div>
                <div class="col col-md-1">
                <label></label>
                <div class="fa fa-plus text-right" id="add"></div>
                </div>
                
            </div>
            <div  id="shower">
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


<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>

            
                
            
var users='';
                var idx=1
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($user->hasRole(str_replace('_',' ','Collection_Manager'))): ?>
                            users=users+`<option value="<?php echo e($user->id); ?>">
                                <?php echo e($user->name); ?>

                            </option>`
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                var pro=''
                <?php $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                       pro=pro+ `<option value="<?php echo e($data->id); ?>"><?php echo e($data->name); ?></option>`
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                var finalData=''
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                var conUser=''
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($user->hasRole(str_replace('_',' ',$item))): ?>
                            conUser=conUser+`<option value="<?php echo e($user->id); ?>">
                                <?php echo e($user->name); ?>

                            </option>`
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                finalData=finalData+ `<div class="col col-md-4">
                        <div class="form-group">
                            <label for="multiple-select" class=" form-control-label"><?php echo e(str_replace('_',' ',$item)); ?>

                                    </label>
                            <select class="form-control" name="<?php echo e($item); ?>${idx}" id="users">
                                    <option value="">Choose <?php echo e(str_replace('_',' ',$item)); ?></option>
                                 ${conUser}       
                            </select>
                        </div>
                    </div>`
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        jQuery('#add').on('click',function(e){
            // var manager=`<div class="col col-md-4">
            //     <div class="form-group">
            //         <label for="multiple-select" class=" form-control-label">Branch Manager
            //                 </label>
            //         <select class="form-control" name="manager_id${idx}" id="users">
            //                 <option value="">Choose Branch Manager</option>
            //                 ${users}          
            //             </select>

            //     </div>
            // </div>`;
            var manager='';
            manager=manager+finalData;
            manager=manager+`    <div class="col col-md-3">
                    <div class="form-group">
                        <label for="multiple-select" class=" form-control-label">Product
                                </label>
                            <select class="form-control" name="product_id${idx}" id="owner_id">
                                <option selected>--Choose Product--</option>
                                ${pro}
                            </select>
                    </div>
                </div>`
                manager=manager+`<div class="col col-md-1 text-center fa fa-trash" id="delete" data-id="${idx}"></div>`   
            jQuery('#shower').append(`<div class="row" id="custom${idx}">${manager}</div>`)
            idx=idx+1;
        })
        jQuery(document).on('click','#delete',function(e){
            var id=jQuery(this).data('id');
            // alert(id);
            jQuery('#custom'+id).remove();
        })
        jQuery('#country').change(function () {
            var cid = jQuery(this).val();
            if (cid) {
                jQuery.ajax({
                    type: "get",
                    url: " <?php echo e(url('/getStates')); ?>/" + cid,
                    success: function (res) {
                        if (res) {
                            jQuery("#state").empty();
                            jQuery("#city").empty();
                            jQuery("#state").append('<option>Select State</option>');
                            jQuery.each(res, function (key, value) {
                                jQuery("#state").append('<option value="' + key + '">' + value + '</option>');
                            });
                        }
                    }

                });
            }
        });
        jQuery('#state').change(function () {
            var sid = jQuery(this).val();
            if (sid) {
                jQuery.ajax({
                    type: "get",
                    url: "<?php echo e(url('/getCities')); ?>/" + sid,
                    success: function (res) {
                        if (res) {
                            jQuery("#city").empty();
                            jQuery("#city").append('<option>Select City</option>');
                            jQuery.each(res, function (key, value) {
                                jQuery("#city").append('<option value="' + key + '">' + value + '</option>');
                            });
                        }
                    }

                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/branch/create.blade.php ENDPATH**/ ?>