<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <?php if (!isset($show_template) || !is_array($show_template)){ ?>
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?php echo base_url(); ?>users/users_dashboard">Home</a></li>
                    <li><i class="fa fa-send"></i>Campaigns</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        
        <div class="col-sm-12 col-lg-6 col-md-12 col-xs-12 panel-group m-bot20" id="accordion">
            <div class="panel panel-primary row" >
                <div class="panel-heading">
                    <header>
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#setup_send_campaign"> 
                            Configure and send campaigns
                        </a>
                    </header>
                </div>
                <section class="panel panel-content panel-collapse collapse in" id="setup_send_campaign" >
                    <ul>
                        <li>* Provide #NAME# in the subject line for the system to replace it with name.</li>
                        <li>* Provide the test-email address and press "Test" button to test the campaign.</li>
                        <li>* Your email-id is the default reply to address. You can change it manually if needed</li>
                    </ul>
                    <form class="form-horizontal" id="set_emails_to_q" method="post" action="<?php base_url(); ?>set_emails_to_queue">
                        <div class="form-group">
                            <label for="template_name" class="col-sm-2 control-label">Template</label>
                            <div class="col-sm-10">
                                <select id="template_name" name="template_name" class="form-control input-sm" required="required">
                                    <option></option>
                                    <?php foreach ($template_data as $template) { ?>
                                        <option  <?php echo  set_select('template_name', $template[2] . "|" . $template[3]); ?> value="<?php echo $template[2] . "|" . $template[3]; ?>"><?php echo $template[2]; ?></option>
                                    <?php } ?>  
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="list_name" class="col-sm-2 control-label">Lists</label>
                            <div class="col-sm-10">
                                <select id="list_name" name="list_name[]" multiple class="input-sm form-control" size="10" required="required">
                                    <option></option>
                                    <?php foreach ($list_data as $list) { ?>
                                        <option <?php echo  set_select('list_name', $list[0]) ?> value="<?php echo $list[0]; ?>"><?php echo $list[1]; ?></option>
                                    <?php } ?> 
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label  for="campaign_subject" class="col-sm-2 control-label">Subject *</label>
                            <div class="col-sm-10">
                                <input type="text" class="input-sm form-control" id="campaign_subject" placeholder="Please provide the email subject" 
                                       required="required" name="campaign_subject" value="<?php echo set_value('campaign_subject');?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  for="senddate1" class="col-sm-2 control-label">Senddate-1 *</label>
                            <div class="col-sm-10">
                                <input disabled="true" type="datetime" class="input-sm form-control" id="senddate1" placeholder="Please provide the email subject" 
                                       required="required" name="senddate1" value="<?php  $dt = new DateTime(); echo $dt->format("Y-m-d H:I:s"); ?>">
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label  for="camp_sender_name" class="col-sm-2 control-label">Sender *</label>
                            <div class="col-sm-4">
                                <input type="text" class="input-sm form-control" id="camp_sender_name" placeholder="Please provide the sender name" 
                                       required="required" name="camp_sender_name" value="<?php echo set_value('camp_sender_name');?>">
                            </div>
                            <label  for="reply_to" class="col-sm-2 control-label">Reply to *</label>
                            <div class="col-sm-4">
                                <input type="email" class="input-sm form-control" id="reply_to" placeholder="Please provide the reply-to email" 
                                       required="required" name="reply_to" value="<?php echo set_value('reply_to',$reply_to) ; ?>">
                            </div>
                        </div>
                        <!--
                        <div class="form-group">
                            <label  for="reply_to" class="col-sm-2 control-label">Reply to *</label>
                            <div class="col-sm-10">
                                <input type="email" class="input-sm form-control" id="reply_to" placeholder="Please provide the reply-to email" 
                                       required="required" name="reply_to" value="<?php //echo $reply_to; ?>">
                            </div>
                        </div>
                        -->
                        <div class="form-group">
                            <div class="col-sm-6">
                                <button type="submit" class="btn input-sm  btn-compose">Add to sending queue</button></form>
                            </div>
                            <div class="col-sm-6">
                                <form id="preview_template_form" target="_blank">
                                <button type="button" id="preview_template" class="btn input-sm btn-compose">Preview Campaign</button>
                                </form>
                            </div>
                        </div>
                </section>
                <section style="background:peachpuff;color:black ;margin:auto;padding-top:5px">
                    <p class="text-center">Test campaign here</p>
                    <form class="form col-sm-12" role="form" action="test_email_campaign" method="post" id="test_campaign_form" style="background:peachpuff;">
                        <input class="col-sm-12 input-sm form-control" type="text" id="test_email" value="<?php echo set_value('test_email') ; ?>"
                               placeholder="Please provide comma seperated value for email-ids to test (maximum 3)" name="test_email">
                               <br /><br />
                        <input type="button" class="col-sm-12 input-sm btn btn-space" id="test_send_button" Value="Click to test email" >
                    </form>
                    <br><br>
                    <br>
                </section>
            </div>
        </div>

        <?php } if (isset($status) && $status !== "") { ?>
            <div class="col-sm-12 col-lg-6 col-md-12 col-xs-12">
                <div class="panel panel-primary" >
                    <div class="panel-heading">
                        <header>
                            Status
                        </header>
                    </div>
                    <section class="panel panel-content" id="setup_send_campaign" >
                        <?php echo $status; ?>    
                    </section>
                </div>
            </div>
        <?php } else if (isset($show_template) && is_array($show_template)) { ?>
            <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
                <section class="panel panel-primary">
                    <div class="panel-heading">
<!--                        <header>
                            Preview campaign: "<?php //echo $show_template[0]["template_name"]; ?>
                        </header>-->
                    </div>
                    <div class="panel-body">
<!--                        <H4>Template Name</H4>
                        <input class="well well-sm" value = '<?php //echo $show_template[0]["template_name"]; ?>' type='text' disabled="disabled">
                        <H4>Template Description</H4>
                        <input class="well well-sm" value = '<?php //echo $show_template[0]["template_desc"]; ?>' type='text' disabled="disabled">-->
<!--                        <H4>Template Subject</H4>
                        <div class="well well-sm"><?php //echo $show_template[0]["template_subject"]; ?></div>-->
                        <!--<H4>Template Content</H4>-->
                        <div  id = "template_content" disabled="disabled" class="well well-sm"> <?php echo str_replace("img src", "img style='width:100%;height:auto' src", $show_template[0]["template_content"]); ?></div>
                        <!--<H4>Reply-To</H4>-->
                        <!--<input type='text' disabled="disabled" class="well well-sm" value = '<?php //echo $reply_to; ?>'>-->
                    </div>
                </section>
            </div>
        <?php } ?>
        <!-- page end-->
    </section>
</section>
<script>

</script>
<!--main content end-->
