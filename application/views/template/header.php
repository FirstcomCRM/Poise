<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>POISE</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
	
	<link rel="icon" href="<?=base_url();?>images/logo_boshi_navyblue.png" type="image/x-icon">
    <link rel="stylesheet" href="<?=base_url()?>bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <!--link rel="stylesheet" href="<?=base_url()?>dist/css/AdminLTE.min.css"-->
    <link rel="stylesheet" href="<?=base_url()?>dist/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?=base_url()?>dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	
	
	<link rel="stylesheet" href="<?=base_url()?>/css/css/jquery.fileupload.css">
<link rel="stylesheet" href="<?=base_url()?>/css/css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="<?=base_url()?>/css/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="<?=base_url()?>/css/css/jquery.fileupload-ui-noscript.css"></noscript>
	

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script src="<?= base_url(); ?>bootstrap/js/jquery-1.11.1.js"></script>
	<script src="<?= base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
	<script src="<?= base_url(); ?>bootstrap/js/jquery-1.11.1.js"></script>
	<script src="<?= base_url(); ?>js/common-scripts.js"></script>
	
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
		 <a href="index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>POISE</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>POISE</b></span>
        </a>
		<!--div style = "background-color:#fff;">
        <!--a href="index.html" class="navbar-brand"><img class = "img-responsive" src="<?=base_url();?>images/logo_boshi_navyblue.png" alt="" width="100" height="40"></a-->
        <!--/div-->
		<!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="<?=base_url()?>#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
             
              <!-- Notifications: style can be found in dropdown.less -->
			 
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?= base_url().$this->session->userdata('user_img'); ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?= $this->session->userdata('name'); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?= base_url()?>dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    <p>
                      <?= $this->session->userdata('name'); ?>
                    </p>
                  </li>
           
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <!--div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div-->
                    <div class="pull-right">
                      <a href="<?= base_url().'logout'?>" class="btn btn-default btn-flat">Log Out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?= base_url().$this->session->userdata('user_img'); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?= $this->session->userdata('name'); ?></p>
              <!--a href="<?=base_url()?>#"><i class="fa fa-circle text-success"></i> Online</a-->
            </div>
          </div>
          <!-- search form -->
          <!--form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form-->
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= ($nav_area == 'dashboard') ? 'active' : ''; ?> treeview">
             <a href="<?= base_url().'dashboard'; ?>">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
              <!--ul class="treeview-menu">
                <li class="active"><a href="<?=base_url()?>index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
                <li><a href="<?=base_url()?>index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
              </ul-->
            </li>
            <li class="<?= ($nav_area == 'events') ? 'active' : ''; ?> treeview">
              <a href="<?= base_url().'events'; ?>">
                <i class="fa fa-calendar"></i>
                <span>Events</span>
                <!--span class="label label-primary pull-right">4</span-->
              </a>
            </li>
            <li class="<?= ($nav_area == 'announcement') ? 'active' : ''; ?> treeview">
              <a href="<?= base_url().'announcement'; ?>">
                <i class="fa fa-th"></i> <span>Announcement</span> 
				<!--small class="label pull-right bg-green">new</small-->
              </a>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>Project</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= base_url().'project'; ?>"><i class="fa fa-circle-o"></i> Project</a></li>
                <li><a href="<?= base_url().'property'; ?>"><i class="fa fa-circle-o"></i> Property</a></li>
                <li><a href="<?= base_url().'transaction'; ?>"><i class="fa fa-circle-o"></i> Property Transaction</a></li>
                <li><a href="<?= base_url().'invoice'; ?>"><i class="fa fa-circle-o"></i> Invoice</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="<?=base_url().'form_agreement';?>">
                <i class="fa fa-laptop"></i>
                <span>Forms and Agreement</span>
                <!--i class="fa fa-angle-left pull-right"></i-->
              </a>
              <!--ul class="treeview-menu">
                <li><a href="<?=base_url()?>pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
                <li><a href="<?=base_url()?>pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
                <li><a href="<?=base_url()?>pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
                <li><a href="<?=base_url()?>pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
                <li><a href="<?=base_url()?>pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
                <li><a href="<?=base_url()?>pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
              </ul-->
            </li>
            <li class="treeview">
              <a href="<?=base_url()?>#">
                <i class="fa fa-edit"></i> <span>Reports</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= base_url().'report/sales_performance'; ?>"><i class="fa fa-circle-o"></i> Sales Performance</a></li>
                <li><a href="<?= base_url().'report/sales_report'; ?>"><i class="fa fa-circle-o"></i> Daily/Monthly/Yearly Sales Report</a></li>
                <li><a href="<?= base_url().'report/top_sales'; ?>"><i class="fa fa-circle-o"></i> Top Sales</a></li>
                <li><a href="<?= base_url().'report/commission_report'; ?>"><i class="fa fa-circle-o"></i> Commission Report</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="<?=base_url()?>#">
                <i class="fa fa-table"></i> <span>User</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= base_url().'role'; ?>"><i class="fa fa-circle-o"></i> Role</a></li>
                <li><a href="<?= base_url().'user'; ?>"><i class="fa fa-circle-o"></i> User</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="<?=base_url()?>#">
                <i class="fa fa-table"></i> <span>Setup</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= base_url().'team'; ?>"><i class="fa fa-circle-o"></i> Team Management</a></li>
                <li><a href="<?= base_url().'user'; ?>"><i class="fa fa-circle-o"></i> Tier Commission Management</a></li>
                <li><a href="<?= base_url().'permission'; ?>"><i class="fa fa-circle-o"></i> User Rights Management</a></li>
                <li><a href="#"><i class="fa fa-circle-o"></i> Project Management</a></li>
                <li><a href="#"><i class="fa fa-circle-o"></i> Management Contact</a></li>
                <li><a href="<?= base_url().'form_category'; ?>"><i class="fa fa-circle-o"></i> Form and Agreement<br> Category Management</a></li>
              </ul>
            </li>
           
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
	  <div class="content-wrapper">