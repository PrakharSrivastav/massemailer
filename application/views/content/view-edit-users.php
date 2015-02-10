<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>groupadmin/group_admin_dashboard">Home</a></li>
                    <li><i class="fa fa-user"></i>Edit Users</li>
                </ol>
            </div>
        </div>
        <!-- page start-->

        <div class="col-sm-6 panel-group m-bot20" id="accordion">
            <!--List of group admins accordin start-->
            <div class="row panel panel-primary">
                <div class="panel-heading" >
                    <h4 class="panel-title" style="background-color: #5dc3e7">
                        <a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> 
                            Edit login/quota details 
                        </a>
                    </h4>
                </div>
                <section id="collapseOne" class="panel table-responsive panel-collapse collapse in">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email-id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Expiery Date</th>
                                <th>Status</th>
                            </tr>
                            <?php
                            $count = 1;
                            if (count($group_admin_list) > 0) {
                                foreach ($group_admin_list as $group_admin) {
                                    $active = ($group_admin['is_active'] == "1")? "Active":"Inactive";
                                    echo "<tr>";
                                    echo "<td>$count</td>";
                                    echo "<td><a href='" . base_url() . "groupadmin/show_edit_user_form/" . $group_admin['email'] . "/login'>" . $group_admin['email'] . "</a></td>";
                                    echo "<td>" . $group_admin['first_name'] . "</td>";
                                    echo "<td>" . $group_admin['last_name'] . "</td>";
                                    echo "<td>" . $group_admin['expire_date'] . "</td>";
                                    echo "<td>" . $active."</td>";
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
                    <h4 class="panel-title" style="background-color: #5dc3e7">
                        <a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#edit_smtp_group_detail"> 
                            Edit SMTP details. 
                        </a>
                    </h4>
                </div>
                <section id="edit_smtp_group_detail" class="panel table-responsive panel-collapse collapse in">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>FirstName</th>
                                <th>Total Quota</th>
                                <th>Hourly Quota</th>
                                <th>Is Active</th>
                            </tr>
                            <?php
                            $count = 1;
                            if (count($group_admin_list) > 0) {
                                foreach ($group_admin_list as $group_admin) {
                                    $active = ($group_admin['is_active'] === "1")?"Active":"Inactive";
                                    echo "<tr>";
                                    echo "<td>$count</td>";
                                    echo "<td><a href='" . base_url() . "groupadmin/show_edit_user_form/" . $group_admin['email'] . "/smtp'>" . $group_admin['email'] . "</a></td>";
                                    echo "<td>" . $group_admin['first_name'] . "</td>";
                                    echo "<td>" . $group_admin['quota_total'] . "</td>";
                                    echo "<td>" . $group_admin['quota_hour'] . "</td>";
                                    echo "<td>" . $active. "</td>";
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
                                <th>Email-id</th>
                                <th>First Name</th>
                                <th>Address 1</th>
                                <th>Mobile #</th>
                                <th>Contact #</th>

                            </tr>
                            <?php
                            $count = 1;
                            if (count($group_admin_list) > 0) {
                                foreach ($group_admin_list as $group_admin) {
                                    echo "<tr>";
                                    echo "<td>$count</td>";
                                    echo "<td><a href='" . base_url() . "groupadmin/show_edit_user_form/" . $group_admin['email'] . "/contact'>" . $group_admin['email'] . "</a></td>";
                                    echo "<td>" . $group_admin['first_name'] . "</td>";
                                    echo "<td>" . $group_admin['address_line_1'] . "</td>";
                                    echo "<td>" . $group_admin['mobile_num'] . "</td>";
                                    echo "<td>" . $group_admin['contact_num'] . "</td>";
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
//                print_r($get_all_user_details);
                ?>
                <div class="form">
                    <form class="form-validate form-horizontal edit_login_details_form" method="post" action="<?php echo base_url() . 'groupadmin/update_user_edited_data/' . $get_all_user_details[5] . '/login'; ?>">
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
                                            <label for="quota_hour" class="control-label col-lg-3">set Hourly Quota <span class="required">*</span></label>
                                            <div class="col-lg-9">
                                                <input class="form-control " id="quota_hour" name="quota_hour" type="text" pattern="\d*"  value="<?php echo $get_all_user_details[6]; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="quota_month" class="control-label col-lg-3">Set Monthly Quota<span class="required">*</span></label>
                                            <div class="col-lg-9">
                                                <input class="form-control " id="quota_month" name="quota_month" type="text" pattern="\d*"  value="<?php echo $get_all_user_details[3]; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="ex_date" class="control-label col-lg-3">Expiery Date<span class="required">*</span></label>
                                            <div class="col-lg-9">
                                                <input class="form-control " id="ex_date" name="ex_date" type="date" value="<?php echo $get_all_user_details[4]; ?>"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-lg-offset-3 col-lg-9">
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
                <?php
            } else if (isset($detail_type) && $detail_type !== "" && $detail_type === 'smtp' && isset($get_all_user_details)) { ?>
                <!-- Edit user SMTP details Start-->
                <div class="form">
                    <form class="form-validate form-horizontal" id="edit_smtp_details_form" method="post" action="<?php echo base_url() . 'groupadmin/update_user_edited_data/' . $get_all_user_details[13] . '/smtp/'; ?>">
                        <div class="row">
                            <div class="col-lg-6">
                                <section class="panel panel-primary">
                                    <header class="panel-heading text-center">
                                        Enter SMTP details
                                    </header>
                                    <div class="panel-body">
                                        <div class="form-group ">
                                            <label for="smtp_detail" class="control-label input-sm col-lg-3">SMTP Host <span class="required">*</span></label>
                                            <div class="col-lg-9">
                                                <select name="smtp_detail" id="smtp_detail" required="required" class="form-control">
                                                    <option value = "0">Primary SMTP setting</option>
                                                    <option value = "1" selected="selected">Secondary SMTP setting</option>
                                                </select>    
                                            </div>
                                            <br />
                                        </div>

                                        <br />
                                        <div class="form-group">
                                            <div class="col-lg-offset-3 col-lg-9">
                                                <button class="btn btn-block input-sm btn-space" type="submit">
                                                    Save
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
                <?php
            } else if (isset($detail_type) && $detail_type !== "" && $detail_type === 'contact') {
                ?>
                <!-- Edit user Contact details Start-->
                <div class="form">
                    <form class="form-validate form-horizontal edit_contact_details_form" method="post" action="<?php echo base_url() . 'groupadmin/update_user_edited_data/' . $group_admin['email'] . '/contact'; ?>">
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
                                            <div class="col-lg-offset-3 col-lg-9">
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
                <!-- Edit-user Contact details end -->
            <?php }
        } else if (isset($success) && $success) { ?>
            <div class="row">
                <div class="col-lg-6">
                    <section class="panel panel-primary">
                        <header class="panel-heading text-center">
                            Status
                        </header>
                        <div class="panel-body text-center text-success">
                            The details are updated successfully.
                        </div>
                    </section>
                </div>
            </div>
<?php } ?>
        <!-- page end-->
    </section>
</section>
<!--main content end-->