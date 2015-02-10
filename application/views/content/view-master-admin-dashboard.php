<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>masteradmin/master_admin_dashboard">Home</a></li>
                     <li><i class="fa fa-laptop"></i>Dashboard</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <!--collapse start-->
        <div class="col-sm-12 col-lg-12 col-xs-12 panel-group m-bot20" id="accordion">
            <div class="row panel panel-primary">
                <div class="panel-heading" >
                    <h4 class="panel-title" style="background-color: #5dc3e7">
                        <a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> 
                            Group admin details (Click on the email to get users under the group admin)
                        </a>
                    </h4>
                </div>
                <section id="collapseOne" class="panel table-responsive panel-collapse collapse in">
                	<table class="table table-condensed table-bordered">
	                        <thead>
	                            <tr>
	                                <th>First Name</th>
	                                <th>Email-id</th>
	                                <th>Status</th>
	                                <th>Primary SMTP Host</th>
	                                <th>Primary Subaccount</th>
	                                <th>Secondary SMTP Host</th>
	                                <th>Secondary Subaccount</th>
	                                <th>Total Quota</th>
	                                <th>Sent Emails</th>
	                                <!-- <th>Get Quota History</th> -->
	                            </tr>
	                            <?php
	                            // $count = 1;
	                            // $total_emails = 0;
	                            // $sent_emails = 0;
	                            if (count($group_admin_list) > 0) {
	                                foreach ($group_admin_list as $group_admin) {
	                                	$active = ($group_admin['is_active'] == "1")? "Active":"Inactive";
										// print_r($users);
	                                    echo "<tr>";
	                                    //echo "<td>$count</td>";
	                                    echo "<td>" . $group_admin['first_name'] . "</td>";
	                                    echo "<td><a href='".base_url().'masteradmin/get_users_under_gadmin/'.$group_admin['user_id']. "'>" . $group_admin['email'] . "</a></td>";
	                                    echo "<td>" . $active . "</td>";
										echo "<td>" . $group_admin['smtp_host'] . "</td>";
										echo "<td>" . $group_admin['smtp_saccount'] . "</td>";
										echo "<td>" . $group_admin['test_smtp_host'] . "</td>";
										echo "<td>" . $group_admin['smtp_test_saccount'] . "</td>";
	                                    echo "<td>" . $group_admin['quota_total'] . "</td>";
	                                    echo "<td>" . $group_admin['quota_used'] . "</td>";
										//echo "<td class='text-center'><a href='".base_url().'masteradmin/get_quota_history/'.$group_admin['user_id']. "'><span class='glyphicon glyphicon-folder-open'></span></a></td>";
	                                    echo "</tr>";
	                                    // $count++;
	                                    // $total_emails += (int) $group_admin['quota_total'];
	                                    // $sent_emails += (int) $group_admin['quota_used'];
	                                }
	                            }
	                            ?>
	                        </thead>
	                    </table>
	            </section>

            </div>

        </div>
        <!--collapse end-->
        <?php if(isset($users)) { if(!empty($users)){ ?>
        <div class="col-sm-12">
            <div class="row panel panel-primary">
                    <div class="panel-heading" >
                        <h4 class="panel-title" style="background-color: #5dc3e7">
                            <a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#user_details"> 
                                User details
                            </a>
                        </h4>
                     </div>
                 <section id="user_details" class="panel table-responsive panel-collapse collapse in">
                      <table class="table table-condensed table-bordered">
	                        <thead>
	                            <tr>
	                                <th>First Name</th>
	                                <th>Last Name</th>
	                                <th>Email-id</th>
	                                <th>Status</th>
	                                <th>Primary SMTP Host</th>
	                                <th>Primary Subaccount</th>
	                                <th>Hourly Quota</th>
	                                <th>Total Quota</th>
	                                <th>Sent Emails</th>
	                                <th>Expiery Date</th>
	                            </tr>
	                            <?php
	                            if (count($users) > 0) {
	                                foreach ($users as $user) {
	                                	$active = ($user['is_active'] == "1")? "Active":"Inactive";
										echo "<tr>";
	                                    echo "<td>" . $user['first_name'] . "</td>";
										echo "<td>" . $user['last_name'] . "</td>";
	                                    echo "<td>" . $user['email'] . "</td>";
	                                    echo "<td>" . $active . "</td>";
										echo "<td>" . $user['smtp_host'] . "</td>";
										echo "<td>" . $user['smtp_saccount'] . "</td>";
										echo "<td>" . $user['quota_hour'] . "</td>";
	                                    echo "<td>" . $user['quota_total'] . "</td>";
	                                    echo "<td>" . $user['quota_used'] . "</td>";
										echo "<td>" . $user['expire_date']."</td>";
	                                    echo "</tr>";
	                                }
	                            }
	                            ?>
	                        </thead>
	                    </table>
                  </section>
              </div>
         </div>
         <?php } else { ?>
         <div class="text-center"><h3>No users under this Group admin</h3></div>
         <?php } }?>
        <!-- page end-->
    </section>
</section >
<!--main content end-->