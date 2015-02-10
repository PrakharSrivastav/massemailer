<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class List_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * $data = array(array($where_array), array($where_array),......)
     * $where_array 	-- array with the where clause
     */

    public function generic_list_delete($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("List_model::generic_list_delete::invalid array provided for update", 1);
            } else {
                # variable for status of deletion process
                $status = false;

                # delete the records from list_master table for each new list
                foreach ($data as $list) {
                    $this->db->where($list["where"]);
                    $status = $this->db->delete("list_master");
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
     * $update_array 	-- array with list of fields to be updated
     */

    public function generic_list_update($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("List_model::generic_list_update::invalid array provided for update", 1);
            } else {

                # variable for status of update
                $status = false;

                # update the list_master table for each new list
                foreach ($data as $list) {
                    $this->db->where($list["where"]);
                    $status = $this->db->update("list_master", $list["update"]);
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

    public function generic_list_select($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("List_model::generic_list_select::invalid array provided for select", 1);
            } else {
                # array for returning the results
                $return_array = array();

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select("*");

                # get the result in query object
                $query = $this->db->get("list_master");

                if ($query->num_rows() > 0) {
                    foreach ($query->result_array() as $row) {
                        $return_array[] = $row;
                    }
                    $query->free_result();
                }

                return $return_array;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_list_insert($data) {
        try {
            //print_r($data);
            //echo count($data);
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("List_model::generic_list_insert::invalid array provided for insert", 1);
            } else {
                # variable to show the status of the insert
                $status = false;

                # insert records for each list item in the $data array
                foreach ($data as $list) {
                    $status = $this->db->insert("list_master", $list);
                }

                # return the status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_list_select_field($data, $field_name) {
        try {
            //print_r($data);
            if (!is_array($data) || empty($data) || count($data) === 0 || $field_name === "") {
                throw new Exception("List_model::generic_list_select_field::invalid array/ or field name provided for select", 1);
            } else {
                # variable for returning the results
                $return_value = "";

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select($field_name);

                # get the result in query object
                $query = $this->db->get("list_master");
                //print_r($query);
                if ($query->num_rows() > 0) {
                    $row = $query->first_row();
                    //print_r($row);
                    $return_value = $row->$field_name;
                    $query->free_result();
                    //return $return_value;
                }
                //echo "list-id is".$return_value;
                return $return_value;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
