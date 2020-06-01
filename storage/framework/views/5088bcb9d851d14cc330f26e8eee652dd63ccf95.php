<!-- Left Panel -->
<style>
    aside.left-panel{
        background: #2f353a;
        height:95%
    }
    .navbar .main-menu {
        padding:0px !important;
    }
    .navbar .navbar-nav li > a {
        color: #FFF;
    }
    .navbar .navbar-nav > li.active {
        /* background: #fafafa; */
        background: #3a4248;
       }
    .navbar .navbar-nav li.active .menu-icon, .navbar .navbar-nav li:hover .toggle_nav_button:before,
    .navbar .navbar-nav li .toggle_nav_button.nav-open:before {
    color: #fff ; }
    .navbar .navbar-nav li > a .menu-icon{
        color: #fff ;
    }
    .navbar .navbar-nav li.menu-item-has-children a:before{
    
    border-color: #fff #fff transparent transparent;
    }
    .navbar .navbar-nav li > a:hover, .navbar .navbar-nav li > a:hover .menu-icon {
        /* background: #20a8d8; */
        color:#20a8d8; 
    }
    .navbar .navbar-nav li:hover{
        /* background: #20a8d8; */
        color:#20a8d8;
    }
    .navbar .navbar-nav li.menu-item-has-children .sub-menu {
        background: #3a4248;
        padding: 0 0 0 25px;
        color:#fff;
        margin:0
    }
    .right-panel .navbar-brand img{
        max-width: 130px;

    }
    .right-panel header.header {
        height: 60px;

    }
    .navbar .navbar-nav li.menu-item-has-children.show .sub-menu{
        background:none
    }
    .nav .navbar-nav{
        
    }
    body {
        font-family: -apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,sans-serif,apple color emoji,segoe ui emoji,segoe ui symbol,noto color emoji;
    font-size: .875rem;
    font-weight: 400;
    line-height: 1.5;
    /* color: #23282c; */
    }
    .content{
        background-color: #e4e5e6;
    }
    .btn-primary {
        color: #fff !important;
        background-color: #20a8d8 !important;
        border-color: #20a8d8 !important;
    }
    .btn-danger {
        color: #fff !important;
        background-color: #f86c6b !important;
        border-color: #f86c6b !important;
    }
    .btn-sm, .btn-group-sm>.btn {
    padding: .25rem .5rem;
    font-size: .765625rem;
    line-height: 2.5;
    border-radius: .2rem;
    }
</style>
<aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav"  style="background: #2f353a;">
                    <?php if(auth()->check() && auth()->user()->hasAnyRole('Collection Manager')): ?>
                    <li>
                        <a href="<?php echo e(route('dashboard')); ?>"><i class="menu-icon fa fa-laptop"></i>Dashboard </a>
                    </li>
                    <?php else: ?>
                    <li class="active">
                        <a href="<?php echo e(route('dashboard')); ?>">
                        <i class="menu-icon fa fa-laptop"></i>Dashboard 
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(auth()->check() && auth()->user()->hasRole('Admin')): ?>
                    
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Users</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('user.create')); ?>">Create User</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('user.index')); ?>">User List</a></li>
                            
                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Products</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('product.create')); ?>">Create Product</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('product.index')); ?>">Product List</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('Hierarchy')); ?>">Product Hierarchy</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('HierarchyView')); ?>">Product Hierarchy View</a></li>

                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Branches</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('branch.create')); ?>">Create Branch</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('branch.index')); ?>">Branch List</a></li>

                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Branch Repo</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('branchrepo.create')); ?>">Create Branch Repo</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('branchrepo.index')); ?>">Branch Repo List</a></li>

                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Agency</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('agency.create')); ?>">Create Agency</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('agency.index')); ?>">Agency List</a></li>
                            
                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Agency Repo</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('agencyrepo.create')); ?>">Create Agency Repo</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('agencyrepo.index')); ?>">Agency Repo List</a></li>

                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Yard</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('yard.create')); ?>">Create Yard</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('yard.index')); ?>">Yard List</a></li>

                        </ul>
                    </li>
                    <!-- <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Yard Repo</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('yardrepo.create')); ?>">Create Yard Repo</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('yardrepo.index')); ?>">Yard Repo List</a></li>

                        </ul>
                    </li> -->
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Analytics</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('upload.index')); ?>">upload</a></li>
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('gapShow')); ?>">Compliance</a></li>
                            <!-- <li><i class="fa fa-users"></i><a href="<?php echo e(route('yard.index')); ?>">Yard List</a></li> -->

                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Bulk Upload</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('bulkUpload.index')); ?>">Bulk Upload</a></li>
                            
                            

                        </ul>
                    </li>
                    
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Qm Sheet</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('qm_sheet.create')); ?>">Create Qm Sheet</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('qm_sheet.index')); ?>">Qm Sheet List</a></li>
                            
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(auth()->check() && auth()->user()->hasAnyRole('Admin|Quality Auditor')): ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Beat Plan</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('beat_plan.create')); ?>">Create Beat plan</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('beat_plan.index')); ?>">Beat plan List</a></li>
                            
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(auth()->check() && auth()->user()->hasRole('Admin')): ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Allocation</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            <li><i class="fa fa-plus"></i><a href="<?php echo e(route('allocation.create')); ?>">Allocation sheet</a></li>
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('allocation.index')); ?>">Allocated sheets List</a></li>
                            
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(auth()->check() && auth()->user()->hasAnyRole('Admin|Quality Control')): ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>QC</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('audited_list')); ?>">QC List</a></li>  
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('done_audited_list')); ?>">ALL QC List</a></li>  
                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Action plan</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('action.index')); ?>">Action plan</a></li>  
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('action-list')); ?>">Action plan Answer List</a></li>  
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(auth()->check() && auth()->user()->hasRole('Admin')): ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Red Alert</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('red-alert.index')); ?>">Red Alert</a></li>  
                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Artifact</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('artifact.index')); ?>">Artifact List</a></li>  
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(auth()->check() && auth()->user()->hasAnyRole('Quality Auditor')): ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-user"></i>Audit Sheet</a>
                        <ul class="sub-menu children dropdown-menu">                         
                            
                            <li><i class="fa fa-users"></i><a href="<?php echo e(route('auditor_list')); ?>">Audit Sheet List</a></li>  
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>
    <!-- /#left-panel --><?php /**PATH /var/www/html/audit/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>