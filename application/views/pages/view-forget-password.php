<!DOCTYPE html>
<html>
  <head>
    <title>
      Reset password
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
      	<?php 
      		$this->load->helper('form'); 
			echo validation_errors();
      		$attributes = array('name'=>'reset_password_form','id'=>'reset_password_form');
			echo form_open('authentication/reset_forget_password',$attributes);
      	?>
        <div class="form-group">
        <label for="login_email">Enter your registered email address(*)</label> 
        <input type="email" class="form-control" id="login_email" name="login_email" placeholder="Enter email" />
        </div>

        <button type='submit' class="btn btn-default">Reset password</button>
        <p class="help-block">An email will be sent to you with instructions</p>
        </form>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url();?>resources/js/jquery-2.1.1.min.js"></script> 
  <script src="<?php echo base_url();?>resources/js/bootstrap.min.js"></script> 
  <script src="<?php echo base_url();?>resources/js/script.js"></script> 
  <script src="<?php echo base_url();?>resources/js/scripts.js"></script> 
  <script src="<?php echo base_url(); ?>resources/js/jquery.scrollTo.min.js"></script>
  <script src="<?php echo base_url(); ?>resources/js/jquery.nicescroll.js"></script>
  <script src="<?php echo base_url(); ?>resources/js/jquery.validate.min.js"></script><script src="<?php echo base_url(); ?>resources/js/tinymce1/tinymce.min.js"></script>
</html>
