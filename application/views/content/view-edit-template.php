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
                    </li><li><i class="fa fa-desktop"></i>Edit template</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12 panel-group m-bot20" id="accordion">

            <div class="panel panel-primary row">
                <div class="panel-heading">
                    <header class="panel-title" style="background-color: #5dc3e7">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#create-template"> Edit the template details </a>
                    </header>
                </div>
                <section  class="panel panel-collapse collapse in">
                    <form method='post' enctype="multipart/form-data" id="template_form" action = '<?php echo base_url(); ?>templatecontroller/savetemplate/<?php echo $show_template[0]["template_id"]; ?>' role="form">
                        <textarea id="edit-template" name='template_content'><?php echo htmlspecialchars($show_template[0]["template_content"]); //echo  ;   ?></textarea>
                </section>
            </div>
            <br>
            <div class="form-group">
                <p class="text-danger">Please validate only when you want to upload local images / attachments</p>
                <div class="col-sm-2">
                    <input  type='button' id="validate_template" class="btn btn-success" value="Validate Template">
                </div>
            </div>
            <br/>
            <div id="file_inputs_via_js" class="row">

            </div><br/>
            <div class="form-group">
                <label class = "col-sm-2 text-right"for="template_name">Template Name*</label>
                <div class="col-sm-10">
                    <input class="form-control col-sm-6" type="text" required="required" value="<?php echo $show_template[0]["template_name"]; ?>" id="template_name" name="template_name">
                </div>
            </div>
            <div class="form-group">
                <label class = "col-sm-2 text-right"for="template_desc">Template Description</label>
                <div class="col-sm-10">
                    <input class="form-control col-sm-6" type="text" value="<?php echo $show_template[0]["template_desc"]; ?>" id="template_desc" name="template_desc">
                </div>
            </div>
<!--            <div class="form-group">
                <label class = "col-sm-2 text-right"for="reply_to">Reply-to address*</label>
                <div class="col-sm-10">
                    <input class="form-control col-sm-6" type="email" required="required" value="<?php echo $show_template[0]["reply_to"]; ?>" id="reply_to" name="reply_to">
                </div>
            </div>-->
            
            <div class="form-group">
                <label class = "col-sm-2 text-right"for="reply_to"></label>
                <div class="col-sm-10">
                    <p class="help-block">Fields marked with (*) are mandatory</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2">
                        <input  id="submit_template_to_database" type='submit' class="btn btn-success" value="click to save template">
                </div>
            </div>
            </form>
        </div>
        <!-- page end-->
    </section>
</section>

<!--main content end-->
