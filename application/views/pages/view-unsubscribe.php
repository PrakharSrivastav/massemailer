<!DOCTYPE html>
<html>
  <head>
    <title>
      Unsubscribe
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
      		$attributes = array('name'=>'unsubscribe','id'=>'unsubscribe');
			echo form_open('unsubscribe/put_data',$attributes);
      	?>
        <div class="form-group">
        <label for="login_email">Enter your email address to unsubscribe from this list</label> 
        <input type="email" class="form-control" id="login_email" name="login_email" placeholder="Enter email" />
        </div>

        <button type='submit' class="btn btn-default">Unsubscribe</button>
        </form>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url();?>resources/js/jquery-2.1.1.min.js"></script> 
  <script src="<?php echo base_url();?>resources/js/bootstrap.min.js"></script> 
  <script src="<?php echo base_url();?>resources/js/script.js"></script> 
  <script src="<?php echo base_url();?>resources/js/scripts.js"></script>
</html>
