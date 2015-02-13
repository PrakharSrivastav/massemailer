<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Unsubscribe
 *
 * @author prakhar
 */
class Unsubscribe extends CI_Controller {

    //put your code here
    public function index() {
        $this->load->view("pages/view-unsubscribe");
    }

    public function put_data() {
        $path = FCPATH . "reports/";
        if (file_exists($path . "unsubscribe.txt"))
            file_put_contents($path . "unsubscribe.txt", print_r($this->input->post (),true), FILE_APPEND);
        else
            file_put_contents($path . "unsubscribe.txt", print_r($this->input->post (),true), FILE_APPEND);
                
		$where = array(
			"email"=>$this->input->post("login_email"),
			"valid"=>'0'
		);
		
		
		$update = array(
			"email_status"=>'4',
			"valid"=>'1'
		);
		$data = array("where"=>$where,"update"=>$update);
		$this->load->model("Subscriber_model","subscriber");
		$this->subscriber->generic_subscriber_update(array($data));
        $this->load->view("pages/unsubscribe-success");
    }

}
