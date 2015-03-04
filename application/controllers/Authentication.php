<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {
    # base function. 
    # if the user is already logged in no credentials would be asked and the user will be redirected to his dashboard
    public function index() {
        if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
            redirect("authentication/user_login_dashboard");
        } else
            $this->load->view('pages/view-landing', array('title' => 'Login|Signup'));
    }
    
    # this function provides the login functionality
    public function login_user() {
        # get the login email and password
        $login_email = trim($this->input->post('login_email'));
        $login_password = trim($this->input->post('login_password'));

        # load the user model
        $this->load->model("User_model", "user");
        $return_array = $this->user->validate_user_login($login_email, $login_password);

        # if the validation is successful
        if ($return_array['return_status'] === true) {

            # upload directory location
            $dir_name = strtolower(str_replace(array("@", "."," "), "_", "upload/" . $return_array['user_name'] . $login_email));
            // print_r($dir_name);
            # Check if the upload directory was created during the activation process
            if (is_dir($dir_name)) {

                # set the login bit in the session
                $data = array(
                    "dir_name" => $dir_name,
                    'is_logged_in' => true,
                    'email' => $login_email,
                    "name" => $return_array['user_name'],
                    "user_role" => $return_array['user_role'],
                    "user_id" => $return_array['user_id']
                );
                $this->session->set_userdata($data);

                # set the cookie on the client
                # redirect to the login page.
                redirect("authentication/user_login_dashboard");
            } else {
                # got to error page if the upload directory is not available for user
                $this->load->view("pages/error_message", array(
                    "message" => "<h3>Your upload directory does not exist. Please ask admin to create the directory</h3>")
                );
            }
        } else {
            # if the validation is not successful then show below message on error page
            $this->load->view("pages/error_message", array(
                "message" => "<h3>" . $return_array['return_status'] . "</h3>")
            );
        }
    }

    # this function redirects the user to his/her dashboard based on his user "role_type"

    public function user_login_dashboard() {

        # check if the user is logged in. (use the data stored in the session)
        if ($this->session->userdata("is_logged_in")) {
            # redirect the user based on his user role 1,2,3.
            $user_role = $this->session->userdata("user_role");
            if ($user_role === '1')
                redirect("masteradmin/master_admin_dashboard");
            else if ($user_role === '2')
                redirect("groupadmin/group_admin_dashboard");
            else if ($user_role === '3')
                redirect("users/users_dashboard");
        }
        else {
            # if the user is not logged in 
            $this->load->view("pages/error_message", array(
                "message" => "<h3>Session has expired. Please login again.'</h3>")
            );
        }
    }

    # method that handles user registration process

    public function register_user() {
        try {
            # load user model
            $this->load->model('User_model', 'user');

            # get the user email and first name from post request
            $email = $this->input->post('signup_email');
            $fname = $this->input->post('signup_first_name');

            # create a random activation code
            $activation_code = uniqid();

            # provide an activation link
            $activation_link = base_url() . "authentication/activation/" . $email . '/' . $activation_code;

            # if the user is registered properly in the database
            if ($this->user->register_user($activation_code)) {
                # set up an email configuration
                # create activatioin-link id
                # send an email
                # online accounts would be created under the online-admin (user_role=4)
                $email_admin_details = $this->user->generic_user_select(array(
                    "user_type" => 2,
                    "user_role" => 4)
                );
                //print_r($email_admin_details);
                # create the email configurations
                $config = array(
                    'is_smtp' => true,
                    'is_html' => true,
                    "smtp_host" => $email_admin_details[0]['smtp_host'],
                    "smtp_debug" => 0,
                    "smtp_auth" => true,
                    "smtp_port" => (int) $email_admin_details[0]['smtp_port'],
                    "smtp_user" => $email_admin_details[0]['sender_email'],
                    "smtp_pass" => trim($email_admin_details[0]['smtp_pass']),
                    "smtp_sec" => $email_admin_details[0]['smtp_auth'],
                    "smtp_sub" => "You activation link",
                    "smtp_body" => "Hi $fname, <br /> Your account has been registered. <br />To activate your account please <a href='$activation_link'>click here</a>.<br />Please activate your account.<br />Thanks,<br />Admin.",
                    "smtp_alt_body" => "Account activation confirmation ",
                    "smtp_to" => array(array(
                            "email" => $email,
                            "name" => $fname)),
                    "smtp_from" => array(array(
                            "email" => $email_admin_details[0]['sender_email'],
                            "name" => $email_admin_details[0]['first_name']),),
                    "smtp_reply_to" => array(array(
                            "email" => $email_admin_details[0]['sender_email'],
                            "name" => $email_admin_details[0]['first_name']),),
                );

                # load custom PHPMailer library to send emails
                $this->load->library('My_PHPMailer', $config, 'emailer');

                # if the emails are sent successfully
                if ($this->emailer->send_email()) {
                    # on successful registration show the below message to the user
                    $this->load->view("pages/message", array(
                        "title" => "User registered",
                        "message" => "User successfully registered. You will recieve an activation link on the registered email-id. "
                        . "<p>Please follow the instructions in the email to activate your login.</p>")
                    );
                } else {
                    # if problems in creating emails, show below messages
                    $this->load->view("pages/error_message", array(
                        "message" => "Problems sending activation link. Please contact your admin.")
                    );
                }
            } else {
                # if problems in registering the user, show below message
                $this->session->unset_userdata('old');
                $this->session->sess_destroy();
                $this->load->view("pages/error_message", array(
                    "message" => "Could not register user, please try again")
                );
            }
        } catch (Exception $e) {
            # in case of any other error, show the exceptioin message on the screen
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            ); //print_r($e);
        }
    }

    # this method provides the functionality to activate the user when they click in the activation link
    # @param : $email = email-id of the user (embedded in the email message)
    # @param : $code = activatin code (embedded in the email)

    public function activation($email, $code) {
        try {
            # load the user model
            $this->load->model('User_model', 'user');

            # ambiguous name of the method (would probabaly need change at later point in time)
            # this method in the user model will check if the user is inactive and activate the user
            # returns true on successful user activation
            $activation_status = $this->user->check_activation($email, $code);

            # if the user is activated successfully in the database
            if ($activation_status === true) {

                # get the current user's email-id
                $user_name = $this->user->get_username($email);

                # if no errors in getting the email-id from the database
                if ($user_name !== false) {
                    //echo "user $user_name activated..... an email confirmation will be sent to your email";
                    # prepare the url (change needed: use codeigniter's prepare url method to prepare this url)
                    $url = base_url() . "authentication/";

                    # get online admin details
                    $email_admin_details = $this->user->generic_user_select(array(
                        "user_type" => 2,
                        "user_role" => 4)
                    );

                    # prepare the SMTP configuration to send the emails
                    $config = array(
                        'is_smtp' => true,
                        'is_html' => true,
                        "smtp_host" => $email_admin_details[0]['smtp_host'],
                        "smtp_debug" => 0,
                        "smtp_auth" => true,
                        "smtp_port" => (int) $email_admin_details[0]['smtp_port'],
                        "smtp_user" => $email_admin_details[0]['sender_email'],
                        "smtp_pass" => trim($email_admin_details[0]['smtp_pass']),
                        "smtp_sec" => $email_admin_details[0]['smtp_auth'],
                        "smtp_sub" => "Your account is successfully activated.",
                        "smtp_body" => "Hi $user_name, <br /> Your account has been successfully activated.<br />"
                        . "Thanks,<br />Admin.",
                        "smtp_alt_body" => "You have successfully activated your account. Please go to the starting url and login with your credentials",
                        "smtp_to" => array(array(
                                "email" => $email,
                                "name" => $user_name),),
                        "smtp_from" => array(array(
                                "email" => $email_admin_details[0]['sender_email'],
                                "name" => $email_admin_details[0]['first_name']),),
                        "smtp_reply_to" => array(array(
                                "email" => $email_admin_details[0]['sender_email'],
                                "name" => $email_admin_details[0]['first_name']),),
                    );

                    # load custom PHP mailer library for sending emails
                    $this->load->library('My_PHPMailer', $config, 'emailer');

                    # if the email is sent successfully
                    if ($this->emailer->send_email()) {

                        # provide the drectory name
                        $dir_name = strtolower(str_replace(array("@", "."," "), "_", "upload/" . $user_name . $email));

                        # if the directory does nto exist, create the directory
                        if (!is_dir($dir_name)) {
                            mkdir($dir_name, 0755, true);
                        }

                        # show success message on the screen to the user
                        $this->load->view("pages/message", array(
                            "title" => "Activation successful",
                            "message" => "<h3>Your account is activated.</h3><h5>Please login to proceed</h5>")
                        );
                    } else {
                        # Show below message if the email was not sent successfully.
                        $this->load->view("pages/error_message", array(
                            "message" => "Could not send confirmation email. Your upload directory is not created.<br /> "
                            . "Please ask you admin to manually create it for you")
                        );
                    }
                } else {
                    # show below message if the user is not registered in the email 
                    # (not possible process-wise but good to have validation)
                    $this->load->view("pages/error_message", array(
                        "message" => "User not registered in the database, please try again.")
                    );
                }
            } else {
                # if there are issues activating the user in the database
                $this->load->view("pages/error_message", array(
                    "message" => $activation_status)
                );
            }
        } catch (Exception $e) {
            # in case of any other exception show the below error message to the user
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    # this function provides the logout functionality

    public function logout() {
        try {
            # remove the userdata from the session 
            # (not really needed but for extra precautions)
            if ($this->session->userdata('is_logged_in')) {
                $this->session->unset_userdata('email', 'is_logged_in', "name", "user_role", "dir_name");
            }
            # additionally destroy the session as well 
            $this->session->sess_destroy();

            # redirect the user to the login page
            redirect('authentication');
        } catch (Exception $e) {
            # in case of any exception show the below error message to the user
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    # below function shows the chagne password form

    public function show_edit_password_form($status = "") {
        try {
            # if the user is logged in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
                # by default status = false
                $data = array("status" => false);

                # if status from the method callee is true
                if ($status) {
                    $data = array("status" => $status);
                }

                # show the password change form
                $this->load->view("pages/change-password", $data);
            } else {
                # if user is not logged-in then show below error message
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorised to view this page")
                );
            }
        } catch (Exception $e) {
            # in case of any other issues show an exception to the user on screen
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    # below function provides the chagne password functionality

    public function edit_password() {
        try {
            # if the user is logged-in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
                # get the post variables
                $old_password = trim($this->input->post("old_password"));
                $new_password = trim($this->input->post("new_password"));

                # load the user model
                $this->load->model("User_model", "user");

                # change the password field
                $return_status = $this->user->change_password($new_password, $old_password);

                # if the password chagned successfully
                if ($return_status === true) {
                    $this->show_edit_password_form(true);
                } else {
                    throw new Exception("Could not add new password to the database. Please try again.");
                }
            } else {
                # if the user is not logged in
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorised to view this page")
                );
            }
        } catch (Exception $e) {
            # in case of other exceptions show error message to the user
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    # show form to user for changing personal information
    # the other business-related details can not be changed here

    public function show_edit_user_details_form($status = "") {
        try {
            # if the user s logged-in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
                $data = array("status" => false);
                if ($status) {
                    $data = array("status" => $status);
                }
				# load the user model
            	$this->load->model('User_model', 'user');
				$user_data = $this->user->generic_user_select(array(
                        "email" => $this->session->userdata("email"),
                        "user_role" => $this->session->userdata("user_role"),
                        "user_id"=>$this->session->userdata("user_id"))
                        
                 );
                 $data["user_details"] = $user_data;
                 // print_r($data);
                # show user the form to change details
                $this->load->view("pages/change-profile-details", $data);
            } else {
                # else chow user the error message in case he is not logged-in
                $this->load->view("pages/error_message", array("message" => "You are not authorised to view this page"));
            }
        } catch (Exception $e) {
            # in case of other exceptions show error message to the user
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    # this method recieves the information from the change details form and processes the inputs

    public function edit_profile_details() {
        try {
            # if the user is logged-in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
                # get the user details from the post request
                $data = array(
                    'first_name' => $this->input->post("firstname"),
                    'last_name' => $this->input->post("lastname"),
                    'address_line_1' => $this->input->post("address"),
                    'address_line_2' => $this->input->post("address2"),
                    'city' => $this->input->post("city"),
                    'state' => $this->input->post("state"),
                    'pincode' => $this->input->post("pincode"),
                    'contact_num' => $this->input->post("c_number"),
                    'mobile_num' => $this->input->post("m_number")
                );

                # load the user model
                $this->load->model("User_model", "user");

                # call the user_model method to change the profile details
                # in future generic methods should be used to update these parameters
                if ($this->user->change_profile_details($data)) {
                    //echo "success";
                    $this->show_edit_user_details_form(true);
                } else {
                    # if the database operation failed
                    $this->load->view("pages/error_message", array(
                        "message" => "Could not change profile details in the database. Please try again")
                    );
                }
            } else {
                # if the user is not logged-in
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorised to view this page")
                );
            }
        } catch (Exception $e) {
            # incase of any other Exception 
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    # show the forget password form to the end-user

    public function show_forget_password_form() {
        try {
            $this->load->view("pages/view-forget-password");
        } catch (Exception $e) {
            $this->load->view("pages/error_message", array("message" => $e->getMessage()));
        }
    }

    # this method takes the inputs from the forget password form and processes them

    public function reset_forget_password() {
        try {
            # post variables
            $email = $this->input->post("login_email");

            # load form validation library
            $this->load->library("form_validation");

            # set form validation rules
            $this->form_validation->set_rules('login_email', 'Registered Email id', 'required|callback_forgotten_email_exists');

            # if the validation rules are ssuccessful
            if ($this->form_validation->run()) {

                # load the user_model
                $this->load->model("User_model", "user");

                # create a new password
                $new_password = uniqid();

                # change this password in the database
                $password_changed = $this->user->change_forgotten_password($email, $new_password);

                # if the password has changed properly
                if ($password_changed) {
                    # select the onlin-admin details from the database
                    $email_admin_details = $this->user->generic_user_select(array(
                        "user_type" => 2,
                        "user_role" => 4)
                    );

                    # create SMTP configuration for sending emails  
                    $config = array(
                        'is_smtp' => true,
                        'is_html' => true,
                        "smtp_host" => $email_admin_details[0]['smtp_host'],
                        "smtp_debug" => 0,
                        "smtp_auth" => true,
                        "smtp_port" => (int) $email_admin_details[0]['smtp_port'],
                        "smtp_user" => $email_admin_details[0]['email'],
                        "smtp_pass" => trim($email_admin_details[0]['smtp_pass']),
                        "smtp_sec" => $email_admin_details[0]['smtp_auth'],
                        "smtp_sub" => "Password rest",
                        "smtp_body" => "Hi , <br /> Your password has been reset. "
                        . "<br />Your new password is $new_password. "
                        . "<br />Please change your password after you login."
                        . "<br />Thanks,"
                        . "<br />Admin.",
                        "smtp_alt_body" => "This is alternate body",
                        "smtp_to" => array(array(
                                "email" => $email,
                                "name" => $user_name),),
                        "smtp_from" => array(array(
                                "email" => $email_admin_details[0]['email'],
                                "name" => $email_admin_details[0]['first_name']),),
                        "smtp_reply_to" => array(array(
                                "email" => $email_admin_details[0]['email'],
                                "name" => $email_admin_details[0]['first_name']),),
                    );

                    # load custom PHPMailer library
                    $this->load->library('My_PHPMailer', $config, 'emailer');

                    # if the email is sent successfully
                    if ($this->emailer->send_email()) {
                        return true;
                    } else {
                        # in case of email-error show below message on the screen
                        $this->load->view("pages/error_message", array(
                            "message" => "Could not send activation email")
                        );
                    }
                } else {
                    # in case of any other issues
                    $this->load->view("pages/error_message", array(
                        "message" => "Problems while changing the password. Please try again with the correct user-email")
                    );
                }
            } else {
                # in case the form validation fail, show the same page to the user again with the error messages
                $this->load->view("pages/view-forget-password");
            }
        } catch (Exception $e) {
            # in case of any exception. show message on the screen
            $this->load->view("pages/error_message", array("message" => $e->getMessage()));
        }
    }

    # check in the database if the forgotten email exists in the database
    public function forgotten_email_exists() {
        try {
            # get the post parameters
            $email = $this->input->post('login_email');

            # load the user model
            $this->load->model("User_model", "user");

            # if the email exists in the database
            if ($this->user->forgotten_email_exists($email)) {
                return true;
            } else {
                # when the forgotten email does not exist in the database then show this error on the form
                $this->form_validation->set_message('forgotten_email_exists', "The {field} does not exist in the database. Please provide the registered email");
                return false;
            }
        } catch (Exception $e) {
            # in case of anytoher exception show it on the screen
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }
}
