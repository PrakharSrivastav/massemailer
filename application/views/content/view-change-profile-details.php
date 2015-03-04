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
                        <i class="fa fa-gear"></i>Change profile settings
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <!--start of the form section-->
            <div class="col-lg-6">
                <div class="form">
                    <form class="form-validate form-horizontal " id="change_user_details_form" method="post" action="<?php echo base_url() . 'authentication/edit_profile_details/'; ?>">
                        <section class="panel panel-primary">
                            <header class="panel-heading text-center">
                                Enter the profile details
                            </header>
                            <div class="panel-body">
                                <div class="form-group ">
                                    <label for="firstname" class="control-label col-lg-3">First name<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class=" form-control input-sm" id="firstname" name="firstname" type="text" required="required" value="<?php echo set_value('firstname',$user_details[0]["first_name"]); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="lastname" class="control-label col-lg-3">Last name<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class=" form-control input-sm" id="lastname" name="lastname" type="text" required="required" value="<?php echo set_value('lastname',$user_details[0]["last_name"]); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="address" class="control-label col-lg-3">Address-1<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class="form-control input-sm" id="address" name="address" type="text" required="required" value="<?php echo set_value('address',$user_details[0]["address_line_1"]); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="address2" class="control-label col-lg-3">Address-2</label>
                                    <div class="col-lg-9">
                                        <input class="form-control input-sm" id="address2" name="address2" type="text" value="<?php echo set_value('address2',$user_details[0]["address_line_2"]); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="city" class="control-label col-lg-3">City<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class=" form-control input-sm" id="city" name="city" type="text" required="required" value="<?php echo set_value('city',$user_details[0]["city"]); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="state" class="control-label col-lg-3">State<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class=" form-control input-sm" id="state" name="state" type="text" required="required" value="<?php echo set_value('state',$user_details[0]["state"]); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="pincode" class="control-label col-lg-3">Pincode<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class="form-control input-sm" id="pincode" name="pincode" type="text" required="required" value="<?php echo set_value('pincode',$user_details[0]["pincode"]); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="c_number" class="control-label col-lg-3">Contact #</label>
                                    <div class="col-lg-9">
                                        <input class=" form-control input-sm" id="c_number" name="c_number" type="text" value="<?php echo set_value('c_number',$user_details[0]["contact_num"]); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="m_number" class="control-label col-lg-3">Mobile #<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class="form-control input-sm" id="m_number" name="m_number" type="text" required="required" value="<?php echo set_value('m_number',$user_details[0]["mobile_num"]); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="password" class="control-label col-lg-3">Enter Password<span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <input class="form-control input-sm" id="password" name="password" type="password"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-block input-sm btn-space" type="submit">Save</button>
                                    <button class="btn btn-block input-sm btn-space" type="reset">Reset</button>
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
                        <strong><i class="fa fa-check"></i></strong> Your profile details are successfully changed.
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