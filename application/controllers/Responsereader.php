<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResponseReader
 *
 * @author prakhar
 */
class Responsereader extends CI_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function response_webhook() {
        $path = FCPATH . "../../reports/";
        try {
            $event = "";
            $url = "";
            $ts = "";
            $ip = "";
            $country = "";
            $timezone = "";
            $state = "";
            $email = "";
            $source_ip = "";
            $destination_ip = "";
            $subaccount = "";
            $campaign_id = "";
            $row_id = "";
            $subscriber_id = "";
            # json_decode the content of the post request

            $response_array = json_decode(stripslashes($_POST['mandrill_events']), true, 512);

            foreach ($response_array as $tag) {
                if (isset($tag["event"])) {
                    $event = $tag["event"];
                    if ($event === "click" && isset($tag["url"])) {
                        $url = $tag["url"];
                    }
                }
                if (isset($tag["ts"])) {
                    $ts = $tag["ts"];
                }
                if (isset($tag["ip"])) {
                    $ip = $tag["ip"];
                }
                if (isset($tag["location"]["country"])) {
                    $country = $tag["location"]["country"];
                }
                if (isset($tag["location"]["timezone"])) {
                    $timezone = $tag["location"]["timezone"];
                }
                if (isset($tag["msg"]["state"])) {
                    $state = $tag["msg"]["state"];
                }
                if (isset($tag["msg"]["email"])) {
                    $email = $tag["msg"]["email"];
                }
                if (isset($tag["msg"]["smtp_events"])) {
                    $source_ip = $tag["msg"]["smtp_events"][0]["source_ip"];
                }
                if (isset($tag["msg"]["smtp_events"])) {
                    $destination_ip = $tag["msg"]["smtp_events"][0]["destination_ip"];
                }
                if (isset($tag["msg"]["subaccount"])) {
                    $subaccount = $tag["msg"]["subaccount"];
                }
                if (isset($tag["msg"]["metadata"]["campaign_id"])) {
                    $campaign_id = $tag["msg"]["metadata"]["campaign_id"];
                }
                if (isset($tag["msg"]["metadata"]["row_id"])) {
                    $row_id = $tag["msg"]["metadata"]["row_id"];
                }
                if (isset($tag["msg"]["metadata"]["subscriber_id"])) {
                    $subscriber_id = $tag["msg"]["metadata"]["subscriber_id"];
                }
            }

            $dt = new DateTime("now");
            $header = "timestamp,event,url,ts,ip,country,timezone,state,email,source_ip,destination_ip,subaccount,campaign_id,row_id,subscriber_id" . PHP_EOL;
            $data = $dt->format("Y-m-d:H:i:s,") . $event . "," . $url . "," . $ts . "," . $ip . "," . $country . "," . $timezone . "," . $state . "," . $email . "," . $source_ip . "," . $destination_ip . "," . $subaccount . "," . $campaign_id . "," . $row_id . "," . $subscriber_id . PHP_EOL;

            if ($campaign_id !== "" && !empty($campaign_id)) {

                if (file_exists($path . $campaign_id . '.txt')) {
                    file_put_contents($path . $campaign_id . '.txt', $data, FILE_APPEND);
                } else {
                    file_put_contents($path . $campaign_id . '.txt', $header, FILE_APPEND);
                    file_put_contents($path . $campaign_id . '.txt', $data, FILE_APPEND);
                }

                $insert_data = array(
                    "event" => $event,
                    "url" => $url,
                    "row_id" => $row_id,
                    "transaction_id" => $ts,
                    "ip" => $ip,
                    "source_ip" => $source_ip,
                    "destination_ip" => $destination_ip,
                    "country" => $country,
                    "time_stamp" => $dt->format("Y-m-d:H:i:s,"),
                    "time_zone" => $timezone,
                    "email_state" => $state,
                    "email" => $email,
                    "subaccount" => $subaccount,
                    "campaign_id" => $campaign_id,
                    "subscriber_id" => $subscriber_id
                );

                $this->handle_event($insert_data);
            } else {
                file_put_contents($path . "response-2.txt", print_r($_POST,true), FILE_APPEND);
            }
        } catch (Exception $ex) {
            file_put_contents($path . "error-logs.txt", $ex->getMessage() . "," . $ex->getCode() . "," . $ex->getLine() . PHP_EOL, FILE_APPEND);
        }
    }

    public function handle_event($insert_data) {
        $path = FCPATH . "../../reports/";
        try {
            if (!empty($insert_data)) {
                $this->load->model("Webhook_model", "webhook");
                if ($this->webhook->insert(array($insert_data))) {
                    if (file_exists($path . "logs.txt"))
                        file_put_contents($path . "logs.txt", serialize($insert_data) . PHP_EOL, FILE_APPEND);


                    $this->load->model("Campaign_model", "campaign");
                    $this->load->model("Subscriber_model", "subscriber");
                    $campaign_update_query = array();
                    $sub_update_query = array();

                    switch (trim($insert_data["event"])) {
                        case "hard_bounce": {
                                # where clause for campaign table
                                $where = array("id" => trim($insert_data["row_id"]), "progress" => 3, "campaign_id" => trim($insert_data["campaign_id"]));

                                # get the hard bounce value from the database
                                $hard_bounce_id = $this->campaign->generic_campaign_select_field($where, "bounce_id");
                                $update_camp = array();
                                $serialized_data = "";

                                # if the value is null then just serialize the array
                                if ($hard_bounce_id === "" || empty($hard_bounce_id)) {
                                    $update_camp = array($insert_data["subscriber_id"]);
                                    $serialized_data = serialize($update_camp);
                                } else {
                                    # if the value is not null then
                                    # unserialize the array
                                    # add an element to it
                                    # serialize the array
                                    $temp = unserialize($hard_bounce_id);
                                    if (!in_array($insert_data["subscriber_id"], $temp)) {
                                        $update_camp = array_merge($temp, array($insert_data["subscriber_id"]));
                                    }
                                    $serialized_data = serialize($update_camp);
                                }

                                # prepare the update query for campaign table
                                $update = array("bounce_id" => $serialized_data,);
                                $campaign_update_query = array("where" => $where, "update" => $update);

                                # prepare the update query for the subscriber table
                                $sub_where = array("subscriber_id" => $insert_data["subscriber_id"]);
                                $sub_update = array("email_status" => "6", "valid" => "1");
                                $sub_update_query = array("where" => $sub_where, "update" => $sub_update);
                                if (file_exists($path . "logs.txt")) {
                                    file_put_contents($path . "logs.txt", serialize($campaign_update_query) . PHP_EOL, FILE_APPEND);
                                    file_put_contents($path . "logs.txt", serialize($sub_update_query) . PHP_EOL, FILE_APPEND);
                                }
                                break;
                            }
                        case "spam": {
                                # where clause for campaign table
                                $where = array(
                                    "id" => trim($insert_data["row_id"]),
                                    "progress" => 3,
                                    "campaign_id" => trim($insert_data["campaign_id"]),
                                );
                                # get the spam value from the database
                                $spam_id = $this->campaign->generic_campaign_select_field($where, "spam_id");
                                $update_camp = array();
                                $serialized_data = "";

                                # if the value is null then just serialize the array
                                if ($spam_id === "" || empty($spam_id)) {
                                    $update_camp = array($insert_data["subscriber_id"]);
                                    $serialized_data = serialize($update_camp);
                                } else {
                                    # if the value is not null then
                                    # unserialize the array
                                    # add an element to it
                                    # serialize the array
                                    $temp = unserialize($spam_id);
                                    if (!in_array($insert_data["subscriber_id"], $temp)) {
                                        $update_camp = array_merge($temp, array($insert_data["subscriber_id"]));
                                    }
                                    $serialized_data = serialize($update_camp);
                                }

                                # prepare the update query for campaign table
                                $update = array("spam_id" => $serialized_data,);
                                $campaign_update_query = array("where" => $where, "update" => $update);

                                # prepare the update query for the subscriber table
                                $sub_where = array("subscriber_id" => $insert_data["subscriber_id"]);
                                $sub_update = array("email_status" => "2", "valid" => "1");
                                $sub_update_query = array("where" => $sub_where, "update" => $sub_update);
                                file_put_contents($path . "logs.txt", serialize($campaign_update_query) . PHP_EOL, FILE_APPEND);
                                file_put_contents($path . "logs.txt", serialize($sub_update_query) . PHP_EOL, FILE_APPEND);
                                break;
                            }
                        case "reject": {
                                # where clause for campaign table
                                $where = array(
                                    "id" => trim($insert_data["row_id"]),
                                    "progress" => 3,
                                    "campaign_id" => trim($insert_data["campaign_id"]),
                                );
                                # get the reject value from the database
                                $reject_id = $this->campaign->generic_campaign_select_field($where, "reject_id");
                                $update_camp = array();
                                $serialized_data = "";

                                # if the value is null then just serialize the array
                                if ($reject_id === "" || empty($reject_id)) {
                                    $update_camp = array($insert_data["subscriber_id"]);
                                    $serialized_data = serialize($update_camp);
                                } else {
                                    # if the value is not null then
                                    # unserialize the array
                                    # add an element to it
                                    # serialize the array
                                    $temp = unserialize($reject_id);
                                    if (!in_array($insert_data["subscriber_id"], $temp)) {
                                        $update_camp = array_merge($temp, array($insert_data["subscriber_id"]));
                                    }
                                    $serialized_data = serialize($update_camp);
                                }

                                # prepare the update query for campaign table
                                $update = array("reject_id" => $serialized_data,);
                                $campaign_update_query = array("where" => $where, "update" => $update);

                                # prepare the update query for the subscriber table
                                $sub_where = array("subscriber_id" => $insert_data["subscriber_id"]);
                                $sub_update = array("email_status" => "7", "valid" => "1");
                                $sub_update_query = array("where" => $sub_where, "update" => $sub_update);
                                if (file_exists($path . "logs.txt")) {
                                    file_put_contents($path . "logs.txt", serialize($campaign_update_query) . PHP_EOL, FILE_APPEND);
                                    file_put_contents($path . "logs.txt", serialize($sub_update_query) . PHP_EOL, FILE_APPEND);
                                }
                                break;
                            }
                        case "open": {
                                # where clause for campaign table
                                $where = array(
                                    "id" => trim($insert_data["row_id"]),
                                    "progress" => 3,
                                    "campaign_id" => trim($insert_data["campaign_id"]),
                                );
                                # get the open value from the database
                                $open_id = $this->campaign->generic_campaign_select_field($where, "open_id");

                                $update_camp = array();
                                $serialized_data = "";

                                # if the value is null then just serialize the array
                                if ($open_id === "" || empty($open_id)) {
                                    $update_camp = array($insert_data["subscriber_id"]);
                                    $serialized_data = serialize($update_camp);
                                } else {
                                    # if the value is not null then
                                    # unserialize the array
                                    # add an element to it
                                    # serialize the array
                                    $temp = unserialize($open_id);
                                    if (!in_array($insert_data["subscriber_id"], $temp)) {
                                        $update_camp = array_merge($temp, array($insert_data["subscriber_id"]));
                                    }$serialized_data = serialize($update_camp);
                                }

                                # prepare the update query for campaign table
                                $update = array("open_id" => $serialized_data,);
                                $campaign_update_query = array("where" => $where, "update" => $update);

                                # prepare the update query for the subscriber table
                                $sub_where = array("subscriber_id" => $insert_data["subscriber_id"]);
                                $sub_update = array("email_status" => "5", "valid" => "0");
                                $sub_update_query = array("where" => $sub_where, "update" => $sub_update);
                                if (file_exists($path . "logs.txt")) {
                                    file_put_contents($path . "logs.txt", serialize($campaign_update_query) . PHP_EOL, FILE_APPEND);
                                    file_put_contents($path . "logs.txt", serialize($sub_update_query) . PHP_EOL, FILE_APPEND);
                                }
                                break;
                            }
                        case "click": {
                                # where clause for campaign table
                                $where = array(
                                    "id" => trim($insert_data["row_id"]),
                                    "progress" => 3,
                                    "campaign_id" => trim($insert_data["campaign_id"]),
                                );
                                # get the click value from the database
                                $click_id = $this->campaign->generic_campaign_select_field($where, "click_id");
                                $update_camp = array();
                                $serialized_data = "";

                                # if the value is null then just serialize the array
                                if ($click_id === "" || empty($click_id)) {
                                    $update_camp = array($insert_data["subscriber_id"]);
                                    $serialized_data = serialize($update_camp);
                                } else {
                                    # if the value is not null then
                                    # unserialize the array
                                    # add an element to it
                                    # serialize the array
                                    $temp = unserialize($click_id);
                                    if (!in_array($insert_data["subscriber_id"], $temp)) {
                                        $update_camp = array_merge($temp, array($insert_data["subscriber_id"]));
                                    }
                                    $serialized_data = serialize($update_camp);
                                }

                                # prepare the update query for campaign table
                                $update = array("click_id" => $serialized_data,);
                                $campaign_update_query = array("where" => $where, "update" => $update);

                                # prepare the update query for the subscriber table
                                $sub_where = array("subscriber_id" => $insert_data["subscriber_id"]);
                                $sub_update = array("email_status" => "5", "valid" => "0");
                                $sub_update_query = array("where" => $sub_where, "update" => $sub_update);
                                if (file_exists($path . "logs.txt")) {
                                    file_put_contents($path . "logs.txt", serialize($campaign_update_query) . PHP_EOL, FILE_APPEND);
                                    file_put_contents($path . "logs.txt", serialize($sub_update_query) . PHP_EOL, FILE_APPEND);
                                }
                                break;
                            }
                        case "soft_bounce": {
                                # where clause for campaign table
                                $where = array(
                                    "id" => trim($insert_data["row_id"]),
                                    "progress" => 3,
                                    "campaign_id" => trim($insert_data["campaign_id"]),
                                );
                                # get the hard bounce value from the database
                                $soft_bounce_id = $this->campaign->generic_campaign_select_field($where, "soft_bounce_id");
                                $update_camp = array();
                                $serialized_data = "";

                                # if the value is null then just serialize the array
                                if ($soft_bounce_id === "" || empty($soft_bounce_id)) {
                                    $update_camp = array($insert_data["subscriber_id"]);
                                    $serialized_data = serialize($update_camp);
                                } else {
                                    # if the value is not null then
                                    # unserialize the array
                                    # add an element to it
                                    # serialize the array
                                    $temp = unserialize($soft_bounce_id);
                                    if (!in_array($insert_data["subscriber_id"], $temp)) {
                                        $update_camp = array_merge($temp, array($insert_data["subscriber_id"]));
                                    }
                                    $serialized_data = serialize($update_camp);
                                }

                                # prepare the update query for campaign table
                                $update = array("soft_bounce_id" => $serialized_data,);
                                $campaign_update_query = array("where" => $where, "update" => $update);

                                # prepare the update query for the subscriber table
                                $sub_where = array("subscriber_id" => $insert_data["subscriber_id"]);
                                $sub_update = array("email_status" => "1", "valid" => "0");
                                $sub_update_query = array("where" => $sub_where, "update" => $sub_update);
                                if (file_exists($path . "logs.txt")) {
                                    file_put_contents($path . "logs.txt", serialize($campaign_update_query) . PHP_EOL, FILE_APPEND);
                                    file_put_contents($path . "logs.txt", serialize($sub_update_query) . PHP_EOL, FILE_APPEND);
                                }
                                break;
                            }
                    }
                    $camp_status = $this->campaign->generic_campaign_update(array($campaign_update_query));
                    if (file_exists($path . "logs.txt")) {
                        file_put_contents($path . "logs.txt", "camp status : " . $camp_status . PHP_EOL, FILE_APPEND);
                    }

                    $sub_status = $this->subscriber->generic_subscriber_update(array($sub_update_query));

                    if (file_exists($path . "logs.txt")) {
                        file_put_contents($path . "logs.txt", "sub status : " . $sub_status . PHP_EOL, FILE_APPEND);
                    }
                    # update the campaign table
                    if (!$camp_status) {
                        if (file_exists($path . "error-logs.txt")) {
                            file_put_contents($path . "error-logs.txt", "Could not update the campaign table" . PHP_EOL, FILE_APPEND);
                        }
                    }
                    # update the subscriber table
                    if (!$sub_status) {
                        if (file_exists($path . "error-logs.txt")) {
                            file_put_contents($path . "error-logs.txt", "Could not update the subscriber table" . PHP_EOL, FILE_APPEND);
                        }
                    }
                } else {
                    throw new Exception("Data not inserted in the database", 1);
                    //file_put_contents($path . "error-logs.txt", "Data not inserted".PHP_EOL, FILE_APPEND);
                }
            } else {
                throw new Exception("Improper data passed to the method", 2);
                //file_put_contents($path . "error-logs.txt", "Improper data passed to method".  serialize($insert_data).PHP_EOL, FILE_APPEND);
            }
        } catch (Exception $ex) {
            if (file_exists($path . "error-logs.txt")) {
                file_put_contents($path . "error-logs.txt", $ex->getMessage() . "," . $ex->getCode() . "," . $ex->getLine() . PHP_EOL, FILE_APPEND);
            }
        }
    }

//    public function get_string_between($string, $start, $end) {
//        $string = " " . $string;
//        $ini = strpos($string, $start);
//        if ($ini == 0)
//            return "";
//        $ini += strlen($start);
//        $len = strpos($string, $end, $ini) - $ini;
//        return substr($string, $ini, $len);
//    }
}
