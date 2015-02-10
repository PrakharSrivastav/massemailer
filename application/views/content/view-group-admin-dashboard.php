<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>groupadmin/group_admin_dashboard">Home</a></li>
                    <li>Dashboard</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <!--collapse start-->
        <div class="col-sm-6 panel-group m-bot20" id="accordion">
            <div class="panel panel-primary">
                <div class="panel-heading" >
                    <h4 class="panel-title" style="background-color: #5dc3e7">
                        <a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> 
                            List of users
                        </a>
                    </h4>
                </div>
                <section id="collapseOne" class="panel  panel-collapse collapse in">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email-id</th>
                                <th>Total Quota</th>
                                <th>Sent Emails</th>
                            </tr>
                            <?php
                            $count = 1;
                            $total_emails = 0;
                            $sent_emails = 0;
                            if (count($user_list) > 0) {
                                foreach ($user_list as $user) {
                                    echo "<tr>";
                                    echo "<td>$count</td>";
                                    echo "<td>" . $user['first_name'] . "</td>";
                                    echo "<td>" . $user['last_name'] . "</td>";
                                    echo "<td>" . $user['email'] . "</td>";
                                    echo "<td>" . $user['quota_total'] . "</td>";
                                    echo "<td>" . $user['quota_used'] . "</td>";
                                    echo "</tr>";
                                    $count++;
                                    $total_emails += (int) $user['quota_total'];
                                    $sent_emails += (int) $user['quota_used'];
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
        <!--collapse end-->
        <!--
        <div class="col-sm-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading" >
                            <h4 class="panel-title" style="background-color: #5dc3e7">
                                <a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#pie-chart"> 
                                    Pie chart
                                </a>
                            </h4>
                        </div>
                        <section id="pie-chart">
                            <div class="panel-body text-center">
                                <canvas id="pie" height="200"></canvas>
                            </div>
                        </section>
                    </div>
                </div>
         -->
        <!-- page end-->
    </section>
</section>
<!--main content end-->