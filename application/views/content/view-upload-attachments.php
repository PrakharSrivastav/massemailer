<!--main content start-->
<section id="main-content">
    <section class="wrapper">
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
                    </li>
                    <li><i class="fa fa-desktop"></i>Create template</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12 panel-group m-bot20" id="accordion">
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
        </div>
        <!-- page end-->
    </section>
</section>

<!--main content end-->
