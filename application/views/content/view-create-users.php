<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>groupadmin/group_admin_dashboard">Home</a></li>
                    <li><i class="fa fa-user"></i>Create Users</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <?php if (isset($return_status) && $return_status) { ?>
            <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
                <div class="row alert alert-success">
                    <i class="fa fa-check-square-o">  User created successfully. Please continue to create more users. </i>
                </div>
            </div>
            <?php
        } if (isset($smtp_details) && count($smtp_details) > 0) {
            //print_r($smtp_details);
            ?>
            <div class="form">
                <form class="form-validate form-horizontal " id="create_users_form" method="post" action="<?php echo base_url(); ?>groupadmin/insert_users">
                    <div class="row">
                        <div class="col-lg-4">
                            <section class="panel panel-primary">
                                <header class="panel-heading text-center">
                                    Enter Account details
                                </header>
                                <div class="panel-body">

                                    <div class="form-group ">
                                        <label for="firstname" class="control-label col-lg-4">First name <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="firstname" name="firstname" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="lastname" class="control-label col-lg-4">Last name <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="lastname" name="lastname" type="text" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="email" class="control-label col-lg-4">Email <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " id="email" name="email" type="email" required="required"/>
                                        </div>
                                    </div>
                                    <input class="form-control " id="user_role" name="user_role" type="hidden" value="3"/>

                                    <!--                                    <div class="form-group ">
                                                                            <label for="user_role" class="control-label col-lg-4">User Role<span class="required">*</span></label>
                                                                            <div class="col-lg-8">
                                                                                <input class="form-control " type="text" pattern="\d*" value="3" disabled="disabled"/>
                                                                                <input class="form-control " id="user_role" name="user_role" type="hidden" value="3"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group ">
                                                                            <label for="quota_total" class="control-label col-lg-4">Total quota<span class="required">*</span></label>
                                                                            <div class="col-lg-8">
                                                                                <input class=" form-control" id="quota_total" name="quota_total" type="text" pattern="\d*" disabled="disabled"/>
                                                                            </div>
                                                                        </div>
                                    -->                                                                        
                                    <div class="form-group ">
                                        <label for="quota_monthly" class="control-label col-lg-4">Monthly quota<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="quota_monthly" name="quota_monthly" type="text" pattern="\d*"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="quota_hour" class="control-label col-lg-4">Hourly quota<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" id="quota_hour" name="quota_hour" type="text" pattern="\d*"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="password" class="control-label col-lg-4">Password<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " id="password" name="password" type="password" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="confirm_password" class="control-label col-lg-4">Confirm Password<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " id="confirm_password" name="confirm_password" type="password" required="required"/>
                                        </div>
                                    </div>
                                    <input class="form-control " id="s_email" name="s_email" type="hidden" value="<?php echo $smtp_details[5] ?>"/>
                                    <input class="form-control " id="b_email" name="b_email" type="hidden" value="<?php echo $smtp_details[6] ?>"/>
<!--                                    <div class="form-group ">
                                        <label for="s_email" class="control-label col-lg-4">Sender Email<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " disabled="disabled" value="<?php //echo $smtp_details[5] ?>"/>
                                            <input class="form-control " id="s_email" name="s_email" type="hidden" value="<?php //echo $smtp_details[5] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="b_email" class="control-label col-lg-4">Bounce Email<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type='email' disabled="disabled" value="<?php //echo $smtp_details[6] ?>"/>
                                            <input class="form-control " id="b_email" name="b_email" type="hidden" value="<?php //echo $smtp_details[6] ?>"/>
                                        </div>
                                    </div>-->
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
                                            <input class="form-control " id="pincode" name="pincode" type="text" pattern="\d*" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="ex_date" class="control-label col-lg-4">Expiery Date<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " id="ex_date" name="ex_date" type="date" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="c_number" class="control-label col-lg-4">Contact #</label>
                                        <div class="col-lg-8"> 	
                                            <input class=" form-control" id="c_number" name="c_number" type="tel" />
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="m_number" class="control-label col-lg-4">Mobile #<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " id="m_number" name="m_number" type="tel" />
                                        </div>
                                    </div>

                                </div>
                            </section>
                            <br /><br />
                            <div class="form-group">
                                <div class=" col-lg-12">
                                    <button class="btn btn-block input-lg btn-space" type="submit">
                                        Create User
                                    </button>
                                    <button class="btn btn-block input-lg btn-space" type="reset">
                                        Reset Form
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <section class="panel panel-primary">
                                <header class="panel-heading text-center">
                                    Select SMTP details
                                </header>
                                <div class="panel-body">
                                    <select name="smtp_detail" id="smtp_detail" required="required" class="form-control">
                                        <option value = "0">Primary SMTP setting</option>
                                        <option value = "1" selected="selected">Secondary SMTP setting</option>
                                    </select>
                                    
                                    
                                    
                                    <!--
                                    <div class="form-group ">
                                        <label for="smtp_user" class="control-label col-lg-4">SMTP User<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class=" form-control" type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[2] ?>"/>
                                            <input class=" form-control" id="smtp_user" name="smtp_user" type="hidden" value="<?php //echo $smtp_details[2] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="smtp_host" class="control-label col-lg-4">SMTP Host <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[0] ?>"/>
                                            <input class="form-control " id="smtp_host" name="smtp_host" type="hidden" value="<?php //echo $smtp_details[0] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="smtp_auth" class="control-label col-lg-4">SMTP Auth<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[4] ?>"/>
                                            <input class="form-control " id="smtp_auth" name="smtp_auth" type="hidden" value="<?php// echo $smtp_details[4] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="smtp_port" class="control-label col-lg-4">SMTP Port<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[1] ?>"/>
                                            <input class="form-control " id="smtp_port" name="smtp_port" type="hidden" value="<?php //echo $smtp_details[1] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="smtp_pass" class="control-label col-lg-4">SMTP Password<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[3] ?>"/>
                                            <input class="form-control " id="smtp_pass" name="smtp_pass" type="hidden" value="<?php //echo $smtp_details[3] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="test_smtp_user" class="control-label col-lg-4">Test-SMTP User<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled"	value="<?php //echo 12345678 //$smtp_details[9] ?>"/>
                                            <input class="form-control " id="test_smtp_user" name="test_smtp_user" type="hidden" value="<?php //echo $smtp_details[9] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="test_smtp_host" class="control-label col-lg-4">Test-SMTP Host<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[7] ?>"/>
                                            <input class="form-control " id="test_smtp_host" name="test_smtp_host" type="hidden" value="<?php //echo $smtp_details[7] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="test_smtp_auth" class="control-label col-lg-4">Test-SMTP Auth<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[11] ?>"/>
                                            <input class="form-control " id="test_smtp_auth" name="test_smtp_auth" type="hidden" value="<?php //echo $smtp_details[11] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="test_smtp_port" class="control-label col-lg-4">Test-SMTP Port<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[8] ?>"/>
                                            <input class="form-control " id="test_smtp_port" name="test_smtp_port" type="hidden" value="<?php //echo $smtp_details[8] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="test_smtp_pass" class="control-label col-lg-4">Test-Password<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[10] ?>"/>
                                            <input class="form-control " id="test_smtp_pass" name="test_smtp_pass" type="hidden" value="<?php //echo $smtp_details[10] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="test_s_email" class="control-label col-lg-4">Test-Sender<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " type="password" disabled="disabled" value="<?php //echo 12345678 //$smtp_details[12] ?>"/>
                                            <input class="form-control " id="test_s_email" name="test_s_email" type="hidden" value="<?php //echo $smtp_details[12] ?>"/>
                                        </div>
                                    </div>
-->                                </div>
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