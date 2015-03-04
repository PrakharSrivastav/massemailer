<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>masteradmin/master_admin_dashboard">Home</a></li>
                    <li><i class="icon_document_alt"></i>Create Group Admin</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <?php if (isset($return_status) && $return_status) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <section class="panel panel-primary ">
                        <header class="panel-heading text-center">
                            Status
                        </header>
                        <div class="panel-body">
                            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 alert alert-success fa fa-check-circle-o">Group Admin created successfully</div>
                        </div>
                    </section>
                </div>
            </div>
        <?php } else { ?>
            <div class="form">
                <form class="form-validate form-horizontal " id="create_group_admin_form" method="post" action="<?php echo base_url(); ?>masteradmin/insert_group_admin">
                    <div class="row">
                        <div class="col-lg-4">
                            <section class="panel panel-primary">
                                <header class="panel-heading text-center">
                                    Enter Account details
                                </header>
                                <div class="panel-body">
                                    <div class="form-group ">
                                        <label for="firstname" class="control-label col-lg-4">Admin name<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="firstname" name="firstname" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="lastname" class="control-label col-lg-4">Company <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="lastname" name="lastname" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="email" class="control-label col-lg-4">Email <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="email" name="email" type="email" required="required"/>
                                        </div>
                                    </div>
                                    <input class="form-control input-sm" id="user_role" name="user_role" type="hidden" value="2" />
                                    <div class="form-group ">
                                        <label for="quota_monthly" class="control-label col-lg-4">Month quota<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="quota_monthly" name="quota_monthly" type="text" pattern="\d*" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="password" class="control-label col-lg-4">Password<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="password" name="password" type="password" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="confirm_password" class="control-label col-lg-4">Confirm Password<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="confirm_password" name="confirm_password" type="password" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="s_email" class="control-label col-lg-4">Sender Email<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="s_email" name="s_email" type="email" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="b_email" class="control-label col-lg-4">Bounce Email<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="b_email" name="b_email" type="email" required="required"/>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col-lg-4">
                            <section class="panel panel-primary">
                                <header class="panel-heading text-center">
                                    Enter Contact details
                                </header>
                                <div class="panel-body">

                                    <div class="form-group ">
                                        <label for="address" class="control-label col-lg-4">Address-1 <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="address" name="address" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="address2" class="control-label col-lg-4">Address-2</label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="address2" name="address2" type="text" />
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="city" class="control-label col-lg-4">City<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="city" name="city" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="state" class="control-label col-lg-4">State<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="state" name="state" type="text" required="required"/>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label for="pincode" class="control-label col-lg-4">Pincode<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="pincode" name="pincode" type="text" pattern="\d*" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="ex_date" class="control-label col-lg-4">Expiery Date<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="ex_date" name="ex_date"  type="date" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="c_number" class="control-label col-lg-4">Contact #</label>
                                        <div class="col-lg-8"> 	
                                            <input class=" form-control input-sm" id="c_number" name="c_number" type="tel" />
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="m_number" class="control-label col-lg-4">Mobile #<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm " id="m_number" name="m_number" type="tel" />
                                        </div>
                                    </div>

                                </div>
                            </section>
                            <br /><br />
                            <div class="form-group">
                                <div class=" col-lg-12">
                                    <button class="btn btn-block input-sm btn-primary" type="submit">
                                        Create Group Admin
                                    </button>
                                    <button class="btn btn-block input-sm btn-primary" type="reset">
                                        Reset Form
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <section class="panel panel-primary">
                                <header class="panel-heading text-center">
                                    Enter SMTP details
                                </header>
                                <div class="panel-body">

									<h2 style="background:grey;margin:0;color:white;padding:3px;"  class="text-center">Primary SMTP Settings</h2>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="smtp_user" class="control-label col-lg-4">Username<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control input-sm" id="smtp_user" name="smtp_user" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="smtp_host" class="control-label col-lg-4">Hostname<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="smtp_host" name="smtp_host" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="smtp_auth" class="control-label col-lg-4">Authentication<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="smtp_auth" name="smtp_auth" type="text" maxlength="3" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="smtp_port" class="control-label col-lg-4">SMTP Port<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="smtp_port" name="smtp_port" type="text" pattern="\d*" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="smtp_subaccount" class="control-label col-lg-4">Subaccount<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="smtp_subaccount" name="smtp_subaccount" type="text" pattern="\w*" required="required" />
                                        </div>
                                    </div>
                                    <div style="background:lightgrey;margin-bottom:10px;" class="form-group ">
                                        <label for="smtp_pass" class="control-label col-lg-4">Password<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="smtp_pass" name="smtp_pass" type="text" required="required"/>
                                        </div>
                                    </div>
                                    
                                    <h2 style="background:grey;margin:0;color:white;padding:3px;" class="text-center">Secondary SMTP Settings</h2>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="test_smtp_user" class="control-label col-lg-4">Username<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="test_smtp_user" name="test_smtp_user" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="test_smtp_host" class="control-label col-lg-4">Hostname<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="test_smtp_host" name="test_smtp_host" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="test_smtp_auth" class="control-label col-lg-4">Authentication<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="test_smtp_auth" name="test_smtp_auth" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="test_smtp_port" class="control-label col-lg-4">SMTP Port<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="test_smtp_port" name="test_smtp_port" type="text" pattern="\d*" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="test_smtp_subaccount" class="control-label col-lg-4">Subaccount<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="test_smtp_subaccount" name="test_smtp_subaccount" type="text" pattern="\d*" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="test_smtp_pass" class="control-label col-lg-4">Password<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="test_smtp_pass" name="test_smtp_pass" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div style="background:lightgrey" class="form-group ">
                                        <label for="test_s_email" class="control-label col-lg-4">Sender Email<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control input-sm" id="test_s_email" name="test_s_email" type="email" required="required"/>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>
        <!-- page end-->
    </section>
</section>
<!--main content end-->