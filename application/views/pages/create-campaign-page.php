<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
        <meta name="author" content="GeeksLabs">
        <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
        <link rel="shortcut icon" href="img/favicon.png">

        <title>Manage Campaigns</title>
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

            if ($logged_in && $user_role === '3') {
                $this->load->view("template/view-header");
                $this->load->view("template/view-user-sidebar");
                $this->load->view("content/view-create-campaign");
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
        <script src="<?php echo base_url(); ?>resources/js/Chart.js"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery.validate.min.js"></script>
        
        <script src="<?php echo base_url(); ?>resources/js/datepicker.js"></script>
        <script type="text/javascript">
            $(function () {
            	// When the document is ready
            // $(document).ready(function () {
// 
                // $('#senddate1').datepicker({
                    // format: "yyyy-mm-dd"
                // });
// 
            // });
                $("#test_send_button").on("click", function (e) {
                    e.preventDefault();
                    var temp = $("#template_name").val();
                    var sub = $("#campaign_subject").val();
                    var email = $("#test_email").val();
                    var reply_to = $("#reply_to").val();
                    var sender = $("#camp_sender_name").val();
                    //alert(sender);
                    if (sender === "" || temp === "" || reply_to === "" || sub === "" || email === "") {
                        alert("The test inputs are missing. Please make sure you provide following details:\n    1. Template name\n    2. Template subject\n    3. Sender Name \n    4. Reply To address\n    5. Test email-id\n Any try again")
                    }
                    else {
                        var validation = true;
                        
                        if (email.split(",").length > 3) {
                            validation = false;
                            alert("Maximum 3 email-ids are allowed for sending test emails. Please try again with a lesser count.");
                            return false;
                        }
                        /*
                        if (email.split(";").length > 1) {
                            validation = false;
                            alert("Only one email-id is allowed. email cannot contain a semi-colon (,).");
                            return false;
                        }
                        if (email.split(" ").length > 1) {
                            validation = false;
                            alert("Only one email-id is allowed. email cannot contain a space.");
                            return false;
                        }
                        */
                        if (reply_to.split(",").length > 1) {
                            validation = false;
                            alert("Only one email-id is allowed in reply to field. email cannot contain a comma (,).");
                            return false;
                        }
                        if (reply_to.split(";").length > 1) {
                            validation = false;
                            alert("Only one email-id is allowed in reply to field. email cannot contain a semi-colon (,).");
                            return false;
                        }
                        if (reply_to.split(" ").length > 1) {
                            validation = false;
                            alert("Only one email-id is allowed in reply to field. email cannot contain a space.");
                            return false;
                        }
                        if (validation) {
                            var myform = document.getElementById("test_campaign_form");
                            var template_name = document.createElement("input");
                            template_name.name = "template_name";
                            template_name.type = "hidden";
                            template_name.setAttribute("id", "template_name");
                            template_name.value = temp;

                            var template_subject = document.createElement("input");
                            template_subject.name = "template_subject";
                            template_subject.type = "hidden";
                            template_subject.setAttribute("id", "template_subject");
                            template_subject.value = sub;

                            var reply_to_email = document.createElement("input");
                            reply_to_email.name = "reply_to";
                            reply_to_email.type = "hidden";
                            reply_to_email.setAttribute("id", "reply_to");
                            reply_to_email.value = reply_to;

                            var sender_name = document.createElement("input");
                            sender_name.name = "sender_name";
                            sender_name.type = "hidden";
                            sender_name.setAttribute("id", "sender_name");
                            sender_name.value = sender;

                            myform.appendChild(template_name);
                            myform.appendChild(template_subject);
                            myform.appendChild(reply_to_email);
                            myform.appendChild(sender_name);
                            myform.submit();
                        }
                    }
                });


                $("#preview_template").on("click", function (e) {
                    e.preventDefault();
                    var temp = $("#template_name").val();
                    var reply_to = $("#reply_to").val();

                    if (temp === "" || reply_to === "") {
                        alert("Template name OR reply to address is missing. Please select the proper data and press Preview button");
                    }
                    else {
                        arr = temp.split("|");
                        base_url = "<?php echo base_url(); ?>";
                        act = base_url + "campaigncontroller/preview_template/" + arr[1] + "/" + arr[0];
                        //alert(reply_to);
                        var preview_form = document.getElementById('preview_template_form');
                        preview_form.method = "post";
                        preview_form.name = "preview_template_form";
                        preview_form.action = act;
                        var template_name = document.createElement("input");
                        template_name.name = "template_name";
                        template_name.type = "hidden";
                        template_name.setAttribute("id", "template_name");
                        template_name.value = arr[0]

                        var template_id = document.createElement("input");
                        template_id.name = "template_id";
                        template_id.type = "hidden";
                        template_id.setAttribute("id", "template_id");
                        template_id.value = arr[1];

                        var reply_to_email = document.createElement("input");
                        reply_to_email.name = "reply_to";
                        reply_to_email.type = "hidden";
                        reply_to_email.setAttribute("id", "reply_to");
                        reply_to_email.value = reply_to;

                        preview_form.appendChild(template_name);
                        preview_form.appendChild(template_id);
                        preview_form.appendChild(reply_to_email);

                        preview_form.submit();
                    }

                });
                $("#set_emails_to_q").submit(function (event) {
                    event.preventDefault();
                    var r = confirm("Please confirm if you want to send this Campaign!!\nOnce clicked this  process cannot be revereted");
                    if (r == true) {
                        var form = document.getElementById('set_emails_to_q');
                        form.submit();
                    } else {
                        return false;
                    }
                });

            });
        </script>
    </body>
</html>
