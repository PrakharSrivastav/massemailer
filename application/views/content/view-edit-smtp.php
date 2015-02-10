<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i>
                        <?php
                           $user_role = $this->session->userdata("user_role");
                           
                         if($user_role === "1")
                             echo "<a href='".base_url()."masteradmin/master_admin_dashboard'>Home</a>";
                         else if ($user_role === "2")
                             echo "<a href='".base_url()."groupadmin/group_admin_dashboard'>Home</a>";
                         else if ($user_role === "3")
                             echo "<a href='".base_url()."user/users_dashboard'>Home</a>";
                        ?>
                    </li>
                    <li>
                        <i class="fa fa-gear"></i>SMTP Settings
                    </li>						  	
                </ol>
            </div>
        </div>
        <!-- page start-->
        <div class = "row">
            <div class="col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <header>
                            Use settings below if you want to use your own SMTP settings
                        </header>
                    </div>
                    <div class="panel-body">
                        <form id="change_settings" action="<?php echo base_url(); ?>users/show_settings_form" method="post" accept-charset="utf-8">
                            <div class="radios">
                                <label class="label_radio"  for="radio-01">
                                    <input  id="radio-01" name="smtp_setting_type" value="0" type="radio" checked /> Use default settings
                                </label>
                                <label class="label_radio" for="radio-02">
                                    <input  id="radio-02" name="smtp_setting_type" value="1" type="radio" /> Use my own SMTP Settings
                                </label>
                            </div>
                            <div>
                                <input type ="submit" class="input-sm btn btn-block btn-space" value="Change SMTP Settings">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if (isset($personal_setting) && $personal_setting) { ?>
                <div class="col-sm-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <header>
                                Use settings below if you want to use your own SMTP settings
                            </header>
                        </div>
                        <div class="panel-body">
                            <form id="external_settings" action="<?php echo base_url(); ?>users/update_external_settings" method="post" accept-charset="utf-8">
                                <div class="form-group">
                                    <label for="smtp_host" class="control-label col-sm-3">SMTP Host<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input class=" form-control input-sm" id="smtp_host" name="smtp_host" type="text" 
                                               required="required" placeholder="Enter your SMTP DNS or IP"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="smtp_port" class="control-label col-sm-3">SMTP Port<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input class=" form-control input-sm" id="smtp_port" name="smtp_port" type="text" pattern="\d*"
                                               required="required" placeholder="Enter your SMTP port (number)"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="smtp_user" class="control-label col-sm-3">SMTP User<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input class=" form-control input-sm" id="smtp_user" name="smtp_user" type="email" 
                                               required="required" placeholder="Enter your email-id"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="smtp_pass" class="control-label col-sm-3">SMTP Pass<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input class=" form-control input-sm" id="smtp_pass" name="smtp_pass" type="password" 
                                              required="required"  placeholder="Enter your SMTP server password"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="smtp_auth" class="control-label col-sm-3">SMTP Auth<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input class=" form-control input-sm" id="smtp_auth" name="smtp_auth" type="text" 
                                              required="required"  placeholder="tls, ssl or blank"/><br /><br />
                                    </div><br /><br />
                                </div>
                                <hr /> <br />
                                <div class="form-group" style="margin-top:25px;">
                                    <br />
                                    <button  class="col-sm-6 btn btn-block input-sm btn-space" type="submit">Save</button>
                                    <button class="col-sm-6 btn btn-block input-sm btn-space" type="reset">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } else if (isset($success) && $success) { ?>
                <div class="col-sm-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <header>
                                Status
                            </header>
                        </div>
                        <div class="panel-body text-center text-success">
                                Your selected SMTP settings are applied.
                        </div>
                    </div>
                </div>
            <?php } if (isset($failure) && $failure) { ?>
                <div class="col-sm-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <header>
                                Status
                            </header>
                        </div>
                        <div class="panel-body text-center text-danger">
                                There were problems changing SMTP settings. Please try again
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->