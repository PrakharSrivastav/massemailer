<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
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
        <!-- page end-->
    </section>
</section>
<!--main content end-->