<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    # this function show the users(user_role =3) their dashboard
    public function users_dashboard() {
        try {
            # if the user is logged-in and has user-role = 3
            if ($this->session->has_userdata("user_role") && $this->session->userdata("user_role") === "3" && $this->session->userdata("is_logged_in")) {
                # load the user dashboard
                
                # get the current time
                $dt = new DateTime("now");
				# get the current year and month
				$y = $dt->format("Y");
				$m = $dt->format("m");
				
				# set new date for the start of the month (day 1, starting 00:00:00)
				$dt_new = new DateTime($y."-".$m."-"."1 00:00:00");
				$d = $dt_new->format("Y-m-d H:i:s");
				$u = $this->session->userdata("user_id");
				
				$query = "SELECT count(1) as count from campaign_data where send_time > '$d' and user_id = '$u'" ;

				$this -> load -> model("Campaign_model", "campaign");
				$return = $this -> campaign -> run_query($query);
				//print_r($return);
				$camp = 0;
				if(empty($return) || count($return) === 0){
					$camp = 0;
				}
				else {
					$camp = $return[0]['count'];
				} 
				
				$query = "select count(1) as count from user_list_relation where user_id = '$u' or shared_with = '$u'";
				$this -> load -> model("User_list_relation_model", "user_list");
				$return2 = $this -> user_list -> run_query($query);
				$lists = 0;
				//print_r($return2);
				if(empty($return) || count($return) === 0){
					$lists = 0;
				}
				else {
					$lists = $return2[0]['count'];
				} 
				$this->load->view("pages/users-dashboard",array("campaigns"=>$camp,"lists"=>$lists));
            } else {
                # if user is logged-in OR the session has expired, show below message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
        } catch (Exception $e) {
            # in case of any other exception, show it on the user screen
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }
    
    # redirects the users to the view list forms
    public function show_create_list_form() {
        try {
            # if the user is logged-in and has user-role = 3
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $this->session->userdata('user_role') === '3') {
                redirect("listcontroller/show_create_list_form");
            } else {
                # if user is logged-in OR the session has expired, show below message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
        } catch (Exception $e) {
            # in case of any other exception, show it on the user screen
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    # used in case user wants to chagne his SMTP details
    public function edit_smtp_settings() {
        try {
            # if the user is logged-in and has user-role = 3
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $this->session->userdata('user_role') === '3') {
                $this->load->view("pages/smtp-settings");
            } else {
                # if user is logged-in OR the session has expired, show below message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
        } catch (Exception $e) {
            # in case of any other exception, show it on the user screen
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }
    
    # show the SMTP settings form to the user
    # O will changs it to the default setting
    # 1 will show the form to enter new SMTP settings
    public function show_settings_form() {
        try {
            # if the user is logged-in and has user-role = 3
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') &&
                    $this->session->userdata('user_role') === '3') {
                # Get the input post parameters
                # User will send 0 (default-SMTP settings) or 1 (Personal SMTP settings)
                $smtp_type = $this->input->post("smtp_setting_type");
                //var_dump($this->input->post());
                # if default setting is chosen by the user
                if ($smtp_type === "0") {
                    $this->load->model("User_model", "user");
                    # pick the details from the temp table
                    $temp_details = $this->user->generic_temp_smtp_select(array("user_id" => $this->session->userdata("user_id")));
                    //print_r($temp_details);
                    if ($this->session->userdata("user_id") === $temp_details[0]['user_id']) {
                        # prepare the update data
                        $update = array(
                            "sender_email" => $temp_details[0]['smtp_user'],
                            "bounce_email" => $temp_details[0]['smtp_user'],
                            "smtp_user" => $temp_details[0]['smtp_user'],
                            "smtp_pass" => $temp_details[0]['smtp_pass'],
                            "smtp_port" => $temp_details[0]['smtp_port'],
                            "smtp_auth" => $temp_details[0]['smtp_auth'],
                            "smtp_host" => $temp_details[0]['smtp_host'],
                        );
                        
                        # prepare the where array
                        $where = array(
                            "user_id" => $this->session->userdata("user_id"),
                            "email" => $this->session->userdata("email"),
                            "user_role" => $this->session->userdata("user_role"),
                            "is_active" => '1'
                        );
                        
                        # prepare the array to be sent to the update method
                        $update_array = array(array("where_array" => $where, "update_array" => $update));
                        if ($this->user->generic_update_user_data($update_array)) {
                            $this->load->view("pages/smtp-settings", array("success" => true));
                        }
                    }
                    # # update the data
                    # load the view with success
                } else if ($smtp_type === "1") {
                    $this->load->view("pages/smtp-settings", array("personal_setting" => true));
                }
            } else {
                # if user is logged-in OR the session has expired, show below message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
        } catch (Exception $e) {
            # in case of any other exception, show it on the user screen
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    public function update_external_settings() {
        try {
            # if the user is logged-in and has user-role = 3
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $this->session->userdata('user_role') === '3') {
                
                # load the form validation library
                $this->load->library("form_validation");
                //print_r($this->input->post());
                
                # set the frm validation rules
                $this->form_validation->set_rules('smtp_host', 'SMTP Host', 'required');
                $this->form_validation->set_rules('smtp_port', 'SMTP Port', 'required');
                $this->form_validation->set_rules('smtp_user', 'SMTP User', 'required|valid_email');
                $this->form_validation->set_rules('smtp_pass', 'SMTP Host', 'required');
                $this->form_validation->set_rules('smtp_auth', 'SMTP Host', 'required');
                
                # run the form validation rules
                if ($this->form_validation->run()) {
                    
                    # prepare the update array
                    $update = array(
                        "smtp_host" => $this->input->post("smtp_host"),
                        "smtp_port" => $this->input->post("smtp_port"),
                        "smtp_user" => $this->input->post("smtp_user"),
                        "smtp_auth" => $this->input->post("smtp_auth"),
                        "smtp_pass" => $this->input->post("smtp_pass"),
                        "sender_email" => $this->input->post("smtp_user"),
                        "bounce_email" => $this->input->post("smtp_user"),
                    );
                    
                    # prepare the where clause
                    $where = array(
                        "user_id" => $this->session->userdata("user_id"),
                        "email" => $this->session->userdata("email"),
                        "user_role" => $this->session->userdata("user_role"),
                        "is_active" => '1'
                    );
                    
                    # prepare the array to be sent to the update method of the model
                    $update_array = array(array("where_array" => $where, "update_array" => $update));
                    
                    # load the user model
                    $this->load->model("User_model", "user");
                    
                    # update the user SMTP details in the database
                    if ($this->user->generic_update_user_data($update_array)) {
                        # on success show the success message
                        $this->load->view("pages/smtp-settings", array("success" => true));
                    } else {
                        # on failure show the error message on the screen
                        $this->load->view("pages/smtp-settings", array("failure" => true));
                    }
                } else {
                    # if form validation fail, provide a feedbask to the user
                    $this->load->view("pages/smtp-settings", array("failure" => true));
                }
            } else {
                # if user is logged-in OR the session has expired, show below message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
        } catch (Exception $e) {
            # in case of any other exception, show it on the user screen
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

	public function manage_queues() {
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '3') {
				
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
				and a.user_id = '".trim($this->session->userdata("user_id"))."'
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

	public function get_reports() {
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '3') {
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
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '3') {

				$query = "select a.e_name as e_name, a.e_amount as e_amount , a.e_details as e_details, c.email as e_user_by, b.email as e_user_to, a.e_info as e_info , a.e_date as e_date". 
				" from events a, user b, user c ".
				"where a.e_user_to = b.user_id ".
				"and a.e_user_by = c.user_id ".
				" and (a.e_user_to = '".trim($this->session->userdata("user_id"))."')". 
				" and a.e_name = 'ADD_QUOTA'". 
				" and a.e_details = 'GROUP_ADMIN'". 
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
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '3') {

				$query = "SELECT a.`campaign_id`,d.`list_name`,b.`email`,a.`progress` ,a.`bounce_id`,a.`spam_id`,a.`soft_bounce_id`,a.`click_id`, a.`reject_id`, a.`open_id`, a.`smtp_details`, a.`subscriber_ids`, c.`template_name`, a.`subject`, a.`sender_name`".
				" from campaign_data a , user b, template_master c, list_master d".
				" where a.list_id = d.list_id".
				" and a.user_id = b.user_id".
				" and a.user_id = '".trim($this->session->userdata("user_id"))."'". 
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
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '3') {

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
	
	public function stop_campaign($row_id){
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '3') {
						
				$where = array("id"=>$row_id);
				$update = array("progress"=>'4');
				$data = array("where"=>$where,"update"=>$update);
				$this -> load -> model("Campaign_model", "campaign");
				$this->campaign->generic_campaign_update(array($data));
				
				redirect("users/manage_queues");
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}
	
	public function start_campaign($row_id){
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '3') {
						
				$where = array("id"=>$row_id,"progress"=>'4');
				$update = array("progress"=>'2');
				$data = array("where"=>$where,"update"=>$update);
				$this -> load -> model("Campaign_model", "campaign");
				$this->campaign->generic_campaign_update(array($data));
				
				redirect("users/manage_queues");
			} else {
				$this -> load -> view("pages/error_message", array("message" => "You are not authorized to view this page"));
			}
		} catch (Exception $e) {
			$this -> load -> view("pages/error_message", array("message" => $e -> getMessage()));
		}
	}

	public function current_report(){
		try {
			if ($this -> session -> has_userdata("is_logged_in") && $this -> session -> userdata('is_logged_in') && $this -> session -> userdata('user_role') === '3') {

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
				and a.user_id = '".trim($this->session->userdata("user_id"))."'
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
}
