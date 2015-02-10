<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title ?></title>
        <?php
        $this->load->helper('html');
        echo link_tag('resources/css/bootstrap.min.css');
        echo link_tag('resources/css/style.css');
        echo link_tag('resources/css/adminstyle.css');
        ?>

    </head>
    <body id='login-page'>
        <div class='container'>
            <h1>
                <!--The stripe at the center of the page-->
                <h3 id='login-strip'>
                    Please 
                    <a class='btn btn-lg btn-default'  data-toggle='modal' data-target='#login-modal'>Login</a> or 
                    <a class='btn btn-lg btn-default' data-toggle='modal' data-target='#signup-modal'>Signup</a> to continue..
                </h3>
            </h1>
            <!--Login Form start-->
            <div class="modal fade" id='login-modal' tabindex="-1" role="dialog" aria-labelledby="login-model" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form role="form" name='login_form' id='login_form' action='<?php echo base_url(); ?>authentication/login_user' method='post'>
                            <div class="form-group">
                                <label for="login_email">Email address(*)</label>
                                <input type="email" class="form-control" id="login_email" name="login_email" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="login_password">Password</label>
                                <input type="password" class="form-control" id="login_password" name='login_password' placeholder="Password">
                            </div>
                            <button type="submit" id='login_submit' class="btn btn-default">  Login  </button>
                            <a id="forget_password_link" href="<?php echo base_url(); ?>authentication/show_forget_password_form" class="pull-right">Forget password?</a>
                            <p class="help-block">Fields marked with * are mandatory</p>
                        </form>
                    </div>
                </div>
            </div>
            <!--Login Form End-->

            <!--Registration Form Start-->
            <div class="modal fade" id='signup-modal' tabindex="-1" role="dialog" aria-labelledby="signup-model" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form class='form-horizontal' role="form" name='signup_form' id='signup_form' action='<?php echo base_url(); ?>authentication/register_user' method='post'>
                            <h3>Personal Data</h3>
                            <div class="form-group">
                                <label for="signup_first_name" class="control-label col-sm-3">First Name(*)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="signup_first_name" name="signup_first_name" placeholder="Enter First name">	
                                </div>
                                <label for="signup_last_name" class="control-label col-sm-3">Last Name(*)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="signup_last_name" name="signup_last_name" placeholder="Enter Last name">
                                </div>
                                <label for="signup_email" class="control-label col-sm-3">Email-id(*)</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control " id="signup_email" name="signup_email" placeholder="Enter Email Address">
                                </div>
                                <label for="signup_password" class="control-label col-sm-3">Password(*)</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="signup_password" name="signup_password" placeholder="Password">
                                </div>
                                <label for="signup_confirm_password" class="control-label col-sm-3">Confirm-Password(*)</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="signup_confirm_password" name="signup_confirm_password" placeholder="Repeat-Password">
                                </div>
                            </div>
                            <h3>Address Details</h3>
                            <div class="form-group">
                                <label for="signup_add_1" class="control-label col-sm-3">Address Line 1(*)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="signup_add_1" name="signup_add_1" placeholder="Enter Address Line 1">
                                </div>
                                <label for="signup_add_2" class="control-label col-sm-3">Address Line 2</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="signup_add_2" name="signup_add_2" placeholder="Enter Address Line 2">
                                </div>
                                <label for="signup_city" class="control-label col-sm-3">City(*)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="signup_city" name="signup_city" placeholder="Enter City">
                                </div>
                                <label for="signup_state" class="control-label col-sm-3">State(*)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="signup_state" name="signup_state" placeholder="Enter State">
                                </div>
                                <label for="signup_pincode" class="control-label col-sm-3">Pincode(*)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="signup_pincode" name="signup_pincode" placeholder="Enter Pincode">
                                </div>
                            </div>
                            <button id='signup_submit' type="submit" class="btn btn-default col-sm-3">Register</button>
                            <br /><br /><br />
                            <p class="help-block">Fields marked with * are mandatory</p>
                        </form>
                        <!-- end of signup form-->
                    </div>
                    <!--end of model-content-->

                </div>
            </div>
        </div>
        <script type="text/javascript">
            var base_url =<?php echo "'" . base_url() . "'"; ?>
        </script>
        <script src="<?php echo base_url(); ?>resources/js/tinymce1/tinymce.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery-2.1.1.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/script.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/scripts.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery.validate.min.js"></script>
    </body>
</html>