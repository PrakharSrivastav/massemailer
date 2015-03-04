<!--sidebar start--><aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu">
            <li class=""><a class="" href="<?php echo base_url(); ?>groupadmin/group_admin_dashboard"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
            <li class="sub-menu">
                <a href="javascript:;" class=""><i class="icon_group"></i><span>Manage Users</span><span class="menu-arrow arrow_carrot-right"></span> </a>
                <ul class="sub">
                    <li><a class="" href="<?php echo base_url(); ?>groupadmin/show_create_user_form">Create Users</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>groupadmin/show_edit_user_form">Edit Users</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>groupadmin/show_delete_user_form">Delete Users</a></li>
                </ul>
            </li>
            <li class=""><a class="" href="<?php echo base_url(); ?>listcontroller/show_create_list_form"><i class="fa fa-list"></i>Manage Lists</a></li>
            <li class="sub-menu">
                <a href="javascript:;" class=""> <i class="icon_desktop"></i> <span>Templates</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
                <ul class="sub">
                    <li><a class="" href="<?php echo base_url(); ?>templatecontroller/upload_attachments">Upload HTML</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>templatecontroller/show_create_template_page">Create template</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>templatecontroller/show_manage_template_page">Manage template</a></li>
                </ul>
            </li>
           <!--
            <li><a class="" href="<?php echo base_url(); ?>groupadmin/get_reports"><i class="fa fa-bar-chart-o"></i><span>Reports</span></a></li>
           -->
           <li class="sub-menu">
	         <a href="javascript:;" class=""> <i class="fa fa-bar-chart-o"></i> <span>Available Reports</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
	         <ul class="sub">
		         <li><a class="" href="<?php echo base_url(); ?>groupadmin/get_quota_reports">Quota reports</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>groupadmin/get_campaign_reports">Campaign reports</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>groupadmin/get_list_reports">List reports</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>groupadmin/current_report">Current reports</a></li>
	         </ul>
         </li>
           <li><a class="" href="<?php echo base_url(); ?>groupadmin/manage_queues"> <i class="fa fa-bars"></i> <span>Manage Queue</span> </a></li>
           <!-- <li><a class="" href=""><i class="icon_info_alt"></i><span>Notifications</span></a></li>-->
            <li class="sub-menu">
                <a href="javascript:;" class=""><i class="fa fa-gear"></i><span>Manage Profile</span><span class="menu-arrow arrow_carrot-right"></span></a>
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
