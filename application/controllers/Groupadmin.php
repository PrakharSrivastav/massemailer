<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class GroupAdmin extends CI_Controller {

/*
    public function group_admin_dashboard() {
        try {
            # if the user is logged in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $this->session->userdata('user_role') === '2') {
                # load user model
                $this->load->model("User_model", "user");
                # get the user data 
                $return_array = $this->user->get_user_data_by_role(3);
                $this->load->view("pages/group-admin-dashboard", array('user_list' => $return_array));
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
            
        } catch (Exception $e) {
            # in case of exception show error page
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }*/


    public function show_create_user_form() {
        try {
            # if the user is logged in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $this->session->userdata('user_role') === '2') {
                # load user model
                $this->load->model("User_model", "user");

                # get the smtp details from user table
                $smtp_details = $this->user->get_all_user_details($this->session->userdata("email"), 'smtp');
                #print_r($smtp_details);
                $this->load->view('pages/create-users', array('smtp_details' => $smtp_details));
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            # in case of exception show error page
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    public function insert_users() {
        try {
            # if the user is logged in
            if ($this->session->userdata("is_logged_in") && $this->session->has_userdata("user_role") && $this->session->userdata("user_role") === '2') {

                # variables
                $smtp_host = "";
                $smtp_port = "";
                $smtp_auth = "";
                $smtp_pass = "";
                $smtp_user = "";
                $smtp_send = "";

                # load the user model
                $this->load->model("User_model", "user");
                $activation_code = uniqid();
                $email = $this->input->post('email');
                $fname = $this->input->post('firstname');
                $activation_link = base_url() . "authentication/activation/" . $email . '/' . $activation_code;

                # For SMTP either 0 or 1 is sent from the form.
                # 0 means groups admin's primary SMTP details will be assigned to user
                # 1 means group admin's secondary(test) SMTP details will be assigned to user
                $smtp_address_type = $this->input->post("smtp_detail");

                # get the email corresponding to the above values
                $where = array(
                    "user_id" => $this->session->userdata("user_id"),
                    "email" => $this->session->userdata("email"),
                    "user_role" => $this->session->userdata("user_role"),
                );

                # get the group admin (current user's) data
                $ga_data = $this->user->generic_user_select($where);
                //print_r($ga_data);
                # if the type is 0 then assign the primary smtp address to user
                if ($smtp_address_type === '0') {
                    $smtp_host = $ga_data[0]["smtp_host"];
                    $smtp_port = $ga_data[0]["smtp_port"];
                    $smtp_auth = $ga_data[0]["smtp_auth"];
                    $smtp_pass = $ga_data[0]["smtp_pass"];
                    $smtp_user = $ga_data[0]["smtp_user"];
                    $smtp_send = $ga_data[0]["sender_email"];
					$subaccount = $ga_data[0]["smtp_saccount"];
                }
                # else if the type is 1 then assign the test smtp details to the user
                else if ($smtp_address_type === '1') {
                    $smtp_host = $ga_data[0]["test_smtp_host"];
                    $smtp_port = $ga_data[0]["test_smtp_port"];
                    $smtp_auth = $ga_data[0]["test_smtp_auth"];
                    $smtp_pass = $ga_data[0]["test_smtp_pass"];
                    $smtp_user = $ga_data[0]["test_smtp_user"];
                    $smtp_send = $ga_data[0]["test_smtp_sender_id"];
					$subaccount = $ga_data[0]["smtp_test_saccount"];
                }

                # prepare the data for inserting into the database
                $data = array(
                    "first_name" => trim($this->input->post("firstname")),
                    "last_name" => trim($this->input->post("lastname")),
                    "address_line_1" => trim($this->input->post("address")),
                    "address_line_2" => trim($this->input->post("address2")),
                    "city" => trim($this->input->post("city")),
                    "state" => trim($this->input->post("state")),
                    "pincode" => trim($this->input->post("pincode")),
                    "email" => trim($this->input->post("email")),
                    "contact_num" => trim($this->input->post("c_number")),
                    "mobile_num" => trim($this->input->post("m_number")),
                    "creation_date" => date("Y-m-d H:i:s"),
                    "expire_date" => trim($this->input->post("ex_date")),
                    //"quota_total" => trim($this->input->post("quota_total")),
                    "quota_month" => trim($this->input->post("quota_monthly")),
                    "quota_hour" => trim($this->input->post("quota_hour")),
                    "sender_email" => $smtp_send, //trim($this->input->post("s_email")),
                    "bounce_email" => $smtp_send, //trim($this->input->post("b_email")),
                    "smtp_user" => $smtp_user, //trim($this->input->post("smtp_user")),
                    "smtp_pass" => $smtp_pass, //trim($this->input->post("smtp_pass")),
                    "smtp_port" => $smtp_port, //trim($this->input->post("smtp_port")),
                    "smtp_auth" => $smtp_auth, //trim($this->input->post("smtp_auth")),
                    "smtp_host" => $smtp_host, //trim($this->input->post("smtp_host")),
                    "smtp_saccount" => $subaccount, //trim($this->input->post("test_smtp_user")),
                    "test_smtp_pass" => "", //trim($this->input->post("test_smtp_pass")),
                    "test_smtp_port" => "", //trim($this->input->post("test_smtp_port")),
                    "test_smtp_auth" => "", //trim($this->input->post("test_smtp_auth")),
                    "test_smtp_host" => "", //trim($this->input->post("test_smtp_host")),
                    "test_smtp_sender_id" => "", //trim($this->input->post("test_s_email")),
                    "user_type" => 2,
                    "login_name" => trim($this->input->post("firstname")) . " " . trim($this->input->post("lastname")),
                    "login_password" => password_hash(trim($this->input->post("password")), PASSWORD_BCRYPT),
                    "user_role" => trim($this->input->post("user_role")),
                    "is_active" => 0,
                    "activation_code" => $activation_code,
                    "user_admin_id" => $this->session->userdata("user_id")
                );

                # If the user details are inserted properly into the user table
                if ($this->user->generic_user_insert(array($data))) {
                    $status = false;
                    # Insert the details in the temp_smtp_settings as well
                    $user_id = $this->user->get_user_field($this->input->post("email"), "user_id");
                    if (!empty($user_id)) {
                        $user_temp_id = $this->user->get_temp_smtp_field(array("user_id" => $user_id), 'user_id');
	                       if ($user_id === $user_temp_id) {
                            # prepare update data
                            $update_data = array(
                                "smtp_host" => $smtp_host,
                                "smtp_port" => $smtp_port,
                                "smtp_auth" => $smtp_auth,
                                "smtp_pass" => $smtp_pass,
                                "smtp_user" => $smtp_user,
                                "subaccount" => $subaccount,
                            );

                            # where data 
                            $where = array("user_id" => $user_id);

                            # combine where and update
                            $update_array = array("where" => $where, "update" => $update_data);

                            # perform an update
                            $status = $this->user->generic_update_temp_smtp_data(array($update_array));
                        } else {
                            # perform an insert
                            # prepare insert data
                            $insert_data = array(
                                "user_id" => $user_id,
                                "smtp_host" => $smtp_host,
                                "smtp_port" => $smtp_port,
                                "smtp_auth" => $smtp_auth,
                                "smtp_pass" => $smtp_pass,
                                "smtp_user" => $smtp_user,
                                "subaccount" => $subaccount,
                            );

                            # perform an insert
                            $status = $this->user->generic_temp_smtp_insert(array($insert_data));
//                            throw new Exception("Invalid user id in the database(temp_smtp_setting table).");
                        }
                    } else {
                        # perform an insert
                        # prepare insert data
                        $insert_data = array(
                            "user_id" => $user_id,
                            "smtp_host" => $smtp_host,
                            "smtp_port" => $smtp_port,
                            "smtp_auth" => $smtp_auth,
                            "smtp_pass" => $smtp_pass,
                            "smtp_user" => $smtp_user,
                            "subaccount" => $subaccount,
                        );

                        # perform an insert
                        $status = $this->user->generic_temp_smtp_insert(array($insert_data));
							
                    }

                    $email_admin_details = $this->user->generic_user_select(array("user_type" => 2, "user_role" => 4));
                    
                    
                    $this->load->library('email');
                    $config['protocol'] = 'smtp';
                    $config['mailtype'] = 'html';
                    $config['smtp_host'] = $email_admin_details[0]['smtp_host'];
                    $config['smtp_crypto'] = $email_admin_details[0]['smtp_auth'];
                    $config['smtp_user'] = $email_admin_details[0]['email'];
                    $config['smtp_pass'] = trim($email_admin_details[0]['smtp_pass']);
                    $config['smtp_port'] = (int) $email_admin_details[0]['smtp_port'];
                    $config['charset'] = 'utf-8';
                    $config['wordwrap'] = false;
                    $this->email->initialize($config);
                    $this->email->subject("You activation link");
                    $this->email->message("Hi $fname, <br /> Your account has been registered. <br />To activate your account please <a href='$activation_link'>click here</a>.<br />Please activate your account.<br />Thanks,<br />Admin.");
                    $this->email->to($email);
                    $this->email->from($email_admin_details[0]['email'], $email_admin_details[0]['first_name']);
                    $this->email->reply_to($email_admin_details[0]['email']);
                    
                    # log add quota event
					$this->load->model("Event_model","event");
					$data = array (
						"e_name"    => "ADD_QUOTA",
						"e_amount"  => 20,
						"e_details" => "GROUP_ADMIN",
						"e_user_to" => $user_id,
						"e_user_by"	=> $this->session->userdata("user_id"),
						"e_info"	=> "Quota assigned to user",
						"e_date"	=> date("Y-m-d H:i:s")
					);
							
					$this->event->insert(array($data));
                    if ($status && $this->email->send()) {
                        $total_quota = (int)$this->user->get_user_field($this->session->userdata("email"),"quota_total");
                        $total_quota = $total_quota - 20 ; 
//                        var_dump($total_quota);
                        $used_quota = (int)$this->user->get_user_field($this->session->userdata("email"),"quota_used");
                        $used_quota = $used_quota + 20 ; 
//                        var_dump($used_quota);
                        $where = array(
                            "user_id"=>  $this->session->userdata("user_id"),
                            "email"  => $this->session->userdata("email"),
                            "user_role"=>"2",
                            "is_active"=>1
                        );
                        $update = array(
                            "quota_total"   =>  $total_quota,
                            "quota_used"    =>  $used_quota
                        );
                        
                        $upd = array("where_array"=>$where,"update_array"=>$update);
                        
                        $this->user->generic_update_user_data(array($upd));
                        $smtp_details = $this->user->get_all_user_details($this->session->userdata("email"), 'smtp');
                        $this->load->view(
                                "pages/create-users", array(
                            'smtp_details' => $smtp_details,
                            "return_status" => $status)
                        );
                    } else {
                        throw new Exception("Could not send activation email");
                    }
                } else {
                    throw new Exception("A user with same email-id is already present in the database. Please go back and use a different email-id to create the user.");
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            # in case of exception show error page
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    public function show_edit_user_form($user_email = '', $detail_type = '') {
        try {
            # if the user is logged in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $this->session->userdata('user_role') === '2') {
                # load user model
                $this->load->model("User_model", "user");
                # get the data for this user
                $return_array = $this->user->get_user_data_by_role(3);

                # get the details for this ueer from user_model
                $user_details = array();
                if ($user_email !== '' && $detail_type !== "") {
                    $user_details = $this->user->get_all_user_details($user_email, $detail_type);
                }

                $this->load->view('pages/edit-users', array(
                    "group_admin_list" => $return_array,
                    "get_all_user_details" => $user_details,
                    "detail_type" => $detail_type)
                );
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            # in case of exception show error page
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    public function update_user_edited_data($admin_email, $type) {
        try {
            # if the user is logged in
            if ($this->session->userdata("is_logged_in") && $this->session->has_userdata("user_role") && $this->session->userdata("user_role") === '2') {
                $data = array();
                $first_name = $this->input->post("firstname");
                $last_name = $this->input->post("lastname");
                $ex_date = $this->input->post("ex_date");
                $quota_hour = $this->input->post("quota_hour");
                $quota_month = $this->input->post("quota_month");
                $smtp_host = $this->input->post("smtp_host");
                $smtp_port = $this->input->post("smtp_port");
                $smtp_user = $this->input->post("smtp_user");
                $smtp_pass = $this->input->post("smtp_pass");
                $smtp_auth = $this->input->post("smtp_auth");
                $sender_email = $this->input->post("s_email");
                $bounce_email = $this->input->post("b_email");
                $test_smtp_host = $this->input->post("test_smtp_host");
                $test_smtp_port = $this->input->post("test_smtp_port");
                $test_smtp_user = $this->input->post("test_smtp_user");
                $test_smtp_pass = $this->input->post("test_smtp_pass");
                $test_smtp_auth = $this->input->post("test_smtp_auth");
                $test_s_email = $this->input->post("test_s_email");
                $address_1 = $this->input->post("address");
                $address_2 = $this->input->post("address2");
                $city = $this->input->post("city");
                $state = $this->input->post("state");
                $pincode = $this->input->post("pincode");
                $c_number = $this->input->post("c_number");
                $m_number = $this->input->post("m_number");

                if ($type === "login") {
                    # load user model
                    $this->load->model("User_model", "user");
                    $current_quota = $this->user->get_user_field($admin_email, "quota_total");
                    $update_data = array(
                        'first_name' => $first_name,
                        "last_name" => $last_name,
                        "expire_date" => $ex_date,
                        'quota_hour' => $quota_hour,
                        'quota_month' => $quota_month
                    );

                    $return_status = $this->user->update_user_detail_from_edit_form($admin_email, $update_data);
                    #print_r($return_status);
                    if ($return_status) {
                        redirect("groupadmin/show_edit_user_form");
                    } else {
                        $this->load->view("pages/error_message", array("message" => $return_status));
                    }
                } else if ($type === "smtp") {

                    # For SMTP either 0 or 1 is sent from the form.
                    # 0 means groups admin's primary SMTP details will be assigned to user
                    # 1 means group admin's secondary(test) SMTP details will be assigned to user
                    $smtp_address_type = $this->input->post("smtp_detail");

                    # get the email corresponding to the above values
                    $where = array(
                        "user_id" => $this->session->userdata("user_id"),
                        "email" => $this->session->userdata("email"),
                        "user_role" => $this->session->userdata("user_role"),
                    );

                    # get the group admin (current user's) data
                    $this->load->model("User_model", "user");
                    $ga_data = $this->user->generic_user_select($where);

                    # if the type is 0 then assign the primary smtp address to user
                    if ($smtp_address_type === '0') {
                        $smtp_host = $ga_data[0]["smtp_host"];
                        $smtp_port = $ga_data[0]["smtp_port"];
                        $smtp_auth = $ga_data[0]["smtp_auth"];
                        $smtp_pass = $ga_data[0]["smtp_pass"];
                        $smtp_user = $ga_data[0]["smtp_user"];
                        $smtp_send = $ga_data[0]["sender_email"];
						$subaccount = $ga_data[0]["smtp_saccount"];
                    }
                    # else if the type is 1 then assign the test smtp details to the user
                    else if ($smtp_address_type === '1') {
                        $smtp_host = $ga_data[0]["test_smtp_host"];
                        $smtp_port = $ga_data[0]["test_smtp_port"];
                        $smtp_auth = $ga_data[0]["test_smtp_auth"];
                        $smtp_pass = $ga_data[0]["test_smtp_pass"];
                        $smtp_user = $ga_data[0]["test_smtp_user"];
                        $smtp_send = $ga_data[0]["test_smtp_sender_id"];
						$subaccount = $ga_data[0]["smtp_test_saccount"];
                    }
                    $status = false;
                    # get the user_id of the user from the $admin_email
                    $user_id = $this->user->get_user_field($admin_email, "user_id");

                    # first insert the data in the temporary table
                    $user_temp_id = $this->user->get_temp_smtp_field(array("user_id" => $user_id), 'user_id');
                    if ($user_temp_id !== false) {
                        if ($user_id === $user_temp_id) {
                            # prepare update data
                            $update_data = array(
                                "smtp_host" => $smtp_host,
                                "smtp_port" => $smtp_port,
                                "smtp_auth" => $smtp_auth,
                                "smtp_pass" => $smtp_pass,
                                "smtp_user" => $smtp_user,
                                "subaccount" => $subaccount,
                            );

                            # where data 
                            $where = array("user_id" => $user_id);

                            # combine where and update
                            $update_array = array("where" => $where, "update" => $update_data);

                            # perform an update
                            $status = $this->user->generic_update_temp_smtp_data(array($update_array));
                        } else {
                            throw new Exception("Could not get your data from the database please check with your admin.");
                        }
                    } else {
                        # perform an insert
                        # prepare insert data
                        $insert_data = array(
                            "user_id" => $user_id,
                            "smtp_host" => $smtp_host,
                            "smtp_port" => $smtp_port,
                            "smtp_auth" => $smtp_auth,
                            "smtp_pass" => $smtp_pass,
                            "smtp_user" => $smtp_user,
                            "subaccount" => $subaccount,
                        );

                        # perform an insert
                        $status = $this->user->generic_temp_smtp_insert(array($insert_data));
                    }
                    if ($status) {
                        # prepare the update data
                        $where = array(
                            "email" => $admin_email,
                            "is_active" => "1",
                            "user_role" => "3"
                        );

                        $update = array(
                            "sender_email" => $smtp_send,
                            "bounce_email" => $smtp_send,
                            "smtp_user" => $smtp_user,
                            "smtp_pass" => $smtp_pass,
                            "smtp_port" => $smtp_port,
                            "smtp_auth" => $smtp_auth,
                            "smtp_host" => $smtp_host,
                            "smtp_saccount" => $subaccount,
                        );

                        $data = array(
                            "where_array" => $where,
                            "update_array" => $update
                        );

                        # update the database with the proper smtp details.
                        if ($this->user->generic_update_user_data(array($data))) {
                            redirect("groupadmin/show_edit_user_form");
                        } else {
                            $this->load->view("pages/error_message", array("message" => $return_status));
                        }
                    }
                } else if ($type === "contact") {
                    #echo "contact";
                    $data['address_line_1'] = $address_1;
                    $data['address_line_2'] = $address_2;
                    $data['city'] = $city;
                    $data['state'] = $state;
                    $data['pincode'] = $pincode;
                    $data['contact_num'] = $c_number;
                    $data['mobile_num'] = $m_number;
                    # load user model
                    $this->load->model("User_model", "user");
                    $return_status = $this->user->update_user_detail_from_edit_form($admin_email, $data);
                    #print_r($return_status);
                    if ($return_status) {
                        redirect("groupadmin/show_edit_user_form");
                    } else {
                        $this->load->view("pages/error_message", array("message" => $return_status));
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            # in case of exception show error page
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    public function show_delete_user_form($user_email = "") {
        try {
            # if the user is logged in
            if ($this->session->userdata("is_logged_in") && $this->session->has_userdata("user_role") && $this->session->userdata("user_role") === '2') {
                # load user model
                $this->load->model("User_model", "user");
                $delete_status = array();
                if ($user_email !== "") {
                    $delete_status = $this->user->delete_user_by_role($user_email, 3);
                }
                $return_array = $this->user->get_user_data_by_role(3);
                $this->load->view("pages/delete-users", array("group_admin_list" => $return_array, "delete_status" => $delete_status));
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Excception $e) {
            # in case of exception show error page
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    public function group_admin_dashboard($useremail = "", $show_results = false, $error = false) {
        try {
            # if the user is logged in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $this->session->userdata('user_role') === '2') {
                # get the email - id from session
                $email = $this->session->userdata("email");

                # variables
                $quota_total = 0;
                $quota_used = 0;
                $quota_user_total = 0;
                $quota_user_used = 0;
                $show_form = false;
                $showresult = false;
                $error_status = false;

                # load the user model and get relevant values
                $this->load->model("User_model", "user");
                $my_total_quota = $this->user->get_user_field($email, 'quota_total');
                $my_used_quota = $this->user->get_user_field($email, 'quota_used');

                # when the quota from the data base are available from the previous step
                if ($my_total_quota !== false && $my_used_quota !== false) {

                    # cast the string values from the database as integer
                    $quota_total = (int) $my_total_quota;
                    $quota_used = (int) $my_used_quota;

                    # get the details of the children(users) from the database for this group admin
                    $my_users = $this->user->get_user_data_by_role(3);
                    
//                    print_r($my_users);
                    
                    # sum of the childrens' total quota and used quota
                    foreach ($my_users as $user) {
                        $quota_user_total += (int) $user['quota_total'];
                        $quota_user_used += (int) $user['quota_used'];
                    }

                    # if user email-id is provided in the url then show the edit form, else just show the status details
                    if ($useremail !== "")
                        $show_form = true;

                    # show results when quota details are properly set
                    if ($show_results)
                        $showresult = true;

                    # if there are issues with the quota limits then show error messages
                    if ($error)
                        $error_status = true;

                    # only when the quota integrity between the admin and the user is consistent (group admin used quota === sum of children total quota)
                    //if ($quota_used === $quota_user_total) {
                    if(true){
                        $statistics_data = array(
                            "error_status" => $error_status,
                            "showresult" => $showresult,
                            "showform" => $show_form,
                            "my_users" => $my_users,
                            "quota_total" => $quota_total,
                            "quota_used" => $quota_used,
                            "quota_user_total" => $quota_user_total,
                            "quota_user_used" => $quota_user_used,
                            "useremail" =>$useremail
                        );
                        $this->load->view("pages/group-admin-dashboard", $statistics_data);
                    } else
                        throw new Exception("The Used quota is not equal to the assigned quota to the users.");
                } else {
                    throw new Exception("Not able to fetch the your quota details");
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            # in case of exception show error page
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    public function add_user_quota_details($user_email) {
        try {
            # if the user is logged in
            if ($user_email !== "" && $this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $this->session->userdata('user_role') === '2') {
                # get admin email
                $email = $this->session->userdata("email");
                # load user model
                $this->load->model("User_model", "user");

                # get relevant data from the model
                $my_total_quota = $this->user->get_user_field($email, 'quota_total');
                $my_used_quota = $this->user->get_user_field($email, 'quota_used');
                $my_user_id = $this->user->get_user_field($email, 'user_id');

                # if the values are fetched properly from the form
                if ($my_total_quota !== false && $my_used_quota !== false) {

                    # get the user quota from form
                    $user_quota = (int) $this->input->post("quota_total");
					//print_r($user_quota);
                   	if ($user_quota === "" || is_string($user_quota) || $user_quota < 0)
                        $user_quota = 0;
					//print_r($user_quota);
					
                    # if admin quota >= quota entered in the form
                    if ((int) $my_total_quota >= $user_quota) {
                        $data = array();
                        # increment the quota of user
                        # set the update parameters for admin
                        $where_admin = array(
                        	"email" => $email, 
                        	"is_active" => 1, 
                        	"user_role" => $this->session->userdata('user_role')
						);
                        # decrease total quota
                        $my_new_quota = (int) ((int) $my_total_quota - (int) $user_quota);
						
						//echo "my new quota : $my_new_quota";
						# if my_quota === 0,
                        if ($my_new_quota === 0) {
                            $this->load->view("pages/message", array(
                                "message" => "Your assigned quota has exhausted. Please check with your admin to get more quota")
                            );
                        }
						
                        # increse used_quota
                        $my_new_used_quota = (int) ((int) $my_used_quota + (int) $user_quota);
						//echo "my new used quota : $my_new_used_quota";
                        $update_admin = array(
                        	"quota_total" => $my_new_quota, 
                        	"quota_used" => $my_new_used_quota
						);
                        $data[] = array(
                        	"where_array" => $where_admin, 
                        	"update_array" => $update_admin
						);
						//print_r($data);	
                        # set the update parameters for user
                        $where_user = array(
                        	"email" => $user_email, 
                        	//"is_active" => 1, 
                        	"user_role" => 3, 
                        	"user_admin_id" => $my_user_id
						);
                        # increase total quota
                        $quota_total_user = (int) $this->user->get_user_field($user_email, 'quota_total');
                        $update_user = array("quota_total" => $quota_total_user + $user_quota);
						
						//print_r($update_user);
						
                        $data[] = array(
                        	"where_array" => $where_user, 
                        	"update_array" => $update_user
						);
						
						//print_r($data);	
						
                        # call the model to update the database
                        $status = $this->user->generic_update_user_data($data);

                        //echo "update status is $status";

                        # if the update was successfull, load the view
                        if ($status) {
                        	# log add quota event
                        	$user_id = $this->user->get_user_field($user_email, 'user_id');
							$this->load->model("Event_model","event");
							$data = array (
								"e_name"    => "ADD_QUOTA",
								"e_amount"  => $user_quota,
								"e_details" => "GROUP_ADMIN",
								"e_user_to" => $user_id,
								"e_user_by"	=> $this->session->userdata("user_id"),
								"e_info"	=> "Quota assigned to user",
								"e_date"	=> date("Y-m-d H:i:s")
							);
									
							$this->event->insert(array($data));
                            $this->group_admin_dashboard("", true, false);
                        } else {
                            throw new Exception("Could not update the user quota");
                        }
                    } else {
                        $this->group_admin_dashboard("", false, true);
                    }
                } else {
                    throw new Exception("Could not get quota details for you. Please check with your admin");
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            # in case of exception show error page
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    public function apply_list_limits_to_users($user_email, $limit_type) {
        try {
            # if the user is logged in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $this->session->userdata('user_role') === '2') {
                if ($user_email === "" || $limit_type === "") {
                    throw new Exception("GroupAdmin::apply_list_limits_to_users::Please provide proper inputs", 1);
                } else {
                    if ($limit_type === "apply") {
                        $where_data = array("email" => $user_email, "user_list_limit" => 0, "is_active" => 1);
                        $updata_data = array("user_list_limit" => 10);
                    } else if ($limit_type === 'remove') {
                        $where_data = array("email" => $user_email, "user_list_limit" => 10, "is_active" => 1);
                        $updata_data = array("user_list_limit" => 0);
                    }
                    $data[] = array("where_array" => $where_data, "update_array" => $updata_data);

                    # call the model to update the database
                    $this->load->model("User_model", "user");
                    $status = $this->user->generic_update_user_data($data);
                    if ($status) {
                        $this->group_admin_dashboard("", false, false);
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this form OR Your session has expired");
            }
        } catch (Exception $e) {
            # in case of exception show error page
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }
	
	public function get_reports() {
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '2') {
				$this -> load -> view("pages/reports");
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}
	
	public function get_quota_reports() {
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '2') {

				$query = "select a.e_name as e_name, a.e_amount as e_amount , a.e_details as e_details, c.email as e_user_by, b.email as e_user_to, a.e_info as e_info , a.e_date as e_date". 
				" from events a, user b, user c ".
				"where a.e_user_to = b.user_id ".
				"and a.e_user_by = c.user_id ".
				" and (a.e_user_by = '".trim($this->session->userdata("user_id"))."' or a.e_user_to = '".trim($this->session->userdata("user_id"))."')". 
				" and a.e_name = 'ADD_QUOTA'". 
				" order by e_user_by,e_date DESC ";

				$this -> load -> model("Event_model", "event");

				$return = $this -> event -> run_query($query);
				$this -> load -> view("pages/reports", array("quota_details" => $return));
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function get_campaign_reports() {
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '2') {

				$query = "SELECT a.`campaign_id`,d.`list_name`,b.`email`,a.`progress` ,a.`bounce_id`,a.`spam_id`,a.`soft_bounce_id`,a.`click_id`, a.`reject_id`, a.`open_id`, a.`smtp_details`, a.`subscriber_ids`, c.`template_name`, a.`subject`, a.`sender_name`".
				" from campaign_data a , user b, template_master c, list_master d".
				" where a.list_id = d.list_id".
				" and a.user_id = b.user_id".
				" and b.user_admin_id = '".trim($this->session->userdata("user_id"))."'". 
				" and a.template_id = c.template_id".
				" order by campaign_id,a.send_time ";

				$this -> load -> model("Campaign_model", "campaign");

				$return = $this -> campaign -> run_query($query);
				//print_r($return);
				$this -> load -> view("pages/reports", array("campaign_details" => $return));
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}
	
	public function get_list_reports(){
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '2') {

				$query = "SELECT c.email,d.list_name,d.list_id,d.list_description ,count(1) as Total,".
				" sum(case when e.email_status = '1' then 1 else 0 end) as '1',".
				" sum(case when e.email_status = 2 then 1 else 0 end) as '2',".
				" sum(case when e.email_status = '3' then 1 else 0 end) as '3' ,".
				" sum(case when e.email_status = 4 then 1 else 0 end) as '4',".
				" sum(case when e.email_status = 5 then 1 else 0 end) as '5',".
				" sum(case when e.email_status = 6 then 1 else 0 end) as '6',".
				" sum(case when e.email_status = 7 then 1 else 0 end) as '7' ,".
				" sum(case when e.valid = '1' then 1 else 0 end) as invalid".
				" FROM `user_list_relation` a,".
				" `list_subscriber_relation` b ,".
				" `user` c ,".
				" `list_master` d,".
				" `subscriber_master` e".
				" where (a.user_id = c.user_id or a.shared_with = c.user_id)".
				" and (c.user_admin_id = '".trim($this->session->userdata('user_id'))."' or c.user_id = '".trim($this->session->userdata('user_id'))."')".
				" and a.list_id = b.list_id".
				" and a.list_id = d.list_id".
				" and b.subscriber_id = e.subscriber_id".
				" group by c.email,d.list_name";

				$this -> load -> model("Campaign_model", "campaign");

				$return = $this -> campaign -> run_query($query);
				//print_r($return);
				$this -> load -> view("pages/reports", array("list_details" => $return));
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}
	
	public function current_report(){
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '2') {

				$query = "SELECT 
				a.campaign_id,
				a.user_id,
				c.email, 
				a.`subject`, 
				a.`sender_name`,
				d.`list_name`, 
				a.`subscriber_ids`,
				a.`progress` ,
				a.`id` 
				from campaign_data a , list_master d ,user c
				where a.list_id = d.list_id 
				and a.user_id = c.user_id
				and a.progress = '2'
				and c.user_admin_id = '".trim($this->session->userdata("user_id"))."'
				order by campaign_id,a.send_time" ;

				$this -> load -> model("Campaign_model", "campaign");

				$return = $this -> campaign -> run_query($query);
				//print_r($return);
				$this -> load -> view("pages/reports", array("current_status" => $return));
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}
	
	public function manage_queues() {
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '2') {
				
				$query = "SELECT 
				a.campaign_id,
				a.user_id,
				c.email, 
				a.`subject`, 
				a.`sender_name`,
				d.`list_name`, 
				a.`quota`,
				a.`sent_emails`,
				a.`progress` ,
				a.`id` 
				from campaign_data a , list_master d ,user c
				where a.list_id = d.list_id 
				and a.user_id = c.user_id
				and c.user_admin_id = '".trim($this->session->userdata("user_id"))."'
				order by campaign_id,a.send_time" ;

				$this -> load -> model("Campaign_model", "campaign");
				$return = $this -> campaign -> run_query($query);
				$this -> load -> view("pages/manage-queues", array("queues" => $return));
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function stop_campaign($row_id){
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '2') {
						
				$where = array("id"=>$row_id);
				$update = array("progress"=>'4');
				$data = array("where"=>$where,"update"=>$update);
				$this -> load -> model("Campaign_model", "campaign");
				$this->campaign->generic_campaign_update(array($data));
				
				redirect("groupadmin/manage_queues");
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}
	
	public function start_campaign($row_id){
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '2') {
						
				$where = array("id"=>$row_id,"progress"=>'4');
				$update = array("progress"=>'2');
				$data = array("where"=>$where,"update"=>$update);
				$this -> load -> model("Campaign_model", "campaign");
				$this->campaign->generic_campaign_update(array($data));
				
				redirect("groupadmin/manage_queues");
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}
	

}
