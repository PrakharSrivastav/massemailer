<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li>
						<i class="fa fa-home"></i><a href="<?php echo base_url(); ?>masteradmin/master_admin_dashboard">Home</a>
					</li>
					<li>
						<i class="icon_document_alt"></i>Edit Group Admin
					</li>
				</ol>
			</div>
		</div>
		<!-- page start-->

		<div class="col-sm-6 panel-group m-bot20" id="accordion">
			<!--List of group admins accordin start-->
			<div class="row panel panel-primary">
				<div class="panel-heading" >
					<h4 class="panel-title" style="background-color: #5dc3e7"><a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> Edit login/quota details </a></h4>
				</div>
				<section id="collapseOne" class="panel table-responsive panel-collapse collapse in">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>First Name</th>
								<th>Email-id</th>
								<th>Total Quota</th>
								<th>Used Quota</th>
								<th>Status</th>
								<!--<th>Add +10000 Quota</th>-->
							</tr>
							<?php $count = 1;
							if (count($group_admin_list) > 0) {
								foreach ($group_admin_list as $group_admin) {
									//                                    print_r($group_admin);
									$active = ($group_admin['is_active'] == "1") ? "Active" : "Inactive";
									echo "<tr>";
									echo "<td>$count</td>";
									echo "<td>" . $group_admin['first_name'] . "</td>";
									echo "<td><a href='" . base_url() . "masteradmin/edit_group_admin/" . $group_admin['email'] . "/login'>" . $group_admin['email'] . "</a></td>";
									echo "<td>" . $group_admin['quota_total'] . "</td>";
									echo "<td>" . $group_admin['quota_used'] . "</td>";
									//echo "<td><a class='btn btn-default btn-block' href='" . base_url() . "masteradmin/add_group_admin_quota/" . $group_admin['email'] . "'><i class='fa fa-check'></i></a></td>";
									echo "<td>" . $active . "</td>";
									echo "</tr>";
									$count++;
								}
							}
							?>
						</thead>
					</table>
				</section>
			</div>
			<!--List of group admins accordin end-->
			<!--Assign Quota accordin start-->
			<div class="row panel panel-primary">
				<div class="panel-heading" >
					<h4 class="panel-title" style="background-color: #5dc3e7"><a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#edit_smtp_group_detail"> Edit SMTP details. </a></h4>
				</div>
				<section id="edit_smtp_group_detail" class="panel table-responsive panel-collapse collapse in">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>FirstName</th>
								<th>Email</th>
								<th>Host</th>
								<th>Port</th>
								<th>Test Host</th>
								<th>Test Port</th>

							</tr>
							<?php $count = 1;
							if (count($group_admin_list) > 0) {
								foreach ($group_admin_list as $group_admin) {
									echo "<tr>";
									echo "<td>$count</td>";
									echo "<td>" . $group_admin['first_name'] . "</td>";
									echo "<td><a href='" . base_url() . "masteradmin/edit_group_admin/" . $group_admin['email'] . "/smtp'>" . $group_admin['email'] . "</a></td>";
									echo "<td>" . $group_admin['smtp_host'] . "</td>";
									echo "<td>" . $group_admin['smtp_port'] . "</td>";
									echo "<td>" . $group_admin['test_smtp_host'] . "</td>";
									echo "<td>" . $group_admin['test_smtp_port'] . "</td>";
									echo "</tr>";
									$count++;
								}
							}
							?>
						</thead>
					</table>
				</section>
			</div>
			<div class="row panel panel-primary">
				<div class="panel-heading" >
					<h4 class="panel-title" style="background-color: #5dc3e7"><a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#edit_address_details"> Edit Address details. </a></h4>
				</div>
				<section id="edit_address_details" class="panel table-responsive panel-collapse collapse in">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>First Name</th>
								<th>Email-id</th>
								<th>Address 1</th>
								<th>City</th>
								<th>State</th>
								<th>Pincode</th>
								<th>Mobile #</th>

							</tr>
							<?php $count = 1;
							if (count($group_admin_list) > 0) {
								foreach ($group_admin_list as $group_admin) {
									echo "<tr>";
									echo "<td>$count</td>";
									echo "<td>" . $group_admin['first_name'] . "</td>";
									echo "<td><a href='" . base_url() . "masteradmin/edit_group_admin/" . $group_admin['email'] . "/contact'>" . $group_admin['email'] . "</a></td>";
									echo "<td>" . $group_admin['address_line_1'] . "</td>";
									echo "<td>" . $group_admin['city'] . "</td>";
									echo "<td>" . $group_admin['state'] . "</td>";
									echo "<td>" . $group_admin['pincode'] . "</td>";
									//echo "<td>" . $group_admin['contact_num'] . "</td>";
									echo "<td>" . $group_admin['mobile_num'] . "</td>";
									echo "</tr>";
									$count++;
								}
							}
							?>
						</thead>
					</table>
				</section>
			</div>

		</div>
		<!--Assign Quota accordin end-->

		<!-- Edit-user-form-start-->
		<?php
		if (isset($get_all_user_details) && !empty($get_all_user_details)) {
		if (isset($detail_type) && $detail_type !== "" && $detail_type === 'login') {
		?>
		<div class="form">
			<form class="form-validate form-horizontal " id="edit_login_details_form" method="post" action="<?php echo base_url() . 'masteradmin/update_group_admin_data/' . $get_all_user_details[5] . '/login'; ?>">
				<div class="row">
					<div class="col-lg-6">
						<section class="panel panel-primary">
							<header class="panel-heading text-center">
								Enter login/quota details
							</header>
							<div class="panel-body">
								<div class="form-group ">
									<label for="firstname" class="control-label col-lg-3">First name <span class="required">*</span></label>
									<div class="col-lg-9">
										<input class=" form-control" id="firstname" name="firstname" type="text" value="<?php echo $get_all_user_details[0]; ?>"/>
									</div>
								</div>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Last name <span class="required">*</span></label>
									<div class="col-lg-9">
										<input class=" form-control" id="lastname" name="lastname" type="text" value="<?php echo $get_all_user_details[1]; ?>"/>
									</div>
								</div>
								<div class="form-group ">
									<label for="quota_total" class="control-label col-lg-3">Add Quota <span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="quota_total" name="quota_total" type="text" pattern="\d*"  value="<?php echo 0; ?>"/>
									</div>
								</div>
								<div class="form-group ">
									<label for="quota_month" class="control-label col-lg-3">Set Monthly Quota<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="quota_month" name="quota_month" type="text" pattern="\d*"  value="<?php echo $get_all_user_details[3]; ?>"/>
									</div>
								</div>
								<div class="form-group ">
									<label for="ex_date" class="control-label col-lg-3">Expiry Date<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="ex_date" name="ex_date" type="d" value="<?php echo $get_all_user_details[4]; ?>"/>
									</div>
								</div>

								<div class="form-group">
									<div class=" col-lg-12">
										<button class="btn btn-block input-sm btn-space" type="submit">
											Save
										</button>
										<button class="btn btn-block input-sm btn-space" type="reset">
											Reset
										</button>
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
			</form>
		</div>
		<!-- Edit user Login detail end-->
		<?php } else if (isset($detail_type) && $detail_type !== "" && $detail_type === 'smtp') { ?>
		<!-- Edit user SMTP details Start-->
		<div class="form">
			<form class="form-validate form-horizontal " id="edit_smtp_details_form" method="post" action="<?php echo base_url() . 'masteradmin/update_group_admin_data/' . $get_all_user_details[13] . '/smtp/'; ?>">
				<div class="row">
					<div class="col-lg-6">
						<section class="panel panel-primary">
							<header class="panel-heading text-center">
								Enter SMTP details
							</header>
							<div class="panel-body">
								<h2 style="background:grey;margin:0;color:white;padding:3px;"  class="text-center">Primary SMTP Settings</h2>
								<div class="form-group" style="background:lightgrey">
									<label for="smtp_host" class="control-label col-lg-3">Hostname<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="smtp_host" name="smtp_host" type="text" value="<?php echo $get_all_user_details[0]; ?>"/>
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="smtp_port" class="control-label col-lg-3">SMTP Port<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class=" form-control" id="smtp_port" name="smtp_port" type="text" pattern="\d*" value="<?php echo $get_all_user_details[1]; ?>"/>
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="smtp_user" class="control-label col-lg-3">Username<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class=" form-control" id="smtp_user" name="smtp_user" type="text" value="<?php echo $get_all_user_details[2]; ?>"/>
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="smtp_pass" class="control-label col-lg-3">Password<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="smtp_pass" name="smtp_pass" type="text" value=""/>
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="smtp_auth" class="control-label col-lg-3">Authentication<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="smtp_auth" name="smtp_auth" type="text" value="<?php echo $get_all_user_details[4]; ?>"/>
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="s_email" class="control-label col-lg-3">Sender Email<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="s_email" name="s_email" type="email" value="<?php echo $get_all_user_details[5]; ?>"/>
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="b_email" class="control-label col-lg-3">Bounce Email<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="b_email" name="b_email" type="email" value="<?php echo $get_all_user_details[6]; ?>"/>
									</div>
								</div>
								<div style="background:lightgrey;margin-bottom:10px;" class="form-group ">
									<label for="smtp_subaccount" class="control-label col-lg-3">Subaccount<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="smtp_subaccount" name="smtp_subaccount" type="text" pattern="\w*" />
									</div>
								</div>
								
								<h2 style="background:grey;margin:0;color:white;padding:3px;"  class="text-center">Secondary SMTP Settings</h2>
								<div class="form-group" style="background:lightgrey">
									<label for="test_smtp_host" class="control-label col-lg-3">Hostname<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="test_smtp_host" name="test_smtp_host" type="text" value="<?php echo $get_all_user_details[7]; ?>"/>
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="test_smtp_port" class="control-label col-lg-3">SMTP Port<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="test_smtp_port" name="test_smtp_port" type="text" pattern="\d*" value="<?php echo $get_all_user_details[8]; ?>" />
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="test_smtp_user" class="control-label col-lg-3">Username<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="test_smtp_user" name="test_smtp_user" type="text" value="<?php echo $get_all_user_details[9]; ?>"/>
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="test_smtp_pass" class="control-label col-lg-3">Password<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="test_smtp_pass" name="test_smtp_pass" type="text" value=""/>
									</div>
								</div>
								<div class="form-group" style="background:lightgrey">
									<label for="test_smtp_auth" class="control-label col-lg-3">Authentication<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="test_smtp_auth" name="test_smtp_auth" type="text" value="<?php echo $get_all_user_details[11]; ?>"/>
									</div>
								</div>
								<div style="background:lightgrey" class="form-group ">
									<label for="test_s_email" class="control-label col-lg-3">Sender Email<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="test_s_email" name="test_s_email" type="email" value="<?php echo $get_all_user_details[12]; ?>"/>
									</div>
								</div>
								<div style="background:lightgrey" class="form-group ">
									<label for="test_smtp_subaccount" class="control-label col-lg-3">Subaccount<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="test_smtp_subaccount" name="test_smtp_subaccount" type="text" pattern="\d*" />
									</div>
								</div>
								<div class="form-group">
									<div class=" col-lg-12">
										<button class="btn btn-block input-sm btn-space" type="submit">
											Save
										</button>
										<button class="btn btn-block input-sm btn-space" type="reset">
											Reset
										</button>
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
			</form>
		</div>
		<!-- Edit user SMTP details End -->
		<?php } else if (isset($detail_type) && $detail_type !== "" && $detail_type === 'contact') { ?>
		<!-- Edit user Contact details Start-->
		<div class="form">
			<form class="form-validate form-horizontal " id="edit_contact_details_form" method="post" action="<?php echo base_url() . 'masteradmin/update_group_admin_data/' . $get_all_user_details[7] . '/contact'; ?>">
				<div class="row">
					<div class="col-lg-6">
						<section class="panel panel-primary">
							<header class="panel-heading text-center">
								Enter Contact details
							</header>
							<div class="panel-body">
								<div class="form-group ">
									<label for="address" class="control-label col-lg-3">Address-1 <span class="required">*</span></label>
									<div class="col-lg-9">
										<input class=" form-control" id="address" name="address" type="text" value="<?php echo $get_all_user_details[0]; ?>"/>
									</div>
								</div>
								<div class="form-group ">
									<label for="address2" class="control-label col-lg-3">Address-2 <span class="required">*</span></label>
									<div class="col-lg-9">
										<input class=" form-control" id="address2" name="address2" type="text" value="<?php echo $get_all_user_details[1]; ?>"/>
									</div>
								</div>
								<div class="form-group ">
									<label for="city" class="control-label col-lg-3">City<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class=" form-control" id="city" name="city" type="text" value="<?php echo $get_all_user_details[2]; ?>"/>
									</div>
								</div>
								<div class="form-group ">
									<label for="state" class="control-label col-lg-3">State<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class=" form-control" id="state" name="state" type="text" value="<?php echo $get_all_user_details[3]; ?>"/>
									</div>
								</div>
								<div class="form-group ">
									<label for="pincode" class="control-label col-lg-3">Pincode<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="pincode" name="pincode" type="text" value="<?php echo $get_all_user_details[4]; ?>"/>
									</div>
								</div>

								<div class="form-group ">
									<label for="c_number" class="control-label col-lg-3">Contact #<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class=" form-control" id="c_number" name="c_number" type="text" value="<?php echo $get_all_user_details[5]; ?>"/>
									</div>
								</div>
								<div class="form-group ">
									<label for="m_number" class="control-label col-lg-3">Mobile #<span class="required">*</span></label>
									<div class="col-lg-9">
										<input class="form-control " id="m_number" name="m_number" type="text" value="<?php echo $get_all_user_details[6]; ?>"/>
									</div>
								</div>
								<div class="form-group">
									<div class=" col-lg-12">
										<button class="btn btn-block input-lg btn-space" type="submit">
											Save
										</button>
										<button class="btn btn-block input-lg btn-space" type="reset">
											Reset
										</button>
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
			</form>
		</div>
		<!-- Edit-user Contact details end -->
		<?php
		}
		}
		?>
		<!-- page end-->
	</section>
</section>
<!--main content end-->