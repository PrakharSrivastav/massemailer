<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu">
            <li class=""><a class="" href="<?php echo base_url(); ?>masteradmin/master_admin_dashboard"> <i class="fa fa-dashboard"></i> <span>My Dashboard</span> </a></li>
            <li class="sub-menu">
                <a href="javascript:;" class=""> <i class="icon_document_alt"></i> <span>Group Admin</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
                <ul class="sub">
                    <li><a class="" href="<?php echo base_url(); ?>masteradmin/create_group_admin">Create</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>masteradmin/edit_group_admin">Edit</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>masteradmin/delete_group_admin">Delete</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="javascript:;" class=""> <i class="icon_desktop"></i> <span>Templates</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
                <ul class="sub">
                    <li><a class="" href="<?php echo base_url(); ?>templatecontroller/upload_attachments">Upload HTML</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>templatecontroller/show_create_template_page">Create template</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>templatecontroller/show_manage_template_page">Manage template</a></li>
                </ul>
            </li>
            <li class=""><a class="" href="<?php echo base_url(); ?>masteradmin/show_create_list_form"><i class="fa fa-list"></i>Manage Lists</a></li>
          <!--<li><a class="" href="<?php //echo base_url(); ?>masteradmin/get_reports"> <i class="fa fa-bar-chart-o"></i> <span>Reports</span> </a></li>-->
          <li class="sub-menu">
	         <a href="javascript:;" class=""> <i class="fa fa-bar-chart-o"></i> <span>Available Reports</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
	         <ul class="sub">
		         <li><a class="" href="<?php echo base_url(); ?>masteradmin/get_quota_reports">Quota reports</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>masteradmin/get_campaign_reports">Campaign reports</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>masteradmin/get_list_reports">List reports</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>masteradmin/current_report">Current reports</a></li>
	         </ul>
         </li>
          <li><a class="" href="<?php echo base_url(); ?>masteradmin/manage_queues"> <i class="fa fa-bars"></i> <span>Manage Queue</span> </a></li>
         <li class="sub-menu">
	         <a href="javascript:;" class=""> <i class="fa fa-gear"></i> <span>Profile settings</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
	         <ul class="sub">
		         <li><a class="" href="<?php echo base_url(); ?>authentication/show_edit_password_form">Edit password</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>authentication/show_edit_user_details_form">Edit Personal details</a></li>
	         </ul>
         </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
