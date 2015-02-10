<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
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
                        <i class="fa fa-gear"></i>Change Password
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <!--start of the form section-->
            <div class="col-lg-6">
                <div class="form">
                    <form class="form-validate form-horizontal " id="change_user_password" method="post" action="<?php echo base_url() . 'authentication/edit_password/'; ?>">
                        <section class="panel panel-primary">
                            <header class="panel-heading text-center">
                                Enter the password details
                            </header>
                            <div class="panel-body">
                                <div class="form-group ">
                                    <label for="old_password" class="control-label col-lg-3">Old Password<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class=" form-control input-sm" id="old_password" name="old_password" type="password" />
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="new_password" class="control-label col-lg-3">New Password<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class=" form-control input-sm" id="new_password" name="new_password" type="password" />
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="confirm_password" class="control-label col-lg-3">Confirm Password<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class="form-control input-sm" id="confirm_password" name="confirm_password" type="password" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="col-lg-6 btn btn-block input-sm btn-space" type="submit">Save</button>
                                    <button class="col-lg-6 btn btn-block input-sm btn-space" type="reset">Reset</button>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
            </div>
            <!--end of the form section-->
            <?php
            if (isset($status) && $status) {
                ?>
                <!--start of the confirmation message-->
                <div class="col-lg-6">
                    <div class="alert alert-info fade in">
                        <button data-dismiss="alert" class="close close-sm" type="button">
                            <i class="icon-remove"></i>
                        </button>
                        <strong><i class="fa fa-check"></i></strong> Password is successfully changed.
                    </div>
                </div>
                <!--end of the confirmation message-->
                <?php
            }
            ?>
        </div>
    </section>
</section>
<!--main content end-->