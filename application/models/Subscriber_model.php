<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Subscriber_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * $data = array(array($where_array), array($where_array),......)
     * $where_array 	-- array with the where clause
     */

    public function generic_subscriber_delete($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("subscriber_model::generic_subscriber_delete::invalid array provided for deletion", 1);
            } else {
                # variable for status of deletion process
                $status = false;

                # delete the records from subscriber_master table for each new subscriber
                foreach ($data as $subscriber) {
                    $this->db->where($subscriber["where"]);
                    $status = $this->db->delete("subscriber_master");
                }

                # return status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
     * $data = array(array($where_array, $update_array), array($where_array, $update_array),......)
     * $where_array 	-- array with the where clause
     * $update_array 	-- array with subscriber of fields to be updated
     */

    public function generic_subscriber_update($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("subscriber_model::generic_subscriber_update::invalid array provided for update", 1);
            } else {

                # variable for status of update
                $status = false;

                # update the subscriber_master table for each new subscriber
                foreach ($data as $subscriber) {
                    $this->db->where($subscriber["where"]);
                    $status = $this->db->update("subscriber_master", $subscriber["update"]);
                }

                # return status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
     * $data = where clause
     */

    public function generic_subscriber_select($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("subscriber_model::generic_subscriber_select::invalid array provided for select", 1);
            } else {
                # array for returning the results
                $return_array = array();

                # set the where clause and select all the fields from the table
                if (isset($data["get_all"]) && $data["get_all"]) {
                    $this->db->select("*");
                } else {
                    $this->db->where($data);
                    $this->db->select("*");
                }

                # get the result in query object
                $query = $this->db->get("subscriber_master");

                if ($query->num_rows() > 0) {
                    foreach ($query->result_array() as $row) {
                        $return_array[] = $row;
                    }
                }

                return $return_array;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_subscriber_insert($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("subscriber_model::generic_subscriber_insert::invalid array provided for insert", 1);
            } else {
                # variable to show the status of the insert
                $status = false;

                //print_r($data);
                # insert records for each subscriber item in the $data array
                foreach ($data as $subscriber) {
                    //print_r($subscriber);
                    $status = $this->db->insert("subscriber_master", $subscriber);
                }

                # return the status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_subscriber_select_field($data, $field_name) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0 || $field_name === "") {
                throw new Exception("subscriber_model::generic_subscriber_select_field::invalid array/ or field name provided for field", 1);
            } else {
                # variable for returning the results
                $return_value = "";

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select($field_name);

                # get the result in query object
                $query = $this->db->get("subscriber_master");

                if ($query->num_rows() > 0) {
                    $row = $query->first_row();
                    $return_value = $row->$field_name;
                    $query->free_result();
                    //return $return_value;
                }

                return $return_value;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
