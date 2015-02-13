<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
            	<!-- Copy from here -->
            	<div class="row" style="font-size:25px; padding:0px 0px 15px 20px;">
            		<span class="label label-danger">create list <i class="glyphicon glyphicon-arrow-right"></i></span> 
            		<span class="label label-danger">create template <i class="glyphicon glyphicon-arrow-right"></i></span>
            		<span class="label label-danger">create te campaign <i class="glyphicon glyphicon-arrow-right"></i></span>
            		<span class="label label-danger">test the email <i class="glyphicon glyphicon-arrow-right"></i></span>
            		<span class="label label-danger">send <i class="glyphicon glyphicon-arrow-right"></i></span>
            		<span class="label label-danger">qmanager <i class="glyphicon glyphicon-arrow-right"></i></span>
            		<span class="label label-danger">report</span>
            	</div>
            	<!-- till here -->
            	<ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>users/users_dashboard">Home</a></li>
                    <li><i class="fa fa-laptop"></i>Dashboard</li>						  	
                </ol>
            </div>
        </div>
        <!-- page start-->
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box  blue-bg">
                <i class="fa fa-send"></i>
                <div class="count"><?php echo $campaigns; ?></div>
                <div class="title">Total Campaigns sent this month</div>						
            </div><!--/.info-box-->			
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box blue-bg">
                <i class="fa fa-list"></i>
                <div class="count"><?php echo $lists; ?></div>
                <div class="title">Total lists (shared/owned)</div>						
            </div><!--/.info-box-->			
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box blue-bg">
                <i class="fa fa-list"></i>
                <div class="count"><?php echo $quota_available; ?></div>
                <div class="title">Available quota</div>						
            </div><!--/.info-box-->			
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->