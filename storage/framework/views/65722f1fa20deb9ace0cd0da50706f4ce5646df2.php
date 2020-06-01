

<?php $__env->startSection('title', '| Branch'); ?>

<!-- <?php $__env->startSection('sh-detail'); ?>
    Users
    <?php $__env->stopSection(); ?> -->

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
                        <strong class="card-title">Branch List</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">
                                    Name
                                </th>
                                <th scope="col">
                                    City
                                </th>
                                <th scope="col">
                                    Manager
                                </th>
                                <th scope="col">
                                    Lob
                                </th>
                                <th scope="col">
                                    Location
                                </th>

                                <th scope="col">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr scope="row">
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <?php echo e($row->name); ?>

                                    </td>
                                    <td><?php echo e(@$row->city->name); ?></td>
                                    <td><?php echo e(@$row->user->name); ?></td>
                                    <td><?php echo e(str_replace('_',' ',$row->lob)); ?></td>
                                    <td><?php echo e($row->location); ?></td>


                                    <td nowrap>
                                    <!-- <div style="display: flex;">
										<?php echo e(Form::open([ 'method'  => 'delete', 'route' => [ 'branch.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])); ?>

                                            <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </form> -->
                                        <a href="<?php echo e(url('branch/'.Crypt::encrypt($row->id).'/edit')); ?>"
                                           class="btn btn-xs btn-info" title="View">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <!-- </div> -->
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/branch/list.blade.php ENDPATH**/ ?>