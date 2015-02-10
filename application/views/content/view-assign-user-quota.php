<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url(); ?>groupadmin/group_admin_dashboard">Dashboard </a>
                    </li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <!-- statistics icons start-->
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box  blue-bg">
                <i class="icon_bag"></i>
                <div class="count"><?php echo $quota_total; ?></div>
                <div class="title">My Available quota</div>						
            </div><!--/.info-box-->			
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box blue-bg">
                <i class="icon_box-checked"></i>
                <div class="count"><?php echo $quota_used; ?></div>
                <div class="title">Assigned quota</div>						
            </div><!--/.info-box-->			
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box blue-bg">
                <i class="icon_profile"></i>
                <div class="count"><?php echo $quota_user_total; ?></div>
                <div class="title">Users available quota</div>						
            </div><!--/.info-box-->			
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box blue-bg">
                <i class="fa fa-mail-forward"></i>
                <div class="count"><?php echo $quota_user_used; ?></div>
                <div class="title">Sent emails</div>						
            </div><!--/.info-box-->			
        </div>
        <!-- statistics icons end-->

        <!-- my user details start -->

        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 panel-group m-bot20" id='accordin'>
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel panel-heading">
                        <header>
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#my_user_data"> 
                                Set Quota details <small>  Click on email-id to set quota details</small>
                            </a>
                        </header>
                    </div>
                    <section id="my_user_data" class="panel panel-collapse collapse in">
                    	<div class="table-responsive">
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Firstname</th>
                                    <th>Quota</th>
                                    <th>Emails sent</th>
                                    <th>Hourly Quota</th>
                                    <th>Expiery Date</th>
                                </tr>
                                <?php foreach ($my_users as $user): ?>
                                    <tr>
                                        <td><a href="<?php echo base_url(); ?>groupadmin/group_admin_dashboard/<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a></td>
                                        <td><?php echo $user['first_name']; ?></td>
                                        <td><?php echo ($user['quota_total']-$user['quota_used']); ?></td>
                                        <td><?php echo $user['quota_used']; ?></td>
                                        <td><?php echo $user['quota_hour']; ?></td>
                                        <td><?php echo $user['expire_date']; ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </thead>
                        </table>
                        </div>
                    </section>
                </div>
                <div class="panel panel-primary">
                    <div class="panel panel-heading">
                        <header>
                            <a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#set_limit_to_lists"> 
                                Set limit to lists <small>  Click on apply-limit to limits user's list to 10</small>
                            </a>
                        </header>
                    </div>
                    <section id="set_limit_to_lists" class="panel panel-collapse collapse in">
                        <div class="table-responsive">
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Firstname</th>
                                    <th>Quota</th>
                                    <th>List-limit</th>
                                    <th>Apply limits</th>
                                    <th>Remove limits</th>
                                </tr>
                                <?php foreach ($my_users as $user): ?>
                                    <?php if ((int) $user['user_list_limit'] === 0) { ?>
                                        <tr>
                                        <?php } else { ?> 
                                        <tr class="danger">
                                        <?php } ?>
                                        <td><?php echo $user['email']; ?></td>
                                        <td><?php echo $user['first_name']; ?></td>
                                        <td><?php echo $user['quota_total']; ?></td>
                                        <td><?php echo $user['user_list_limit']; ?></td>
                                        <td><a href="<?php echo base_url(); ?>groupadmin/apply_list_limits_to_users/<?php echo $user['email']; ?>/apply" class="btn input-sm btn-block btn-default"><i class='fa fa-cut'></i></a></td>
                                        <td><a href="<?php echo base_url(); ?>groupadmin/apply_list_limits_to_users/<?php echo $user['email']; ?>/remove" class="btn input-sm btn-block btn-default"><i class='fa fa-check'></i></a></td>
                                    </tr>
                                <?php endforeach ?>
                            </thead>
                        </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- my user details end -->
        <?php if ($showform) {  ?>
            <!-- quota assignment form start-->
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 panel-group m-bot20">
                <div class="panel panel-primary">
                    <div class="panel panel-heading">
                        <header>
                            Update user quota details
                        </header>
                    </div>
                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal edit_login_details_form" method="post" id="assign_quota_to_users_form" 
                                  name="assign_quota_to_users_form" action="<?php echo base_url(); ?>groupadmin/add_user_quota_details/<?php echo $useremail; ?>">
                                <div class="form-group ">
                                    <label for="quota_total" class="control-label col-lg-3">Set new quota<span class="required">*</span></label>
                                    <div class="col-lg-3">
                                        <input class=" form-control" id="quota_total" name="quota_total" type="text" />
                                    </div>

                                    <div class=" col-lg-3">
                                        <button class="btn btn-block btn-default" type="submit">
                                            Save
                                        </button>
                                    </div>
                                    <div class=" col-lg-3">
                                        <button class="btn btn-block btn-default" type="reset">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- quota assingment form end --->
            <?php  } else if ($showresult) {  ?>
            <div class = "row">
	            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 alert alert-info fade in">
	                <button data-dismiss="alert" class="close close-sm" type="button">
	                    <i class="icon-remove"></i>
	                </button>
	                <strong><i class="fa fa-check"></i></strong> The quota details are applied
	            </div>
            </div>

    <?php } else if ($error_status) {  ?>

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 alert alert-warning fade in">
                <button data-dismiss="alert" class="close close-sm" type="button">
                    <i class="icon-remove"></i>
                </button>
                <strong><i class="fa fa-warning"></i></strong> You do not have the sufficient quota to assign. Please assing within quota limits or contact your admin.
            </div>

    <?php } ?>
        <!-- page end-->
    </section>
</section>
<!--main content end-->