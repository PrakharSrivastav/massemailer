<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Listcontroller extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function show_create_list_form() {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {

                # load the models and get the page data
                $this->load->model("User_model", "user");
                $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                $new_arr = array();
                foreach ($page_data as $data) {
                    $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                    foreach ($count->result_array() as $row)
                        $new_arr[] = array_merge($data, $row);
                }
                $this->load->view("pages/create-list-form", array("page_data" => $new_arr));
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            # in case of any other exception show the below error message to the user
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
    }

    public function import_subscriber_csv($update_list = "") {
        # variables
        $list_id = "";
        $user_id = $this->session->userdata("user_id");
        $if_subscriber_needs_delete = array();

        # load the models
        $this->load->model("List_model", "list");
        $this->load->model("User_model", "user");
        $this->load->model("User_list_relation_model", "user_list");
        $this->load->model("Subscriber_model", "subscriber");
        $this->load->model("List_subscriber_model", "list_subscriber");

        try {
            # if the user is logged in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {

                # get the post variables
                $list_name = $this->input->post("list_name");
                $list_description = $this->input->post("list_description");

                # Throw and exception when the post variables are empty
                if ($list_description === "" || $list_name === "") {
                    throw new Exception("Please provide list name and list id", 1);
                }
                # else if the post variables are set then proceed
                else {

                    if ($update_list !== "" && !is_null($update_list)) {

                        ## check to see here if the list is owned by the user in case of update
                        # load the user_list model
                        $this->load->model("User_list_relation_model", "user_list");

                        # get the list id from the post request
                        $list_id_from_form = $this->input->post("list_id");

                        # prepare the array to check if the current userr is the owner of this list
                        $list_array_query_db = array("list_id" => $list_id_from_form);

                        # get the list lowner id from the database
                        $list_owner_id = $this->user_list->generic_user_list_relation_select_field($list_array_query_db, "user_id");

                        # if list owner id is blank or null, then this list should not be in the system
                        if ($list_array_query_db === "" || is_null($list_array_query_db)) {
                            throw new Exception("Orphaned list. There is no owner assigned to the list. Please ask admin to delete this list and create a new one.");
                        } else {
                            if ($list_owner_id === $this->session->userdata("user_id")) {
                                # set where clause to get the list details
                                $where_clause = array(
                                    "list_name" => $list_name,
                                    "is_list_complete" => '1'
                                );

                                # get the list-id
                                $list_id = $this->list->generic_list_select_field($where_clause, "list_id");

                                # check the return value of the list_id
                                if ($list_id === "") {
                                    throw new Exception("The list is not available in the database", 1);
                                }
                            } else {
                                throw new Exception("Shared lists can not be edited. Only list-owner can edit the lists.");
                            }
                        }
                    } else {

                        $list_quota = (int) $this->user->get_user_field($this->session->userdata("email"), "user_list_limit");
                        $my_current_list_count = (int) $this->user_list->get_my_current_list_count($this->session->userdata("user_id"));
                        if ($list_quota !== 0 && $my_current_list_count >= $list_quota) {
                            throw new Exception("Your list quota has exhausted. You cannot create any more lists.", 2);
                        }


                        # set the initial list values -- 1
                        $list_data = array("list_name" => $list_name, "list_description" => $list_description);

                        # if the details in (1) are inserted properly
                        if ($this->list->generic_list_insert(array($list_data))) {
                            # set the where clause
                            $list_where = array(
                                "list_name" => $list_name,
                                "send_status" => '2',
                                "is_list_complete" => '0',
                                "total_send" => 0
                            );

                            # get the list-id
                            $list_id = $this->list->generic_list_select_field($list_where, "list_id");

                            # if the list id is blank throw an exception
                            if ($list_id === "") {
                                throw new Exception("Could  not get list-id", 1);
                            }
                            # else continue processing
                            else {
                                # set the user_list data
                                $data = array(array("user_id" => $user_id, "list_id" => $list_id, "is_enabled" => '0'));

                                # if the user_list details are not inserted properly then throw an exception
                                if (!$this->user_list->generic_user_list_relation_insert($data)) {
                                    throw new Exception("Could not insert user_list_relation details", 1);
                                }
                            }
                        }
                        # if the list details are not inserted then throw an exception
                        else {
                            throw new Exception("List with the same name already exists. Please change the list name and try again", 1);
                        }
                    }
                }

                # file upload variables
                $dir_name = $this->session->userdata("dir_name") . '/uploads';
                $file_name = "imported_csv_" . $this->session->userdata("name") . ".csv";

                # configuation array for the file upoad property
                $config = array(
                    "upload_path" => $dir_name,
                    "max_size" => "2048",
                    "allowed_types" => "csv",
                    "file_name" => $file_name,
                    "overwrite" => true
                );
                $list_subscriber_insert = array();

                # load the fileupload library
                $this->load->library("upload", $config);

                # if the user directory is not available the create
                if (!is_dir($dir_name)) {
                    mkdir($dir_name, 0755, true);
                }

                # upload the file
                if ($this->upload->do_upload("csv_file_input")) {

                    # read the csv file
                    $csv_configuration = array("file_name" => $dir_name . "/" . $file_name, "count" => 7);
                    $this->load->library("csvhandler", $csv_configuration);
                    $csv_data = $this->csvhandler->read_csv_data();

                    # print_r($csv_data);
                    # inform the user if there are no records in the input file
                    if (count($csv_data) === 0) {
                        throw new Exception("No data in the imported csv file", 1);
                    } else {

                        # process each subscriber in the csv array one by one
                        foreach ($csv_data as $subscriber) {
                            $subscriber_id = "";
                            $email = $subscriber[0];
                            # Check if the email exists in the database and get the subscriber-id
                            $id = $this->subscriber->generic_subscriber_select_field(array("email" => trim($email)), "subscriber_id");
                            //print_r($id);echo "    ";
                            if ($id !== "" || !empty($id)) {
                                # if email exists, get the subscriber id
                                $subscriber_id = $id;
                            }
                            # if email does not exists
                            else {

                                # by default set the valid parameter as true (0=valid,1=invalid in the databse)
                                $valid = "0";

                                # if the email-id is not valid then mark the status of the customer as invalid 
                                if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) === FALSE) {
                                    $valid = "1";
                                    $invalid_emails[] = trim($email);
                                }

                                # prepare the insert array for the database
                                $insert_data = array(
                                    "email" => trim($email),
                                    "phone" => $subscriber[1],
                                    "mobile" => $subscriber[2],
                                    "place" => $subscriber[3],
//                                    "email_status" => $subscriber[4],
                                    "fname" => $subscriber[5],
                                    "lname" => $subscriber[6],
                                    "valid" => $valid,
                                    "email_status" => "3"
                                );
                                # insert the data in the subscriber_master table.
                                if ($this->subscriber->generic_subscriber_insert(array($insert_data))) {
                                    # get the subscriber id from the database.
                                    //echo "valid is  :" . $valid;
                                    $subscriber_id = $this->subscriber->generic_subscriber_select_field(array(
                                        "email" => trim($email),
                                        "email_status" => "3",
                                        "valid" => $valid), "subscriber_id"
                                    );
                                    if (empty($subscriber_id) || $subscriber_id === "") {
                                        throw new Exception("Could not get a subscriber id from the database for new subscriber");
                                    }
                                } else {
                                    throw new Exception("Could not insert the subscriber in the database", 1);
                                }
                            }
                            # So far we have managed to get a valid list-id and a valid subscriber-id from the database
                            # Now we populate the below 2 arrays
                            #   $list_subscriber_insert[] 
                            #   $if_subscriber_needs_delete[] 
                            $list_subscriber_insert[] = array(
                                "list_id" => $list_id,
                                "subscriber_id" => $subscriber_id
                            );
                            $if_subscriber_needs_delete[] = array(
                                "where" => array("subscriber_id" => $subscriber_id)
                            );
                        }
                    }
                }
                # if there are issues during the file upload
                else {
                    throw new Exception($this->upload->display_errors(), 1);
                }


                # if thre are few valid records fir inserting into the list_subscriber_relation table for insert
                if (count($list_subscriber_insert) > 0) {

                    # if the insertion in the table list_subscriber_relation is successful
                    if ($this->list_subscriber->generic_list_subscriber_insert($list_subscriber_insert)) {

                        # set the where clause
                        $where_list_array = array("list_name" => $list_name, "list_description" => $list_description, "is_list_complete" => '0');
                        $update_list_array = array("is_list_complete" => '1');
                        $data = array(array("where" => $where_list_array, "update" => $update_list_array));

                        # update the initial inserts in the user_list_ralation table (1)
                        if ($this->list->generic_list_update($data)) {
                            $update_user_list = array(array(
                                    "where" => array(
                                        "list_id" => $list_id,
                                        "user_id" => $user_id,
                                        "is_enabled" => '1'),
                                    "update" => array("is_enabled" => 1))
                            );

                            # finally add the details into user_list_relation table
                            if ($this->user_list->generic_user_list_relation_update($update_user_list)) {

                                # load the model for the list table.
                                $page_data = $this->user->get_my_lists_data($user_id);
                                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                                # load this page again.
                                $message = "";
                                if ($update_list === "")
                                    $message = "The list is uploaded";
                                else
                                    $message = "The list is updated";
                                $new_arr = array();
                                foreach ($page_data as $data) {
                                    $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                                    foreach ($count->result_array() as $row)
                                        $new_arr[] = array_merge($data, $row);
                                }
                                $this->load->view("pages/create-list-form", array(
                                    "page_data" => $new_arr,
                                    "success" => $message)
                                );
                            }
                            # if the records in the user_list_relation are not inserted
                            else {
                                throw new Exception("could not update user_list data", 1);
                            }
                        }
                        # throw exception when could not update list_subscriber
                        else {
                            throw new Exception("could not update list_subscriber data", 1);
                        }
                    }
                    # throw exception when could not insert list_subscriber
                    else {
                        throw new Exception("could not insert list_subscriber data", 1);
                    }
                }
            }
            # if you are not group admin then
            else {
                throw new Exception("You are not authorized to view this page OR your session has expired", 1);
            }
        } catch (Exception $e) {

            echo $e->getMessage();
            # delete the data from the list_master
            # this wil cascade delete the data from list_user_relation and list_subscriber_relation
            if ($e->getCode() !== 2) {
                $list_del = array(array("where" => array("list_id" => $list_id)));
                $this->list->generic_list_delete($list_del);
            }

            # delete the data from subscriber_master
            $this->subscriber->generic_subscriber_delete($if_subscriber_needs_delete);
        }
    }

    public function share_list_with_users($list_id, $list_name) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                if ($list_name === "" || $list_id === "") {
                    throw new Exception("The list details is blank. Please provide proper details", 1);
                } else {
                    $this->load->model("User_model", "user");
                    $where = array("user_admin_id" => $this->session->userdata("user_id"), "is_active" => '1');
                    $user_list = $this->user->generic_user_select($where);

                    $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                    $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                    $new_arr = array();
                    foreach ($page_data as $data) {
                        $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                        foreach ($count->result_array() as $row)
                            $new_arr[] = array_merge($data, $row);
                    }
                    $this->load->view("pages/create-list-form", array(
                        "page_data" => $new_arr,
                        "show_share_list_form" => $user_list,
                        "list_name" => $list_name,
                        "list_id" => $list_id)
                    );
                }
            } else {
                echo "You are not authorised to view this page.";
            }
        } catch (Exeption $e) {
            //throw $e;
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function share_with_users_form_process() {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                $request_count = count($this->input->post());
                $post_array = $this->input->post();
                //print_r($post_array);
                $user_id = $this->session->userdata("user_id");

                $this->load->model("User_model", "user");
                $page_data = $this->user->get_my_lists_data($user_id);
                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($user_id));
                $new_arr = array();
                foreach ($page_data as $data) {
                    $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                    foreach ($count->result_array() as $row)
                        $new_arr[] = array_merge($data, $row);
                }
                if ($request_count === 0) {
                    $where = array("user_admin_id" => $user_id);
                    $user_list = $this->user->generic_user_select($where);
                    $this->load->view("pages/create-list-form", array("page_data" => $new_arr));
                } else {
                    $this->load->model("User_list_relation_model", "user_list");
                    $shared_status = false;
                    $i = 0;
                    foreach ($post_array as $key => $value) {

                        $post_values = explode(":", $value);

                        $child_user_id = $this->user->get_user_field($post_values[1], "user_id");
                        if ($child_user_id !== false) {
                            $list_id = $post_values[0];
                            if ($i === 0) {
                                $delete_where = array("user_id" => $user_id, "list_id" => $list_id);
                                $data = array("where" => $delete_where);
                                $shared_status = $this->user_list->generic_user_list_relation_delete(array($data));
                                $i++;
                            }
                            $insert_array = array("list_id" => $list_id, "user_id" => $user_id, "shared_with" => $child_user_id);
                            $shared_status = $this->user_list->generic_user_list_relation_insert(array($insert_array));
                        } else {
                            throw new Exception("could not get user_id for the children", 1);
                        }
                    }
                    if ($shared_status) {
                        $this->load->view("pages/create-list-form", array(
                            "page_data" => $new_arr,
                            "success" => "The list is shared with the users.")
                        );
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            //throw $e;
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function unshare_with_users($list_id) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $list_id !== "") {
                $user_id = $this->session->userdata("user_id");
                $shared_status = FALSE;
                $this->load->model("User_model", "user");
                $page_data = $this->user->get_my_lists_data($user_id);
                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($user_id));
                $new_arr = array();
                foreach ($page_data as $data) {
                    $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                    foreach ($count->result_array() as $row)
                        $new_arr[] = array_merge($data, $row);
                }
                $this->load->model("User_list_relation_model", "user_list");
                $delete_where = array("user_id" => $user_id, "list_id" => $list_id);
                $data = array("where" => $delete_where);
                $shared_status = $this->user_list->generic_user_list_relation_delete(array($data));

                $insert_array = array("list_id" => $list_id, "user_id" => $user_id, "shared_with" => '0');
                $shared_status = $this->user_list->generic_user_list_relation_insert(array($insert_array));
                if ($shared_status) {
                    $this->load->view("pages/create-list-form", array(
                        "page_data" => $new_arr,
                        "success" => "The list is un-shared with the users.")
                    );
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            //throw $e;
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function show_list_in_detail($list_id, $list_name) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
                if ($list_name === "" || $list_id === "") {
                    throw new Exception("Listcontroller::show_list_in_detail::The list details is blank. Please provide proper details", 1);
                } else {
                    $this->load->model("User_model", "user");
                    $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                    $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                    $new_arr = array();
                    foreach ($page_data as $data) {
                        $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                        foreach ($count->result_array() as $row)
                            $new_arr[] = array_merge($data, $row);
                    }
                    $this->load->model("List_subscriber_model", "list_subscriber");
                    $show_view_list_form = $this->list_subscriber->get_list_subscriber_join_details($list_id);

                    $this->load->view("pages/create-list-form", array(
                        "page_data" => $new_arr,
                        "show_view_list_form" => $show_view_list_form,
                        "list_name" => $list_name,
                        "list_id" => $list_id)
                    );
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            //throw $e;
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function process_subscriber_deletion($list_id) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {

                if ($list_id === "" || is_null($list_id)) {
                    throw new Exception("No list id provided");
                } else {
                    # get the list owner
                    $this->load->model("User_list_relation_model", "user_list");
                    $delete_list_where = array("list_id" => $list_id);
                    $list_owner_id = $this->user_list->generic_user_list_relation_select_field($delete_list_where, "user_id");

                    # if list owner id is blank or null, then this list should not be in the system
                    if ($list_owner_id === "" || is_null($list_owner_id)) {
                        throw new Exception("Orphaned list. There is no owner assigned to the list. Please ask admin to delete this list and create a new one.");
                    } else {
                        # if the list is in the system and you are the owner of this list then proceed
                        if ($list_owner_id === $this->session->userdata("user_id")) {
                            # get the post variables in an array because we are not sure of the number of checkboxed checked in the input form
                            $post_array = $this->input->post();
                            $data = array();
                            $status = false;

                            # populate the post_array with the
                            foreach ($post_array as $post_val) {
                                $temp_arr = array("where" => array("subscriber_id" => $post_val));
                                $data[] = $temp_arr;
                            }

                            # load the user model
                            $this->load->model("User_model", "user");

                            # load the subscriber model
                            $this->load->model("Subscriber_model", "subscriber");
                            $status = $this->subscriber->generic_subscriber_delete($data);

                            # if the deletion is successful then load the view
                            if ($status) {
                                # get the page data below
                                $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                                $new_arr = array();
                                foreach ($page_data as $data) {
                                    $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                                    foreach ($count->result_array() as $row)
                                        $new_arr[] = array_merge($data, $row);
                                }
                                $this->load->view("pages/create-list-form", array(
                                    "page_data" => $new_arr,
                                    "success" => "The selected subscribers are deleted.")
                                );
                            }
                            # else throw an exception
                            else {
                                throw new Exception("Could not delete the list in the database");
                            }
                        } else {
                            throw new Exception("You are not authorized to delete the subscriber. Only list owner can delete the subscriber from this list.");
                        }
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            //throw $e;
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function delete_list($list_id, $list_name) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
                # validate if the input variables are provided.
                if ($list_name === "" || $list_id === "") {
                    throw new Exception("The list details are blank. Please provide proper details", 1);
                } else {
                    # get the list owner
                    $this->load->model("User_list_relation_model", "user_list");
                    $delete_list_where = array("list_id" => $list_id);
                    $list_owner_id = $this->user_list->generic_user_list_relation_select_field($delete_list_where, "user_id");

                    # if list owner id is blank or null, then this list should not be in the system
                    if ($list_owner_id === "" || is_null($list_owner_id)) {
                        throw new Exception("Orphaned list. There is no owner assigned to the list. Please ask admin to delete this list and create a new one.");
                    } else {
                        # if the list is in the system and you are the owner of this list then proceed
                        if ($list_owner_id === $this->session->userdata("user_id")) {
                            # prepare the data for the list deletion
                            $where = array("where" => array("list_id" => $list_id));
                            $data = array($where);

                            # load the list model
                            $this->load->model("List_model", "list");

                            # get the status of the deletion
                            $status = $this->list->generic_list_delete($data);
                            if ($status) {
                                $this->load->model("User_model", "user");
                                $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                                $new_arr = array();
                                foreach ($page_data as $data) {
                                    $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                                    foreach ($count->result_array() as $row)
                                        $new_arr[] = array_merge($data, $row);
                                }
                                $this->load->view("pages/create-list-form", array(
                                    "page_data" => $new_arr,
                                    "success" => "The selected list is deleted.")
                                );
                            } else {
                                throw new Exception("Could not delete the list in the database");
                            }
                        } else {
                            throw new Exception("You are not authorized to delete this list. Only list owner can delete the list.");
                        }
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            //throw $e;
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function show_edit_list_form($list_id, $list_name) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
                if ($list_name === "" || $list_id === "") {
                    throw new Exception("Listcontroller::show_edit_list_form::The list details are blank. Please provide proper details", 1);
                } else {
                    $this->load->model("User_model", "user");
                    $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                    $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                    $new_arr = array();
                    foreach ($page_data as $data) {
                        $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                        foreach ($count->result_array() as $row)
                            $new_arr[] = array_merge($data, $row);
                    }
                    $this->load->view("pages/create-list-form", array(
                        "page_data" => $new_arr,
                        "show_edit_list_form" => true,
                        "list_id" => $list_id,
                        "list_name" => $list_name)
                    );
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            //throw $e;
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function import_subscriber_txt($update_list = "") {
        # variables
        $list_id = "";
        $user_id = $this->session->userdata("user_id");
        $if_subscriber_needs_delete = array();

        # load the models
        $this->load->model("List_model", "list");
        $this->load->model("User_model", "user");
        $this->load->model("User_list_relation_model", "user_list");
        $this->load->model("Subscriber_model", "subscriber");
        $this->load->model("List_subscriber_model", "list_subscriber");

        try {
            # if the user is logged in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {

                # get the post variables
                $list_name = $this->input->post("list_name");
                $list_description = $this->input->post("list_description");

                # Throw and exception when the post variables are empty
                if ($list_description === "" || $list_name === "") {
                    throw new Exception("Please provide list name and list id", 1);
                }
                # else if the post variables are set then proceed


                /* --------------START Get the list id to use from database ------------------------------ */
                # The section below performs following
                # if its an update to the existing list, then validate if this user is the owner of the list.
                # only the owner of the list can update the list.
                # if this is a new list, then no need to check for the owner as this is a new list.
                # create the new list and insert records in the list_master and list_user_relation table.
                # use this newly created list_id for the further processing.
                else {
                    if ($update_list !== "" && !is_null($update_list)) {
                        ## check to see here if the list is owned by the user in case of update
                        # get the list id from the post request
                        $list_id_from_form = $this->input->post("list_id");

                        # prepare the array to check if the current userr is the owner of this list
                        $list_array_query_db = array("list_id" => $list_id_from_form);

                        # get the list lowner id from the database
                        $list_owner_id = $this->user_list->generic_user_list_relation_select_field($list_array_query_db, "user_id");

                        # if list owner id is blank or null, then this list should not be in the system
                        if ($list_owner_id === "" || is_null($list_owner_id)) {
                            throw new Exception("Orphaned list. There is no owner assigned to the list. Please ask admin to delete this list and create a new one.");
                        } else {
                            # the idea of this block is to make sure that user can edit their own lists only
                            # In case the list is their's then list-id is stored else and exception is thrown
                            if ($list_owner_id === $this->session->userdata("user_id")) {
                                # set where clause to get the list details
                                $where_clause = array(
                                    "list_name" => $list_name,
                                    "is_list_complete" => '1'
                                );

                                # get the list-id
                                $list_id = $this->list->generic_list_select_field($where_clause, "list_id");

                                # check the return value of the list_id
                                if ($list_id === "") {
                                    throw new Exception("The list is not available in the database", 1);
                                }
                            } else {
                                throw new Exception("Shared lists can not be edited. Only list-owner can edit the lists.");
                            }
                        }
                    } else {
                        # get tge list quota from the database
                        $list_quota = (int) $this->user->get_user_field($this->session->userdata("email"), "user_list_limit");

                        # get the list-limit set in the databse for this user
                        $my_current_list_count = (int) $this->user_list->get_my_current_list_count($this->session->userdata("user_id"));

                        # if the list limit is exhausted, throw an exception
                        if ($list_quota !== 0 && $my_current_list_count >= $list_quota) {
                            throw new Exception("Your list quota has exhausted. You cannot create any more lists.", 2);
                        }

                        # set the initial list values -- (1)
                        $list_data = array("list_name" => $list_name, "list_description" => $list_description);

                        # if the details in (1) are inserted properly
                        if ($this->list->generic_list_insert(array($list_data))) {
                            # set the where clause
                            $list_where = array(
                                "list_name" => $list_name,
                                "send_status" => '2',
                                "is_list_complete" => '0',
                                "total_send" => 0
                            );

                            # get the list-id
                            $list_id = $this->list->generic_list_select_field($list_where, "list_id");

                            # if the list id is blank throw an exception
                            if ($list_id === "") {
                                throw new Exception("Could not get list-id", 1);
                            }
                            # else continue processing
                            else {
                                # set the user_list data
                                $data = array(array(
                                        "user_id" => $user_id,
                                        "list_id" => $list_id,
                                        "is_enabled" => '0')
                                );

                                # if the user_list details are not inserted properly then throw an exception
                                if (!$this->user_list->generic_user_list_relation_insert($data)) {
                                    throw new Exception("Could not insert user_list_relation details", 1);
                                }
                            }
                        }
                        # if the list details are not inserted then throw an exception
                        else {
                            throw new Exception("List with the same name already exists. Please change the list name and try again", 1);
                        }
                    }
                }
                /* --------------END Get the list id to use from database ------------------------------ */

                /* --------------START file upload configuration and create dir ------------------------ */
                # file upload variables
                $dir_name = $this->session->userdata("dir_name") . '/uploads';
                $file_name = "imported_txt_" . $this->session->userdata("name") . ".txt";

                # configuation array for the file upoad property
                $config = array(
                    "upload_path" => $dir_name,
                    "max_size" => "2048",
                    "allowed_types" => "txt",
                    "file_name" => $file_name,
                    "overwrite" => true);
                $list_subscriber_insert = array();

                # load the fileupload library
                $this->load->library("upload", $config);

                # if the user directory is not available the create
                if (!is_dir($dir_name)) {
                    mkdir($dir_name, 0777, true);
                }
                /* --------------END file upload configuration and create dir -------------------------- */

                # perform the file upload and if success proceed further
                if ($this->upload->do_upload("txt_file_input")) {

                    # read the csv file
                    $txt_configuration = array("file_name" => $dir_name . "/" . $file_name, "count" => 4);
                    $this->load->library("texthandler", $txt_configuration);
                    $txt_data = $this->texthandler->read_txt_data();

                    //print_r($txt_data);
                    //throw new Exception("Error Processing Request", 1);
                    # inform the user if there are no records in the input file
                    if (count($txt_data) === 0) {
                        throw new Exception("No data in the imported txt file. Please validate the text file and try again", 1);
                    } else {
                        //print_r($csv_data);
                        # get the list of all the subscribers from the database
                        $data = array("get_all" => true);

                        # Take each element of the array
                        foreach ($txt_data as $subscriber) {
                            $subscriber_id = "";
                            $email = $subscriber[0];
                            # Check if the email exists in the database and get the subscriber-id
                            $id = $this->subscriber->generic_subscriber_select_field(array("email" => trim($email)), "subscriber_id");
                            //print_r($id);echo "    ";
                            if ($id !== "" || !empty($id)) {
                                # if email exists, get the subscriber id
                                $subscriber_id = $id;
                            }
                            # if email does not exists
                            else {

                                # by default set the valid parameter as true (0=valid,1=invalid in the databse)
                                $valid = "0";

                                # if the email-id is not valid then mark the status of the customer as invalid 
                                if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) === FALSE) {
                                    $valid = "1";
                                    $invalid_emails[] = trim($email);
                                }

                                # prepare the insert array for the database
                                $insert_data = array(
                                    "email" => trim($email),
                                    "phone" => $subscriber[1],
                                    "mobile" => $subscriber[2],
                                    "place" => $subscriber[3],
//                                    "email_status" => $subscriber[4],
                                    "fname" => $subscriber[5],
                                    "lname" => $subscriber[6],
                                    "valid" => $valid,
                                    "email_status" => "3"
                                );
                                # insert the data in the subscriber_master table.
                                if ($this->subscriber->generic_subscriber_insert(array($insert_data))) {
                                    # get the subscriber id from the database.
                                    //echo "valid is  :" . $valid;
                                    $subscriber_id = $this->subscriber->generic_subscriber_select_field(array(
                                        "email" => trim($email),
                                        "email_status" => "3",
                                        "valid" => $valid), "subscriber_id"
                                    );
                                    if (empty($subscriber_id) || $subscriber_id === "") {
                                        throw new Exception("Could not get a subscriber id from the database for new subscriber");
                                    }
                                } else {
                                    throw new Exception("Could not insert the subscriber in the database", 1);
                                }
                            }
                            # So far we have managed to get a valid list-id and a valid subscriber-id from the database
                            # Now we populate the below 2 arrays
                            #   $list_subscriber_insert[] 
                            #   $if_subscriber_needs_delete[] 
                            $list_subscriber_insert[] = array(
                                "list_id" => $list_id,
                                "subscriber_id" => $subscriber_id
                            );
                            $if_subscriber_needs_delete[] = array(
                                "where" => array("subscriber_id" => $subscriber_id)
                            );
                        }
                    }
                }
                # if there are issues during the file upload
                else {
                    throw new Exception($this->upload->display_errors(), 1);
                }


                # if thre are few valid records for inserting into the list_subscriber_relation table for insert
                if (count($list_subscriber_insert) > 0) {

                    # if the insertion in the table list_subscriber_relation is successful
                    if ($this->list_subscriber->generic_list_subscriber_insert($list_subscriber_insert)) {

                        # set the where clause
                        $where_list_array = array(
                            "list_name" => $list_name,
                            "list_description" => $list_description,
                            "is_list_complete" => '0'
                        );
                        $update_list_array = array("is_list_complete" => '1');
                        $data = array(array("where" => $where_list_array, "update" => $update_list_array));

                        # update the initial inserts in the user_list_ralation table (1)
                        if ($this->list->generic_list_update($data)) {
                            $update_user_list = array(array("where" => array(
                                        "list_id" => $list_id,
                                        "user_id" => $user_id,
                                        "is_enabled" => '1'),
                                    "update" => array("is_enabled" => 1))
                            );

                            # finally add the details into user_list_relation table
                            if ($this->user_list->generic_user_list_relation_update($update_user_list)) {

                                # load the model for the list table.
                                $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                                $new_arr = array();
                                foreach ($page_data as $data) {
                                    $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                                    foreach ($count->result_array() as $row)
                                        $new_arr[] = array_merge($data, $row);
                                }
                                $message = "";
                                if ($update_list === "")
                                    $message = "The list is uploaded";
                                else
                                    $message = "The list is updated";
                                # load this page again.
                                $this->load->view("pages/create-list-form", array(
                                    "page_data" => $new_arr,
                                    "success" => $message)
                                );
                            }
                            # if the records in the user_list_relation are not inserted
                            else {
                                throw new Exception("Could not update user_list data", 1);
                            }
                        }
                        # throw exception when could not update list_subscriber
                        else {
                            throw new Exception("Could not update list_subscriber data", 1);
                        }
                    }
                    # throw exception when could not insert list_subscriber
                    else {
                        throw new Exception("Could not insert list_subscriber data", 1);
                    }
                }
            }
            # if you are not group admin then
            else {
                throw new Exception("You are not authorized to view this page OR Your session has expired", 1);
            }
        } catch (Exception $e) {

            //echo $e->getMessage();
            # delete the data from the list_master
            # this wil cascade delete the data from list_user_relation and list_subscriber_relation
            if ($e->getCode() !== 2) {
                $list_del = array(array("where" => array("list_id" => $list_id)));
                $this->list->generic_list_delete($list_del);
            }

            # delete the data from subscriber_master
            if (count($if_subscriber_needs_delete) > 0)
                $this->subscriber->generic_subscriber_delete($if_subscriber_needs_delete);

            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function import_subscribers_via_dashboard($update_list = "") {
        # variables
        $list_id = "";
        $user_id = $this->session->userdata("user_id");
        $if_subscriber_needs_delete = array();

        # load the models
        $this->load->model("List_model", "list");
        $this->load->model("User_model", "user");
        $this->load->model("User_list_relation_model", "user_list");
        $this->load->model("Subscriber_model", "subscriber");
        $this->load->model("List_subscriber_model", "list_subscriber");
        $invalid_emails = array();

        try {
            # if the user is logged in
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {

                # get the post variables
                $list_name = $this->input->post("list_name");
                $list_description = $this->input->post("list_description");
                $post_array = explode(PHP_EOL, $this->input->post('list_data'));

                # Throw and exception when the post variables are empty
                if ($list_description === "" || $list_name === "" || !isset($post_array) || count($post_array) === 0) {
                    throw new Exception("Listcontoller::import_subscribers_via_dashboard::Please provide proper list details", 1);
                }

                /* --------------START Get the list id to use from database ------------------------------ */
                # The section below performs following
                # if its an update to the existing list, then validate if this user is the owner of the list.
                # only the owner of the list can update the list.
                # if this is a new list, then no need to check for the owner as this is a new list.
                # create the new list and insert records in the list_master and list_user_relation table.
                # use this newly created list_id for the further processing.
                # else if the post variables are set then proceed
                else {
                    if ($update_list !== "" && !is_null($update_list)) {
                        ## check to see here if the list is owned by the user in case of update
                        # get the list id from the post request
                        $list_id_from_form = $this->input->post("list_id");

                        # prepare the array to check if the current user is the owner of this list
                        $list_array_query_db = array("list_id" => $list_id_from_form);

                        # get the list owner id from the database
                        $list_owner_id = $this->user_list->generic_user_list_relation_select_field($list_array_query_db, "user_id");

                        # if list owner id is blank or null, then this list should not be in the system
                        if ($list_owner_id === "" || empty($list_owner_id)) {
                            throw new Exception("Orphaned list. There is no owner assigned to the list. Please ask admin to delete this list and create a new one.");
                        } else {

                            # if the list owner from the database is same as the current user
                            if ($list_owner_id === $this->session->userdata("user_id")) {
                                # set where clause to get the list details
                                $where_clause = array("list_name" => $list_name, "is_list_complete" => '1');

                                # get the list-id
                                $list_id = $this->list->generic_list_select_field($where_clause, "list_id");
                            } else {
                                throw new Exception("You are not the owner of this list. Please contact the owner of the list for updating this list", 2);
                            }
                        }
                    } else {
                        # Check database to see if there is any list quota on this user
                        $list_quota = (int) $this->user->get_user_field($this->session->userdata("email"), "user_list_limit");

                        # get the count of lists owned by me now
                        $my_current_list_count = (int) $this->user_list->get_my_current_list_count($this->session->userdata("user_id"));

                        # if number of lists is >= to my current quota, then throw and exception
                        if ($list_quota !== 0 && $my_current_list_count >= $list_quota) {
                            throw new Exception("Your list quota has exhausted. You cannot create any more lists.", 2);
                        }

                        # set the initial list values -- (1)
                        $list_data = array("list_name" => $list_name, "list_description" => $list_description);

                        # insert listname and list description in the database
                        if ($this->list->generic_list_insert(array($list_data))) {
                            # set the where clause
                            $list_where = array(
                                "list_name" => $list_name,
                                "send_status" => '2',
                                "is_list_complete" => '0',
                                "total_send" => 0
                            );

                            # get the list-id from list_master table
                            $list_id = $this->list->generic_list_select_field($list_where, "list_id");

                            # if the list id is blank throw an exception
                            if (empty($list_id) || $list_id === "") {
                                throw new Exception("Could not get the list-id from the database. Please check with your admin and try again", 1);
                            }
                            # else continue processing
                            else {
                                # Here after above validation, we are trying to insert the details in the user_list_relation table
                                # This will ensure that no list is orphan.
                                # In case, the list is deleted, This relation will be cascade deleted from the database. (auto delete)
                                # set the user_list data
                                $data = array(array(
                                        "user_id" => $user_id,
                                        "list_id" => $list_id,
                                        "is_enabled" => '0')
                                );

                                # if the user_list details are not inserted properly then throw an exception
                                if (!$this->user_list->generic_user_list_relation_insert($data)) {
                                    throw new Exception("Could not create user-list relation in the database", 1);
                                }
                            }
                        }
                        # if the list details are not inserted then throw an exception
                        else {
                            throw new Exception("List with the same name already exists. Please change the list name and try again", 1);
                        }
                    }
                }
                /* --------------END Get the list id to use from database ------------------------------ */


                /* --------------START Get subscriber id from database ------------------------------ */
                # if atleast 1 email id is submitted from the dashboard
                if (count($post_array) > 0) {
                    //print_r($post_array);
                    # Take each element of the array
                    foreach ($post_array as $email) {
                        $subscriber_id = "";
                        # Check if the email exists in the database and get the subscriber-id
                        $id = $this->subscriber->generic_subscriber_select_field(array("email" => trim($email)), "subscriber_id");
                        //print_r($id);echo "    ";
                        if ($id !== "" || !empty($id)) {
                            # if email exists, get the subscriber id
                            $subscriber_id = $id;
                        }
                        # if email does not exists
                        else {

                            # by default set the valid parameter as true (0=valid,1=invalid in the databse)
                            $valid = "0";

                            # if the email-id is not valid then mark the status of the customer as invalid 
                            if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) === FALSE) {
                                $valid = "1";
                                $invalid_emails[] = trim($email);
                            }

                            # prepare the insert array for the database
                            $insert_data = array(
                                "email" => trim($email),
                                "valid" => $valid,
                                "email_status" => "3"
                            );

                            # insert the data in the subscriber_master table.
                            if ($this->subscriber->generic_subscriber_insert(array($insert_data))) {
                                # get the subscriber id from the database.
                                //echo "valid is  :" . $valid;
                                $subscriber_id = $this->subscriber->generic_subscriber_select_field(array(
                                    "email" => trim($email),
                                    "email_status" => "3",
                                    "valid" => $valid), "subscriber_id"
                                );
                                if (empty($subscriber_id) || $subscriber_id === "") {
                                    throw new Exception("Could not get a subscriber id from the database for new subscriber");
                                }
                            } else {
                                throw new Exception("Could not insert the subscriber in the database", 1);
                            }
                        }
                        # So far we have managed to get a valid list-id and a valid subscriber-id from the database
                        # Now we populate the below 2 arrays
                        #   $list_subscriber_insert[] 
                        #   $if_subscriber_needs_delete[] 
                        $list_subscriber_insert[] = array(
                            "list_id" => $list_id,
                            "subscriber_id" => $subscriber_id
                        );
                        $if_subscriber_needs_delete[] = array(
                            "where" => array("subscriber_id" => $subscriber_id)
                        );
                    }
                } else {
                    throw new Exception("No data provided for importing into the database", 1);
                }

//                print_r($invalid_emails);
                //print_r($if_subscriber_needs_delete);
                /* --------------END Get subscriber id from database ------------------------------ */

                # if thre are few valid records fir inserting into the list_subscriber_relation table for insert
                if (count($list_subscriber_insert) > 0) {

                    # if the insertion in the table list_subscriber_relation is successful
                    if ($this->list_subscriber->generic_list_subscriber_insert($list_subscriber_insert)) {

                        # set the where clause
                        $where_list_array = array(
                            "list_name" => $list_name,
                            "list_description" => $list_description,
                            "is_list_complete" => '0'
                        );
                        $update_list_array = array("is_list_complete" => '1');
                        $data = array(array("where" => $where_list_array, "update" => $update_list_array));

                        # update the initial inserts in the user_list_ralation table (1)
                        if ($this->list->generic_list_update($data)) {
                            $update_user_list = array(array("where" => array(
                                        "list_id" => $list_id,
                                        "user_id" => $user_id,
                                        "is_enabled" => '1'),
                                    "update" => array("is_enabled" => 1))
                            );

                            # finally add the details into user_list_relation table
                            if ($this->user_list->generic_user_list_relation_update($update_user_list)) {

                                # load the model for the list table.
                                $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                                $new_arr = array();
                                foreach ($page_data as $data) {
                                    $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                                    foreach ($count->result_array() as $row)
                                        $new_arr[] = array_merge($data, $row);
                                }
                                # load this page again.
                                $message = "";
                                if ($update_list === "")
                                    $message = "The list is uploaded";
                                else
                                    $message = "The list is updated";
                                $this->load->view("pages/create-list-form", array(
                                    "page_data" => $new_arr,
                                    "success" => $message,
                                    "invalid_emails" => $invalid_emails)
                                );
                            }
                            # if the records in the user_list_relation are not inserted
                            else {
                                throw new Exception("Could not update user_list data", 1);
                            }
                        }
                        # throw exception when could not update list_subscriber
                        else {
                            throw new Exception("Could not update list_subscriber data", 1);
                        }
                    }
                    # throw exception when could not insert list_subscriber
                    else {
                        throw new Exception("Could not insert list_subscriber data", 1);
                    }
                }
            }
            # if you are not group admin then
            else {
                throw new Exception("You are not authorized to view this page OR Your session has expired", 1);
            }
        } catch (Exception $e) {

            # delete the data from the list_master
            # this wil cascade delete the data from list_user_relation and list_subscriber_relation
            if ($e->getCode() !== 2) {
                $list_del = array(array("where" => array("list_id" => $list_id)));
                $this->list->generic_list_delete($list_del);
            }

            # delete the data from subscriber_master
            if (count($if_subscriber_needs_delete) > 0) {
                $this->subscriber->generic_subscriber_delete($if_subscriber_needs_delete);
            }

            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function show_list_for_update($list_id, $list_name, $type = "") {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
                if ($list_name === "" || $list_id === "") {
                    throw new Exception("The list details are blank. Please provide proper details", 1);
                } else {

                    # get the list owner
                    $this->load->model("User_list_relation_model", "user_list");
                    $delete_list_where = array("list_id" => $list_id);
                    $list_owner_id = $this->user_list->generic_user_list_relation_select_field($delete_list_where, "user_id");

                    # if list owner id is blank or null, then this list should not be in the system
                    if ($list_owner_id === "" || is_null($list_owner_id)) {
                        throw new Exception("Orphaned list. There is no owner assigned to the list. Please ask admin to delete this list and create a new one.");
                    } else {
                        # if the list is in the system and you are the owner of this list then proceed
                        if ($list_owner_id === $this->session->userdata("user_id")) {
                            # prepare the data for the list deletion
                            # 
                            //$data = array($where);
                            $this->load->model("User_model", "user");
                            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));

                            $this->load->model("List_subscriber_model", "list_subscriber");
							if($type === "" || empty($type) || is_null($type))
                            	$list_data_for_update = $this->list_subscriber->get_list_subscriber_join_details($list_id);
							else
								$list_data_for_update = $this->list_subscriber->get_list_subscriber_join_details($list_id,$type);
                            //print_r($list_data_for_update);
                            $this->load->view("pages/create-list-form", array(
                                "list_data_for_update" => $list_data_for_update,
                                "list_id" => $list_id,
                                "list_name" => $list_name)
                            );
                        } else {
                            throw new Exception("You are not authorized to delete this list. Only list owner can delete the list.");
                        }
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            //throw $e;
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

    public function save_list_details($list_id, $list_name) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata("is_logged_in")) {
                if ($list_name === "" || $list_id === "") {
                    throw new Exception("The list details are blank. Please provide proper details", 1);
                } else {

                    # get the list owner
                    $this->load->model("User_list_relation_model", "user_list");
                    $delete_list_where = array("list_id" => $list_id);
                    $list_owner_id = $this->user_list->generic_user_list_relation_select_field($delete_list_where, "user_id");

                    # if list owner id is blank or null, then this list should not be in the system
                    if ($list_owner_id === "" || is_null($list_owner_id)) {
                        throw new Exception("Orphaned list. There is no owner assigned to the list. Please ask admin to delete this list and create a new one.");
                    } else {
                        # if the list is in the system and you are the owner of this list then proceed
                        if ($list_owner_id === $this->session->userdata("user_id")) {

                            # prepare the data for the list deletion
                            $post_array = $this->input->post();
                            $count_post = count($post_array) / 8;
//print_r($post_array);
                            $count = 0;
                            $update_array = array();
                            $invalid_emails = array();
                            for ($i = 0; $i < $count_post; $i++) {
                                $where = array("subscriber_id" => $this->input->post("id_" . $count));
                                if (filter_var($this->input->post("email_" . $count), FILTER_VALIDATE_EMAIL) === false) {
                                    $invalid_emails[] = $this->input->post("email_" . $count);
                                    //throw new Exception("Invalid email id " . $this->input->post("email_" . $count) . " Please provide a correct email", 2);
                                }
								if( in_array($this->input->post("status_" . $count), array("1","3","5")))
									$status = "0";
								else 
									$status = "1";	
                                $update = array(
                                    "email" => $this->input->post("email_" . $count),
                                    "fname" => $this->input->post("fname_" . $count),
                                    "lname" => $this->input->post("lname_" . $count),
                                    "place" => $this->input->post("place_" . $count),
                                    "mobile" => $this->input->post("mobile_" . $count),
                                    "phone" => $this->input->post("phone_" . $count),
                                    "email_status" => $this->input->post("status_" . $count),
                                    "valid"=>$status
                                );
                                $update_array[] = array(
                                    "where" => $where,
                                    "update" => $update
                                );
                                $count++;
                            }
                            $this->load->model("Subscriber_model", "subscriber");
                            $status = $this->subscriber->generic_subscriber_update($update_array);
                            if ($status) {
                                # load the models and get the page data
                                $this->load->model("User_model", "user");
                                $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
                                $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
                                $new_arr = array();
                                foreach ($page_data as $data) {
                                    $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                                    foreach ($count->result_array() as $row)
                                        $new_arr[] = array_merge($data, $row);
                                }
                                $this->load->view("pages/create-list-form", array(
                                    "page_data" => $new_arr,
                                    "success" => "The list data is modified",
                                    "invalid_emails" => $invalid_emails)
                                );
                            } else {
                                throw new Exception("There is a duplicate email-id in the form. Please remove the duplicate and try again.");
                            }
                        } else {
                            throw new Exception("You are not authorized to delete this list. Only list owner can delete the list.");
                        }
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $new_arr = array();
            foreach ($page_data as $data) {
                $count = $this->db->select("count(1) as count")->where(array("list_id" => $data[0]))->get("list_subscriber_relation");
                foreach ($count->result_array() as $row)
                    $new_arr[] = array_merge($data, $row);
            }
            $this->load->view("pages/create-list-form", array(
                "page_data" => $new_arr,
                "error" => array("error_type" => $e->getMessage()))
            );
        }
    }

	public function download_list($list_id){
		try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {

                $query = "select b.`email`,b.`phone`,b.`mobile`,b.`place`, 
                	(case 
                		when b.`email_status`='1' then 'Soft Bounce' 
                		when b.`email_status`='2' then 'Spam' 
                		when b.`email_status`='3' then 'Unverified' 
                		when b.`email_status`='4' then 'Unsubscribed' 
                		when b.`email_status`='5' then 'Verified' 
                		when b.`email_status`='6' then 'Hard Bounce' 
                		when b.`email_status`='7' then 'Rejected' 
                	end) as Email_status,b.`fname`,b.`lname`, 
                	(case 
                		when b.`valid`='0' then 'Valid' else 'Invalid' 
                	end) as Status 
                	from list_subscriber_relation a, subscriber_master b 
                	where a.subscriber_id = b.subscriber_id and 
                	a.list_id ='$list_id'";
					
				$this->load->helper('download');
				$this->load->dbutil();
				$result = $this->db->query($query);
				force_download("list.csv", $this->dbutil->csv_from_result($result));
            } else {
                throw new Exception("You are not authorized to view this page OR Your session has expired");
            }
        } catch (Exception $e) {
            # in case of any other exception show the below error message to the user
            $this->load->view("pages/error_message", array(
                "message" => $e->getMessage())
            );
        }
	}
}
