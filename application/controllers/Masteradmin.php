<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Masteradmin extends CI_Controller {

	public function update_group_admin_data($admin_email, $type) {
		try {
			if ($this -> session -> userdata("is_logged_in") && $this -> session -> has_userdata("user_role") && $this -> session -> userdata("user_role") === '1') {
				$data = array();
				$first_name = $this -> input -> post("firstname");
				$last_name = $this -> input -> post("lastname");
				$ex_date = $this -> input -> post("ex_date");
				$quota_total = $this -> input -> post("quota_total");
				$quota_month = $this -> input -> post("quota_month");
				$smtp_host = $this -> input -> post("smtp_host");
				$smtp_port = $this -> input -> post("smtp_port");
				$smtp_user = $this -> input -> post("smtp_user");
				$smtp_pass = $this -> input -> post("smtp_pass");
				$smtp_auth = $this -> input -> post("smtp_auth");
				$sender_email = $this -> input -> post("s_email");
				$bounce_email = $this -> input -> post("b_email");
				$test_smtp_host = $this -> input -> post("test_smtp_host");
				$test_smtp_port = $this -> input -> post("test_smtp_port");
				$test_smtp_user = $this -> input -> post("test_smtp_user");
				$test_smtp_pass = $this -> input -> post("test_smtp_pass");
				$test_smtp_auth = $this -> input -> post("test_smtp_auth");
				$test_s_email = $this -> input -> post("test_s_email");
				$address_1 = $this -> input -> post("address");
				$address_2 = $this -> input -> post("address2");
				$city = $this -> input -> post("city");
				$state = $this -> input -> post("state");
				$pincode = $this -> input -> post("pincode");
				$c_number = $this -> input -> post("c_number");
				$m_number = $this -> input -> post("m_number");

				if ($type === "login") {
					// $admin_email;
					$this -> load -> model("User_model", "user");
					$current_quota = $this -> user -> get_user_field($admin_email, "quota_total");
					$update_data = array('first_name' => $first_name, "last_name" => $last_name, "expire_date" => $ex_date, 'quota_total' => $current_quota + $quota_total, 'quota_month' => $quota_month);
					$return_status = $this -> user -> update_user_detail_from_edit_form($admin_email, $update_data);
					//echo "the return status is $return_status";

					# log add quota event
					$user_id = $this -> user -> get_user_field($admin_email, "user_id");
					$this -> load -> model("Event_model", "event");
					$data = array("e_name" => "ADD_QUOTA", "e_amount" => $quota_total, "e_details" => "MASTER_ADMIN", "e_user_to" => $user_id, "e_user_by" => $this -> session -> userdata("user_id"), "e_info" => "Quota assigned to group admin", "e_date" => date("Y-m-d H:i:s"));

					$this -> event -> insert(array($data));

					if ($return_status) {
						$this -> edit_group_admin();
					} else {
						echo $return_status;
					}
				} else if ($type === "smtp") {
					//echo "smtp";
					$data['smtp_host'] = $smtp_host;
					$data['smtp_port'] = $smtp_port;
					$data['smtp_auth'] = $smtp_auth;
					$data['smtp_pass'] = $smtp_pass;
					$data['smtp_user'] = $smtp_user;
					$data['test_smtp_user'] = $test_smtp_user;
					$data['test_smtp_host'] = $test_smtp_host;
					$data['test_smtp_port'] = $test_smtp_port;
					$data['test_smtp_auth'] = $test_smtp_auth;
					$data['test_smtp_pass'] = $test_smtp_pass;
					$data['sender_email'] = $sender_email;
					$data['bounce_email'] = $bounce_email;
					$data['test_smtp_sender_id'] = $test_s_email;
					$data["smtp_test_saccount"] = trim($this -> input -> post("test_smtp_subaccount"));
					$data["smtp_saccount"] = trim($this -> input -> post("smtp_subaccount"));
					//print_r($data);
					$this -> load -> model("User_model", "user");
					$return_status = $this -> user -> update_user_detail_from_edit_form($admin_email, $data);
					//print_r($return_status);
					if ($return_status) {
						$this -> edit_group_admin();
					} else {
						echo $return_status;
					}
				} else if ($type === "contact") {
					//echo "contact";
					$data['address_line_1'] = $address_1;
					$data['address_line_2'] = $address_2;
					$data['city'] = $city;
					$data['state'] = $state;
					$data['pincode'] = $pincode;
					$data['contact_num'] = $c_number;
					$data['mobile_num'] = $m_number;
					$this -> load -> model("User_model", "user");
					$return_status = $this -> user -> update_user_detail_from_edit_form($admin_email, $data);
					//                    print_r($return_status);
					if ($return_status) {
						$this -> edit_group_admin();
					} else {
						$this -> load -> view("pages/error_message", array("message" => $return_status));
					}
				}
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function delete_group_admin($user_email = "") {
		try {
			if ($this -> session -> userdata("is_logged_in") && $this -> session -> has_userdata("user_role") && $this -> session -> userdata("user_role") === '1') {

				$this -> load -> model("User_model", "user");
				$delete_status = array();
				if ($user_email !== "") {
					$user_name = $this -> user -> get_user_field($user_email, "first_name");
					$user_dir = strtolower(str_replace(array("@", "."), "_", "upload/" . $user_name . $user_email));
					$delete_status = $this -> user -> delete_user_by_role($user_email, 2);
					if (is_dir($user_dir)) {
						rmdir($user_dir);
					}
				}

				$return_array = $this -> user -> get_user_data_by_role(2);

				$this -> load -> view("pages/delete-group-admin", array("group_admin_list" => $return_array, "delete_status" => $delete_status));
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Excception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function edit_group_admin($user_email = '', $detail_type = '') {
		try {
			# if the user is logged in
			if ($this -> session -> userdata("is_logged_in") && $this -> session -> has_userdata("user_role") && $this -> session -> userdata("user_role") === '1') {

				# load the user model
				$this -> load -> model("User_model", "user");
				$return_array = $this -> user -> get_user_data_by_role(2);

				//print_r($return_array);
				$user_details = array();
				if ($user_email !== '' && $detail_type !== "") {
					$user_details = $this -> user -> get_all_user_details($user_email, $detail_type);
					//print_r($user_details);
				}

				$this -> load -> view('pages/edit-group-admin', array("group_admin_list" => $return_array, "get_all_user_details" => $user_details, "detail_type" => $detail_type));
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function create_group_admin() {
		try {
			if ($this -> session -> userdata("is_logged_in") && $this -> session -> has_userdata("user_role") && $this -> session -> userdata("user_role") === '1') {
				$this -> load -> view('pages/create-group-admin');
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function master_admin_dashboard() {
		try {
			if ($this -> session -> userdata("is_logged_in") && $this -> session -> has_userdata("user_role") && $this -> session -> userdata("user_role") === '1') {
				$this -> load -> model("User_model", "user");
				$return_array = $this -> user -> get_user_data_by_role(2);
				// print_r($return_array);
				$this -> load -> view('pages/master-admin-dashboard', array("group_admin_list" => $return_array));
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Excception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function insert_group_admin() {
		try {
			# if the user is logged in
			if ($this -> session -> userdata("is_logged_in") && $this -> session -> has_userdata("user_role") && $this -> session -> userdata("user_role") === '1') {

				# load the user model
				$this -> load -> model("User_model", "user");
				$activation_code = uniqid();
				$email = $this -> input -> post('email');
				$fname = $this -> input -> post('firstname');
				$activation_link = base_url() . "authentication/activation/" . $email . '/' . $activation_code;

				# For SMTP either 0 or 1 is sent from the form.
				# 0 means groups admin's primary SMTP details will be assigned to user
				# 1 means group admin's secondary(test) SMTP details will be assigned to user
				$smtp_address_type = $this -> input -> post("smtp_detail");

				# get the email corresponding to the above values
				$where = array("user_id" => $this -> session -> userdata("user_id"), "email" => $this -> session -> userdata("email"), "user_role" => $this -> session -> userdata("user_role"), );

				# prepare the data for inserting into the database
				$data = array("first_name" => trim($this -> input -> post("firstname")), "last_name" => trim($this -> input -> post("lastname")), "address_line_1" => trim($this -> input -> post("address")), "address_line_2" => trim($this -> input -> post("address2")), "city" => trim($this -> input -> post("city")), "state" => trim($this -> input -> post("state")), "pincode" => trim($this -> input -> post("pincode")), "email" => trim($this -> input -> post("email")), "contact_num" => trim($this -> input -> post("c_number")), "mobile_num" => trim($this -> input -> post("m_number")), "creation_date" => date("Y-m-d H:i:s"), "expire_date" => trim($this -> input -> post("ex_date")), "smtp_saccount" => trim($this -> input -> post("smtp_subaccount")), "quota_month" => trim($this -> input -> post("quota_monthly")), "smtp_test_saccount" => trim($this -> input -> post("test_smtp_subaccount")), "sender_email" => trim($this -> input -> post("s_email")), "bounce_email" => trim($this -> input -> post("b_email")), "smtp_user" => trim($this -> input -> post("smtp_user")), "smtp_pass" => trim($this -> input -> post("smtp_pass")), "smtp_port" => trim($this -> input -> post("smtp_port")), "smtp_auth" => trim($this -> input -> post("smtp_auth")), "smtp_host" => trim($this -> input -> post("smtp_host")), "test_smtp_user" => trim($this -> input -> post("test_smtp_user")), "test_smtp_pass" => trim($this -> input -> post("test_smtp_pass")), "test_smtp_port" => trim($this -> input -> post("test_smtp_port")), "test_smtp_auth" => trim($this -> input -> post("test_smtp_auth")), "test_smtp_host" => trim($this -> input -> post("test_smtp_host")), "test_smtp_sender_id" => trim($this -> input -> post("test_s_email")), "user_type" => 2, "login_name" => trim($this -> input -> post("firstname")) . " " . trim($this -> input -> post("lastname")), "login_password" => password_hash(trim($this -> input -> post("password")), PASSWORD_BCRYPT), "user_role" => trim($this -> input -> post("user_role")), "is_active" => 0, "activation_code" => $activation_code, "user_admin_id" => $this -> session -> userdata("user_id"));

				# If the user details are inserted properly into the user table
				if ($this -> user -> generic_user_insert(array($data))) {
					$status = false;

					# Insert the details in the temp_smtp_settings as well
					$user_id = $this -> user -> get_user_field($this -> input -> post("email"), "user_id");
					if (!empty($user_id)) {

						$user_temp_id = $this -> user -> get_temp_smtp_field(array("user_id" => $user_id), 'user_id');
						if ($user_id === $user_temp_id) {

							# prepare update data
							$update_data = array("smtp_host" => trim($this -> input -> post("smtp_host")), "smtp_port" => trim($this -> input -> post("smtp_port")), "smtp_auth" => trim($this -> input -> post("smtp_auth")), "smtp_pass" => trim($this -> input -> post("smtp_pass")), "smtp_user" => trim($this -> input -> post("smtp_user")), );

							# where data
							$where = array("user_id" => $user_id);

							# combine where and update
							$update_array = array("where" => $where, "update" => $update_data);

							# perform an update
							$status = $this -> user -> generic_update_temp_smtp_data(array($update_array));
						} else {
							# perform an insert
							# prepare insert data
							$insert_data = array("user_id" => $user_id, "smtp_host" => trim($this -> input -> post("smtp_host")), "smtp_port" => trim($this -> input -> post("smtp_port")), "smtp_auth" => trim($this -> input -> post("smtp_auth")), "smtp_pass" => trim($this -> input -> post("smtp_pass")), "smtp_user" => trim($this -> input -> post("smtp_user")), );

							# perform an insert
							$status = $this -> user -> generic_temp_smtp_insert(array($insert_data));

						}
					} else {
						# perform an insert
						# prepare insert data
						$insert_data = array("user_id" => $user_id, "smtp_host" => trim($this -> input -> post("smtp_host")), "smtp_port" => trim($this -> input -> post("smtp_port")), "smtp_auth" => trim($this -> input -> post("smtp_auth")), "smtp_pass" => trim($this -> input -> post("smtp_pass")), "smtp_user" => trim($this -> input -> post("smtp_user")), );

						# perform an insert
						$status = $this -> user -> generic_temp_smtp_insert(array($insert_data));
					}

					$email_admin_details = $this -> user -> generic_user_select(array("user_type" => 2, "user_role" => 4));

					$this -> load -> library('email');
					$config['protocol'] = 'smtp';
					$config['mailtype'] = 'html';
					$config['smtp_host'] = $email_admin_details[0]['smtp_host'];
					$config['smtp_crypto'] = $email_admin_details[0]['smtp_auth'];
					$config['smtp_user'] = $email_admin_details[0]['email'];
					$config['smtp_pass'] = trim($email_admin_details[0]['smtp_pass']);
					$config['smtp_port'] = (int)$email_admin_details[0]['smtp_port'];
					$config['charset'] = 'utf-8';
					$config['wordwrap'] = false;
					$this -> email -> initialize($config);
					$this -> email -> subject("You activation link");
					$this -> email -> message("Hi $fname, <br /> Your account has been registered. <br />To activate your account please <a href='$activation_link'>click here</a>.<br />Please activate your account.<br />Thanks,<br />Admin.");
					$this -> email -> to($email);
					$this -> email -> from($email_admin_details[0]['email'], $email_admin_details[0]['first_name']);
					$this -> email -> reply_to($email_admin_details[0]['email']);

					# log add quota event
					$this -> load -> model("Event_model", "event");
					$data = array("e_name" => "ADD_QUOTA", "e_amount" => 20, "e_details" => "MASTER_ADMIN", "e_user_to" => $user_id, "e_user_by" => $this -> session -> userdata("user_id"), "e_info" => "Quota assigned", "e_date" => date("Y-m-d H:i:s"));

					$this -> event -> insert(array($data));

					if ($status && $this -> email -> send()) {
						$smtp_details = $this -> user -> get_all_user_details($this -> session -> userdata("email"), 'smtp');
						$this -> load -> view("pages/create-group-admin", array('smtp_details' => $smtp_details, "return_status" => $status));
					} else {
						throw new Exception("Could not send activation email");
					}
				} else {
					throw new Exception("A user with same email-id is already present in the database. Please go back and use a different email-id to create the user.");
				}
			} else {
				throw new Exception("123 You are not authorized to view this page OR Your session has expired");
			}
		} catch (Exception $e) {
			# in case of exception show error page
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function show_create_list_form() {
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {
				redirect("listcontroller/show_create_list_form");
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function get_users_under_gadmin($admin_id) {
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {
				if (is_null($admin_id) || $admin_id === "") {
					throw new Exception("Group admin email not provided, Please check and try again");
				} else {

					# get all the users with admin-id as $admin_id
					$where = array("user_admin_id" => $admin_id);

					$this -> load -> model("User_model", "user");
					$users = $this -> user -> generic_user_select($where);
					$return_array = $this -> user -> get_user_data_by_role(2);
					$this -> load -> view('pages/master-admin-dashboard', array("group_admin_list" => $return_array, "users" => $users));
				}
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function get_reports() {
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {
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
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {

				$query = "select a.e_name as e_name, a.e_amount as e_amount , a.e_details as e_details, c.email as e_user_by,b.email as e_user_to, a.e_info as e_info , a.e_date as e_date " . "from events a, user b, user c" . " where a.e_user_to = b.user_id and " . "a.e_user_by = c.user_id and " . "a.e_name = 'ADD_QUOTA' "
				// . "a.e_details = 'MASTER_ADMIN' "
				."order by e_user_by,e_date DESC";

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
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {

				$query = "SELECT a.`campaign_id`,d.`list_name`,b.`email`,a.`progress` ,a.`bounce_id`,a.`spam_id`,a.`soft_bounce_id`,a.`click_id`, a.`reject_id`, a.`open_id`, a.`smtp_details`, a.`subscriber_ids`, c.`template_name`, a.`subject`, a.`sender_name` from campaign_data a ,user b,template_master c,list_master d where a.list_id = d.list_id and a.user_id = b.user_id and a.template_id = c.template_id order by campaign_id,a.send_time ";

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
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {

				$query = "SELECT c.email,d.list_name,d.list_id,d.list_description ,count(1) as Total, sum(case when e.email_status = '1' then 1 else 0 end) as '1', sum(case when e.email_status = 2 then 1 else 0 end) as '2', sum(case when e.email_status = '3' then 1 else 0 end) as '3' , sum(case when e.email_status = 4 then 1 else 0 end) as '4', sum(case when e.email_status = 5 then 1 else 0 end) as '5', sum(case when e.email_status = 6 then 1 else 0 end) as '6', sum(case when e.email_status = 7 then 1 else 0 end) as '7' ,sum(case when e.valid = '1' then 1 else 0 end) as invalid FROM `user_list_relation` a, `list_subscriber_relation` b , `user` c , `list_master` d, `subscriber_master` e where (a.user_id = c.user_id or a.shared_with = c.user_id) and a.list_id = b.list_id and a.list_id = d.list_id and b.subscriber_id = e.subscriber_id group by c.email,d.list_name";

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
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {

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
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {
				
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
				order by campaign_id,a.send_time" ;

				$this -> load -> model("Campaign_model", "campaign");

				$return = $this -> campaign -> run_query($query);
				//print_r($return);
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
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {
						
				$where = array("id"=>$row_id);
				$update = array("progress"=>'4');
				$data = array("where"=>$where,"update"=>$update);
				$this -> load -> model("Campaign_model", "campaign");
				$this->campaign->generic_campaign_update(array($data));
				
				redirect("masteradmin/manage_queues");
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}
	
	public function start_campaign($row_id){
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '1') {
						
				$where = array("id"=>$row_id,"progress"=>'4');
				$update = array("progress"=>'2');
				$data = array("where"=>$where,"update"=>$update);
				$this -> load -> model("Campaign_model", "campaign");
				$this->campaign->generic_campaign_update(array($data));
				
				redirect("masteradmin/manage_queues");
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}
	

}
