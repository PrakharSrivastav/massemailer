<?php
/*
$logged_in = $this -> session -> userdata("is_logged_in");
$user_role = $this -> session -> userdata("user_role");

$quota_url = "";
$list_url = "";
$camp_url = "";
$curr_url = "";
if ($logged_in && $user_role === "1") {
	$quota_url = base_url() . "masteradmin/get_quota_reports";
	$list_url = base_url() . "masteradmin/get_campaign_reports";
	$camp_url = base_url() . "masteradmin/get_list_reports";
	$curr_url = base_url() . "masteradmin/current_report";
} else if ($logged_in && $user_role === "2") {
	$quota_url = base_url() . "groupadmin/get_quota_reports";
	$list_url = base_url() . "groupadmin/get_campaign_reports";
	$camp_url = base_url() . "groupadmin/get_list_reports";
	$curr_url = base_url() . "groupadmin/current_report";
} else if ($logged_in && $user_role === "3") {
	$quota_url = base_url() . "users/get_quota_reports";
	$list_url = base_url() . "users/get_campaign_reports";
	$camp_url = base_url() . "users/get_list_reports";
	$curr_url = base_url() . "users/current_report";
}*/

?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <!--
                        <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>masteradmin/master_admin_dashboard">Home</a></li>
                    
                    -->
                    <li><i class="fa fa-bar-chart-o"></i>Reports</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <!--accordin start-->
        <!--
        <div class="col-sm-12 col-lg-3 col-md-12 col-xs-12 panel-group m-bot20" id="accordion">
                    <div class="panel panel-primary">
                        <div class="panel-heading" >
                            <h4 class="panel-title" style="background-color: #5dc3e7"><a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> Available reports </a></h4>
                        </div>
                        <section style="padding:5px" id="collapseOne" class="panel panel-collapse collapse in">
                            <br />
                            <a href="<?php //echo $quota_url; ?>" class="btn btn-block input-sm btn-send">Get Quota History</a>
                            <a href="<?php //echo $list_url; ?>" class="btn btn-block input-sm btn-send">Get Campaign History</a>
                            <a href="<?php //echo $camp_url; ?>" class="btn btn-block input-sm btn-send">Get List Report</a>
                            <a href="<?php //echo $curr_url; ?>" class="btn btn-block input-sm btn-send">Get Current Status</a>
                        </section>
                    </div>
                </div>
        -->
        
        <?php if (isset($quota_details) && count($quota_details)>0) {  ?>
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
        	<div class ="panel panel-primary">
        		<div class="panel-heading" style="padding-top: 5px;">
        			<h4>Quota reports</h4>
        		</div>
        	<div class="table-responsive">
        	<table style="width:100%;color:black" class="table-bordered">
        		<thead>
        			<tr>
        				<th>Event Name</th>
        				<th>Quota Assigned</th>
        				<th>Quota Details</th>
        				<th>Assigned By</th>
        				<th>Assigned To</th>
        				<th>Extra Info</th>
        				<th>Transaction Date</th>
        			</tr>
					<?php  foreach ($quota_details as $event): ?>
		           	<tr>
		           		<td><?php echo $event['e_name']; ?></td>
		           		<td><?php echo $event['e_amount']; ?></td>
		           		<td><?php echo $event['e_details']; ?></td>
		           		<td><?php echo $event['e_user_by']; ?></td>
		           		<td><?php echo $event['e_user_to']; ?></td>
		           		<td><?php echo $event['e_info']; ?></td>
		           		<td><?php echo $event['e_date']; ?></td>
		           	</tr>		
					<?php  endforeach; ?>
				</thead>    
			</table>
			</div>
			</div
        ></div>
        <?php }if (isset($campaign_details)) { $this->session->set_userdata(array("camp"=>"")); ?>
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
            <div class ="panel panel-primary">
        		<div class="panel-heading" style="padding-top: 5px;">
        			<h4>Campaign reports</h4>
        		</div>
        	<div class="table-responsive">
            <?php $first = 1; ?>
        	<table style="color:black" class="table table-advance table-bordered">
        		<thead>
        			<!--
					<tr>
											<th>List name</th>
											<th>Status</th>
											<th>Total</th>
											<th>Total Sent</th>
											<th>Hardbounce</th>
											<th>Softbounce	</th>
											<th>Spam</th>
											<th>Clicks</th>
											<th>Rejects</th>
											<th>Open</th>
											<th>Start Time</th>
											<th>End Time</th>
										</tr>-->
					
					<?php  foreach ($campaign_details as $event): ?>
					
					<?php
					$smtp = unserialize($event['smtp_details']);
					if (unserialize($event['subscriber_ids']) === false)
						$total = 0;
					else
						$total = count(unserialize($event['subscriber_ids']));
					if (unserialize($event['bounce_id']) === false)
						$h_bounce = 0;
					else
						$h_bounce = count(unserialize($event['bounce_id']));
					// var_dump((unserialize($event['bounce_id'])));
					if (unserialize($event['soft_bounce_id']) === false)
						$s_bounce = 0;
					else
						$s_bounce = count(unserialize($event['soft_bounce_id']));
					if (unserialize($event['spam_id']) === FALSE)
						$spam = 0;
					else
						$spam = count(unserialize($event['spam_id']));
					if (unserialize($event['click_id']) === FALSE)
						$click = 0;
					else
						$click = count(unserialize($event['click_id']));
					if (unserialize($event['reject_id']) === FALSE)
						$reject = 0;
					else
						$reject = count(unserialize($event['reject_id']));
					if (unserialize($event['open_id']) === FALSE)
						$open = 0;
					else
						$open = count(unserialize($event['open_id']));
					if (unserialize($event['sent_emails']) === false)
						$total_sent = 0;
					else
						$total_sent = count(unserialize($event['sent_emails']));
						
					if (isset($event['progress']) && $event['progress'] == '1')
						$progress = "Queued";
					else if (isset($event['progress']) && $event['progress'] == '2')
						$progress = "Processing";
					else if (isset($event['progress']) && $event['progress'] == '3')
						$progress = "Sent";
					else if (isset($event['progress']) && $event['progress'] == '4')
						$progress = "Paused";
					
						
					if($this->session->userdata("camp") !== $event['campaign_id']){
						$camp_name = $event['subject'];
						//print_r($camp_name);
						$this->session->set_userdata(array("camp"=>$event['campaign_id']));
					}
					else {
						$camp_name ="";
						$send_time = $event['send_time'];
					}
					?>
                    <?php
					if(!empty($camp_name) && $first == 0){
					?>
		           	<tr style="background:#ccc">
		           	  <td>Total</td>
		           	  <td>&nbsp;-</td>
		           	  <td><?=$totalctr;?></td>
		           	  <td><?=$sentctr;?></td>
		           	  <td><?=$hardctr;?></td>
		           	  <td><?=$softctr;?></td>
		           	  <td><?=$spamctr;?></td>
		           	  <td><?=$clickctr;?></td>
		           	  <td><?=$rejectctr;?></td>
		           	  <td><?=$openctr;?></td>
		           	  <td><?=$start_time;?></td>
		           	  <td><?=$send_time;?></td>
	           	  	</tr>
		           	<tr>
		           	  <td colspan="12">&nbsp;</td>
		           	</tr>
					<?php
					}
					if(!empty($camp_name)){
						$totalctr = 0;
						$hardctr = 0;
						$softctr = 0;
						$spamctr = 0;
						$clickctr = 0;
						$rejectctr = 0;
						$openctr = 0;	
						$sentctr = 0;
						$start_time = $event['start_time'];
						$send_time =$event['send_time'];
					}
					$first = 0;
					?>

					<?php  if($event['progress'] != '3'){ ?>
                    <?php if(!empty($camp_name)){ ?>
                    <tr style="background:#68dff0">
                    	<td colspan="12">Subject - <a style="color:blue" href="<?php echo base_url()."campaigncontroller/download_campaign/".$event['campaign_id']; ?>"><?php echo $camp_name ?></a><br />
                   	    Template - <?php echo $event['template_name']; ?><br />Sender - <?php echo $event['sender_name']; ?> (<?php echo $event['email']; ?>)<br />
                   	    Smtp - <?php echo $smtp['smtp_host']; ?> (<?php echo $smtp['smtp_user']; ?>)</td>
                    </tr>
                    <tr>
        				<th>List name</th>
        				<th>Status</th>
        				<th>Total</th>
        				<th>Total Sent</th>
        				<th>Hardbounce</th>
        				<th>Softbounce	</th>
        				<th>Spam</th>
        				<th>Clicks</th>
        				<th>Rejects</th>
        				<th>Open</th>
        				<th>Start Time</th>
        				<th>End Time</th>
        			</tr>
                    <?php } ?>
		           	<tr style="background:#68dff0">
		           	<?php  } else if($event['progress'] == '3'){ ?>
                    <?php if(!empty($camp_name)){ ?>
                    <tr style="background:#68dff0">
                    	<td colspan="12">
                    		Subject - <a style="color:blue" href="<?php echo base_url()."campaigncontroller/download_campaign/".$event['campaign_id']; ?>"><?php echo $camp_name ?></a><br />
                    	  	Template - <?php echo $event['template_name']; ?> <br />
               	      		Sender - <?php echo $event['sender_name']; ?> (<?php echo $event['email']; ?>)<br />
               	      		Smtp - <?php echo $smtp['smtp_host']; ?> (<?php echo $smtp['smtp_user']; ?>)
               	      	</td>
                    </tr>
                    <tr>
        				<th>List name</th>
        				<th>Status</th>
        				<th>Total</th>
        				<th>Total Sent</th>
        				<th>Hardbounce</th>
        				<th>Softbounce	</th>
        				<th>Spam</th>
        				<th>Clicks</th>
        				<th>Rejects</th>
        				<th>Open</th>
        				<th>Start Time</th>
        				<th>End Time</th>
        			</tr>
                    <?php } ?>
                    <?php 
						$totalctr = $totalctr + $total_sent + $total ;
						$hardctr = $hardctr + $h_bounce;
						$softctr = $softctr + $s_bounce;
						$spamctr = $spamctr + $spam;
						$clickctr = $clickctr + $click;
						$rejectctr = $rejectctr + $reject;
						$openctr = $openctr + $open;
						$sentctr = $sentctr + $total_sent;
					?>
		           	<tr style="background:#f9f9f9">
		           	<?php  } ?>	
		           		<td><?php echo $event['list_name']; ?></td>
		           		<td><?php echo $progress; ?></td>
		           		<td><?php echo $total+$total_sent; ?></td>
		           		<td><?php echo $total_sent; ?></td>
		           		<td><?php echo $h_bounce; ?></td>
		           		<td><?php echo $s_bounce; ?></td>
		           		<td><?php echo $spam; ?></td>
		           		<td><?php echo $click; ?></td>
		           		<td><?php echo $reject; ?></td>
		           		<td><?php echo $open; ?></td>
		           		<td><?php echo $event['start_time']; ?></td>
		           		<td><?php echo $event['send_time']; ?></td>
		           	</tr>
                  
		           	<?php  endforeach; ?>

				</thead>    
			</table>
			</div>
			</div>
            </div>
        <?php }if (isset($list_details)) { ?>
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
            <div class ="panel panel-primary">
        		<div class="panel-heading" style="padding-top: 5px;">
        			<h4>List reports</h4>
        		</div>
        	<div class="table-responsive">
        	<table style="width:100%;color:black" class="table-bordered">
        		<thead>
        			<tr>
        				<th>Id</th>
        				<th>Name</th>
        				<th>Details</th>
        				
        				<th>Count</th>
        				<th>Hard</th>
        				<th>Soft</th>
        				<th>Spam</th>
        				<th>Unverified</th>
        				<th>Verified</th>
        				<th>Rejected</th>
        				<th>Invalid</th>
        				<th>Owner</th>
        			</tr>
					<?php  foreach ($list_details as $event): ?>
					<tr>
		           		<td><?php echo $event['list_id']; ?></td>
		           		<td><a href="<?php echo base_url()."listcontroller/download_list/".$event['list_id']; ?>"><?php echo $event['list_name']; ?></a></td>
		           		<td><?php echo $event['list_description']; ?></td>
		           		
		           		<td><?php echo $event['Total']; ?></td>
		           		<td><?php echo $event['6']; ?></td>
		           		<td><?php echo $event['1']; ?></td>
		           		<td><?php echo $event['2']; ?></td>
		           		<td><?php echo $event['3']; ?></td>
		           		<td><?php echo $event['5']; ?></td>
		           		<td><?php echo $event['7']; ?></td>
		           		<td><?php echo $event['invalid']; ?></td>
		           		<td><?php echo $event['email']; ?></td>
		           	</tr>		
					<?php  endforeach; ?>
				</thead>    
			</table>
			</div>
			</div 
                
                
        ></div>
        <?php }if (isset($current_status)) { ?>
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
            <div class ="panel panel-primary">
        		<div class="panel-heading" style="padding-top: 5px;">
        			<h4>List reports</h4>
        		</div>
        	<div class="table-responsive">
        	<table style="width:100%;color:black" class="table-bordered">
        		<thead>
        			<tr>
                    	<th>Subject</th>
                    	<th>Sender</th>
                    	<th>Email</th>
                    	<th>List</th>
                    	<th>Total</th>
                    	<th>Status</th>
                    </tr>
					<?php  if(isset($current_status) && count($current_status)>0){ ?>
                    <?php  foreach ($current_status as $queue): ?>
                    <?php  
                    	if(unserialize($queue['subscriber_ids']) === false) 
							$total = 0; 
						else $total = count(unserialize($queue['subscriber_ids']));
							$status = "";
						if($queue['progress'] ==="1")
							$status = "Queued";
						else if($queue['progress'] ==="2")
							$status = "Processing";
						else if($queue['progress'] ==="3")
							$status = "Sent";
						else if($queue['progress'] ==="4")
							$status = "Paused";
								
                    ?>
					<?php  if($queue['progress'] != '3'){ ?>
					<tr style="background:#FF9966">
					 	<td><?php echo $queue['subject']; ?></td>
						<td><?php echo $queue['sender_name']; ?></td>
						<td><?php echo $queue['email']; ?></td>
						<td><?php echo $queue['list_name']; ?></td>
						<td><?php echo $total; ?></td>
						<td><?php echo $status; ?></td>
					</tr>
					<?php  }else if($queue['progress'] == '3'){ ?>
					<tr style="background:#66FF66">
						<td><?php echo $queue['subject']; ?></td>
					    <td><?php echo $queue['sender_name']; ?></td>
						<td><?php echo $queue['email']; ?></td>
						<td><?php echo $queue['list_name']; ?></td>
						<td><?php echo $total; ?></td>
						<td><?php echo $status; ?></td>
					</tr>
					<?php  } ?>	
					<?php  endforeach;} ?>
				</thead>    
			</table>
			</div>
			</div 
                
        ></div>
        <?php } ?>
        <!--Accordin end-->

        <!-- page end-->
    </section>
</section>
<!--main content end-->