<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class User_list_relation_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_my_current_list_count($user_id) {
        try {
            if ($user_id === "" || empty($user_id) || strlen($user_id) === 0) {
                throw new Exception("List_model::get_my_current_list_count::invalid user-id provided", 1);
            } else {
                $this->db->where(array("user_id" => $user_id));
                $result = $this->db->get('user_list_relation');
                return $result->num_rows();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
     * $data = array(array($where_array), array($where_array),......)
     * $where_array 	-- array with the where clause
     */

    public function generic_user_list_relation_delete($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("user_list_relation_model::generic_user_list_relation_delete::invalid array provided for deletion", 1);
            } else {
                # variable for status of deletion process
                $status = false;

                # delete the records from user_list_relation table for each new user_list_relation
                foreach ($data as $user_list_relation) {
                    $this->db->where($user_list_relation["where"]);
                    $status = $this->db->delete("user_list_relation");
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
     * $update_array 	-- array with user_list_relation of fields to be updated
     */

    public function generic_user_list_relation_update($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("user_list_relation_model::generic_user_list_relation_update::invalid array provided for update", 1);
            } else {

                # variable for status of update
                $status = false;

                # update the user_list_relation table for each new user_list_relation
                foreach ($data as $user_list_relation) {
                    $this->db->where($user_list_relation["where"]);
                    $status = $this->db->update("user_list_relation", $user_list_relation["update"]);
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

    public function generic_user_list_relation_select($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("user_list_relation_model::generic_user_list_relation_select::invalid array provided for select", 1);
            } else {
                # array for returning the results
                $return_array = array();

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select("*");

                # get the result in query object
                $query = $this->db->get("user_list_relation");

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

    public function generic_user_list_relation_insert($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("user_list_relation_model::generic_user_list_relation_insert::invalid array provided for insert", 1);
            } else {
                # variable to show the status of the insert
                $status = false;

                # insert records for each user_list_relation item in the $data array
                foreach ($data as $user_list_relation) {
                    $status = $this->db->insert("user_list_relation", $user_list_relation);
                }

                # return the status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_user_list_relation_select_field($data, $field_name) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0 || $field_name === "") {
                throw new Exception("user_list_relation_model::generic_user_list_relation_select_field::invalid array/ or field name provided for field", 1);
            } else {
                # variable for returning the results
                $return_value = "";

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select($field_name);

                # get the result in query object
                $query = $this->db->get("user_list_relation");

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

	public function run_query($query) {
		try {
			if (empty($query)) {
				throw new Exception("Query is empty", 1);
			} else {
				# array for returning the results
				$return_array = array();

				# get the result in query object
				$query_result = $this -> db -> query($query);

				if ($query_result -> num_rows() > 0) {
					foreach ($query_result->result_array() as $row) {
						$return_array[] = $row;
					}
					$query_result -> free_result();
				}

				return $return_array;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}
}
