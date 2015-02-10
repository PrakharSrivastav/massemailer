<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i>
                        <?php
                        $user_role = $this->session->userdata("user_role");
                        if ($user_role === "1") {
                            echo "<a href='" . base_url() . "masteradmin/master_admin_dashboard'>Home</a>";
                        } else if ($user_role === "2") {
                            echo "<a href='" . base_url() . "groupadmin/group_admin_dashboard'>Home</a>";
                        } else if ($user_role === "3") {
                            echo "<a href='" . base_url() . "user/users_dashboard'>Home</a>";
                        }
                        ?>
                    </li>
                    <li><i class="fa fa-desktop"></i>Create template</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12 panel-group m-bot20" id="accordion">
            <?php if (isset($status) && $status) { ?>
                <div class="row alert alert-success">
                    <h4>Template is saved successfully</h4>
                    <p>Stay on this page to create more templates.</p>
                </div>
            <?php } ?>
            <div class="panel panel-primary row" >
                <div class="panel-heading">
                    <header class="panel-title" style="background-color: #5dc3e7">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#upload_image_template"> Upload html template here </a>
                    </header>
                </div>
                <section class="panel panel-collapse collapse in" id="upload_image_template" >
                    <div class="panel-content col-sm-6">
                        <form role="form" id="template_image_upload_form" name="template_image_upload_form"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
                            <div class="form-group">
                                <label for="template_image_name">Template name
                                    <small>
                                        <p> Use this page to upload the html templates</p>
                                        <p> (* Please provide a unique name. If there is a file with the same name, then it will be over-written.)</p>
                                    </small>
                                </label>
                                <input type="text" class="form-control" id="template_image_name" name="template_image_name" placeholder="Enter html template name" required="required">
                            </div>
                            <div class="form-group">
                                <label for="template_image">Upload Image/Attachment</label>
                                <input type="file" id="template_image" name="template_image" required="required">
                            </div>
                            <input type="button" id="upload_template_image" class="btn btn-block btn-default" value="Upload">
                        </form>
                    </div>
                    <div  class="panel-content col-sm-6">
                        <div id="upload_image_status_success" class="alert alert-success">
                            <p><i class="fa fa-check-square"></i> The image is uploaded successfully</p>
                        </div>
                        <div id="upload_image_status_warning"class="alert alert-danger">
                            <p><i class="fa fa-times"></i></p>
                        </div>
                        
                    </div>
                </section>
            </div>
            <div class="panel panel-primary row">
                <div class="panel-heading">
                    <header class="panel-title" style="background-color: #5dc3e7">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#create-template"> Create email template </a>
                    </header>
                </div>
                <section id="create-template" class="panel panel-collapse collapse in">
                    <form method='post' enctype="multipart/form-data" action = 'savetemplate' id="template_form" role="form">
                        <textarea name='template_content' class="form-control" id="template_content" style = "height:30%"></textarea>
                </section>
            </div>
            <br/>
            <div class="form-group">
                <p class="text-danger">Please validate only when you want to upload local images / attachments</p>
                <div class="col-sm-2">
                    <input  type='button' id="validate_template" class="btn btn-success" value="Validate Template">
                </div>
            </div>
            <br/>
            <div id="file_inputs_via_js" class="row">

            </div>
            <!--            
                        <div class="panel panel-primary row">
                            <div class="panel-heading">
                                <header class="panel-title" style="background-color: #5dc3e7">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#create-footer"> Create email footer </a>
                                </header>
                            </div>
                            <section id="create-footer" class="panel panel-collapse collapse in">
                                <textarea name='template_footer' class="form-control" style = "height:auto"></textarea>
                            </section>
                        </div>
            -->
            <br><br>
            <div class="form-group">
                <label class = "col-sm-2 text-right" for="template_name">Template Name*</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" required="required" placeholder="Please provide a unique template name" id="template_name" name="template_name">
                </div>
            </div>
            <!--            
                        <div class="form-group">
                            <label class = "col-sm-2 text-right"for="template_subject">Template Subject</label><div class="col-sm-10">
                                <input class="form-control col-sm-6" type="text" required="required" id="template_subject" name="template_subject" required="required">
                            </div>
                        </div>
            -->
            <div class="form-group">
                <label class = "col-sm-2 text-right" for="template_desc">Template Description</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" id="template_desc" name="template_desc" placeholder="Please provide the list description">
                </div>
            </div>
            <!--            <div class="form-group">
                            <label class = "col-sm-2 text-right"for="reply_to">Reply-to address*</label>
                            <div class="col-sm-10">
                                <input class="form-control col-sm-6" type="email" required="required" id="reply_to" name="reply_to"  placeholder="Please provide a reply to email address">
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
                    <input  type='submit' id="submit_template_to_database" class="btn btn-success" value="Click to save template">
                </div>
            </div>
            </form>
        </div>
<!--        <form role="form" id="template_image_upload_form" action="<?php //echo base_url(); ?>templatecontroller/ajaxy" name="template_image_upload_form"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            
        </form>-->
        <!-- page end-->
    </section>
</section>

<!--main content end-->
