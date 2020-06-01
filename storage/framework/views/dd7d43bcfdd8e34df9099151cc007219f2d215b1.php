

<?php $__env->startSection('sh-title'); ?>
Audited
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sh-detail'); ?>
Call
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
				<div class="card-header">
					<strong class="card-title">Create Action Plan</strong>
				</div>
				<div class="card-body">
                    <form method="post" action="<?php echo e(route('action.store')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                            <div class="row">
                                  <div class="col-md-5 form-group">
                                  <input type="hidden" name="sheet_id" value="<?php echo e($did); ?>"/>
                                        <label>Question*</label>
                                        <textarea name="question"  class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <label>Artifact*</label><br/>
                                        <input type="file" name="artifact"/>
                                    </div>
                                    
                                </div>
                               <div id="kt_repeater_6"></div>

                        
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <div id="add" class="btn btn-primary btn-sm">
                                    <span>
                                        <i class="fa fa-plus"></i>
                                        <span>Add</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<?php echo $__env->make('shared.table_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>


<script>
    var i=1;
jQuery("#add").on('click',function(e){ 
    var data=''
    data='<div class="col-md-5 form-group">\
            <label>Question*</label>\
            <textarea name="question'+i+'"  class="form-control"></textarea>\
        </div>\
        <div class="col-md-5 form-group">\
            <label>Artifact*</label><br/>\
            <input type="file" name="artifact'+i+'"/>\
        </div>\
        <div class="col-md-2">\
            <button type="button" class="btn-sm btn btn-danger btn-pill remove"  data-id="'+i+'">\
                    <span class="remove" onclick="removeField('+i+');">Delete</span></button>\
            </div>';
        jQuery('#kt_repeater_6').append('<div class="row delete'+i+'">'+data+'</div>')
        i++;
    })
 function removeField(id){
        
        jQuery('.delete'+id).remove();
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/Action/create.blade.php ENDPATH**/ ?>