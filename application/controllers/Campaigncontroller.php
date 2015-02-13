<?php

//ini_set('max_execution_time', 3600);
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaigncontroller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    # Function to show the campaign page to the user (user_role = 3)

    public function show_create_campaign_page() {
        try {
            # if the user is logged-in
            if ($this->session->has_userdata("is_logged_in") &&
                    $this->session->userdata('is_logged_in') &&
                    $this->session->userdata("user_role") === "3") {

                # load the model
                $this->load->model("Template_model", "template");

                # get the template details
                $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));

                # load user model
                $this->load->model("User_model", "user");

                # get the page data details
                $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));

                # get the default sender email id for the form
                # this will be set as the reply to address.
                $reply_to = $this->user->get_user_field($this->session->userdata("email"), "sender_email");

                # load the crete campaign view
                $this->load->view("pages/create-campaign-page", array(
                    "template_data" => $template_data,
                    "list_data" => $page_data,
                    "reply_to" => $reply_to
                ));
            } else {
                # if user is logged-in OR the session has expired, show below message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
        } catch (Exception $ex) {
            # in case of exception, show the exception on the screen
            $this->load->view("pages/error_message", array(
                "message" => $ex->getMessage())
            );
        }
    }

    # function to add all the emails to the queue

    public function set_emails_to_queue() {
        try {
            # if the user is logged-in
            if ($this->session->has_userdata("is_logged_in") &&
                    $this->session->userdata('is_logged_in') &&
                    $this->session->userdata("user_role") === "3") {

                # get the values from the post variables
                $template_name = $this->input->post("template_name");
                $list_name = $this->input->post("list_name");
                $campaign_sub = $this->input->post("campaign_subject");
				$send_time = $this->input->post("senddate1");
				//print_r($send_time);
                # test the input variables: check validations below
                # template name should not be null or blank
                # list name should be an array and sould have atleast an element
                # campaign subject should not be null or blank
                # test and throw exception in case any of the fields are blank
                if (($template_name === "" || is_null($template_name)) ||
                        (!is_array($list_name) || count($list_name) === 0) ||
                        (($campaign_sub === "" || is_null($campaign_sub)))) {
                    throw new Exception("Invalid input parameters for sending campaign");
                } else {
                    	
                    # set the parameters from the session data and the post input variables
                    # split the template_name to get template name and template id
                    $template_arr = explode("|", $this->input->post("template_name"));
                    $template_id = $template_arr[1];

                    # there are several list names, these are available as an array
                    $list_id = $this->input->post("list_name");
                    $sender_id = $this->session->userdata("user_id");
                    $campaign_sub = $this->input->post("campaign_subject");
                    $campaign_from = $this->input->post("camp_sender_name");
                    $reply_to = $this->input->post("reply_to");

                    # validate all the input parameters here before sending the details to the queue
                    if ($this->validate_sending_queue_data($template_id, $list_id, $sender_id, $campaign_sub, $campaign_from, $reply_to)) {
                        # load the user model
                        $this->load->model("User_model", "user");

                        # get the user_quota
                        $my_quota = $this->user->get_user_field($this->session->userdata("email"), 'quota_hour');

                        # if user has a fixed hourly quota
                        if ($my_quota > 0) {

                            # load the list_subscriber_model
                            $this->load->model("List_subscriber_model", "list_subscriber");
                            $list_details = array();

                            $subscriber_count = 0;
                            # iterate for each list in the form post data
                            foreach ($list_id as $list) {
                                	
                                # do a join and get the total number of proper customer in each list
                                # list name
                                $temp_subs["name"] = $list;
                                
                                # list data
                                $temp_subs["data"] = $this->list_subscriber->get_all_subscriber_ids(array("list_id" => $list));
                                
                                # add list details to the list_details array
                                $list_details [] = $temp_subs;
                                $subscriber_count += count($temp_subs["data"]);
                            }

                            $total_quota = $this->user->get_user_field($this->session->userdata("email"), 'quota_total');
                            $used_quota = $this->user->get_user_field($this->session->userdata("email"), 'quota_used');
                            $available_q = $total_quota - $used_quota;
                            
                            if ($subscriber_count > $available_q) {
                                throw new Exception("Number of subscribers in the mailing list exceeds your total quota limit. Please ask your admin to assign you additional quota.");
                            } else {
                                	
                                $new_used_quota = $used_quota + $subscriber_count;
                                $update = array(
                                    "where_array" => array(
                                        "user_id" => $this->session->userdata("user_id"),
                                        "is_active" => 1,
                                        "email" => $this->session->userdata("email"),
                                        "user_role" => $this->session->userdata("user_role")
                                    ),
                                    "update_array" => array(
                                        "quota_used" => $new_used_quota
                                    )
                                );
                                
                                if (!$this->user->generic_update_user_data(array($update))) {
                                    throw new Exception("Could not update the total quota for you. Please inform this to your admin.");
                                }
                            }


                            # make batches of lists of hourly quota limit
                            # foreach list
                            # do an array splice of first 250 customers
                            $list_batch = array();

                            foreach ($list_details as $list) {
                                	
                                # count of subscribers in the list
                                $count = count($list["data"]);
                                
                                # get the batch size = (number of subscribers/hourly quota) + 1
                                # +1 is done to accomodate the remainder
                               $batch_size = 1 + ($count / $my_quota);
                                // $batch_size = 1 + ($count / 200);
                                
                                # temp variable
                                $arr = $list["data"];

                                # iterate through the batch size
                                for ($x = 0; $x < $batch_size; $x++) {
                                    	
                                    # if the array is not empty split the list into batches of hourly quota
                                    if (!empty($arr)) {
                                        $temp["name"] = $list["name"];
                                        $temp["data"] = array_splice($arr, 0, $my_quota);
                                        $list_batch[] = $temp;
                                    }
                                }
                            }

                            # get the smtp details from the database
                            $search = array(
                                "user_id" => $this->session->userdata("user_id"),
                                "email" => $this->session->userdata("email"),
                                "user_role" => $this->session->userdata("user_role"),
                                "is_active" => 1
                            );

                            # get all the details from the database for this user
                            $all_details = $this->user->generic_user_select($search);
                            $smtp_details = array();
                            
                            # make sure that there are records for current user in the database
                            if (count($all_details) <= 0) {
                                throw new Exception("No data for this user in the database");
                            } else {
                                $smtp_details ["smtp_user"] = $all_details[0]['smtp_user'];
                                $smtp_details ["smtp_pass"] = $all_details[0]['smtp_pass'];
                                $smtp_details ["smtp_port"] = $all_details[0]['smtp_port'];
                                $smtp_details ["smtp_auth"] = $all_details[0]['smtp_auth'];
                                $smtp_details ["smtp_host"] = $all_details[0]['smtp_host'];
                                $smtp_details ["sender_name"] = $campaign_from;
                            }

                            # get the send_to data from the user table                            
                            $db_date = $this->user->get_user_field($this->session->userdata("email"), "send_time");
                            $user_send_to = new DateTime($db_date);

                            # get the current date
                            $date_today = new DateTime("now");
                            $next_send_to;
                            $inc_date_flag = false;

                            # get the time difference between the dates
                            #   if the user_send_to date <0 (new user) or the time difference is less than 1 hour
                            #   update the date to start of next hour
                            $interval = $date_today->diff($user_send_to);
                            if ($interval->days > 1 || ($interval->invert === 1 && $interval->m < 60)) {
                                $next_send_to = $date_today->add(new DateInterval("P0Y0M0DT0H" . (60 - $interval->i) . "M" . (60 - $interval->s) . "S"));
                            }

                            #   if the date is more than an hour 
                            #   add 1 hour to the date
                            else if (($interval->invert === 0)) {
                                $next_send_to = $user_send_to->add(new DateInterval("P0Y0M0DT1H0M" . (60 - $interval->s) . "S"));
                            }


                            # this date will be updated in the user table
                            # get the date from send_campaign table for any record in 1 and 2 status
                            # generate a unique campaign-id
                            $campaign_id = uniqid() . date("ymdhis");

                            $db_insert_data = array();
                            foreach ($list_batch as $batch) {
                                # for each iteration increment the campaign send date by an hour
                                # create the metadata
                                $meta_data = uniqid() . uniqid();

                                # added a flag to not increment the date for the first time
                                # date should be changed for the next lists getting queued
                                if ($inc_date_flag) {
                                    $next_send_to = $next_send_to->add(new DateInterval("P0Y0M0DT1H0M0S"));
                                }

                                # prepare the insert data
                                $insert_data = array(
                                    "user_id" => $this->session->userdata("user_id"),
                                    "list_id" => $batch["name"],
                                    "send_time" => $next_send_to->format("Y-m-d H:i:s"),
                                    "bounce_id" => "",
                                    "spam_id" => "",
                                    "soft_bounce_id" => "",
                                    "click_id" => "",
                                    "reject_id" => "",
                                    "open_id" => "",
                                    "smtp_details" => serialize($smtp_details),
                                    "subscriber_ids" => serialize($batch["data"]),
                                    "progress" => 1,
                                    "metadata" => $meta_data,
                                    "template_id" => $template_id,
                                    "subject" => $campaign_sub,
                                    "sender_name" => $campaign_from,
                                    "reply_to" => $reply_to,
                                    "campaign_id" => $campaign_id,
                                    "insert_date" => date("Y-m-d H:i:s"),
                                    "quota"=>count($batch["data"]),
                                    "send_date1" =>$send_time
                                );
                                $db_insert_data [] = $insert_data;
                                $inc_date_flag = true;
                            }
                            # load the campaign model to insert these rows in the database
                            $this->load->model("Campaign_model", "campaign");

                            # if the database insert is successful
                            if ($this->campaign->generic_campaign_insert($db_insert_data)) {
                                	
                                # increase the total_send count in the list_master by one
                                # load list_master model
                                $this->load->model("list_model", "list");
                                foreach ($list_id as $list) {
                                    	
                                    # get the amount of sent emails on this list
                                    $total_send = (int) $this->list->generic_list_select_field(array("list_id" => $list), "total_send");
                                    if (!is_null($total_send) && $total_send >= 0) {
                                        	
                                        $total_send +=1;
                                        $where = array("list_id" => $list);
                                        $update = array("total_send" => $total_send);
                                        $db_array = array("where" => $where, "update" => $update);
                                        
                                        # increment the send count by 1 for this list
                                        if ($this->list->generic_list_update(array($db_array))) {
                                            	
											
												
                                            # update the current timestamp in the user table
                                            
                                            $update = array(
                                                "send_time" => $next_send_to->format("Y-m-d H:i:s"),//$date_today->format("Y-m-d H:i:s"),
                                                // "send_status" => '0'
                                            );
                                            
                                            $where = array(
                                                "user_id" => $this->session->userdata("user_id"),
                                                "email" => $this->session->userdata("email"),
                                                "user_role" => $this->session->userdata("user_role")
                                            );
                                            
                                            $update_arr = array(
                                                array(
                                                    "where_array" => $where,
                                                    "update_array" => $update
                                                )
                                            );

                                            # increament the next send time on the user table for current user
                                            if ($this->user->generic_update_user_data($update_arr)) {
                                                # load template model
	                                            $this->load->model("Template_model", "template");
	                                                
	                                            # get the template data for this user
	                                            $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
	
	                                            # load the user model
	                                            $this->load->model("User_model", "user");
	
	                                            # get the campaign page data for this user
	                                            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
	                                            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
	
	                                            # load that campaign page
	                                            $this->load->view("pages/create-campaign-page", array(
	                                                "template_data" => $template_data,
	                                                "list_data" => $page_data,
	                                                "reply_to" => $reply_to,
	                                                "status" => "Your emails are queued. <br />They would be sent across as per the hourly quota assigned to you.")
	                                            );
                                                
                                            } else {
                                                throw new Exception("Could not update the send to time in the user records");
                                            }
                                        } else {
                                            throw new Exception("Could not update the total send in the list_master table");
                                        }
                                    } else {
                                        throw new Exception("Could not get the number of sent emails from list_master table");
                                    }
                                }
                            } else {
                                throw new Exception("Could not insert campaign details in the database. Please try again.");
                            }
                        } else {
                            throw new Exception("You are not assigned any hourly quota. Please ask your admin to asssign you an hourly quota");
                        }
                    } else {
                        throw new Exception("Improper data provided for sending campaign. Please provide all the necessary parameters");
                    }
                }
            } else {
                # if user is logged-in OR the session has expired, show below message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
        } catch (Exception $ex) {
            $this->load->view("pages/error_message", array(
                "message" => $ex->getMessage())
            );
        }
    }

    # this function set the SMTP parameters and sends a test email

    public function test_email_campaign() {
        try {
            # if the user is logged-in
            if ($this->session->has_userdata("is_logged_in") &&
                    $this->session->userdata('is_logged_in') &&
                    $this->session->userdata("user_role") === "3") {

                # get the post values
                $template_name = $this->input->post("template_name");
                /*$test_email = $this->input->post("test_email");*/
                $test_email = explode(",", $this->input->post("test_email"));
				/*
				$test_email = array();
				foreach($temp as $email){
					$temp1["email"] = $email;
					$test_email[]=$temp1;
				}
				 */
				if(count($test_email)>3){
					throw new Exception("You can not use more than 3 email-ids to test. Please go back and try again with a lesser count");
				}
                $campaign_sub = $this->input->post("template_subject");
                $reply_to_address = $this->input->post("reply_to");
                $campaign_sender = $this->input->post("sender_name");

                # test the input variables: check validations below
                # template name should not be null or blank
                # list name should be an array and sould have atleast an element
                # campaign subject should not be null or blank
                # test and throw exception in case any of the fields are blank
                if (($reply_to_address === "" || is_null($reply_to_address)) ||
                        ($campaign_sender === "" || is_null($campaign_sender)) ||
                        ($template_name === "" || is_null($template_name)) ||
                        (is_null($test_email)) ||
                        (($campaign_sub === "" || is_null($campaign_sub)))) {
                    throw new Exception("campaigncontroller::test_email_campaign::invalid input parameters");
                } else {

                    # configure the parameters
                    $temp_arr = explode("|", $template_name);
                    $temp_name = $temp_arr[0];
                    $temp_id = $temp_arr[1];
                    $template_data = array(
                        "template_name" => $temp_name,
                        "template_id" => $temp_id
                    );

                    # load the template model
                    $this->load->model("Template_model", "template");
                    # get the template details
                    $template_details = $this->template->generic_template_select($template_data);

                    # load the user model
                    $this->load->model("User_model", "user");
                    # get current users smtp details
                    $my_smtp_details = $this->user->generic_user_select(array("user_id" => $this->session->userdata("user_id")));

                    # prepare the meta data header for the email 
                    $meta = json_encode(array(
                        "campaign_id" => "test_camp_id",
                        "row_id" => "test_row_id",
                        "subscriber_id" => "test_sub_id")
                    );

                    # set the smtp configuration
                    $this->load->library('email');
	                $this->email->clear();
	                $config['protocol'] = 'smtp';
	                $config['mailtype'] = 'html';
	                $config['smtp_host'] = $my_smtp_details[0]['smtp_host'];
	                $config['smtp_crypto'] = $my_smtp_details[0]['smtp_auth'];
	                $config['smtp_user'] = $my_smtp_details[0]['sender_email'];
	                $config['smtp_pass'] = $my_smtp_details[0]['smtp_pass'];
	                $config['smtp_port'] = (int) $my_smtp_details[0]['smtp_port'];
	                $config['charset'] = 'utf-8';
	                $config['wordwrap'] = false;
	                $this->email->initialize($config);
	                $this->email->subject($campaign_sub);
	                $this->email->message($template_details[0]['template_content']);
	                $this->email->to($test_email);
	                $this->email->from($my_smtp_details[0]['sender_email'], $campaign_sender);
	                $this->email->reply_to($reply_to_address);
	                $this->email->set_header("X-MC-Subaccount", "smartcontactpreview");
	                $this->email->set_header("X-MC-Metadata", $meta);
					$this->email->set_header("X-Mailer", "smartcontact.biz");
					$this->email->set_header("X-Originating-IP", $_SERVER['REMOTE_ADDR']);
					$this->email->set_header("X-Organization", $campaign_sender);
					$this->email->set_header("X-Copyright", $campaign_sender);
					$this->email->set_header("X-Unsubscribe-email", "unsub@smartcontact.biz");
					$this->email->set_header("X-Unsubscribe-Web", base_url()."unsubscribe");
					$this->email->set_header("X-Report-Abuse", "Please forward a copy of this message, including all headers, to abuse@smartcontact.biz");
	                
	
					# PHP MAILER CODE. USING EMAIL LIBRARY FROM CODEIGNITER IINSTEAD
                    /*
                    $config = array(
                        'is_smtp' => true,
                        'is_html' => true,
                        "smtp_host" => $my_smtp_details[0]['smtp_host'],
                        "smtp_debug" => 0,
                        "smtp_auth" => TRUE,
                        "smtp_port" => (int) $my_smtp_details[0]['smtp_port'],
                        "smtp_user" => $my_smtp_details[0]['sender_email'],
                        "smtp_pass" => $my_smtp_details[0]['smtp_pass'],
                        "smtp_sec" => $my_smtp_details[0]['smtp_auth'],
                        "smtp_sub" => $campaign_sub,
                        "smtp_body" => $template_details[0]['template_content'],
                        "smtp_alt_body" => "This is test email body",
                        //"smtp_to" => array(array("email" => $test_email)),
                        "smtp_to" => $test_email,
                        "smtp_from" => array(array(
                                "email" => $my_smtp_details[0]['sender_email'],
                                "name" => $campaign_sender),),
                        "smtp_reply_to" => array(array(
                                "email" => $reply_to_address),),
                        "headers" => array(
                        	"X-MC-Subaccount: smartcontactpreview", 
                        	"X-MC-Metadata: $meta",
							"X-Mailer: smartcontact.biz",
							"X-Originating-IP: ".$_SERVER['REMOTE_ADDR'],
							"X-Organization: $campaign_sender",
							"X-Copyright: $campaign_sender",
							"X-Unsubscribe-email:  unsub@smartcontact.biz",
							"X-Unsubscribe-Web: ".base_url()."unsubscribe",
							"X-Report-Abuse: Please forward a copy of this message, including all headers, to abuse@smartcontact.biz"
							)
                    );

                    # load the custom phpmailer library
                    $this->load->library('My_PHPMailer', $config, 'emailer');
                    $dt = new DateTime("now");
					*/
					
                    # if the email is sent successfully
                    if ($this->email->send()) {

                        # load all the templates
                        $this->load->model("Template_model", "template");
                        $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));

                        # load the user lists
                        $this->load->model("User_model", "user");
                        $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                        $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
						$this->load->helper('form');
                        # load the create camoaign page
                        $this->load->view("pages/create-campaign-page", array(
                            "template_data" => $template_data,
                            "list_data" => $page_data,
                            "reply_to" => $reply_to_address,
                            "status" => "Test email sent. Please verify the email and send to the list.")
                        );
                    } else {
                        throw new Exception("Could not send campaign email");
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this page OR your session has expired.");
            }
        } catch (phpmailerException $e) {
            # show if there are any email errors
            $this->load->view("pages/error_message", array("message" => $e->errorMessage()));
        } catch (Exception $ex) {
            # show exceptions if any
            $this->load->view("pages/error_message", array("message" => $ex->getMessage()));
        }
    }

    # function to show the template preview

    public function preview_template($template_id, $template_name) {
        try {
            # if the user is logged-in
            if ($this->session->has_userdata("is_logged_in") &&
                    $this->session->userdata('is_logged_in') &&
                    $this->session->userdata('user_role') == "3") {

                # validate form inputs
                if ($template_id === "" || $template_name === "") {
                    throw new Exception("Templatecontroller::show_template::The template details are blank. Please provide proper details", 1);
                } else {
                    # load the template model
                    $this->load->model("Template_model", "template");
                    # where clause
                    $where = array("template_id" => $template_id);
                    # get template details from database
                    $show_template = $this->template->generic_template_select($where);
                    $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));

                    # load user model
                    $this->load->model("User_model", "user");

                    # get campaign page data
                    $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                    $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));

                    # reply to details 
                    $reply_to = $this->input->post("reply_to");

                    # load the preview page
                    $this->load->view("content/view-create-campaign", array(
                        "template_data" => $template_data,
                        "list_data" => $page_data,
                        "reply_to" => $reply_to,
                        "show_template" => $show_template)
                    );
                }
            } else {
                # if user is logged-in OR the session has expired, show below message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
        } catch (Exeption $e) {
            # on Exception reload the template page
            $this->load->model("Template_model", "template");
            $template_data = array();
            $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
            $this->load->view("pages/manage-template-page", array(
                "template_data" => $template_data,
                "error" => array("error_type" => $e->getMessage()
            )));
        }
    }

    # local method to validate the data being sent to queue

    public function validate_sending_queue_data($template_id, $list_id, $sender_id, $campaign_sub, $campaign_from, $reply_to) {
        try {
            if ($template_id === "" || empty($template_id)) {
                return false;
            }
            if (count($list_id) === 0 || empty($list_id)) {
                return false;
            }
            if ($sender_id === "" || empty($sender_id)) {
                return false;
            }
            if ($campaign_sub === "" || empty($campaign_sub)) {
                return false;
            }
            if ($campaign_from === "" || empty($campaign_from)) {
                return false;
            }
            if ($reply_to === "" || empty($reply_to)) {
                return false;
            }
            return true;
        } catch (Exception $ex) {
            throw new Exception("Improper data provided for sending campaign. Please provide all the necessary parameters");
        }
    }

    # method to run via the cron job

    public function run_cron_job() {
        try {
            $path = FCPATH . "reports/";
            $file_name = "cron-details.txt";

            # LOGGING
            if (file_exists($path . $file_name)) {
                file_put_contents($path . $file_name, "********************************************************************" . PHP_EOL, FILE_APPEND);
                file_put_contents($path . $file_name, "Cron-job started at : " . date("Y-m-d H:i:s") . PHP_EOL, FILE_APPEND);
            } else {
                file_put_contents($path . $file_name, "********************************************************************" . PHP_EOL, FILE_APPEND);
                file_put_contents($path . $file_name, "Cron-job started at : " . date("Y-m-d H:i:s") . PHP_EOL, FILE_APPEND);
            }
            # load the models
            $this->load->model("Campaign_model", "campaign");
            $this->load->model("Subscriber_model", "subscriber");
            $this->load->model("Template_model", "template");

            # get the valid data to be picked for this run
            $campaigns = $this->campaign->cron_get_campaign_data();

            # LOGGING
            file_put_contents($path . $file_name, "Total campaign count : " . count($campaigns) . PHP_EOL, FILE_APPEND);

            # iterate for each item from campaigns
            foreach ($campaigns as $campaign) {

                # set up the varaibles to be used in the email
                $subscribers = unserialize($campaign->subscriber_ids);
				
                $smtp_details = unserialize($campaign->smtp_details);
                $template_id = $campaign->template_id;
                $subject = $campaign->subject;
                $sender_name = $campaign->sender_name;
                $reply_to = $campaign->reply_to;
                $user_id = $campaign->user_id;
                $id = $campaign->id;
                $campaign_id = $campaign->campaign_id;
                $list_id = $campaign->list_id;
                $date = new DateTime($campaign->time);
				$email_count = $campaign->quota;
				# set the where clause
	            $where = array(
	                "template_id" => $template_id,
	                "user_id" => $user_id,
	                "id" => $id,
	                "list_id" => $list_id
	            );
				if(unserialize($campaign->sent_emails) === false){
					$sent_emails = array();
					$update = array( "start_time"	=>date("Y-m-d H:i:s") );
					
	                $data = array(array("where" => $where, "update" => $update));
	                $this->campaign->generic_campaign_update($data);
				}
				else  
					$sent_emails = unserialize($campaign->sent_emails);
				
				
				# check if current time is > last send time
				$curr_date = new DateTime("now");
				$interval = $curr_date->diff($date);
				print_r($interval);
				if ($interval->invert !== 1){
					echo "<br />no current compaign";	
					throw new Exception("no current campaign", 1);
				}
				else{
					
					# take 25 subscribers from total subscribers
					$temp = array_splice($subscribers, 0, 25);
					# print the number of subscribers
					echo "Number of picked : ".count($temp);
					echo "<br />Number of remaining : ".count($subscribers);
					
	                # LOGGING
	                file_put_contents($path . $file_name, "DB Time:" . $date->format("Y-m-d H:i:s") . " List-id : $list_id, Row-id : $id, Campaign-id : $campaign_id : START" . PHP_EOL, FILE_APPEND);
	
	                # set the update values
	                $update = array("progress" => 2);
	
	                # update array
	                $data = array(array("where" => $where, "update" => $update));
	                $this->load->library('email');
	                # if the progress column is set up to 2
	                if ($this->campaign->generic_campaign_update($data)) {
	
	                    # iterate for each subscriber
	                    foreach ($temp as $subscriber) {
	                        $to_email = "";
	                        $to_name = "";
	                        $sub_detail = $this->subscriber->generic_subscriber_select(array(
	                            "subscriber_id" => (int) trim($subscriber["subscriber_id"]),
	                            "valid" => "0")
	                        );
	                        $to_email = $sub_detail[0]['email'];
	                        $to_name = $sub_detail[0]['fname'];
	
	//                        print_r($to_email);
	//                        print_r($to_name);
	                        $temp_cont = $this->template->generic_template_select_field(array(
	                            "template_id" => $template_id), "template_content"
	                        );
	
	                        if ($temp_cont === "" || empty($temp_cont)) {
	                            throw new Exception("Template does not exist in the databae. Please recheck the template data and try again");
	                        }
	
	//                        $template_content = str_replace("#NAME#", $to_name, $temp_cont);
	//                        $subject = str_replace("#NAME#", $to_name, $subject);
	//                        $this->email->clear();
	//                        $meta = json_encode(array("campaign_id" => $campaign_id, "row_id" => $id, "subscriber_id" => trim($subscriber["subscriber_id"])));
	//                        $config['protocol'] = 'smtp';
	//                        $config['mailtype'] = 'html';
	//                        $config['smtp_host'] = $smtp_details['smtp_host'];
	//                        $config['smtp_crypto'] = $smtp_details["smtp_auth"];
	//                        $config['smtp_user'] = $smtp_details["smtp_user"];
	//                        $config['smtp_pass'] = $smtp_details["smtp_pass"];
	//                        $config['smtp_port'] = $smtp_details["smtp_port"];
	//                        $config['charset'] = 'utf-8';
	//                        $config['wordwrap'] = false;
	//                        $this->email->initialize($config);
	//                        $this->email->subject($subject);
	//                        $this->email->message($template_content);
	//                        $this->email->to($to_email);
	//                        $this->email->from($smtp_details["smtp_user"], $sender_name);
	//                        $this->email->reply_to($reply_to);
	//                        $this->email->set_header("X-MC-Subaccount", "smartcontactpreview");
	//                        $this->email->set_header("X-MC-Metadata", $meta);
	//                        $this->email->send();
	
	//                        var_dump($temp_cont);
	//                        var_dump($template_content);
	//                        var_dump($subject);
	                        # if the email address is not empty
	//                        if (!empty($to_email) && filter_var($to_email, FILTER_VALIDATE_EMAIL) !== false) {
	//                        if (true) {
	//                            # prepare metadata
	//                            $meta = json_encode(array("campaign_id" => $campaign_id, "row_id" => $id, "subscriber_id" => trim($subscriber["subscriber_id"])));
	//                            $config = array();
	//
	//                            # set the smtp details for the email
	//                            $config = array(
	//                                'is_smtp' => true,
	//                                'is_html' => true,
	//                                "smtp_host" => $smtp_details['smtp_host'],
	//                                "smtp_debug" => 0,
	//                                "smtp_auth" => TRUE,
	//                                "smtp_port" => (int) $smtp_details["smtp_port"],
	//                                "smtp_user" => $smtp_details["smtp_user"],
	//                                "smtp_pass" => $smtp_details["smtp_pass"],
	//                                "smtp_sec" => $smtp_details["smtp_auth"],
	//                                "smtp_sub" => $subject,
	//                                "smtp_body" => $template_content, //$template_details[0]['template_content'],
	//                                "smtp_alt_body" => "This is test email body",
	//                                "smtp_to" => array(array(
	//                                        "email" => $to_email)),
	//                                "smtp_from" => array(array(
	//                                        "email" => $smtp_details["smtp_user"],
	//                                        "name" => $sender_name),),
	//                                "smtp_reply_to" => array(array(
	//                                        "email" => $reply_to),),
	//                                "headers" => array("X-MC-Subaccount: smartcontactpreview", "X-MC-Metadata: $meta")
	//                            );
	////                            print_r($config);
	//                            # load custom PHP mailer class
	//                            $this->load->library('My_PHPMailer', $config, 'emailer');
	//
	//                            # send emails
	//                            if ($this->emailer->send_email()) {
	//                                echo "Email Sent to $to_email <br />";
	//                            } else {
	//                                # LOGGING
	//                                file_put_contents($path . $file_name, "Not able to send email for " . $subscriber["subscriber_id"] . PHP_EOL, FILE_APPEND);
	//                            }
	////                            $this->emailer->clear();
	//                        } else {
	//                            # if email-id is blank or invalid, then store it in the file
	//                            # LOGGING
	//                            file_put_contents($path . $file_name, "Email not sent to : subscriber-id" . $subscriber["subscriber_id"] . " List-id : $list_id, Row-id : $id, Campaign-id : $campaign_id " . PHP_EOL, FILE_APPEND);
	//                        }
	                    }
						# add the subscribers to "sent_emails"
						# update the field
						
						# deduct the subscribers from the "subscribers list"
						# update the field
						
						# get the current time
						# update the last sent time
						echo "<br /> Temp is ";
						print_r($temp);
						echo "<br /> count of Temp is ";
						print_r(count($temp));
						echo "<br /> sent is ";
						//print_r($sent_emails);
						echo "<br /> updating the details to the database";
						echo "<br /> count".count(array_merge($temp,$sent_emails));
						//$progress = 0;
						if(count($temp)<=25 && count(array_merge($temp,$sent_emails)) == $email_count){
							$update = array(
								"subscriber_ids" =>serialize($subscribers),
								"sent_emails"	=>serialize(array_merge($temp,$sent_emails)),
								"send_time"		=>date("Y-m-d H:i:s"),
								"progress"		=>'3',
							);
						}
						else{
							$update = array(
								"subscriber_ids" =>serialize($subscribers),
								"sent_emails"	=>serialize(array_merge($temp,$sent_emails)),
								"send_time"		=>date("Y-m-d H:i:s"),
								"progress"		=>'2',
							);
						}
						
						//print_r($update);
						
						# check if the  count to "subscribers"< 25
						# update the status to 3
						# else update the status to 2
						
						
	                    # update the status of the process to 3 in the campaign table to mark it as complete.
	                    //$update = array("progress" => 3);
	                    $data = array(array("where" => $where, "update" => $update));
	                    $this->campaign->generic_campaign_update($data);
	                }
				}
                # LOGGING
                file_put_contents($path . $file_name, "DB Time:" . $date->format("Y-m-d H:i:s") . " email-id : $to_email" . PHP_EOL, FILE_APPEND);
            }
            # LOGGING
            file_put_contents($path . $file_name, "Cron-job finished at : " . date("Y-m-d H:i:s") . PHP_EOL, FILE_APPEND);
            
        } catch (Exception $ex) {
//            print_r($ex);
            # LOGGING
            file_put_contents($path . $file_name, "Cron-job error at : " . date("Y-m-d H:i:s") . PHP_EOL, FILE_APPEND);
            file_put_contents($path . $file_name, "Cron-job error is : " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }

    public function test2() {
        /*
        echo ini_get('max_execution_time');
                ini_set('max_execution_time',3000);
                echo ini_get('max_execution_time');
/*
                echo ini_get('time_limit');*/
    /*    
        $dt = new DateTime("now");
                echo $dt->format("Y-m-d");
                $y = $dt->format("Y");
                $m = $dt->format("m");
                                    $dt_new = new DateTime($y."-".$m."-"."1 00:00:00");
                echo $dt_new->format("Y-m-d H:i:s");
                $d = $dt_new->format("Y-m-d H:i:s");
                $u = $this->session->userdata("user_id");
                $query = "SELECT count(1) from campaign_data where send_time > '$d' and user_id = '$u'" ;
                                    $this -> load -> model("Campaign_model", "campaign");
                        $return = $this -> campaign -> run_query($query);
                        print_r($return);*/

        
        //$query = "select count(1) from user_list_relation where user_id = $u or shared_with = $u";
    }
	
	public function download_campaign($camp_id){
		try {
            # if the user is logged-in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') ) {
				//print_r(FCPATH."../../reports");
				$this->load->helper('download');
				force_download($camp_id.".csv", file_get_contents(FCPATH . "reports/".$camp_id.".txt"));
				
			} else {
                # if user is logged-in OR the session has expired, show below message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
        } catch (Exception $ex) {
            # in case of exception, show the exception on the screen
            $this->load->view("pages/error_message", array(
                "message" => $ex->getMessage())
            );
        }
	}
}
