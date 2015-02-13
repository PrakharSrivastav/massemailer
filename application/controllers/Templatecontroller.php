<?php

defined('BASEPATH') OR exit('No direct script access authorized');

class Templatecontroller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function show_template_edit_page($template_id, $template_name) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                if ($template_id === "" || $template_name === "") {
                    throw new Exception("Templatecontroller::show_template_edit_page::The template details are blank. Please provide proper details", 1);
                } else {
                    $this->load->model("Template_model", "template");
                    $where = array("template_id" => $template_id);
                    $show_template = $this->template->generic_template_select($where);
                    $this->load->view("pages/edit-template-page", array("show_template" => $show_template));
                }
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exeption $e) {
            //throw $e;
            $this->load->model("Template_model", "template");
            $template_data = array();
            $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
            $this->load->view("pages/manage-template-page", array("template_data" => $template_data, "error" => array("error_type" => $e->getMessage())));
        }
    }

    public function show_template($template_id, $template_name) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                if ($template_id === "" || $template_name === "") {
                    throw new Exception("Templatecontroller::show_template::The template details are blank. Please provide proper details", 1);
                } else {
                    $this->load->model("Template_model", "template");
                    $where = array("template_id" => $template_id);
                    $show_template = $this->template->generic_template_select($where);

                    $template_data = array();
                    $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
                    $this->load->view("content/view-manage-template", array("template_data" => $template_data, "show_template" => $show_template));
                }
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exeption $e) {
            //throw $e;
            $this->load->model("Template_model", "template");
            $template_data = array();
            $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
            $this->load->view("pages/manage-template-page", array("template_data" => $template_data, "error" => array("error_type" => $e->getMessage())));
        }
    }

    public function unshare_with_users($template_id) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in') && $template_id !== "") {
                $user_id = $this->session->userdata("user_id");
                $shared_status = FALSE;

                $this->load->model("Template_model", "template");
                $template_data = array();
                $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));

                $this->load->model("User_template_model", "user_template");
                $delete_where = array("user_id" => $user_id, "template_id" => $template_id);
                $data = array("where" => $delete_where);
                $shared_status = $this->user_template->generic_user_template_delete(array($data));

                $insert_array = array("template_id" => $template_id, "user_id" => $user_id, "shared_with" => '0');
                $shared_status = $this->user_template->generic_user_template_insert(array($insert_array));
                if ($shared_status) {
                    $this->load->model("Template_model", "template");
                    $template_data = array();
                    $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
                    $this->load->view("pages/manage-template-page", array("template_data" => $template_data, "success" => "The Template is un-shared with the users."));
                }
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exception $e) {
            $this->load->model("Template_model", "template");
            $template_data = array();
            $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
            $this->load->view("pages/manage-template-page", array("template_data" => $template_data, "error" => array("error_type" => $e->getMessage())));
            ;
        }
    }

    public function share_with_users_form_process() {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                $request_count = count($this->input->post());
                $post_array = $this->input->post();
                print_r($post_array);
                $user_id = $this->session->userdata("user_id");

                if ($request_count === 0) {
                    $this->load->model("Template_model", "template");
                    $template_data = array();
                    $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
                    $this->load->view("pages/manage-template-page", array("template_data" => $template_data));
                } else {
                    $this->load->model("User_template_model", "user_template");
                    $shared_status = false;
                    $i = 0;
                    foreach ($post_array as $key => $value) {

                        $post_values = explode(":", $value);
                        $this->load->model("User_model", "user");
                        $child_user_id = $this->user->get_user_field($post_values[1], "user_id");
                        if ($child_user_id !== false) {
                            $template_id = $post_values[0];
                            if ($i === 0) {
                                $delete_where = array("user_id" => $user_id, "template_id" => $template_id);
                                $data = array("where" => $delete_where);
                                $shared_status = $this->user_template->generic_user_template_delete(array($data));
                                $i++;
                            }
                            $insert_array = array("template_id" => $template_id, "user_id" => $user_id, "shared_with" => $child_user_id);
                            //$this->load->model("tem")
                            $shared_status = $this->user_template->generic_user_template_insert(array($insert_array));
                        } else {
                            throw new Exception("Templatecontroller:share_with_users_form_process:could not get user_id for the children", 1);
                        }
                    }
                    if ($shared_status) {
                        $this->load->model("Template_model", "template");
                        $template_data = array();
                        $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
                        $this->load->view("pages/manage-template-page", array("template_data" => $template_data, "success" => "The template is shared with the users."));
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exception $e) {
            $this->load->model("Template_model", "template");
            $template_data = array();
            $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
            $this->load->view("pages/manage-template-page", array("template_data" => $template_data, "error" => array("error_type" => $e->getMessage())));
        }
    }

    public function share_template_with_users($template_id, $template_name) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                if ($template_id === "" || $template_name === "") {
                    throw new Exception("Templatecontroller::share_template_with_users::The template details are blank. Please provide proper details", 1);
                } else {
                    $this->load->model("User_model", "user");
                    $where = array("user_admin_id" => $this->session->userdata("user_id"), "is_active" => '1');
                    $user_list = $this->user->generic_user_select($where);

                    //print_r($user_list);

                    $this->load->model("Template_model", "template");
                    $template_data = array();
                    $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
                    $this->load->view("pages/manage-template-page", array("template_data" => $template_data, "show_share_list_form" => $user_list, "template_id" => $template_id, "template_name" => $template_name));
                }
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exeption $e) {
            //throw $e;
            $this->load->model("User_model", "user");
            $page_data = $this->user->get_my_lists_data($this->session->userdata("user_id"));
            $page_data = array_merge($page_data, $this->user->get_lists_shared_with_me($this->session->userdata("user_id")));
            $this->load->view("pages/create-list-form", array("page_data" => $page_data, "error" => array("error_type" => $e->getMessage())));
        }
    }

    public function delete_template($template_id) {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                if (!is_null($template_id) && $template_id !== "") {
                    # load the tempalte_model
                    $this->load->model("Template_model", "template");

                    # find the criteria for the search in the database
                    $delete_where = array("template_id" => $template_id);

                    # get the template upload directory from the database
                    $upload_dir = $this->template->generic_template_select_field($delete_where, "upload_dir");

                    # delete the tempalte data in the database
                    if ($this->template->generic_template_delete(array(array("where" => $delete_where)))) {
                        # if the database delete is successful, then load the file helper
                        $this->load->helper("file");
                        # delets the files under the tempalte directory
                        delete_files($upload_dir, TRUE);
                        # once the files are deleted, the remove the directory
                        if (is_dir($upload_dir)) {
                            if (rmdir($upload_dir)) {

                                # get the data for the page
                                $template_data = array();
                                $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
                                # call the view to load
                                $this->load->view("pages/manage-template-page", array("template_data" => $template_data));
                            } else {
                                throw new Exception("Could not delete the tempalte directory");
                            }
                        } else {
                            # in case the data is deleted from the databse, but the directory is not then still go to the manage template page.
                            $template_data = array();
                            $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
                            $this->load->view("pages/manage-template-page", array("template_data" => $template_data));
                        }
                    }
                } else {
                    throw new Exception("Templatecontroller::delete_template::Please provide the template-id to delete");
                }
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exception $ex) {
            $this->load->view("pages/error_message", array("message" => $ex->getMessage()));
        }
    }

    public function show_manage_template_page() {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                $this->load->model("Template_model", "template");
                $template_data = array();
                $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
                //print_r($template_data);
                $this->load->view("pages/manage-template-page", array("template_data" => $template_data));
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exception $ex) {
            $this->load->view("pages/error_message", array("message" => $ex->getMessage()));
        }
    }

    public function show_create_template_page() {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                $this->load->view("pages/create-template-page");
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exception $ex) {
            $this->load->view("pages/error_message", array("message" => $ex->getMessage()));
        }
    }

    public function ajaxy() {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                # file upload variables
                $dir_name = "./".$this->session->userdata("dir_name") . '/uploads/template';
                $file_name = $this->input->post("target_filename");
                $upload_path = $this->input->post("upload_path");

                if ($upload_path === "" || empty($upload_path)) {

                    $file_name = $this->input->post("template_image_name").$this->input->post("file_ext");
					/*
					echo $file_name;
					echo "\ndirname "+$dir_name;
					echo "\nupload_path"+$upload_path;
					*/
                    $config = array(
                        "upload_path" => $dir_name,
                        "max_size" => "2048000",
                        "allowed_types" => "HTM|HTML|htm|html",
                        "file_name" => $file_name,
                        "overwrite" => true
                    );
                } else {
                    $config = array(
                        "upload_path" => $upload_path,
                        "max_size" => "2048000",
                        "allowed_types" => "rtf|txt|doc|docx|ppt|pptx|xls|xlsx|txt|zip|rar|pdf|PDF|JPG|HTM|HTML|JPEG|GIF|PNG|TIF|IMG|jpg|htm|html|jpeg|gif|png|tif|img",
                        "file_name" => $file_name,
                        "overwrite" => true
                    );
                }


                # load the fileupload library
                $this->load->library("upload", $config);

                # if the user directory is not available the create
                if (!is_dir($dir_name)) {
                    mkdir($dir_name, 0777, true);
                    echo "dir created";
                }
                $f = $this->input->post("filename");
                if ($f === "" || is_null($f)) {
                    $f = "template_image";
                }

                //echo $f;
                if ($this->upload->do_upload($f)) {
                    if ($f === "template_image")
                        echo "File/Attachment uploaded successfully";
                    else
                        echo 1;
                } else {
                    print_r($this->upload->display_errors());
                }
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exception $ex) {
            //$this->load->view("pages/error_message", array("message" => $ex->getMessage()));
            print_r($ex->getMessage());
        }
    }

    public function create_dir_for_upload($template_name = "") {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                $dir_name = "";
                if ($template_name != "") {
                    $this->load->model("Template_model", "template");
                    $dir_name = $this->template->generic_template_select_field(array("template_name" => $template_name), "upload_dir");
                } else {
                    $template_id = uniqid();
                    $dir_name = $this->session->userdata("dir_name") . '/uploads/' . $template_id;
                    if (!is_dir($dir_name)) {
                        mkdir($dir_name, 0777, true);
                    }
                }

                echo $dir_name;
            } else {
                echo "0";
            }
        } catch (Exeption $e) {
            echo "0";
        }
    }

    public function savetemplate($template_id = "") {
        //$template_id = "";
        $this->load->model("Template_model", "template");
        $this->load->model("User_template_model", "user_template");
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {

                $images = $this->input->post("images");
                $image_array = explode(",", $images);
                $upload_dir = $this->input->post("upload_dir");
                $template_content = $this->input->post("template_content");
                $template_name = $this->input->post("template_name");
                $template_desc = $this->input->post("template_desc");
                $dir = base_url() . $upload_dir."/";

                $post_array = $this->input->post();
				//print_r($post_array);
                $src_details = array();
                foreach ($image_array as $i) {
                    //echo $dir.$i;
                    $temp = array();
                    $temp["image"] = $i;
                    $temp["src"] = $dir . $i;
                    $src_details[] = $temp;
                }
                //print_r($src_details);

                

                if ($template_id === "") {
                    // get the post variables

                    $html_template = "<html>"
                            . "<head><meta http-equiv='Content-Type' content='text/html'><meta charset='UTF-8'></head>"
                            . "<body>"
                            . "<section id='body'>CONTENT</section>"
                            . "</body>"
                            . "</html>";
                    if (($template_name === "" || is_null($template_name))) {
                        throw new Exception("Templatecontroller::savetempalate::Please provide all the necessary inputs");
                    } else {
                        $this->load->library("html_dom");
                        $this->html_dom->loadHTML($template_content);
                        $img = $this->html_dom->find("img");
						$str = str_replace("&amp;","&",$template_content);
                        //print_r($img);
                        foreach ($img as $a) {
                            $image_source = $a->src;
                            foreach ($src_details as $s) {
                                if (stripos($image_source, $s["image"]) !== false) {
                                    $str = str_replace($image_source, $s["src"], $str);
                                }
                            }
                        }

                        $this->html_dom->loadHTML($str);
                        $anchor = $this->html_dom->find("a");
                        foreach ($anchor as $a) {
                            $href = $a->href;
                            //print($href);
                            if ($href != "0") {
                                foreach ($src_details as $s) {
                                    if (stripos($href, $s["image"]) !== false) {
                                        $str = str_replace($href, $s["src"], $str);
                                    }
                                }
                            }
                        }
                        $str = "Hi #NAME#,<br/>".$str;
						$str = str_replace(array("> <",">  <"),"><",$str);
                        $html = str_replace("CONTENT", $str, $html_template);
                        $html = str_replace(array("\n","\r","\t"),"",$html);
						
                        $insert_data = array(
                            "template_name" => $template_name,
                            "template_desc" => $template_desc,
                            //"template_subject" => $template_subject,
                            "template_content" => $html,
                            "upload_dir" => $upload_dir,
                            "last_updated"=>date("Y-m-d H:i:s")
                                //"reply_to" => $reply_to,
                        );

                        if ($this->template->generic_template_insert(array($insert_data))) {
                            $template_data = array(
                                "template_name" => $template_name
                            );
                            $template_id = $this->template->generic_template_select_field($template_data, "template_id");
                            //echo "Template id is $template_id";
                            if ($template_id === false) {
                                throw new Exception("Templatecontroller::savetempalate::Could not get the template id");
                            } else {

                                $insert_user_temp_data = array(
                                    "template_id" => $template_id,
                                    "user_id" => $this->session->userdata("user_id"),
                                );
                                if ($this->user_template->generic_user_template_insert(array($insert_user_temp_data))) {
                                    $this->load->view("pages/create-template-page", array("status" => true));
                                } else {
                                    throw new Exception("Templatecontroller::savetempalate::Could not insert user_template data");
                                }
                            }
                        } else {
                            throw new Exception("Templatecontroller::savetempalate::Template could not be saved, please try again");
                        }
                    }
                } else {

                    $this->load->library("html_dom");
                    $this->html_dom->loadHTML($template_content);
                    $img = $this->html_dom->find("img");
					//print_r($img);
					$str = str_replace("&amp;","&",$template_content);
                    foreach ($img as $a) {
                        $image_source = $a->src;
						//print_r($image_source);
                        foreach ($src_details as $s) {
                            if (stripos($image_source, $s["image"]) !== false) {
                            	//print_r($image_source);
                            	//echo "<br />";
                            	//print_r($s["image"]);
                                //echo "<br />";
                                //print_r($s["src"]);
								//echo "<br/>";
                                $str = str_replace($image_source , $s["src"],  $str);
								//print_r($str);
                            }
                        }
                    }
//					print_r($str);
                   $this->html_dom->loadHTML($str);
                   $anchor = $this->html_dom->find("a");
                   foreach ($anchor as $a) {
                       $href = $a->href;
//                        //print($href);
                       if ($href != "0") {
                           foreach ($src_details as $s) {
                               if (stripos($href, $s["image"]) !== false) {
                                   $str = str_replace($href, $s["src"], $str);
                               }
                           }
                       }
                   }
                    $str = str_replace(array("\n","\r","\t"),"",$str);
                    $str = str_replace(array("> <"),"><",$str);
                    $status = false;
                    $where = array("template_id" => $template_id);
                    $update = array(
                        "template_name" => $template_name,
                        "template_desc" => $template_desc,
                        "template_content" => $str,
                        "last_updated"=>date("Y-m-d H:i:s")
                    );
                    $template = array("where" => $where, "update" => $update);

                    $status = $this->template->generic_template_update(array($template));

                    if ($status) {
                        $this->load->model("Template_model", "template");
                        $template_data = array();
                        $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
                        $this->load->view("pages/manage-template-page", array("template_data" => $template_data, "success" => "The template is updated."));
                    } else {
                        throw new Exception("Templatecontroller::savetempalate::Template could not be saved, please try again");
                    }
                }
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exception $ex) {
            $delete_where = array("template_id" => $template_id);
            $this->template->generic_template_delete(array(array("where" => $delete_where)));
            //$this->load->view("pages/error_message", array("message" => $ex->getMessage()));

            $this->load->model("Template_model", "template");
            $template_data = array();
            $template_data = $this->template->get_my_tempalte_data($this->session->userdata("user_id"));
            $this->load->view("pages/manage-template-page", array("template_data" => $template_data, "error" => array("error_type" => $ex->getMessage())));
        }
    }

    public function get_attachment_list() {

        $files = $this->session->userdata("dir_name") . "/uploads/template/";
        $all_files = glob("$files/*.*");
        //$image_details = array();
        //print_r($files);
        $pdf_details = array();
        foreach ($all_files as $file) {

            $file_arr = explode("/", $file);
            $full_name = end($file_arr);
            $file_info = explode(".", $full_name);
            $file_name = ucfirst($file_info[0]);
            $file_ext = end($file_info);
            $file_path = base_url($files . $full_name);

            if (in_array(strtolower($file_ext), array("rtf", "txt", "doc", "docx", "ppt", "pptx", "xls", "xlsx", "txt", "zip", "rar", "pdf"))) {
                $temp = array();
                $temp["title"] = $file_name;
                $temp["value"] = "$file_path";
                $pdf_details [] = $temp;
            }
            //print_r($file_path);
        }
        //print_r($pdf_details);
        echo "<html><head></head><body style='color:blue'> Please select the attachment document:";
        echo "<select id='content' >";
        foreach ($pdf_details as $pdf) {
            $title = $pdf['title'];
            $value = $pdf['value'];
            //ECHO $value;
            echo "<option value ='<a href=$value>$title</a>'>" . $title . "</option>";
            $value = "";
            $title = "";
        }
        echo "</select>";
        echo "</body></html>";
    }

    public function upload_attachments() {
        try {
            if ($this->session->has_userdata("is_logged_in") && $this->session->userdata('is_logged_in')) {
                $this->load->view("pages/upload-attachments");
            } else {
                throw new Exception("You are not authorized to view this page");
            }
        } catch (Exception $ex) {
            $this->load->view("pages/error_message", array("message" => $e->getMessage()));
        }
    }

}
