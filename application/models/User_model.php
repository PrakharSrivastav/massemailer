<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function register_user($link) {
        try {
            $admin_data = array(
                "first_name" => "online-admin",
                "last_name" => "online-admin",
                "city" => "online",
                "state" => "online",
            );
            $online_admin_details = array();
            $online_admin_details = $this->generic_user_select($admin_data);

            if (count($online_admin_details) === 0) {
                throw Exception("Online admin does not exist in the database. Please check with your admin", 1);
            } else {
                $data = array(
                    "first_name" => $this->input->post('signup_first_name'),
                    "last_name" => $this->input->post('signup_last_name'),
                    "address_line_1" => $this->input->post('signup_add_1'),
                    "address_line_2" => $this->input->post('signup_add_2'),
                    "city" => $this->input->post('signup_city'),
                    "state" => $this->input->post('signup_state'),
                    "pincode" => $this->input->post('signup_pincode'),
                    "email" => $this->input->post('signup_email'),
                    "login_name" => $this->input->post('signup_first_name') . " " . $this->input->post('signup_last_name'),
                    "login_password" => password_hash($this->input->post('signup_password'), PASSWORD_BCRYPT),
                    "activation_code" => $link,
                    "creation_date" => date("Y-m-d H:i:s"),
                    "sender_email" => $online_admin_details[0]['sender_email'], //trim($this->input->post("s_email")),
                    "bounce_email" => $online_admin_details[0]['bounce_email'], //trim($this->input->post("b_email")),
                    "smtp_user" => $online_admin_details[0]['smtp_user'], //trim($this->input->post("smtp_user")),
                    "smtp_pass" => $online_admin_details[0]['smtp_pass'], //trim($this->input->post("smtp_pass")),
                    "smtp_port" => $online_admin_details[0]['smtp_port'], //trim($this->input->post("smtp_port")),
                    "smtp_auth" => $online_admin_details[0]['smtp_auth'], //trim($this->input->post("smtp_auth")),
                    "smtp_host" => $online_admin_details[0]['smtp_host'], //trim($this->input->post("smtp_host")),
                    "test_smtp_user" => $online_admin_details[0]['test_smtp_user'], //trim($this->input->post("test_smtp_user")),
                    "test_smtp_pass" => $online_admin_details[0]['test_smtp_pass'], //trim($this->input->post("test_smtp_pass")),
                    "test_smtp_port" => $online_admin_details[0]['test_smtp_port'], //trim($this->input->post("test_smtp_port")),
                    "test_smtp_auth" => $online_admin_details[0]['test_smtp_auth'], //trim($this->input->post("test_smtp_auth")),
                    "test_smtp_host" => $online_admin_details[0]['test_smtp_host'], //trim($this->input->post("test_smtp_host")),
                    "test_smtp_sender_id" => $online_admin_details[0]['test_smtp_sender_id'], //trim($this->input->post("test_s_email")),
                    "user_type" => 2,
                    "user_role" => 3,
                    "is_active" => 1,
                    "user_admin_id" => $online_admin_details[0]['user_id'], //$user_id
                );
                return $this->db->insert('user', $data);
            }
        } catch (Exception $e) {
            print_r($e);
        }
    }

    public function check_activation($email, $code) {
        try {
            $this->db->where('email', $email);
            $this->db->where('activation_code', $code);
            $this->db->where('is_active', 0);
            $result = $this->db->get('user');

            if ($result->num_rows() === 1) {

                // add logic to check if its 2 hours within user generation.
                $this->db->reset_query();
                $data = array('is_active' => 1);
                $this->db->where('email', $email);
                $this->db->where('activation_code', $code);
                $this->db->update('user', $data);
                return TRUE;
            } else
                return "User is not registered or account is already activated";
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_username($email) {
        try {
            return $this->get_user_field($email, 'first_name');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_user_field($email, $field_name) {
        try {
            if ($email === "" || $field_name === "") {
                throw new Exception("Blank value User-id: $email, Field-name: $field_name", 1);
            } else {
                $this->db->where('email', $email);
                //$this->db->where('is_active', 1);
                $this->db->select($field_name);
                $query = $this->db->get("user");

                if ($query->num_rows() > 0) {
                    $row = $query->first_row();
                    $return_value = $row->$field_name;
                    $query->free_result();
                    return $return_value;
                } else
                    return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function validate_user_login($email, $password) {

        $this->db->where('email', $email);
        $this->db->where('user_type', 2);
        $this->db->where('is_active', 1);
        $this->db->select('login_password,first_name,user_role,user_id');
        $result = $this->db->get('user');
        //$first_name ='';
        if ($result->num_rows() === 1) {
            $row = $result->row();
            $hashed_pw = $row->login_password;
            //$first_name = $row->first_name;

            if (password_verify($password, $hashed_pw)) {
                return array("return_status" => true, "user_name" => $row->first_name, "user_role" => $row->user_role, "user_id" => $row->user_id);
            } else {
                return array("return_status" => "Incorrect credentials. Please try again with correct details");
            }
        } else {
            return array("return_status" => "User not registered. Please signup and try again");
        }
    }

    public function get_user_data_by_role($user_role) {
        $my_user_id = $this->get_user_field($this->session->userdata("email"), "user_id");
        if ($my_user_id !== false) {
            $data = array(
//                'is_active' => 1,
                'user_role' => $user_role,
                "user_admin_id" => $my_user_id
            );
			$this->db->order_by("expire_date");
            $this->db->where($data);
            $this->db->select("*");
            $result = $this->db->get("user");
            $return_array = array();
            if ($result->num_rows() > 0) {
                foreach ($result->result_array() as $row) {
                    $return_array[] = $row;
                }
            }
            return $return_array;
        }
    }

    public function delete_user_by_role($email, $user_role) {
        $data = array('is_active' => 1, 'user_role' => $user_role, "email" => $email);
        $this->db->where($data);
        return ($this->db->delete("user"));
    }

    public function get_all_user_details($user_email, $detail_type) {
        $data = array('is_active' => 1, 'email' => $user_email); // only active group admins
        $this->db->where($data);
        if ($detail_type === 'login') {
            $this->db->select("first_name, last_name, expire_date, quota_total,quota_month,email,quota_hour");
            $result = $this->db->get("user");
            $return_array = array();
            if ($result->num_rows() > 0) {
                foreach ($result->result() as $row) {
                    $return_array = array(
                        $row->first_name,
                        $row->last_name,
                        $row->quota_total,
                        $row->quota_month,
                        $row->expire_date,
                        $row->email,
                        $row->quota_hour
                    );
                }
            }
        } else if ($detail_type === 'smtp') {
            $this->db->select("smtp_host,smtp_port,smtp_user,smtp_pass,smtp_auth,sender_email,bounce_email,test_smtp_host,test_smtp_port,test_smtp_user,test_smtp_pass,test_smtp_auth,test_smtp_sender_id,email");
            $result = $this->db->get("user");
            $return_array = array();
            if ($result->num_rows() > 0) {
                foreach ($result->result() as $row) {
                    $return_array = array(
                        $row->smtp_host,
                        $row->smtp_port,
                        $row->smtp_user,
                        $row->smtp_pass,
                        $row->smtp_auth,
                        $row->sender_email,
                        $row->bounce_email,
                        $row->test_smtp_host,
                        $row->test_smtp_port,
                        $row->test_smtp_user,
                        $row->test_smtp_pass,
                        $row->test_smtp_auth,
                        $row->test_smtp_sender_id,
                        $row->email
                    );
                }
            }
        } else if ($detail_type === 'contact') {
            $this->db->select("address_line_1,address_line_2,city,state,pincode,contact_num,mobile_num,email");
            $result = $this->db->get("user");
            $return_array = array();
            if ($result->num_rows() > 0) {
                foreach ($result->result() as $row) {
                    $return_array = array(
                        $row->address_line_1,
                        $row->address_line_2,
                        $row->city,
                        $row->state,
                        $row->pincode,
                        $row->contact_num,
                        $row->mobile_num,
                        $row->email
                    );
                }
            }
        }

        return $return_array;
    }

//    public function insert_user_data($activation_code) {
//        try {
//            $user_id = $this->get_user_field($this->session->userdata("email"), "user_id"); //get_userid($this->session->userdata("email"));
//            if ($user_id !== false) {
//                
//
//                return $this->db->insert('user', $data);
//            } else {
//                echo "Could not get the master/group admin user-id";
//            }
//        } catch (Exception $e) {
//            throw $e;
//        }
//    }
//    public function add_user_quota_by_role($email, $quota ,$quota_month) {
//        try {
//            if ($email === "" || $quota === "" || $quota_month === "") {
//                return "No user email-id/Quota provided ";
//            } else {
//                //$quota = 10000;
//                $current_quota = $this->get_user_field($email, "quota_total");
//                $where_data = array('email' => $email, "is_active" => 1, "user_admin_id" =>$this->session->userdata("user_id"));
//                $update_data = array('quota_total' => $current_quota + $quota,'quota_month'=>$quota_month);
//                $this->db->where($where_data);
//                return($this->db->update("user", $update_data));
//            }
//        } catch (Exception $e) {
//            throw $e;
//        }
//    }

    public function update_user_detail_from_edit_form($admin_email, $data) {
        try {
            if (is_array($data) || count($data) !== 0) {
                $this->db->where(array('email' => $admin_email, 'is_active' => 1, "user_admin_id" => $this->session->userdata("user_id")));
                return $this->db->update("user", $data);
            } else {
                throw new Exception("Please provide relevant input array for updating group admin data.", 1);
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public function change_password($new_password, $old_password) {
        try {
            if ($new_password === '' || $old_password === '') {
                throw new Exception("Password Change: Parameters are blank, please provide proper inputs", 1);
            } else {
                $email = $this->session->userdata('email');
                $result_status = $this->get_user_field($email, 'login_password');
                if ($result_status !== false && password_verify($old_password, $result_status)) {
                    $update_data = array('login_password' => password_hash($new_password, PASSWORD_BCRYPT));
                    $this->db->where(array('email' => $email, 'is_active' => 1));
                    return $this->db->update('user', $update_data);
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function change_profile_details($data) {
        try {
            if (!isset($data) || count($data) === 0) {
                throw new Exception("Profile Change: Parameters are blank, please provide proper inputs", 1);
            } else {
                //print_r($data);
                $email = $this->session->userdata('email');
                $password = trim($this->input->post("password"));
                //print_r($password);
                $result_status = $this->get_user_field($email, 'login_password');
                if ($result_status !== false && password_verify($password, $result_status)) {
                    $this->db->where(array('email' => $email, 'is_active' => 1));
                    return $this->db->update('user', $data);
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function forgotten_email_exists($email) {
        try {
            $database_email = $this->get_user_field($email, "email");
            if ($database_email !== false) {
                if ($email === $database_email)
                    return true;
                else
                    return false;
            }
            else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function change_forgotten_password($email, $new_password) {
        try {
            if ($email === "" || $new_password === "") {
                throw new Exception("Fprgoteen password change: please provide the correct parameters", 1);
            } else {
                $new_password = password_hash($new_password, PASSWORD_BCRYPT);
                $this->db->where(array("email" => $email, 'is_active' => 1));
                return $this->db->update('user', array('login_password' => $new_password));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_update_user_data($data) {
        try {
            if (is_array($data) && count($data) > 0) {
                // do updates for each iteration
                $status = false;
//                print_r($data);
                foreach ($data as $user) {
                    $this->db->where($user["where_array"]);
                    $status = $this->db->update("user", $user["update_array"]);
                }
                return $status;
            } else {
                throw new Exception("User Model::generic_update_user_data::Invalid array provided for update", 1);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_user_insert($data) {
        try {
            //print_r($data);
            //echo count($data);
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("List_model::generic_user_insert::invalid array provided for insert", 1);
            } else {
                # variable to show the status of the insert
                $status = false;

                # insert records for each list item in the $data array
                foreach ($data as $list) {
                    $status = $this->db->insert("user", $list);
                }

                # return the status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_my_lists_data($user_id) {
        try {
            if ($user_id === "") {
                throw new Exception("User_model::get_my_lists_data:Blank user id sent. Please valdate and try again.", 1);
            } else {
                $this->db->distinct();
                $this->db->select("list_master.list_id, list_master.list_name , user.email, user_list_relation.is_enabled,user_list_relation.shared_with");
                $this->db->from("user");
                $this->db->join("user_list_relation", "user.user_id = user_list_relation.user_id");
                $this->db->join("list_master", " user_list_relation.list_id = list_master.list_id");
                $this->db->where(array("user.user_id" => $user_id, "user.is_active" => '1'));
                $result = $this->db->get();
                $return_array = array();
                if ($result->num_rows() > 0) {
                    foreach ($result->result() as $row) {
                        $return_array[] = array(
                            $row->list_id,
                            $row->list_name,
                            $row->email,
                            $row->is_enabled,
                            $row->shared_with
                        );
                        //print_r($row);
                    }
                }
                return $return_array;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_lists_shared_with_me($user_id) {
        try {
            if ($user_id === "") {
                throw new Exception("User_model::get_lists_shared_with_me:Blank user id sent. Please valdate and try again.", 1);
            } else {
                $this->db->distinct();
                $this->db->select("list_master.list_id, list_master.list_name , c.email, user_list_relation.is_enabled,user_list_relation.shared_with");
                $this->db->from("user_list_relation");
                $this->db->join("user", "user_list_relation.shared_with = user.user_id");
                $this->db->join("user c", "c.user_id = user.user_admin_id");
                $this->db->join("list_master", "user_list_relation.list_id = list_master.list_id");
                $this->db->where(array("user_list_relation.shared_with" => $user_id));
                $result = $this->db->get();
                $return_array = array();
                if ($result->num_rows() > 0) {
                    foreach ($result->result() as $row) {
                        $return_array[] = array($row->list_id, $row->list_name, $row->email, $row->is_enabled, $row->shared_with);
                    }
                }
                return $return_array;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_user_select($data) {
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
                $query = $this->db->get("user");

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

    public function get_temp_smtp_field($data, $field_name) {
        try {
            if (empty($data) || $field_name === "") {
                throw new Exception("Incorrect input parameters, please provide relevant details", 1);
            } else {
                $this->db->where($data);
                $this->db->select($field_name);
                $query = $this->db->get("temp_smtp_setting");
//                print_r($user_id);
                if ($query->num_rows() > 0) {
                    $row = $query->first_row();
                    $return_value = $row->$field_name;
                    $query->free_result();
                    return $return_value;
                } else
                    return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_update_temp_smtp_data($data) {
        try {
            if (is_array($data) && count($data) > 0) {
                // do updates for each iteration
                $status = false;
//                print_r($data);
                foreach ($data as $user) {
                    $this->db->where($user["where"]);
                    $status = $this->db->update("temp_smtp_setting", $user["update"]);
                }
                return $status;
            } else {
                throw new Exception("User Model::generic_update_temp_smtp_data::Invalid array provided for update", 1);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_temp_smtp_insert($data) {
        try {
            //print_r($data);
            //echo count($data);
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("User Model::generic_temp_smtp_insert::invalid array provided for insert", 1);
            } else {
                # variable to show the status of the insert
                $status = false;

                # insert records for each list item in the $data array
                foreach ($data as $list) {
                    $status = $this->db->insert("temp_smtp_setting", $list);
                }

                # return the status
                return $status;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generic_temp_smtp_select($data) {
        try {
            if (!is_array($data) || empty($data) || count($data) === 0) {
                throw new Exception("user_list_relation_model::generic_temp_smtp_select::invalid array provided for select", 1);
            } else {
                # array for returning the results
                $return_array = array();

                # set the where clause
                $this->db->where($data);

                # select all the fields from the table
                $this->db->select("*");

                # get the result in query object
                $query = $this->db->get("temp_smtp_setting");

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
