<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
        <meta name="author" content="GeeksLabs">
        <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
        <link rel="shortcut icon" href="img/favicon.png">

        <title>Manage Lists</title>
        <?php
        $this->load->helper('html');
        echo link_tag('resources/css/bootstrap.min.css');
        echo link_tag('resources/css/bootstrap-theme.min.css');
        echo link_tag('resources/css/style.css');
        echo link_tag('resources/css/adminstyle.css');
        echo link_tag('resources/css/elegant-icons-style.css');
        echo link_tag('resources/css/font-awesome.min.css');
        echo link_tag('resources/css/nice-style.css');
        echo link_tag('resources/css/style-responsive.css');
        ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
          <script src="js/lte-ie7.js"></script>
        <![endif]-->
    </head>

    <body>
        <!-- container section start -->
        <section id="container" class="">
            <?php
            $logged_in = $this->session->userdata('is_logged_in');
            $user_role = $this->session->userdata('user_role');

            if ($logged_in && $user_role === '1') {
                $this->load->view("template/view-header");
                $this->load->view("template/view-sidebar");
                $this->load->view("content/view-create-list-master-admin");
            } else if ($logged_in && $user_role === '2') {
                $this->load->view("template/view-header");
                $this->load->view("template/view-group-admin-sidebar");
                $this->load->view("content/view-create-list-group-admin");
            } else if ($logged_in && $user_role === '3') {
                $this->load->view("template/view-header");
                $this->load->view("template/view-user-sidebar");
                $this->load->view("content/view-create-list-user");
            } else {
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
            ?>    
        </section>
        <!-- container section end -->
        <!-- javascripts -->
        <script src="<?php echo base_url(); ?>resources/js/jquery-2.1.1.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/script.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/scripts.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery.nicescroll.js"></script> 
        <script src="<?php echo base_url(); ?>resources/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery.validate.min.js"></script>
        <script>
            $(function () {
                $(".delete").click(function () {
                    return confirm("Do you really want to delete the selected list?");
                });
            });
        </script>
    </body>
</html>
