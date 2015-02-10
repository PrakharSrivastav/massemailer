<!DOCTYPE html>
<html>
    <head>
        <title>Error</title>
        <?php
        $this->load->helper('html');
        echo link_tag('resources/css/bootstrap.min.css');
        echo link_tag('resources/css/style.css');
        echo link_tag('resources/css/adminstyle.css');
        ?>
    </head>
    <body id='login-page'>
        <h3 id='login-strip' style="background-color: #ce8483">
            <div><?php echo $message; ?></div>

            <small class="help-block" style="color:black;">Please click below to go to the login page.</small>
            <a class='btn btn-primary' href="<?php echo base_url() ?> "authentication/>Click to Login</a>
    </h3> 
    <script src="<?php echo base_url(); ?>resources/js/jquery-2.1.1.min.js"></script>
    <script src="<?php echo base_url(); ?>resources/js/script.js"></script>
</body>
</html>