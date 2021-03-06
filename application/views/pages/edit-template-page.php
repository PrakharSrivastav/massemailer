<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
        <meta name="author" content="GeeksLabs">
        <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
        <link rel="shortcut icon" href="img/favicon.png">

        <title>Edit Templates</title>
        <?php
        $this->load->helper('html');
        echo link_tag('resources/css/bootstrap.min.css');
        echo link_tag('resources/css/bootstrap-theme.min.css');
        echo link_tag('resources/css/style.css');
        echo link_tag('resources/css/adminstyle.css');
        echo link_tag('resources/css/elegant-icons-style.css');
        echo link_tag('resources/css/font-awesome.min.css');
        echo link_tag('resources/css/nice-style.css');
        echo link_tag('resources/css/style-responsive.css');
        ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
          <script src="js/lte-ie7.js"></script>
        <![endif]-->
    </head>

    <body>
        <!-- container section start -->
        <section id="container" class="">
            <?php
            $logged_in = $this->session->userdata('is_logged_in');
            $user_role = $this->session->userdata('user_role');

            if ($logged_in && $user_role === '1') {
                $this->load->view("template/view-header");
                $this->load->view("template/view-sidebar");
                $this->load->view("content/view-edit-template");
            } else if ($logged_in && $user_role === '2') {
                $this->load->view("template/view-header");
                $this->load->view("template/view-group-admin-sidebar");
                $this->load->view("content/view-edit-template");
            } else if ($logged_in && $user_role === '3') {
                $this->load->view("template/view-header");
                $this->load->view("template/view-user-sidebar");
                $this->load->view("content/view-edit-template");
            } else {
                $this->load->view("pages/error_message", array(
                    "message" => "You are not authorized to view this page OR your session has expired.")
                );
            }
            ?>    
        </section>
        <!-- container section end -->
        <!-- javascripts -->    
        <script src="<?php echo base_url(); ?>resources/js/jquery-2.1.1.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/script.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/scripts.js"></script>
        <!--<script src="<?php echo base_url(); ?>resources/js/Chart.js"></script>-->
        <script src="<?php echo base_url(); ?>resources/js/jquery.validate.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/tinymce1/tinymce.min.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/template.js"></script>
        <?php
        $files = $this->session->userdata("dir_name") . "/uploads/template/";
        $all_files = glob("$files/*.*");
        $image_details = array();
        $html_details = array();
        foreach ($all_files as $file) {
            $temp = array();
            $file_arr = explode("/", $file);
            $full_name = end($file_arr);
            $file_info = explode(".", $full_name);
            $file_name = ucfirst($file_info[0]);
            $file_ext = end($file_info);
            $file_path = base_url($files . $full_name);
            $temp["title"] = $file_name;
            $temp["value"] = $file_path;
            if (strtolower($file_ext) === "htm" || strtolower($file_ext) === "html") {
                $html_details [] = $temp;
            } else if (!in_array(strtolower($file_ext), array("rtf", "txt", "doc", "docx", "ppt", "pptx", "xls", "xlsx", "txt", "zip", "rar", "pdf"))) {
                $image_details [] = $temp;
            }
        }
        ?>
        <script>
            var base_url = '<?php echo base_url(); ?>';
            tinymce.init({
                selector: '#edit-template',
                plugins: 'template  code attachment advlist paste autolink link image lists charmap print preview',
                paste_data_images: true,
                height:400,
                image_list: [<?php
        foreach ($image_details as $image) {
            $title = $image["title"];
            $value = $image["value"];
            echo"{title:'$title',value:'$value'},";
        }
        ?>],
                templates: [<?php
        foreach ($html_details as $html) {
            $title = $html["title"];
            $value = $html["value"];
            echo"{title:'$title',url:'$value'},";
        }
        ?>]
            });
            $(function () {
//                $("#validate_template").attr("disabled", false);
//                $("#template_name").attr("disabled", true);
//                $("#template_desc").attr("disabled", true);
//                $("#submit_template_to_database").attr("disabled", true);
                var dir_name = "";

                $("#validate_template").on("click", function () {

                    var template_name = $("#template_name").val();
                    $("#file_inputs_via_js").empty();
                    var ajaxRequest;

                    try {
                        ajaxRequest = new XMLHttpRequest();
                    }
                    catch (e) {
                        try {
                            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                        }
                        catch (e) {
                            try {
                                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            catch (e) {
                                alert("Problem with the browser");
                            }
                        }
                    }

                    ajaxRequest.open("POST", base_url + "templatecontroller/create_dir_for_upload/" + template_name, false);
                    ajaxRequest.send();

                    if (ajaxRequest.responseText !== '0') {

                        dir_name = ajaxRequest.responseText;
//                        console.log(dir_name);
//                        console.log("one");

                        $("#file_inputs_via_js").empty();
//                        $("#template_name").attr("disabled", false);
//                        $("#template_desc").attr("disabled", false);
//                        $("#submit_template_to_database").attr("disabled", false);
                        $("#validate_template").attr("disabled", true);
						// regex for identifying the image tags
                        var html = tinyMCE.activeEditor.getContent();
                        var m, urls = [], rex = /<img.*?src="((.*?))".*?>/g;
                        while (m = rex.exec(html)) {
                            rex2 = /[a-zA-Z0-9-_]*\.(png|jpeg|gif|tif|img|ico|img|jpg)\[?|w*]$/i;
                            v = rex2.exec(m[1]);
                            if (v !== null) {
                                if (-1 === jQuery.inArray(v[0], urls)) {
                                    urls.push(v[0]);
                                }
                            }
                        }
                        
                        // regex for identifying the other attachment tags
                        // var url_att = [], rex3 = /<a.*?href="([^">]*\/([^">]*?))".*?>/g;
                        var url_att = [], rex3 = /<a.*?href="(.*?)".*?>/g;
                        while (m = rex3.exec(html)) {
                            rex4 = /[a-zA-Z0-9-_]*\.(doc|docx|xls|xlsx|ppt|pps|wp|rtf|pdf|zip|rar|tar|txt)\[?|w*]$/i;
                            v = rex4.exec(m[1]);
                            if (v !== null) {
                                if (-1 === jQuery.inArray(v[0], url_att)) {
                                    url_att.push(v[0]);
                                }
                            }
                        }
						
						// regex for identifying the html files
                        // var url_html = [], rex_html = /<a.*?href="([^">]*\/([^">]*?))".*?>/g;
                        var url_html = [], rex_html = /<a.*?href="(.*?)".*?>/g;
                        while (m = rex_html.exec(html)) {
                            rex4html = /[a-zA-Z0-9-_]*\.(htm|html)\[?|w*]$/i;
                            v = rex4html.exec(m[1]);
                            if (v !== null) {
                                if (-1 === jQuery.inArray(v[0], url_att)) {
                                    url_html.push(v[0]);
                                }
                            }
                        }
                        // console.log(urls , urls.length);
						// console.log(url_att, url_att.length);
						// console.log(url_html,url_html.length);
                        if (urls.length > 0 || url_att.length > 0 || url_html.length >0) {
                            count = 0;
                            $("#file_inputs_via_js").append("<br /><div class='rownew-element' style='padding:15px'>");
                            jQuery.each(urls, function (i, l) {
                            	// console.log("appending"+l);
                                $("#file_inputs_via_js").append(
                                        "<div>" +
                                        "<div id='div_" + l + "'class='col-sm-6' style='border-radius:5px;color:black;background-color:lightgrey;width:48%;margin:10px;padding:5px;'>" +
                                        "<label class='col-sm-4'>" + l + "</label>" +
                                        "<input  class='col-sm-6' type='file' required='required' id='" + count + "' name='" + l + "'>" +
                                        "<input type='submit' class='col-sm-2 pull-right upload' id='bt_" + count + "'value='upload'>" +
                                        "</div>" +
                                        "</div>");
                                count++;
                            });
                            jQuery.each(url_att, function (i, l) {
                            	// console.log("appending"+l);
                                $("#file_inputs_via_js").append(
                                        "<div>" +
                                        "<div id='div_" + l + "'class='col-sm-6' style='border-radius:5px;color:black;background-color:lightgrey;width:48%;margin:10px;padding:5px;'>" +
                                        "<label class='col-sm-4'>" + l + "</label>" +
                                        "<input  class='col-sm-6' type='file' required='true' id='" + count + "' name='" + l + "'>" +
                                        "<input type='submit' class='col-sm-2 pull-right upload' id='bt_" + count + "'value='upload'>" +
                                        "</div>" +
                                        "</div>");
                                count++;
                            });
                            jQuery.each(url_html, function (i, l) {
                            	// console.log("appending"+l);
                                $("#file_inputs_via_js").append(
                                        "<div>" +
                                        "<input  type='hidden' required='true' id='" + count + "' name='" + l + "'>" +
                                        "</div>");
                                count++;
                            });
                            $("#file_inputs_via_js").append("</div>");
//                            console.log(dir_name);
//                            console.log("2");
//                            console.log(url_html);
                        }
                    }
                    else {
                        alert("error creating file");
                    }
                });
                $("#submit_template_to_database").on("click", function (e) {
                    e.preventDefault();
                    var flag = false, template_flag = true, url = [];
                    count = 0;
                    var message = "Following items are missing: ";
                    var f = $("input[required]").each(function () {
                        // if ($(this).attr("name") !== "template_name")
                        // {
                            // url.push($(this).attr('name'));
                        // }
						// console.log($(this).attr("name"));
                        if ($(this).val() === "") {
                            if ($(this).attr("name") === "template_name") {
                                alert("Template name is missing. You cannot save template without providing a template name");
                                template_flag = false;
                                return false;
                            }
                            else {
                                //alert($(this).attr('name') + "  " + $(this).attr('name').search(".htm"));
                                if ($(this).attr('name').search(".htm") < 0)
                                    message += "\n    " + count + ". Image " + $(this).attr('name');
                            }
                            flag = false;
                        }
                        else {
                            if ($(this).attr("name") !== "template_name")
                            { 
                            	//console.log($(this).attr("name"));
                            	
                            	url.push($(this).attr('name'));
                            
                                flag = true;
                            }
                        }
                        count++;
                    });
                    // console.log(url);
                    if (flag === true) {
                        $("#template_form ").append("<input type='hidden' name='images' value='" + url + "'>");
                        $("#template_form ").append("<input type='hidden' name='upload_dir' value='" + dir_name + "'>");
                        $("#template_form ").submit();
                    }
                    else {
                        if (template_flag === true) {
                            message += "\nDo you wish to continue saving the template?";
                            var r = confirm(message);
                            if (r === true) {
                                $("#template_form ").append("<input type='hidden' name='images' value='" + url + "'>");
                                $("#template_form ").append("<input type='hidden' name='upload_dir' value='" + dir_name + "'>");
                                $("#template_form ").submit();
                            } else {
                                return false;
                            }
                        } else
                            return false;
                    }
                });
                $(document).on("click", ".upload", (function () {
                    btn_id = $(this).attr("id");
                    file_id = btn_id.substr(3);
                    var file_input = document.getElementById(file_id);
                    var file_name = file_input.value;
                    var file_element = document.getElementById(file_id).files[0];

                    if (file_name === "") {
                        alert("Please provide the input file to upload");
                        return false;
                    }
                    else {
                        if (file_element.name === file_input.getAttribute("name")) {
                            formdata = new FormData();
                            formdata.append("userfile", file_element);
                            formdata.append("filename", "userfile");
                            formdata.append("target_filename", file_element.name);
                            var ajaxRequest;
                            try {ajaxRequest = new XMLHttpRequest();}
                            catch (e) {try {ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");}
                            catch (e) {try { ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");}
                            catch (e) {alert("Problem with the browser");}}}
                            // console.log(dir_name);
                            // console.log("3");
                            formdata.append("upload_path", dir_name);
                            ajaxRequest.open("POST", base_url + "templatecontroller/ajaxy", false);
                            ajaxRequest.send(formdata);
                            // console.log(ajaxRequest.responseText);
                            div = document.getElementById("div_" + file_element.name);
                            if (ajaxRequest.responseText == '1') {
                                div.style.background = "lightgreen";
                                document.getElementById(btn_id).disabled = true;
                                document.getElementById(file_id).disabled = true;
                            }
                            else {
                                div.style.background = "orange";
                            }
                        }
                        else {
                            alert("The uploaded file has a different name. Please upload \"" + file_input.getAttribute("name") + "\"");
                        }
                    }
                }));
            });
        </script>
    </body>
</html>
