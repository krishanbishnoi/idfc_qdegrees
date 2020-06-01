<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
 <!-- Content -->
 <input type="hidden" name="url" id="url" value=<?php echo e(url('/')); ?>>
 <input type="hidden" name="token" id="token" value=<?php echo e(@csrf_token()); ?>>
 <div class="content" style="font-size: 13px !important;">
            <!-- Animated -->
            <div class="animated fadeIn">
                <!-- Widgets  -->
                <div class="card">
                    <div class="card-header">
                        <form action="<?php echo e(route('dashboard')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-7"><h4>Line of Business Score</h4></div>
                                <div class="col-md-5">
                                    <label for="audit_cycle">Audit Cycle</label>
                                    <select class="text-right " name="lob_audit_cycle" id="lob_audit_cycle">
                                        <option value="current" <?php echo e((isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='current')?'selected':''); ?>>Current Audit Cycle</option>
                                    <option value="last_2" <?php echo e((isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='last_2')?'selected':''); ?>>Last 2 Audit Cycle</option>
                                    <option value="last_3" <?php echo e((isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='last_3')?'selected':''); ?>>Last 3 Audit Cycle</option>
                                    <option value="last_4" <?php echo e((isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='last_4')?'selected':''); ?>>Last 4 Audit Cycle</option>
                                    <option value="custom" <?php echo e((isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='custom')?'selected':''); ?>>Custom Audit Cycle</option>
                                    </select>
                                <input type="text" style="<?php echo e((isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='custom')?'':'display:none;'); ?>" value="<?php echo e((isset($old['lob_audit_cycle_custom']))? $old['lob_audit_cycle_custom']:''); ?>" name="lob_audit_cycle_custom" id="lob_audit_cycle_custom"/>
                                    <input type="submit" class="text-right ml-2" value="Show Result">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="stat-widget-five text-center">
                                            <div class="text-center dib">
                                                <div class="stat-heading">IDFC First Collection</div>
                                                <div class="stat-text"><span class="count"><?php echo e(isset($lob['collection'])?(round(($lob['collection']['point']/$lob['collection']['total'])*100,2)) : 0); ?>%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="stat-widget-five text-center">
                                            <div class="text-center dib">
                                                <div class="stat-heading">IDFC First Commercial Vehicle</div>
                                                <div class="stat-text"><span class="count"><?php echo e(isset($lob['commercial_vehicle'])?(round(($lob['commercial_vehicle']['point']/$lob['commercial_vehicle']['total'])*100,2)) : 0); ?>%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="stat-widget-five text-center">
                                            <div class="text-center dib">
                                                <div class="stat-heading">IDFC First Rural</div>
                                                <div class="stat-text"><span class="count"><?php echo e(isset($lob['rural'])?(round(($lob['rural']['point']/$lob['rural']['total'])*100,2))  : 0); ?>%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="stat-widget-five text-center">
                                            <div class="text-center dib">
                                                <div class="stat-heading">IDFC First Alliance</div>
                                                <div class="stat-text"><span class="count"><?php echo e(isset($lob['alliance'])?(round(($lob['alliance']['point']/$lob['alliance']['total'])*100,2))  : 0); ?>%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                        <div class="col-md-3"><h4>Product Score</h4></div>
                        <div class="col-md-9 ">
                            <form action="<?php echo e(route('dashboard')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <label for="lob">Line of Business</label>
                                <select class="text-right" id="Productlob" name="productlob">
                                    <option value="all" <?php echo e((isset($old['productlob']) && $old['productlob']=='all')?'selected':''); ?>>All</option>
                                    <option value="collection" <?php echo e((isset($old['productlob']) && $old['productlob']=='collection')?'selected':''); ?>>IDFC First Collection</option>
                                    <option value="commercial_vehicle" <?php echo e((isset($old['productlob']) && $old['productlob']=='commercial_vehicle')?'selected':''); ?>>IDFC First Commercial Vehicle</option>
                                    <option value="rural" <?php echo e((isset($old['productlob']) && $old['productlob']=='all')?'rural':''); ?>>IDFC First Rural</option>
                                    <option value="alliance" <?php echo e((isset($old['productlob']) && $old['productlob']=='all')?'alliance':''); ?>>IDFC First Alliance</option>
                                </select>
                                <label for="audit_cycle">Audit Cycle</label>
                                <select class="text-right" id="product_audit_cycle" name="product_audit_cycle">
                                    <option value="current" <?php echo e((isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='current')?'selected':''); ?>>Current Audit Cycle</option>
                                    <option value="last_2" <?php echo e((isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='last_2')?'selected':''); ?>>Last 2 Audit Cycle</option>
                                    <option value="last_3" <?php echo e((isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='last_3')?'selected':''); ?>>Last 3 Audit Cycle</option>
                                    <option value="last_4" <?php echo e((isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='last_4')?'selected':''); ?>>Last 4 Audit Cycle</option>
                                    <option value="custom" <?php echo e((isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='custom')?'selected':''); ?>>Custom Audit Cycle</option>
                                </select>
                                <input type="text" style="<?php echo e((isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='custom')?'':'display:none;'); ?>" value="<?php echo e((isset($old['product_audit_cycle_custom']))? $old['product_audit_cycle_custom']:''); ?>" name="product_audit_cycle_custom" id="product_audit_cycle_custom"/>
                                <input type="submit" class="text-right" value="Show Result">
                            </form>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                        <div class="row" style="padding-bottom:10px!important">
                            <div class="col-md-8">
                                <span><strong>Top 4 Products</strong></span>
                                <a style="padding-left:30px;" class="cursor" onClick="showProduct();">SHOW ALL PRODUCTS</a>
                            </div>
                        </div>
                        <div class="row">
                           <?php $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                <div class="stat-heading"><?php echo e($item['lob'].'-'.$item['name']); ?></div>
                                                    <div class="stat-text"><span class="count"><?php echo e((round(($item['point']/$item['total'])*100,2))); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>National Score</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">Product</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="product" id="nationalProduct">
                                            <option value="all">All</option>
                                            <?php $__currentLoopData = $productList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </Select>
                                    </div>
                                    <div class="col-md-6">Line of Business </div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="lob" id="nationalLob">
                                            <option value="all" <?php echo e((isset($old['lob']) && $old['lob']=='all')?'selected':''); ?>>All</option>
                                            <option value="collection" <?php echo e((isset($old['lob']) && $old['lob']=='collection')?'selected':''); ?>>IDFC First Collection</option>
                                            <option value="commercial_vehicle" <?php echo e((isset($old['lob']) && $old['lob']=='commercial_vehicle')?'selected':''); ?>>IDFC First Commercial Vehicle</option>
                                            <option value="rural" <?php echo e((isset($old['lob']) && $old['lob']=='all')?'rural':''); ?>>IDFC First Rural</option>
                                            <option value="alliance" <?php echo e((isset($old['lob']) && $old['lob']=='all')?'alliance':''); ?>>IDFC First Alliance</option>
                                        </Select>
                                    </div>
                                    <div class="col-md-6">Zone</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="zone" id="nationalZone">
                                            <option value="all">All</option>
                                            <option value="2">East</option>
                                            <option value="3">West</option>
                                            <option value="1">North</option>
                                            <option value="4">South</option>
                                        </Select>
                                    </div>
                                    <div class="col-md-6">Audit Cycle</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="audit_cycle" id="nationalAudit_cycle">
                                            <option value="current">Current Audit Cycle</option>
                                            <option value="last_2">Last 2 Audit Cycle</option>
                                            <option value="last_3">Last 3 Audit Cycle</option>
                                            <option value="last_4">Last 4 Audit Cycle</option>
                                            <option value="custom">Custom Audit Cycle</option>
                                        </Select>
                                        <input type="text" class="form-control" style="display:none;" name="nationalAudit_cycle_custom" id="nationalAudit_cycle_custom"/>
                                    </div>
                                    <div class="col-md-6">State</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="state" id="nationalState">
                                            <option value="all">All</option>
                                        </Select>
                                    </div>
                                    <div class="col-md-6">Branch</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="branch" id="nationalBranch">
                                            <option value="all">All</option>
                                        </Select>
                                    </div>

                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <input type="button" value="Show Result" id="nationalResult"/>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-control text-center">
                                            <strong>Total Score: <span id="NationalTotal">0</span></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div id="map" style="height: 450px;"></div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(route('dashboard')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="lob">Product</label>
                                            <select class="form-control" name="filterProduct" id="filterProduct">
                                                <option value="all" <?php echo e((isset($old['filterProduct']) && $old['filterProduct']=='all')?'selected':''); ?>>All</option>
                                                <?php $__currentLoopData = $productList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($item->id); ?>" <?php echo e((isset($old['filterProduct']) && $old['filterProduct']==$item->id)?'selected':''); ?>><?php echo e($item->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="lob">Line of Business</label>
                                            <select class="form-control" name="filterlob" id="filterlob">
                                                <option value="all" <?php echo e((isset($old['filterlob']) && $old['filterlob']=='all')?'selected':''); ?>>All</option>
                                                <option value="collection" <?php echo e((isset($old['filterlob']) && $old['filterlob']=='collection')?'selected':''); ?>>IDFC First Collection</option>
                                                <option value="commercial_vehicle" <?php echo e((isset($old['filterlob']) && $old['filterlob']=='commercial_vehicle')?'selected':''); ?>>IDFC First Commercial Vehicle</option>
                                                <option value="rural" <?php echo e((isset($old['filterlob']) && $old['filterlob']=='all')?'rural':''); ?>>IDFC First Rural</option>
                                                <option value="alliance" <?php echo e((isset($old['filterlob']) && $old['filterlob']=='all')?'alliance':''); ?>>IDFC First Alliance</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="audot_cycle">Audit Cycle</label>
                                            <select class="form-control" name="filteraudit_cycle" id="filteraudit_cycle">
                                                <option <?php echo e((isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='All')?'selected':''); ?>>All</option>
                                                <option value="current" <?php echo e((isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='current')?'selected':''); ?>>Current Audit Cycle</option>
                                                <option value="last_2" <?php echo e((isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='last_2')?'selected':''); ?>>Last 2 Audit Cycle</option>
                                                <option value="last_3" <?php echo e((isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='last_3')?'selected':''); ?>>Last 3 Audit Cycle</option>
                                                <option value="last_4" <?php echo e((isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='last_4')?'selected':''); ?>>Last 4 Audit Cycle</option>
                                                <option value="custom" <?php echo e((isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='custom')?'selected':''); ?>>Custom Audit Cycle</option>
                                            </select>
                                            <input type="text" class="form-control" style="<?php echo e((isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='custom')?'':'display:none;'); ?>" value="<?php echo e((isset($old['filteraudit_cycle_custom']))? $old['filteraudit_cycle_custom']:''); ?>" name="filteraudit_cycle_custom" id="filteraudit_cycle_custom"/>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="zone">Zone</label>
                                            <select class="form-control" id="zone" name="filterzone">
                                                <option value="all" <?php echo e((isset($old['filterzone']) && $old['filterzone']=='all')?'selected':''); ?>>All</option>
                                                <option value="2" <?php echo e((isset($old['filterzone']) && $old['filterzone']=='2')?'selected':''); ?>>East</option>
                                                <option value="3" <?php echo e((isset($old['filterzone']) && $old['filterzone']=='3')?'selected':''); ?>>West</option>
                                                <option value="1" <?php echo e((isset($old['filterzone']) && $old['filterzone']=='1')?'selected':''); ?>>North</option>
                                                <option value="4" <?php echo e((isset($old['filterzone']) && $old['filterzone']=='4')?'selected':''); ?>>South</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="State">State</label>
                                            <select class="form-control" id="State" name="filterstate">
                                                <option value="all">All</option>

                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="branch">Branch</label>
                                            <select class="form-control" id="branch" name="filterbranch">
                                                <option value="all">All</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" class="mt-3" value="Apply Filters">
                                    <br><a href="#">Reset Filters</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5>Collection Manager’s Scorecard</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped  table-hover table-checkable">
                            <thead style="background-color: rgba(0,0,0,.03);">
                                <tr>
                                    <th>Collection Manager Name</th>
                                    <th>Current Score</th>
                                    <th>Previous Score</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $filterData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                <td><?php echo e($item['name']); ?></td>
                                <td><?php echo e(round((($item['point']/$item['total'])*100),2)); ?>%</td>
                                    <td>0</td>
                                <td><a style="cursor: pointer;" class="filterShow" data-id="<?php echo e($k); ?>" data-name="<?php echo e($item['name']); ?>">Show Details</a></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <div>
                            
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Bottom 10 Collection Managers</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $topCollectionManager['bottom']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($item['name']); ?></td>
                                                <td><?php echo e(round((($item['point']/$item['total'])*100),2)); ?>%</td>
                                                <td>0</td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Top 10 Collection Managers</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $topCollectionManager['top']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['name']); ?></td>
                                            <td><?php echo e(round((($item['point']/$item['total'])*100),2)); ?>%</td>
                                            <td>0</td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Bottom 10 Parameters of Product</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $bottomProductParameter['bottom']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['name']); ?></td>
                                            <td><?php echo e($item['point']); ?></td>
                                            <td>0</td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Top 10 Parameters of Product</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $bottomProductParameter['top']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['name']); ?></td>
                                            <td><?php echo e($item['point']); ?></td>
                                            <td>0</td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Bottom 10 Agencies</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $topAgency['bottom']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['name']); ?></td>
                                            <td><?php echo e(round((($item['point']/$item['total'])*100),2)); ?>%</td>
                                            <td>0</td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Top 10 Agencies</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $topAgency['top']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['name']); ?></td>
                                            <td><?php echo e(round((($item['point']/$item['total'])*100),2)); ?>%</td>
                                            <td>0</td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Modal - Calendar - Add Category -->
                <div class="modal  bd-example-modal-lg fade none-border" id="add-category">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title text-center"><strong id="title"></strong></h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-striped  table-hover table-checkable">
                                            <thead style="background-color: rgba(0,0,0,.03);">
                                                <tr>
                                                    <th>Agency Name</th>
                                                    <th>Score</th>
                                                </tr>
                                            </thead>
                                            <tbody id="agencyData">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-striped  table-hover table-checkable tableParameter" style="display:none">
                                            <thead style="background-color: rgba(0,0,0,.03);">
                                                <tr>
                                                    <th>Parameter Name</th>
                                                    <th>Score</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="parameterdata">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="pareto"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal fade none-border" id="add-product">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title text-center"><strong>Parameters </strong></h4>
                            </div>
                            <div class="modal-body">
                                <h4 class="mb-3">pareto Chart</h4>
                                <div class="flot-container">
                                    <div id="pareto" style="width:100%;height:275px;"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade none-border" id="show-product">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <div class="modal-title text-center">
                                    <span class="float-right">Total Product: <span id="totalPoint"></span></span>
                                    <strong><h4>ALL PRODUCT SCORE</h4></strong>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="productBack">

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                
                            </div>
                        </div>
                    </div>
                </div>
            <!-- /#add-category -->

            <div class="modal fade none-border" id="showStateBranch">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title text-center"><strong id="stateName"></strong><strong class="float-right">State Score:<span id="stateTotal"></span></strong></h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped  table-hover table-checkable">
                                <thead style="background-color: rgba(0,0,0,.03);">
                                    <tr>
                                        <th>Name</th>
                                        <th>Previous Score</th>
                                        <th>Current Score</th>
                                    </tr>
                                </thead>
                                <tbody id="showStateBranchBody">
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                            
                        </div>
                    </div>
                </div>
            </div>

            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->
        <div class="clearfix"></div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
<script src="<?php echo e(URL::asset('public/js/highmaps.js')); ?>"></script>
<script src="<?php echo e(URL::asset('public/js/exporting.js')); ?>"></script>
<script src="<?php echo e(URL::asset('public/js/in-all.js')); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="<?php echo e(URL::asset('public/js/dashboard.js')); ?>"></script>
<script src="https://code.highcharts.com/modules/pareto.js"></script>
<script>
    jQuery(document).ready(function() {
        indiaMap([]);
        // jQuery('#add-product').modal('show')
        // pareto();
        })
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/audit/resources/views/dashboard.blade.php ENDPATH**/ ?>