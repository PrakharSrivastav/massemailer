<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>users/users_dashboard">Home</a></li>
                    <li><i class="fa fa-list"></i>Manage Lists</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <?php if (!isset($list_data_for_update)) { ?>
            <div class="row">
                   <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <header>
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#lists"> 
                            		My Lists (Click here to expand the list)
                            	</a>
                            </header>
                        </div>
                        <div class="table-responsive panel-collapse collapse" id="lists">
                            <table class="table table-condensed table-bordered">
                                <thead>
                                    <tr>
                                            <!--<th>List Id</th>-->
                                        <th>List Name</th>
                                        <th>List Owner</th>
                                        <th>Count</th>
                                        <th>Share</th>
                                        <th>View</th>
                                        <!--<th>Update All</th>-->
                                        <th>Soft Bounce</th>
                                        <th>Spam</th>
                                        <th>Unverified</th>
                                        <th>Unsubscribed</th>
                                        <th>Verified</th>
                                        <th>Hard Bounce</th>
                                        <th>Rejected</th>
                                        <th>Add</th>
                                        <th>Delete</th>
                                    </tr>
                                    <?php if (isset($page_data) && count($page_data) > 0) {
                                    	 foreach ($page_data as $data) { ?>
                                            <tr>
                                                <!--<td><?php //echo $data[4];               ?></td>-->
                                                <td><?php echo $data[1]; ?></td>
                                                <td><?php echo $data[2]; ?></td>
                                                <td><?php echo $data["count"] ?></td>
                                                <!--<td class="text-center"><i class="fa fa-share"></i></td>-->
                                                <?php if ($data[4] != $this->session->userdata("user_id")) { ?>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/share_list_with_users/<?php echo $data[0] . "/" . $data[1]; ?>"><i class="fa fa-share"></i></a></td>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_list_in_detail/<?php echo $data[0] . "/" . $data[1]; ?>"><i class="fa fa-list-alt"></i></a></td>
                                                    <!--<td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_list_for_update/<?php //echo $data[0] . "/" . $data[1]; ?>"><i class="fa fa-file-text-o"></i></a></td>-->
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_list_for_update/<?php echo $data[0] . "/" . $data[1]."/1"; ?>"><i class="fa fa-file-text-o"></i></a></td>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_list_for_update/<?php echo $data[0] . "/" . $data[1]."/2"; ?>"><i class="fa fa-file-text-o"></i></a></td>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_list_for_update/<?php echo $data[0] . "/" . $data[1]."/3"; ?>"><i class="fa fa-file-text-o"></i></a></td>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_list_for_update/<?php echo $data[0] . "/" . $data[1]."/4"; ?>"><i class="fa fa-file-text-o"></i></a></td>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_list_for_update/<?php echo $data[0] . "/" . $data[1]."/5"; ?>"><i class="fa fa-file-text-o"></i></a></td>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_list_for_update/<?php echo $data[0] . "/" . $data[1]."/6"; ?>"><i class="fa fa-file-text-o"></i></a></td>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_list_for_update/<?php echo $data[0] . "/" . $data[1]."/7"; ?>"><i class="fa fa-file-text-o"></i></a></td>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/show_edit_list_form/<?php echo $data[0] . "/" . $data[1]; ?>"><i class="fa fa-edit"></i></a></td>
                                                    <td class="delete text-center"><a href="<?php echo base_url(); ?>listcontroller/delete_list/<?php echo $data[0] . "/" . $data[1]; ?>"><i class="fa fa-trash-o"></i></a></td>
                                                <?php } else if ($data[4] == $this->session->userdata("user_id")) { ?>
                                                    <td class="text-center"><a href="<?php echo base_url(); ?>listcontroller/share_list_with_users/<?php echo $data[0] . "/" . $data[1]; ?>"><i class="fa fa-share"></i></a></td>
                                                    <td class="text-center"><i class="fa fa-list-alt"></i></td>
                                                    <td class="text-center"><i class="fa fa-file-text-o"></i></td>  
                                                    <!--<td class="text-center"><i class="fa fa-file-text-o"></i></td> -->
                                                    <td class="text-center"><i class="fa fa-file-text-o"></i></td> 
                                                    <td class="text-center"><i class="fa fa-file-text-o"></i></td> 
                                                    <td class="text-center"><i class="fa fa-file-text-o"></i></td> 
                                                    <td class="text-center"><i class="fa fa-file-text-o"></i></td> 
                                                    <td class="text-center"><i class="fa fa-file-text-o"></i></td> 
                                                    <td class="text-center"><i class="fa fa-file-text-o"></i></td>        
                                                    <td class="text-center"><i class="fa fa-edit"></i></td>
                                                    <td class="delete text-center"><i class="fa fa-trash-o"></i></td>
                                                <?php } ?>
                                            </tr>
                                            <?php  }  } ?>

                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class = "col-sm-12 col-lg-6 col-md-6 col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <header>
                                Please chose a method to create list
                            </header>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="create-subscribers" class="col-sm-5">Chose your import method</label>
                                <div id="chose-option" class="col-sm-7">
                                    <input type="radio" name="add-subscriber" value="1"/>
                                    Import using a CSV file
                                    <br />
                                    <input type="radio" name="add-subscriber" value="2"/>
                                    Import using a text file
                                    <br />
                                    <input type="radio" name="add-subscriber" value="3"/>
                                    Import through the dashboard
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                   </div>
                <div id="form-options" class="col-sm-12 col-lg-6 col-md-6 col-xs-12">
                    <div id="option1" class = "row col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <header>
                                    Create a list by importing subscribers from a CSV file
                                </header>
                            </div>
                            <div class="panel-body">
                                <h4>Please provide an input CSV file to import subscribers</h4>
                                <ul>
                                    <li><small>Please provide the header in the file</small></li>
                                    <li><small>Header format should be "email,phone,mobile,place;email-status,fname,lname"</small></li>
                                    <li><small>Please make sure that file extension is .csv</small></li>
                                    <li><small>File would only be processed if above requirements are met.</small></li>
                                    <li><small>Please download a sample csv file  <a href="<?php echo base_url(); ?>resources/sample/sample-csv.zip">here</a> for your reference</small></li>
                                </ul>
                                <?php
                                $this->load->helper('form');
                                $data = array("role" => 'form', 'id' => 'import_subscribers_via_csv_ga');
                                echo form_open_multipart("listcontroller/import_subscriber_csv", $data);
                                ?>
                                <div class="form-group">
                                    <label for="list_name">List Name <small> *List Name should be unique.</small></label>
                                    <input type="text" class="form-control" name="list_name" id="list_name" placeholder="Enter list name" required="required">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="list_description">List Descriptions</label>
                                    <textarea class="form-control" rows="3" id="list_description" name="list_description" required="required" placeholder="Please provide the list description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="csv_file_input">Please select the csv file</label>
                                    <input type="file" id="csv_file_input" name="csv_file_input" required="required">
                                    <p class="help-block">
                                        Uploaded file should match the specifications above.
                                    </p>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Upload and process data
                                </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="option2" class = "row col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <header>Create a list by importing subscribers from a text file</header>
                            </div>
                            <div class="panel-body">
                                <h4>Please provide an input-text file to import subscribers</h4>
                                <ul>
                                    <li><small>Prefer this method if you want to upload information via txt file.</small></li>
                                    <li><small>The first column should follow the format "email,phone,mobile,place;email-status,fname,lname"</small></li>
                                    <li><small>The file extension should be .txt. File of other formats like .doc, .docx etc will not be processed</small></li>
                                    <li><small>Please download a sample text file  <a href="<?php echo base_url(); ?>resources/sample/sample-txt.zip">here</a> for your reference</small></li>
                                </ul>
                                <?php
                                $this->load->helper('form');
                                $data = array("role" => 'form', 'id' => 'import_subscribers_via_txt_ga');
                                echo form_open_multipart("listcontroller/import_subscriber_txt", $data);
                                ?>
                                <div class="form-group">
                                    <label for="list_name">List Name <small> *List Name should be a unique name</small></label>
                                    <input type="text" class="form-control" name="list_name" id="list_name" placeholder="Enter list name" required="required">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="list_description">List Descriptions</label>
                                    <textarea class="form-control" rows="3" id="list_description" name="list_description" required="required" placeholder="Please provide the list description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="txt_file_input">Please select the text file (*.txt)</label>
                                    <input type="file" id="txt_file_input" name="txt_file_input">
                                    <p class="help-block">
                                        Uploaded file should match the above specifications.
                                    </p>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Upload and process data
                                </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="option3" class = "row col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <header>Create a list by manually adding customers through dashboard</header>
                            </div>
                            <div class="panel-body">
                                <h4>Each subscriber email should be in a new line.</h4>
                                <ul>
                                    <li><small>User this method if you just want to import the customer email-ids</small></li>
                                    <li><small>Each subscriber emails should be in a new line.</small></li>
                                    <li><small>Please avoid any duplicates.</small></li>
                                </ul>
                                <form role="form" method="post" action="<?php echo base_url(); ?>listcontroller/import_subscribers_via_dashboard">
                                    <div class="form-group">
                                        <label for="list_name">List Name <small> *List Name should be a unique name</small></label>
                                        <input type="text" class="form-control" name="list_name" id="list_name" placeholder="Enter list name" required="required">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="list_description">List Descriptions</label>
                                        <textarea class="form-control" rows="3" id="list_description" name="list_description" required="required" placeholder="Please provide the list description"></textarea>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="list_data">Enter email ids</label>
                                        <textarea class="form-control" rows="3" id="list_data" name="list_data" required="required" placeholder="Please provide the emails"></textarea>
                                        <p class="help-block">
                                            Email-ids should be in a seperate line
                                        </p>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Update data
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($show_view_list_form) && is_array($show_view_list_form)) {
                        ?>
                        <section class="panel panel-primary">
                            <div class="panel-heading">
                                <header>
                                    View list: "<?php echo $list_name; ?>" (Check the subscriber and submit to delete)
                                </header>
                            </div>
                            <div role="form">
                                <form action="<?php echo base_url(); ?>listcontroller/process_subscriber_deletion/<?php echo $list_id; ?>" method="post">
                                    <div class="table">
                                        <table class="table table-condensed table-bordered">
                                            <thead>
                                                <tr>
                                                    <!--<th>Subscriber id</th>-->
                                                    <th>Email</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Location</th>
                                                    <th>Delete</th>
                                                </tr>
                                                <?php
                                                $count = 0;
                                                foreach ($show_view_list_form as $subscriber):
                                                    ?>
                                                    <tr>
                                                        <!--<td><?php //echo $subscriber[0];         ?></td>-->
                                                        <td><?php echo $subscriber[1]; ?></td>
                                                        <td><?php echo $subscriber[2]; ?></td>
                                                        <td><?php echo $subscriber[3]; ?></td>
                                                        <td><?php echo $subscriber[4]; ?></td>
                                                        <td><input type="checkbox" name = "del_sub_<?php echo $count; ?>" value="<?php echo $subscriber[0]; ?>"</td>
                                                    </tr>
                                                    <?php
                                                    $count++;
                                                endforeach
                                                ?>
                                            </thead>
                                        </table>
                                        <input type="submit" value="Click to delete the selected subscribers" class="btn btn-primary btn-block">
                                    </div>
                                </form>
                            </div>
                        </section>
                        <?php
                    }
                    else if (isset($show_share_list_form) && is_array($show_share_list_form)) {
                        ?>
                        <section class="panel panel-primary">
                            <div class="panel-heading">
                                <header>
                                    Share list: "<?php echo $list_name; ?>" with users.
                                </header>
                            </div>
                            <div class="table">
                                <div class="form">
                                    <form action="<?php echo base_url(); ?>listcontroller/share_with_users_form_process" method="post">
                                        <table class="table no-padding table-condensed table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>User name</th>
                                                    <th>User email</th>
                                                    <th>Share (check to share)</th>
                                                </tr>
                                                <?php
                                                $count = 0;
                                                foreach ($show_share_list_form as $user):
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $user["first_name"]; ?></td>
                                                        <td><?php echo $user["email"]; ?></td>
                                                        <td>
                                                            <input type="checkbox" name="share_<?php echo $count; ?>" value = "<?php echo $list_id . ":", $user["email"]; ?>"/>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $count++;
                                                endforeach
                                                ?>
                                            </thead>

                                        </table>
                                        <input type = "submit" value="Select users and submit to share the list" class="col-sm-6 btn btn-primary"/>
                                        <a href="<?php echo base_url(); ?>listcontroller/unshare_with_users/<?php echo $list_id; ?>" class="col-sm-6 btn  btn-primary">Click to unshare this list</a>
                                    </form>
                                </div>
                            </div>
                        </section>
                        <?php
                    }
                    else if (isset($show_edit_list_form) && $show_edit_list_form) {
                        ?>
                        <section id="list_edit_forms"class="panel panel-primary">
                            <div class="panel-heading">
                                <header>
                                    Edit lists
                                </header>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-5">Chose you input method</label>
                                    <div id="edit-lists-options" class="col-sm-7">
                                        <input type="radio" name="add-subscriber" value="csv" checked="checked"/>
                                        Edit list using a CSV file
                                        <br />
                                        <input type="radio" name="add-subscriber" value="text"/>
                                        Edit list using a text file
                                        <br />
                                        <input type="radio" name="add-subscriber" value="dashboard"/>
                                        Edit manually through the dashboard
                                        <br />
                                    </div>
                                </div>
                                <br />
                                <br />
                                <br />
                                <hr />

                                <!-- Edit lists using the csv START-->
                                <div class="edit_list_form_option" id="edit_using_csv">
                                    <h4>Please provide an input CSV file to add subscribers</h4>
                                    <ul>
                                        <li><small>Please provide the header in the file</small></li>
                                        <li><small>Header format should be "email,phone,mobile,place;email-status,fname,lname"</small></li>
                                        <li><small>Please make sure that file extension is .csv</small></li>
                                        <li><small>File would only be processed if above requirements are met.</small></li>
                                        <li><small>Please download a sample csv file  <a href="<?php echo base_url(); ?>resources/sample/sample-csv.zip">here</a> for your reference</small></li>
                                    </ul>
                                    <?php
                                    $this->load->helper('form');
                                    $data = array("role" => 'form', 'id' => 'add_subscribers_via_csv_ga');
                                    echo form_open_multipart("listcontroller/import_subscriber_csv/update", $data);
                                    ?>
                                    <div class="form-group">
                                        <label for="csv_file_input">Please select the csv file</label>
                                        <input type="file" id="csv_file_input" name="csv_file_input">
                                        <input type="hidden" name="list_id" value="<?php echo $list_id; ?>" />
                                        <input type="hidden" name="list_name" value="<?php echo $list_name; ?>" />
                                        <input type="hidden" name="list_description" value="123" />
                                        <p class="help-block">
                                            Uploaded file should match the specifications above.
                                        </p>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Upload and process data
                                    </button>
                                    </form>
                                </div>
                                <!-- Edits lists using the csv END -->

                                <!-- Edit lists using the text-file START-->
                                <div class="edit_list_form_option" id="edit_using_text">
                                    <h4>Please provide an input-text file to add subscribers</h4>
                                    <ul>
                                        <li><small>Prefer this method if you want to upload information via txt file.</small></li>
                                        <li><small>The first column should follow the format "email,phone,mobile,place;email-status,fname,lname"</small></li>
                                        <li><small>The file extension should be .txt. File of other formats like .doc, .docx etc will not be processed</small></li>
                                        <li><small>Please download a sample text file  <a href="<?php echo base_url(); ?>resources/sample/sample-txt.zip">here</a> for your reference</small></li>
                                    </ul>
                                    <?php
                                    $this->load->helper('form');
                                    $data = array("role" => 'form', 'id' => 'add_subscribers_via_txt_ga');
                                    echo form_open_multipart("listcontroller/import_subscriber_txt/update", $data);
                                    ?>
                                    <div class="form-group">
                                        <label for="txt_file_input">Please select the text file (*.txt)</label>
                                        <input type="file" id="txt_file_input" name="txt_file_input">
                                        <input type="hidden" name="list_id" value="<?php echo $list_id; ?>" />
                                        <input type="hidden" name="list_name" value="<?php echo $list_name; ?>" />
                                        <input type="hidden" name="list_description" value="123" />
                                        <p class="help-block">Uploaded file should match the above specifications.</p>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload and process data</button>
                                    </form>
                                </div>
                                <!-- Edits lists using the text-file END -->

                                <!-- Edit lists using the dashboard START-->
                                <div class="edit_list_form_option" id="edit_using_dashboard">
                                    <h4>Each subscriber email should be in a new line.</h4>
                                    <ul>
                                        <li><small>User this method if you just want to import the customer email-ids</small></li>
                                        <li><small>Each subscrber emails should be in a new line.</small></li>
                                        <li><small>Please avoid any duplicates.</small></li>
                                    </ul>
                                    <form role="form" method="post" action="<?php echo base_url(); ?>listcontroller/import_subscribers_via_dashboard/update">
                                        <div class="form-group col-sm-12">
                                            <label for="list_data">Enter email ids</label>
                                            <textarea class="form-control" rows="3" id="list_data" name="list_data" required="required" placeholder="Please provide the list description"></textarea>
                                            <p class="help-block">Email-ids should be in a new line.</p>
                                            <input type="hidden" name="list_id" value="<?php echo $list_id; ?>" />
                                            <input type="hidden" name="list_name" value="<?php echo $list_name; ?>" />
                                            <input type="hidden" name="list_description" value="123" />
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            Update data
                                        </button>
                                    </form>
                                </div>
                                <!-- Edits lists using the dashboard END -->

                            </div>
                        </section>
                        
                    <?php } else if (isset($error) && count($error) > 0 && $error["error_type"] !== "") { ?>
                        <section class="alert alert-danger fade in">
                            <button data-dismiss="alert" class="close close-sm" type="button">
                                <i class="icon-remove"></i>
                            </button>
                            <strong><i class="fa fa-alert"><?php echo $error["error_type"]; ?></i></strong>
                        </section>
                    <?php } else if (isset($success) && $success !== "") { ?>
                        <section class="alert alert-info fade in">
                            <button data-dismiss="alert" class="close close-sm" type="button">
                                <i class="icon-remove"></i>
                            </button>
                            <strong><i class="fa fa-check"><?php echo $success; ?></i></strong>
                            <?php if(isset($invalid_emails) && count($invalid_emails)>0) { ?>
                            <br/>Following email-ids are invalid due to wrong email-id format. Please correct them to avoid bounces.
                            <ul>
                                <?php foreach($invalid_emails as $email) { ?>
                                <li><?php echo $email ; ?></li>
                                <?php } ?>
                            </ul>
                            <?php } ?>
                        </section>
                    <?php } ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <header>
                                Update list details below.
                            </header>
                        </div>
                        <div class="panel-body">
                            <?php $count = 0; if (count($list_data_for_update) > 0) { ?>
                                <table style="width:100%" class="table-bordered">
                                    <thead>
                                        <tr>
                                        	<th>ID</th>
                                            <th>Email</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Location</th>
                                            <th>Mobile</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <form method ="post" action="<?php echo base_url(); ?>listcontroller/save_list_details/<?php echo $list_id . "/" . $list_name; ?>" >
                                        <?php  foreach ($list_data_for_update as $subscriber) { ?>
                                            <tr>
                                            <td><?php echo $subscriber[0]; ?></td>	
                                          	    <input type ="hidden" name="id_<?php echo $count ?>" value="<?php echo $subscriber[0]; ?>">
                                            <td><input type="email" required="required" class="input-sm" name = "email_<?php echo $count ?>" value="<?php echo $subscriber[1]; ?>"></td>
                                            <td><input type="text"  class="input-sm" name = "fname_<?php echo $count ?>" value="<?php echo $subscriber[2]; ?>"></td>
                                            <td><input type="text"  class="input-sm" name = "lname_<?php echo $count ?>" value="<?php echo $subscriber[6]; ?>"></td>
                                            <td><input type="text"  class="input-sm" name = "place_<?php echo $count ?>" value="<?php echo $subscriber[4]; ?>"></td>
                                            <td><input type="text"  class="input-sm" name = "mobile_<?php echo $count ?>" value="<?php echo $subscriber[5]; ?>"></td>
                                            <td><input type="text"  class="input-sm" name = "phone_<?php echo $count ?>" value="<?php echo $subscriber[3]; ?>"></td>
                                            <td>
                                            	<select name="status_<?php echo $count ?>" class="input-sm">
                                            		<option value = '1' <?php if($subscriber[7]=='1') echo "selected='selected'" ?>>Softbounce</option>
                                            		<option value = '2' <?php if($subscriber[7]=='2') echo "selected='selected'" ?>>Spam</option>
                                            		<option value = '3' <?php if($subscriber[7]=='3') echo "selected='selected'" ?>>Unverified</option>
                                            		<option value = '4' <?php if($subscriber[7]=='4') echo "selected='selected'" ?>>Unsubscribed</option>
                                            		<option value = '5' <?php if($subscriber[7]=='5') echo "selected='selected'" ?>>Verified</option>
                                            		<option value = '6' <?php if($subscriber[7]=='6') echo "selected='selected'" ?>>Hardbounce</option>
                                            		<option value = '7' <?php if($subscriber[7]=='7') echo "selected='selected'" ?>>Rejected</option>
                                            	</select>
                                            </td>
                                            </tr>  
                                            <?php $count++;
                                        }
                                        ?>
                                        <tr><td><input type="submit" class="btn btn-success"></td></tr> 
                                    </form>
                                    </tbody>
                                </table>
                                <?php
                            } else {
                                ?>
                                <h1 class="text-center">No records in this list</h1>
    <?php } ?>



                        </div>
                    </div>
                </div>
            </div>
<?php } ?>
        <!-- page end-->
    </section>
</section>

<!--main content end-->
