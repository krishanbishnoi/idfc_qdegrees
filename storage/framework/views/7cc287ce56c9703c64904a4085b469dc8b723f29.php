

<?php $__env->startSection('title', '| Upload'); ?>

<?php $__env->startSection('sh-detail'); ?>
    Create New
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div id="loader"></div>
<div class="row" style="display:none;" id="myDiv">
    <div class="col-md-12">
    <div class="card w-100">
        <div class="card-header">
            <strong>Compliance show</strong> 
        </div>
        <div class="card-body card-block">
            <?php echo Form::open(
                     array(
                       'route' => 'getGap',
                       'class' => 'form-horizontal',
                       'role'=>'form',
                       'id'=>'gap',
                       'data-toggle'=>"validator",
                    //    'files' => true
                       )
                     ); ?>

            <div class="row">
                <div class="col-sm-3">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">Choose lob</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <select name="lob" class="form-control" id="lob">
                            <option value="">Choose Lob Name</option>
                            <option value="collection" <?php echo e(($lob=='collection')?'selected':''); ?>>Collection</option>
                            <option value="commercial_vehicle" <?php echo e(($lob=='commercial_vehicle')?'selected':''); ?>>Commercial Vehicle</option>
                            <option value="rural" <?php echo e(($lob=='rural')?'selected':''); ?>>Rural</option>
                            <option value="alliance" <?php echo e(($lob=='alliance')?'selected':''); ?>>Alliance</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class=" form-group">
                        <label for="text-input" class="form-control-label">Choose Branch</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <select class="form-control" id="branch" name="branch">
                            <option>Choose Branch Name</option>
                            
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">Choose Agency</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <select class="form-control" id="agency" name="agency">
                            <option>Choose Agency Name</option>
                        
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">Choose date</label>
                    </div>
                </div>
                <div class="col-sm-4" style="float:right">
                    <div class="form-group">
                            <input type="text" name="date" class="form-control datepicker" placeholder="Choose From Date" value="<?php echo e($date ?? ''); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">bucket</label>
                    </div>
                </div>
                <div class="col-sm-4" style="float:right">
                    <div class="form-group">
                            <input type="number" name="bucket" class="form-control" placeholder="enter bucket value" value="<?php echo e($bucket ?? ''); ?>">
                    </div>
                </div>
            </div>
            
            
         <div class="card-footer">
                 <button type="submit" id="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Submit
                </button>
                <button type="reset" class="btn btn-danger btn-sm">
                    <i class="fa fa-ban"></i> Reset
                </button> 
            </div> 
            </form>
        </div>
        
    </div>
</div>
<?php if(isset($allocation)): ?>
    <div class="col-lg-12">
        <div class="card  w-100">
            <div class="card-header">
            <strong class="card-title"><?php echo e($branchId); ?> Branch - Allocation GAP</strong>
            </div>
            <div class="card-body">
                <div>
                    <?php 
                        $total=$allocation['total'];
                        $com=count($allocation['data']);
                        $per=($com!=0)?(($com/$total)*100):0;
                    ?>
                    <span style="margin-right:10px">Total Data :</span><span style="margin-right:10px"><?php echo e($total); ?></span>
                    <span style="margin-right:10px">Total Compliance :</span><span style="margin-right:10px"><?php echo e($com); ?></span>
                    <span style="margin-right:10px">Total per :</span><span style="margin-right:10px"><?php echo e($per); ?>%</span>
                </div>
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                        <thead>
                            <tr>
                                <th>PRODUCTFLAG</th>
                                <th>BRANCH</th>
                                <th>REGION</th>
                                <th>Agency Code</th>
                                <th>Agency Name</th>
                                <th>Status</th>
                                <th>Date Stamp</th>
                                <th>Allocation GAP</th>
                                <th>Delay Allocation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $allocation['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                            <tr>
                                <td><?php echo e($item->product_flag); ?></td>
                                <td><?php echo e($item->branch); ?></td>
                                <td><?php echo e($item->region); ?></td>
                                <td><?php echo e($item->agency_code); ?></td>
                                <td><?php echo e($item->agency_name); ?></td>
                                <td><?php echo e($item->status); ?></td>
                                <td><?php echo e($item->date_stamp); ?></td>
                                <td><?php echo e(($item->gap1!=null && $item->gap1!='')?$item->gap1.' Days':'0'); ?> </td>
                                <td><?php echo e($item->agent_allocation_status); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>        
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($dac)): ?>
    <div class="col-lg-12">
        <div class="card w-100">
            <div class="card-header">
                <strong class="card-title"><?php echo e($branchId); ?> Branch - DAC GAP</strong>
            </div>
            <div class="card-body">
                <div>
                    <?php 
                        $total=$dac['total'];
                        $com=count($dac['data']);
                        $per=($com!=0)?(($com/$total)*100):0;
                    ?>
                    <span style="margin-right:10px">Total Data :</span><span style="margin-right:10px"><?php echo e($total); ?></span>
                    <span style="margin-right:10px">Total Compliance :</span><span style="margin-right:10px"><?php echo e($com); ?></span>
                    <span style="margin-right:10px">Total per :</span><span style="margin-right:10px"><?php echo e($per); ?>%</span>
                </div>
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
                        <thead>
                                <tr>
                                        <th>Product</th>
                                        <th>Payment Id</th>
                                        <th>Branch</th>
                                        <th>Agency Id</th>
                                        <th>Agency Name</th>
                                        <th>Recipt No.</th>
                                        <th>Recipt Date.</th>
                                        <th>Deposit Date</th>
                                        <th>Finnone Update</th>
                                        <th>Delay deposition</th>
                                        <th>Delay deposition</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $dac['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <tr>
                                        <td><?php echo e($item->PRODUCT); ?></td>
                                        <td><?php echo e($item->PaymentId); ?></td>
                                        <td><?php echo e($item->BranchName); ?></td>
                                        <td><?php echo e($item->AgencyId); ?></td>
                                        <td><?php echo e($item->AgencyName); ?></td>
                                        <td><?php echo e($item->ReceiptNo); ?></td>
                                        <td><?php echo e($item->ReceiptDate); ?></td>
                                        <td><?php echo e($item->DepositDate); ?></td>
                                        <td><?php echo e($item->Finnone_Update); ?></td>
                                        <td><?php echo e($item->gap1); ?></td>
                                        <td><?php echo e($item->gap2); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>        
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($trail)): ?>
    <div class="col-lg-12">
        <div class="card w-100">
            <div class="card-header">
                <strong class="card-title"><?php echo e($branchId); ?> Branch - Trail Intensity GAP</strong>
            </div>
            <div class="card-body">
                <div>
                    <?php 
                        $total=$trail['total'];
                        $com=count($trail['data']);
                        $per=($com!=0)?(($com/$total)*100):0;
                    ?>
                    <span style="margin-right:10px">Total Data :</span><span style="margin-right:10px"><?php echo e($total); ?></span>
                    <span style="margin-right:10px">Total Compliance :</span><span style="margin-right:10px"><?php echo e($com); ?></span>
                    <span style="margin-right:10px">Total per :</span><span style="margin-right:10px"><?php echo e($per); ?>%</span>
                </div>
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_3">
                        <thead>
                            <tr>
                                    <th>PRODUCTFLAG</th>
                                    <th>BRANCH</th>
                                    <th>REGION</th>
                                    <th>Agency Code</th>
                                    <th>Agency Name</th>
                                    <th>Status</th>
                                    <th>Date Stamp</th>
                                    <th>Attempts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $trail['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                            <tr>
                                <td><?php echo e($item->product_flag_1); ?></td>
                                <td><?php echo e($item->branch); ?></td>
                                <td><?php echo e($item->region); ?></td>
                                <td><?php echo e($item->agency_code); ?></td>
                                <td><?php echo e($item->agency_name); ?></td>
                                <td><?php echo e($item->status); ?></td>
                                <td><?php echo e($item->date_stamp); ?></td>
                                <td><?php echo e($item->attempts); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>        
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($adverseBulk)): ?>
    <div class="col-lg-12">
        <div class="card w-100">
            <div class="card-header">
                <strong class="card-title"><?php echo e($branchId); ?> Branch - Adverse Bulk</strong>
            </div>
            <div class="card-body">
                <div>
                    <?php 
                        $total=$adverseBulk['total'];
                        $com=count($adverseBulk['data']);
                        $per=($com!=0)?(($com/$total)*100):0;
                    ?>
                    <span style="margin-right:10px">Total Data :</span><span style="margin-right:10px"><?php echo e($total); ?></span>
                    <span style="margin-right:10px">Total Compliance :</span><span style="margin-right:10px"><?php echo e($com); ?></span>
                    <span style="margin-right:10px">Total per :</span><span style="margin-right:10px"><?php echo e($per); ?>%</span>
                </div>
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_4">
                        <thead>
                            <tr>
                                <th>AGRMNT ID</th>
                                <th>PRODUCT FLAG</th>
                                <th>PRODUCT FLAG_Q</th>
                                <th>BRANCH</th>
                                <th>BOM POS</th>
                                <th>Agency Code</th>
                                <th>Agency Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $adverseBulk['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                            <tr>
                                <td><?php echo e($item->AGRMNTID); ?></td>
                                <td><?php echo e($item->PRODUCTFLAG); ?></td>
                                <td><?php echo e($item->PRODUCTFLAG_Q); ?></td>
                                <td><?php echo e($item->BRANCH); ?></td>
                                <td><?php echo e($item->month_BOM_POS); ?></td>
                                <td><?php echo e($item->month_Agent_Code); ?></td>
                                <td><?php echo e($item->month_Agency_Name); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>        
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($settlement)): ?>
    <div class="col-lg-12">
        <div class="card w-100">
            <div class="card-header">
                <strong class="card-title"><?php echo e($branchId); ?> Branch - Settlement</strong>
            </div>
            <div class="card-body">
                <div>
                    <?php 
                        $total=$settlement['total'];
                        $com=count($settlement['data']);
                        $per=($com!=0)?(($com/$total)*100):0;
                    ?>
                    <span style="margin-right:10px">Total Data :</span><span style="margin-right:10px"><?php echo e($total); ?></span>
                    <span style="margin-right:10px">Total Compliance :</span><span style="margin-right:10px"><?php echo e($com); ?></span>
                    <span style="margin-right:10px">Total per :</span><span style="margin-right:10px"><?php echo e($per); ?>%</span>
                </div>
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_5">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>REQUEST NO</th>
                                <th>LOAN NO</th>
                                <th>CUSTOMERNAME</th>
                                <th>BRANCH</th>
                                <th>STATE</th>
                                <th>PRODUCT </th>
                                <th>SCHEME DESC</th>
                                <th>PENALTY</th>
                                <th>LOANAMT</th>
                                <th>EMI</th>
                                <th>SETTLEMENTAMT</th>
                                <th>REQUEST_DATE</th>
                                <th>REQUESTED_BY</th>
                                <th>SETTLEMENTSTART_DATE</th>
                                <th>SETTLEMENTEND_DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $settlement['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                            <tr>
                                <td><?php echo e($item->Month); ?></td>
                                <td><?php echo e($item->REQUEST_NO); ?></td>
                                <td><?php echo e($item->LOAN_NO); ?></td>
                                <td><?php echo e($item->CUSTOMERNAME); ?></td>
                                <td><?php echo e($item->BRANCH); ?></td>
                                <td><?php echo e($item->STATE); ?></td>
                                <td><?php echo e($item->PRODUCT_1); ?></td>
                                <td><?php echo e($item->SCHEMEDESC); ?></td>
                                <td><?php echo e($item->PENALTY); ?></td>
                                <td><?php echo e($item->LOANAMT); ?></td>
                                <td><?php echo e($item->EMI); ?></td>
                                <td><?php echo e($item->SETTLEMENTAMT); ?></td>
                                <td><?php echo e($item->REQUESTED_BY); ?></td>
                                <td><?php echo e($item->SETTLEMENTSTART_DATE); ?></td>
                                <td><?php echo e($item->SETTLEMENTEND_DATE); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>        
            </div>
        </div>
    </div>
<?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
/* Center the loader */
#loader {
  position: absolute;
  left: 60%;
  top: 60%;
  z-index: 1;
  /* margin: -75px 0 0 -75px; */
  border: 2px solid #f3f3f3;
  border-radius: 50%;
  border-top: 2px solid #3498db;
  width: 60px;
  height: 60px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}



</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        jQuery('#branch').on('change',function(e){
            // jQuery('#gap').submit();
            getAgency(e.target.value,'')
        })
        jQuery('#lob').on('change',function(e){
             getBranch(e.target.value,'')
        })
        jQuery('#submit').on('click',function(e){
            // e.preventDefault();
            // jQuery('#gap').submit();
            document.getElementById("loader").style.display = "block";
            document.getElementById("myDiv").style.display = "none";
        })
        
        jQuery(document).ready(function() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("myDiv").style.display = "block";
            jQuery('#kt_table_1').DataTable();
            jQuery('#kt_table_2').DataTable();
            jQuery('#kt_table_3').DataTable();
            jQuery('#kt_table_4').DataTable();
            jQuery('#kt_table_5').DataTable();
            jQuery('.datepicker').daterangepicker();
            <?php if(isset($branchId) && $branchId!=''): ?>
                getBranch('<?php echo e($lob); ?>','<?php echo e($branchId); ?>')
            <?php endif; ?>
            <?php if(isset($agencyId)): ?>
                getAgency('<?php echo e($branchId); ?>','<?php echo e($agencyId); ?>')
            <?php endif; ?>
    })
    function getAgency(name,selected){
        var saveAlert = jQuery.ajax({
            type: 'get',
            url: "<?php echo e(url('get-agencies-upload')); ?>/"+name,
            accept: "application-json",
            processData: false,
            contentType: false,
            success: function(resultData) { 
                // console.log(resultData)
                var html='<option>Choose Agency Name</option>'
                for( var val in resultData.data ) {
                    if( resultData.data.hasOwnProperty( val ) ) {
                        html=html+`<option value="${resultData.data[val]}" ${(resultData.data[val]==selected)?'selected':''} >${resultData.data[val]}</option>`
                    }
                }
                jQuery('#agency').html(html)
            }
        });
        saveAlert.error(function() { alert("Something went wrong"); });

    }
    function getBranch(lob,selected){
        var saveAlert = jQuery.ajax({
            type: 'get',
            url: "<?php echo e(url('get-branch-upload')); ?>/"+lob,
            accept: "application-json",
            processData: false,
            contentType: false,
            success: function(resultData) { 
                // console.log(resultData)
                var html='<option>Choose branch Name</option>'
                for( var val in resultData ) {
                    if( resultData.hasOwnProperty( val ) ) {
                        html=html+`<option value="${resultData[val]}" ${(resultData[val]==selected)?'selected':''} >${resultData[val]}</option>`
                    }
                }
                jQuery('#branch').html(html)
            }
        });
        saveAlert.error(function() { alert("Something went wrong"); });

    }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/bulkUpload/gap.blade.php ENDPATH**/ ?>