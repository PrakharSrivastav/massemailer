<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>masteradmin/master_admin_dashboard">Home</a></li>
                    <li><i class="icon_document_alt"></i>Delete Group Admin</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <!--accordin start-->
        <div class="col-sm-6 panel-group m-bot20" id="accordion">
            <div class="panel panel-primary">
                <div class="panel-heading" >
                    <h4 class="panel-title" style="background-color: #5dc3e7"><a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> List of group admins </a></h4>
                </div>
                <section id="collapseOne" class="panel panel-collapse collapse in">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email-id</th>
                                <th>Total Quota</th>
                                <th>Available Quota</th>
                                <th>Delete this user</th>
                            </tr>
                            <?php
                            $count = 1;
                            if (count($group_admin_list) > 0) {
                                foreach ($group_admin_list as $group_admin) {
                                    echo "<tr>";
                                    echo "<td>$count</td>";
                                    echo "<td>" . $group_admin['first_name'] . "</td>";
                                    echo "<td>" . $group_admin['last_name'] . "</td>";
                                    echo "<td>" . $group_admin['email'] . "</td>";
                                    echo "<td>" . $group_admin['quota_total'] . "</td>";
                                    echo "<td>" . $group_admin['quota_used'] . "</td>";
                                    echo "<td><a class='confirm_user_deletion' href='" . base_url() . "masteradmin/delete_group_admin/" . $group_admin['email'] . "" . "' ><span class='text-center fa fa-trash-o'></span></a></td>";
                                    echo "</tr>";
                                    $count++;
                                }
                            }
                            ?>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </section>

            </div>

        </div>
        <div class="col-lg-6">
            <?php
            if (isset($delete_status) && $delete_status) {
                ?>
                <div class="alert alert-info fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="icon-remove"></i>
                    </button>
                    <strong><i class="fa fa-check"></i></strong> The selected group admin is deleted from the system
                </div>
                <?php
            }
            ?>
        </div>
        <!--Accordin end-->

        <!-- page end-->
    </section>
</section>
<!--main content end-->