<?php
$logged_in = $this -> session -> userdata("is_logged_in");
$user_role = $this -> session -> userdata("user_role");

$pause_url = "";
$start_url = "";
if ($logged_in && $user_role === "1") {
	$pause_url = base_url() . "masteradmin/stop_campaign";
	$start_url = base_url() . "masteradmin/start_campaign";
} else if ($logged_in && $user_role === "2") {
	$pause_url = base_url() . "groupadmin/stop_campaign";
	$start_url = base_url() . "groupadmin/start_campaign";
} else if ($logged_in && $user_role === "3") {
	$pause_url = base_url() . "users/stop_campaign";
	$start_url = base_url() . "users/start_campaign";
}
?><!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>users/users_dashboard">Home</a></li>
                    <li><i class="fa fa-send"></i>Manage Queues</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12 panel-group m-bot20" id="accordion">
            <div class="panel panel-primary row" >
                <div class="panel-heading">
                    <header>
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#setup_send_campaign"> 
                            Manage your queues
                        </a>
                    </header>
                 </div>   
                <section class="panel panel-content panel-collapse collapse in" id="setup_send_campaign" >
                    <?php if($this->session->userdata("user_role")==="3") { ?>
                    <div class="pull-right">
                    	<form action="<?php echo base_url().'campaigncontroller/abort_campaigns';?>" method="post">
                    		<input type="hidden" name="abort" value="abort">
                    		<input type="submit" class="btn btn-danger" value="Abort all queued emails">
                    	</form>
                    </div>
                    <?php } ?>
                    <br /><br />
                    	<div class="table-responsive">
                    		<table class="table table-bordered">
                    			<tr>
                    				<th>Subject</th>
                    				<th>Sender</th>
                    				<th>List</th>
                    				<th>Total</th>
                    				<th>Sent</th>
                    				<th>Status</th>
                    				<th>Stop</th>
                    				<th>Resume</th>
                    				<th>Email</th>
                    				<th>Start Time</th>
                    				<th>End Time</th>
                    			</tr>
                    			<?php  if(isset($queues) && count($queues)>0){ ?>
                    			<?php  foreach ($queues as $queue): ?>
                    			<?php
									$total = $queue['quota'];
									if (unserialize($queue['sent_emails']) === false)
										$total_sent = 0;
									else
										$total_sent = count(unserialize($queue['sent_emails']));
									$status = "";
									if ($queue['progress'] === "1")
										$status = "Queued";
									else if ($queue['progress'] === "2")
										$status = "Processing";
									else if ($queue['progress'] === "3")
										$status = "Sent";
									else if ($queue['progress'] === "4")
										$status = "Paused";
									else if ($queue['progress'] === "5")
										$status = "Aborted";
                    			?>
								<?php  if($queue['progress'] != '3'){ ?>
					           	<tr style="background:#FF9966">
					           		<td><small style="color:black;"><?php echo $queue['subject']; ?></small></td>
									<td><small style="color:black;"><?php echo $queue['sender_name']; ?></small></td>
									<td><?php echo $queue['list_name']; ?></td>
									<td><?php echo $total; ?></td>
									<td><?php echo $total_sent; ?></td>
									<td><?php echo $status; ?></td>
									<td class="text-center"><a href="<?php echo $pause_url."/".$queue['id']; ?>"><span class="fa fa-pause"></span></a></td>
									<td class="text-center"><a href="<?php echo $start_url."/".$queue['id']; ?>"><span class="fa fa-play"></span></a></td>
									<td><?php echo $queue['email']; ?></td>
									<td><?php echo $queue['start_time']; ?></td>
									<td><?php echo $queue['send_time']; ?></td>
								</tr>
					           	<?php  } //else if($queue['progress'] == '3'){ ?>
					           	<!--<tr style="background:#66FF66">
					           		<td><?php //echo $queue['subject']; ?></td>
									<td><?php //echo $queue['sender_name']; ?></td>
									<td><?php //echo $queue['email']; ?></td>
									<td><?php //echo $queue['list_name']; ?></td>
									<td><?php //echo $total; ?></td>
									<td><?php //echo $total_sent; ?></td>
									<td><?php //echo $status; ?></td>
									<td></td>
									<td></td>
								</tr>-->
					           	<?php  //} ?>	
								<?php  endforeach;} ?>
                    		</table>
                    	</div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<script></script>
<!--main content end-->
