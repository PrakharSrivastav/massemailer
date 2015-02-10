<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class List_subscriber_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * $data = array(array($where_array), array($where_array),......)
     * $where_array 	-- array with the where clause
     */

    public function generic_list_subscriber_delete($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("list_subscriber_model::generic_list_subscriber_delete::invalid array provided for deletion", 1);
            } else {
                # variable for status of deletion process
                $status = false;

                # delete the records from list_subscriber table for each new list_subscriber
                foreach ($data as $list_subscriber) {
                    $this->db->where($list_subscriber["where"]);
                    $status = $this->db->delete("list_subscriber_relation");
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
     * $update_array 	-- array with list_subscriber of fields to be updated
     */

    public function generic_list_subscriber_update($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("list_subscriber_model::generic_list_subscriber_update::invalid array provided for update", 1);
            } else {

                # variable for status of update
                $status = false;

                # update the list_subscriber table for each new list_subscriber
                foreach ($data as $list_subscriber) {
                    $this->db->where($list_subscriber["where"]);
                    $status = $this->db->update("list_subscriber_relation", $list_subscriber["update"]);
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

    public function generic_list_subscriber_select($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("list_subscriber_model::generic_list_subscriber_select::invalid array provided for select", 1);
            } else {
                # array for returning the results
                $return_array = array();

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select("*");

                # get the result in query object
                $query = $this->db->get("list_subscriber_relation");

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

    public function generic_list_subscriber_insert($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("list_subscriber_model::generic_list_subscriber_insert::invalid array provided for insert", 1);
            } else {
                # variable to show the status of the insert
                $status = false;

                # insert records for each list_subscriber item in the $data array
                foreach ($data as $list_subscriber) {
                    $status = $this->db->insert("list_subscriber_relation", $list_subscriber);
                }

                # return the status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_list_subscriber_select_field($data, $field_name) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0 || $field_name === "") {
                throw new Exception("list_subscriber_model::generic_list_subscriber_select_field::invalid array/ or field name provided for field", 1);
            } else {
                # variable for returning the results
                $return_value = "";

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select($field_name);

                # get the result in query object
                $query = $this->db->get("list_subscriber_relation");

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

    public function get_list_subscriber_join_details($list_id, $type = "") {
        try {
            if (is_null($list_id) || $list_id === "") {
                throw new Exception("list_subscriber_model::get_list_subscriber_join_details::invalid list-id provided.", 1);
            } else {
                $this->db->distinct();
                $this->db->select("list_subscriber_relation.subscriber_id, "
                        . "subscriber_master.email , "
                        . "subscriber_master.fname, "
                        . "subscriber_master.phone,"
                        . "subscriber_master.place,"
                        . "subscriber_master.mobile,"
                        . "subscriber_master.lname,"
                        . "subscriber_master.email_status,"
                );

                $this->db->from("list_subscriber_relation");
                $this->db->join("subscriber_master", "list_subscriber_relation.subscriber_id = subscriber_master.subscriber_id");
                if($type === "")
                	$this->db->where(array("list_subscriber_relation.list_id" => $list_id));
				else 
					$this->db->where(array("list_subscriber_relation.list_id" => $list_id,"email_status"=>$type));
                $query = $this->db->get(); //print_r($result);
                $return_array = array();
                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $row) {
                        $return_array[] = array(
                            $row->subscriber_id,
                            $row->email,
                            $row->fname,
                            $row->phone,
                            $row->place,
                            $row->mobile,
                            $row->lname,
                            $row->email_status
                        );
                        //print_r($row);
                    }
                }
                return $return_array;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /*
     * $data = where clause
     */

    public function get_all_subscriber_ids($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("list_subscriber_model::generic_list_subscriber_select::invalid array provided for select", 1);
            } else {
                # array for returning the results
                $return_array = array();
//print_r($data);
                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select("subscriber_id");

                # get the result in query object
                $query = $this->db->get("list_subscriber_relation");

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

}
