<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
        <meta name="author" content="GeeksLabs">
        <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
        <link rel="shortcut icon" href="img/favicon.png">

        <title>Upload Attachments</title>
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
                $this->load->view("content/view-upload-attachments");
            } else if ($logged_in && $user_role === '2') {
                $this->load->view("template/view-header");
                $this->load->view("template/view-group-admin-sidebar");
                $this->load->view("content/view-upload-attachments");
            } else if ($logged_in && $user_role === '3') {
                $this->load->view("template/view-header");
                $this->load->view("template/view-user-sidebar");
                $this->load->view("content/view-upload-attachments");
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
        <script src="<?php echo base_url(); ?>resources/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/scripts.js"></script>
        <!--<script src="<?php echo base_url(); ?>resources/js/Chart.js"></script>-->
        <script src="<?php echo base_url(); ?>resources/js/jquery.validate.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/tinymce1/tinymce.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/template.js"></script>
        <?php
        $files = $this->session->userdata("dir_name") . "/uploads/template/";
        $all_files = glob("$files/*.*");
        $image_details = array();
        $html_details = array();
        foreach ($all_files as $file) {
            $temp = array();
            $file_arr = explode("/", $file);
            $full_name = end($file_arr);
            $file_info = explode(".", $full_name);
            $file_name = ucfirst($file_info[0]);
            $file_ext = end($file_info);
            $file_path = base_url($files . $full_name);
            $temp["title"] = $file_name;
            $temp["value"] = $file_path;
            if (strtolower($file_ext) === "htm" || strtolower($file_ext) === "html") {
                $html_details [] = $temp;
            } else if (strtolower($file_ext) !== "pdf") {
                $image_details [] = $temp;
            }
            //print_r($file_path);
        }
        ?>
        <script>
            var base_url = '<?php echo base_url(); ?>';
            tinymce.init({
                selector: '#create-template textarea,#create-footer textarea',
                plugins: 'pdf template advlist paste autolink link image lists charmap print preview',
                paste_data_images: true,
                image_list: [<?php
        foreach ($image_details as $image) {
            $title = $image["title"];
            $value = $image["value"];
            echo"{title:'$title',value:'$value'},";
        }
        ?>],
                templates: [<?php
        foreach ($html_details as $html) {
            $title = $html["title"];
            $value = $html["value"];
            echo"{title:'$title',url:'$value'},";
        }
        ?>]
            });
        </script>
    </body>
</html>
