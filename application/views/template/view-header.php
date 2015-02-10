<!--header start-->
<header class="header dark-bg">
	<div class="toggle-nav">
		<div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"></div>
	</div>
	<!--logo start-->
	<!--<a href="<?php echo base_url(); ?>" class="logo">Email <span class="lite">Manager</span></a>-->
	<!--logo end-->
	
	
	<div class="nav" id="header-logout-btn">
		<a class="btn btn-danger"  href="<?php echo base_url();?>authentication/logout">Log Out</a>
	</div>
	
	<div >
		<span class="pull-left" id="welcome-note" ><h4><i class="fa fa-circle-o-notch"> </i> Welcome : <?php echo $this->session->userdata("name"); ?></h4></span>
	</div>
	<div >
		<span class="pull-left" id="welcome-note" style="color:yellow"><h4><i class="fa fa-clock-o"> </i>  : <?php $dt = new DateTime(); echo $dt->format("Y-m-d H:I:s"); ?> <small style="color:yellow">Refresh to see the current time</small></h4></span>
	</div>
</header>
<!--header end-->
