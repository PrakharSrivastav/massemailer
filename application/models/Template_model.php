<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Template_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * $data = array(array($where_array), array($where_array),......)
     * $where_array 	-- array with the where clause
     */

    public function generic_template_delete($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("List_model::generic_template_delete::invalid array provided for update", 1);
            } else {
                # variable for status of deletion process
                $status = false;

                # delete the records from template_master table for each new template
                foreach ($data as $template) {
                    $this->db->where($template["where"]);
                    $status = $this->db->delete("template_master");
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
     * $update_array 	-- array with template of fields to be updated
     */

    public function generic_template_update($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("List_model::generic_template_update::invalid array provided for update", 1);
            } else {

                # variable for status of update
                $status = false;
                //print_r($data);
                # update the template_master table for each new template
                foreach ($data as $template) {
                    $this->db->where($template["where"]);
                    $status = $this->db->update("template_master", $template["update"]);
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

    public function generic_template_select($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("List_model::generic_template_select::invalid array provided for select", 1);
            } else {
                # array for returning the results
                $return_array = array();

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select("*");

                # get the result in query object
                $query = $this->db->get("template_master");

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

    public function generic_template_insert($data) {
        try {
            //print_r($data);
            //echo count($data);
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("List_model::generic_template_insert::invalid array provided for insert", 1);
            } else {
                # variable to show the status of the insert
                $status = false;

                # insert records for each template item in the $data array
                foreach ($data as $template) {
                    $status = $this->db->insert("template_master", $template);
                }

                # return the status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_template_select_field($data, $field_name) {
        try {
            //print_r($data);
            if (!is_array($data) || empty($data) || count($data) === 0 || $field_name === "") {
                throw new Exception("List_model::generic_template_select_field::invalid array/ or field name provided for select", 1);
            } else {
                # variable for returning the results
                $return_value = "";

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select($field_name);

                # get the result in query object
                $query = $this->db->get("template_master");
                //print_r($query);
                if ($query->num_rows() > 0) {
                    $row = $query->first_row();
                    //print_r($row);
                    $return_value = $row->$field_name;
                    $query->free_result();
                    //return $return_value;
                }
                //echo "template-id is".$return_value;
                return $return_value;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_my_tempalte_data($user_id) {
        try {
            if ($user_id === "") {
                throw new Exception("Template_model::get_my_template_data:Blank user id sent. Please valdate and try again.", 1);
            } else {
                $this->db->distinct();
                //$this->db->select("user.email, user.first_name , template_master.template_name, template_master.template_id,user_template.shared_with");
                $this->db->select("user.email, user.first_name , template_master.template_name, template_master.template_id");
                $this->db->from("user");
                $this->db->join("user_template", "user.user_id = user_template.user_id");
                $this->db->join("template_master", " user_template.template_id = template_master.template_id");
                $this->db->where(array("user.user_id" => $user_id,"is_active"=>'1'));
                $result = $this->db->get();
                $return_array = array();
                if ($result->num_rows() > 0) {
                    foreach ($result->result() as $row) {
                       // $return_array[] = array($row->email, $row->first_name, $row->template_name, $row->template_id, $row->shared_with);
                      $return_array[] = array($row->email, $row->first_name, $row->template_name, $row->template_id, 0);
                    }
                }

                $this->db->distinct();
                $this->db->select("c.email, user.first_name , template_master.template_name, template_master.template_id,user_template.shared_with");
                $this->db->from("user");
                $this->db->join("user_template", "user.user_id = user_template.shared_with");
                $this->db->join("user c", "c.user_id = user.user_admin_id");
                $this->db->join("template_master", " user_template.template_id = template_master.template_id");
                $this->db->where(array("user_template.shared_with" => $user_id));
                $result = $this->db->get();
                $return_array_1 = array();
                if ($result->num_rows() > 0) {
                    foreach ($result->result() as $row) {
                        $return_array_1[] = array($row->email, $row->first_name, $row->template_name, $row->template_id, $row->shared_with);
                    }
                }
                return array_merge($return_array,$return_array_1);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
