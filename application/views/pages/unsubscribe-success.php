<!DOCTYPE html>
<html>
  <head>
    <title>
      Unsubscribe Success
    </title>
    <?php
        $this -> load -> helper('html');
        echo link_tag('resources/css/bootstrap.min.css');
        echo link_tag('resources/css/style.css');
        echo link_tag('resources/css/adminstyle.css');
    ?>
  </head>
  <body id='login-page'>
  <div class='container'>
    <br />
    <br />
    <div class="modal-dialog">
      <div class="modal-content">
          You are successfully removed from this list.
      </div>
    </div>
  </div>
  <script src="<?php echo base_url();?>resources/js/jquery-2.1.1.min.js"></script> 
  <script src="<?php echo base_url();?>resources/js/bootstrap.min.js"></script>
</html>
