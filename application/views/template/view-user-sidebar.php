<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu">
            <li class=""><a class="" href="<?php echo base_url(); ?>users/users_dashboard"><i class="icon_house_alt"></i><span>User Dashboard</span></a></li>
            <li class=""><a class="" href="<?php echo base_url(); ?>users/show_create_list_form"><i class="fa fa-list"></i>Manage Lists</a></li>
            <li class="sub-menu">
                <a href="javascript:;" class=""> <i class="icon_desktop"></i> <span>Templates</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
                <ul class="sub">
                    <li><a class="" href="<?php echo base_url(); ?>templatecontroller/upload_attachments">Upload HTML</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>templatecontroller/show_create_template_page">Create template</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>templatecontroller/show_manage_template_page">Manage template</a></li>
                </ul>
            </li>
            <li><a class="" href="<?php echo base_url(); ?>campaigncontroller/show_create_campaign_page"><i class="fa fa-send"></i>My Campaigns</a></li>
            <!-- <li><a class="" href="<?php //echo base_url(); ?>users/get_reports"> <i class="icon_piechart"></i> <span>Reports</span> </a></li>
             -->
         <li class="sub-menu">
	         <a href="javascript:;" class=""> <i class="fa fa-bar-chart-o"></i> <span>Available Reports</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
	         <ul class="sub">
		         <li><a class="" href="<?php echo base_url(); ?>users/get_quota_reports">Quota reports</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>users/get_campaign_reports">Campaign reports</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>users/get_list_reports">List reports</a></li>
		         <li><a class="" href="<?php echo base_url(); ?>users/current_report">Current reports</a></li>
	         </ul>
         </li>
            <li><a class="" href="<?php echo base_url(); ?>users/manage_queues"> <i class="fa fa-bars"></i> <span>Manage Queue</span> </a></li>
            <li class="sub-menu">
                <a href="javascript:;" class=""><i class="fa fa-gear"></i><span>Manage Profile</span><span class="menu-arrow arrow_carrot-right"></span></a>
                <ul class="sub">
                    <li><a class="" href="<?php echo base_url(); ?>authentication/show_edit_password_form">Edit password</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>authentication/show_edit_user_details_form">Edit Personal details</a></li>
                    <li><a class="" href="<?php echo base_url(); ?>users/edit_smtp_settings">Edit SMTP details</a></li>
                </ul>
            </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
