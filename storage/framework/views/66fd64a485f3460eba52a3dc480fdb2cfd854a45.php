<?php $__env->startSection('title', '| Users'); ?>



<?php $__env->startSection('sh-detail'); ?>

    Create New

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>

    <div class="card">

        <div class="card-header">

            <strong>Create City</strong> 

        </div>

        <div class="card-body card-block">

            <?php echo Form::open(

                     array(

                       'route' => 'location.store',

                       'class' => 'form-horizontal',

                       'role'=>'form',

                       'data-toggle'=>"validator")

                     ); ?>




            <div class="row">

            

            <div class="col col-md-4">

                <div class="form-group">

                    <label for="multiple-select" class=" form-control-label">Regions

                            </label>

                    <select class="form-control" name="region_id" id="country" required>

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

                        <select class="form-control" name="state" id="state" required>
                            <option>Choose State</option>

                        </select>



                </div>

            </div>

            <div class="col col-md-4">

                <div class=" form-group">

                        <label for="text-input" class=" form-control-label">City Name</label>

                        <input type="text" id="text-input" name="city" placeholder="City Name" class="form-control" value="" tabindex="1" required>

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

        </div>



    </div>





<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

    <script>

     

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
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/location/create.blade.php ENDPATH**/ ?>