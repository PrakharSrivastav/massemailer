<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class User_template_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * $data = array(array($where_array), array($where_array),......)
     * $where_array 	-- array with the where clause
     */

    public function generic_user_template_delete($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("user_template_model::generic_user_template_delete::invalid array provided for deletion", 1);
            } else {
                # variable for status of deletion process
                $status = false;

                # delete the records from user_template table for each new user_template
                foreach ($data as $user_template) {
                    $this->db->where($user_template["where"]);
                    $status = $this->db->delete("user_template");
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
     * $update_array 	-- array with user_template of fields to be updated
     */

    public function generic_user_template_update($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("user_template_model::generic_user_template_update::invalid array provided for update", 1);
            } else {

                # variable for status of update
                $status = false;

                # update the user_template table for each new user_template
                foreach ($data as $user_template) {
                    $this->db->where($user_template["where"]);
                    $status = $this->db->update("user_template", $user_template["update"]);
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

    public function generic_user_template_select($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("user_template_model::generic_user_template_select::invalid array provided for select", 1);
            } else {
                # array for returning the results
                $return_array = array();

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select("*");

                # get the result in query object
                $query = $this->db->get("user_template");

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

    public function generic_user_template_insert($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("user_template_model::generic_user_template_insert::invalid array provided for insert", 1);
            } else {
                # variable to show the status of the insert
                $status = false;

                # insert records for each user_template item in the $data array
                foreach ($data as $user_template) {
                    $status = $this->db->insert("user_template", $user_template);
                }

                # return the status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_user_template_select_field($data, $field_name) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0 || $field_name === "") {
                throw new Exception("user_template_model::generic_user_template_select_field::invalid array/ or field name provided for field", 1);
            } else {
                # variable for returning the results
                $return_value = "";

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select($field_name);

                # get the result in query object
                $query = $this->db->get("user_template");

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
