<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <?php if (!isset($show_template) || !is_array($show_template)){ ?>
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i>
                        <?php
                        $user_role = $this->session->userdata("user_role");

                        if ($user_role === "1")
                            echo "<a href='" . base_url() . "masteradmin/master_admin_dashboard'>Home</a>";
                        else if ($user_role === "2")
                            echo "<a href='" . base_url() . "groupadmin/group_admin_dashboard'>Home</a>";
                        else if ($user_role === "3")
                            echo "<a href='" . base_url() . "user/users_dashboard'>Home</a>";
                        ?>
                    </li><li><i class="fa fa-desktop"></i>Manage template</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <!--accordin start-->
        
        <div class="col-sm-6 panel-group m-bot20" id="accordion">
            <div class="panel panel-primary">
                <div class="panel-heading" >
                    <h4 class="panel-title" style="background-color: #5dc3e7">
                        <a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#list_of_templates"> List of templates </a>
                    </h4>
                </div>
                <section id="list_of_templates" class="panel panel-collapse collapse in">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Owner</th>
                                <th>Share</th>
                                <th>View</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            <?php
                            $count = 1;
                            if (count($template_data) > 0) {
                                //print_r($template_data);
                                $user_role = $this->session->userdata("user_role");
                                foreach ($template_data as $template) {
                                  //var_dump($template[4]);
                                    if ($user_role === '1' || $user_role === '2') {
                                        echo "<tr>";
                                        echo "<td>$count</td>";
                                        echo "<td>" . $template[2] . "</td>";
                                        echo "<td>" . $template[0] . "</td>";
                                        if ($template[4] == '0') {
                                            echo "<td class='text-center'><a href='" . base_url() . "templatecontroller/share_template_with_users/$template[3]/$template[2] '><i class='fa fa-share'></i></a></td>";
                                            echo "<td class='text-center'><a target='_blank'href='" . base_url() . "templatecontroller/show_template/$template[3]/$template[2]'><i class='fa fa-list-alt'></i></a></td>";
                                            echo "<td class='text-center'><a href='" . base_url() . "templatecontroller/show_template_edit_page/$template[3]/$template[2]' ><i class='fa fa-edit'></i></a></td>";
                                            echo "<td class='delete text-center'><a href='" . base_url() . "templatecontroller/delete_template/$template[3]'><i class='fa fa-trash-o'></i></a></td>";
                                        } else {
                                            echo "<td class='text-center'><a href='" . base_url() . "templatecontroller/share_template_with_users/$template[3]/$template[2] '><i class='fa fa-share'></i></a></td>";
                                            echo "<td class='text-center'><a target='_blank'href='" . base_url() . "templatecontroller/show_template/$template[3]/$template[2]'><i class='fa fa-list-alt'></i></a></td>";
                                            echo "<td class='text-center'><i class='fa fa-edit'></i></td>";
                                            echo "<td class='delete text-center'><i class='fa fa-trash-o'></i></td>";
                                        }
                                        echo "</tr>";
                                        $count++;
                                    } else {
                                        echo "<tr>";
                                        echo "<td>$count</td>";
                                        echo "<td>" . $template[2] . "</td>";
                                        echo "<td>" . $template[0] . "</td>";
                                        echo "<td class='text-center'><i class='fa fa-share'></i></td>";
                                        if ($template[4] == '0') {
                                            echo "<td class='text-center'><a target='_blank'href='" . base_url() . "templatecontroller/show_template/$template[3]/$template[2]'><i class='fa fa-list-alt'></i></a></td>";
                                            echo "<td class='text-center'><a href='" . base_url() . "templatecontroller/show_template_edit_page/$template[3]/$template[2]' ><i class='fa fa-edit'></i></a></td>";
                                            echo "<td class='delete text-center'><a href='" . base_url() . "templatecontroller/delete_template/$template[3]'><i class='fa fa-trash-o'></i></a></td>";
                                        } else {
                                            echo "<td class='text-center'><a target='_blank'href='" . base_url() . "templatecontroller/show_template/$template[3]/$template[2]'><i class='fa fa-list-alt'></i></a></td>";
                                            echo "<td class='text-center'><i class='fa fa-edit'></i></td>";
                                            echo "<td class='delete text-center'><i class='fa fa-trash-o'></i></td>";
                                        }
                                        echo "</tr>";
                                        $count++;
                                    }
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
        <?php }
            if (isset($view_template) && $view_template) {
                ?>

                <?php
            } else if (isset($show_share_list_form) && is_array($show_share_list_form)) {
                ?>
                <section class="panel panel-primary">
                    <div class="panel-heading">
                        <header>
                            Share template: "<?php echo $template_name; ?>" with users.
                        </header>
                    </div>
                    <div class="table">
                        <div class="form">
                            <form action="<?php echo base_url(); ?>templatecontroller/share_with_users_form_process" method="post">
                                <table class="table no-padding table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th>User name</th>
                                            <th>User email</th>
                                            <th>Share (check to share)</th>
                                        </tr>
                                        <?php
                                        $count = 0;
                                        foreach ($show_share_list_form as $user):
                                            ?>
                                            <tr>
                                                <td><?php echo $user["first_name"]; ?></td>
                                                <td><?php echo $user["email"]; ?></td>
                                                <td>
                                                    <input type="checkbox" name="share_<?php echo $count; ?>" value = "<?php echo $template_id . ":", $user["email"]; ?>"/>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        endforeach
                                        ?>
                                    </thead>

                                </table>
                                <input type = "submit" value="Select users and submit to share the list" class="col-sm-6 btn btn-primary"/>
                                <a href="<?php echo base_url(); ?>templatecontroller/unshare_with_users/<?php echo $template_id; ?>" class="col-sm-6 btn  btn-primary">Click to unshare this list</a>
                            </form>
                        </div>
                    </div>
                </section>
                <?php
            } else if (isset($error) && count($error) > 0 && $error["error_type"] !== "") {
                ?>
                <section class="alert alert-danger fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="icon-remove"></i>
                    </button>
                    <strong><i class="fa fa-exclamation-triangle">    <?php echo $error["error_type"]; ?></i></strong>
                </section>
                <?php
            //} //else if (isset($show_template) && is_array($show_template)) {
                ?>
<!--                <section class="panel panel-primary">
                    <div class="panel-heading">
                        <header>
                            View template: "<?php //echo $show_template[0]["template_name"]; ?>
                        </header>
                    </div>
                    <div class="panel-body">
                        <H4>Template Name</H4>
                        <input class="well well-sm" value = '<?php //echo $show_template[0]["template_name"]; ?>' type='text' disabled="disabled">
                        <H4>Template Description</H4>
                        <input class="well well-sm" value = '<?php//echo $show_template[0]["template_desc"]; ?>' type='text' disabled="disabled">
                        <H4>Template Subject</H4>
                        <div class="well well-sm"><?php //echo//$show_template[0]["template_subject"]; ?></div>
                        <H4>Template Content</H4>
                        <div  id = "template_content" disabled="disabled" class="well well-sm"> <?php //echo str_replace("img src", "img style='width:100%;height:auto' src", $show_template[0]["template_content"]); ?></div>
                        <H4>Reply-To</H4>
                        <input type='text' disabled="disabled" class="well well-sm" value = '<?php //echo $show_template[0]["reply_to"]; ?>'>
                    </div>
                </section>-->


            <?php } else if (isset($success) && $success !== "") {
                ?>
                <section class="alert alert-info fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="icon-remove"></i>
                    </button>
                    <strong><i class="fa fa-check"><?php echo $success; ?></i></strong>
                </section>
                <?php
            }
            ?>
        </div>
       <?php   if (isset($show_template) && is_array($show_template)) {
                ?>
                <section class="panel panel-primary">
                    <div class="panel-heading">
<!--                        <header>
                            View template: "<?php //echo $show_template[0]["template_name"]; ?>
                        </header>-->
                    </div>
                    <div class="panel-body">
<!--                        <H4>Template Name</H4>
                        <input class="well well-sm" value = '<?php //echo $show_template[0]["template_name"]; ?>' type='text' disabled="disabled">
                        <H4>Template Description</H4>
                        <input class="well well-sm" value = '<?php //echo $show_template[0]["template_desc"]; ?>' type='text' disabled="disabled">
                        <H4>Template Subject</H4>
                        <div class="well well-sm"><?php //echo $show_template[0]["template_subject"]; ?></div>
                        <H4>Template Content</H4>-->
                        <div  id = "template_content" class="well well-sm"> <?php echo str_replace("img src", "img style='width:100%;height:auto' src", $show_template[0]["template_content"]); ?></div>
<!--                        <H4>Reply-To</H4>
                        <input type='text' disabled="disabled" class="well well-sm" value = '<?php //echo $show_template[0]["reply_to"]; ?>'>-->
                    </div>
                </section>
       <?php } ?>

            
        <!--Accordin end-->


        <!-- page end-->
    </section>
</section>
<!--main content end-->
