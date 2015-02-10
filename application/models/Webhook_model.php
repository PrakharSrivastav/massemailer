<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Webhook_model
 *
 * @author prakhar
 */
class Webhook_model extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function insert($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("Invalid array", 1);
            } else {
                # variable to show the status of the insert
                $status = false;

                # insert records for each list item in the $data array
                foreach ($data as $list) {
                    $status = $this->db->insert("webhook_response", $list);
                }

                # return the status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
