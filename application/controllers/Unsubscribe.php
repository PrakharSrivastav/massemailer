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
        $path = FCPATH . "../../reports/";
        if (file_exists($path . "unsubscribe.txt"))
            file_put_contents($path . "unsubscribe.txt", print_r($this->input->post (),true), FILE_APPEND);
        else
            file_put_contents($path . "unsubscribe.txt", print_r($this->input->post (),true), FILE_APPEND);
        
        
        $this->load->view("pages/unsubscribe-success");
    }

}
