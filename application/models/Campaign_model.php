<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Campaign_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/*
	 * $data = array(array($where_array), array($where_array),......)
	 * $where_array 	-- array with the where clause
	 */

	public function generic_campaign_delete($data) {
		try {
			if (!is_array($data) || empty($data) || count($data) === 0) {
				throw new Exception("Campaign_model::generic_campaign_delete::invalid array provided for update", 1);
			} else {
				# variable for status of deletion process
				$status = false;

				# delete the records from campaign_data table for each new campaign
				foreach ($data as $campaign) {
					$this -> db -> where($campaign["where"]);
					$status = $this -> db -> delete("campaign_data");
				}

				# return status
				return $status;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/*
	 * $data = array(array($where_array, $update_array), array($where_array, $update_array),......)
	 * $where_array 	-- array with the where clause
	 * $update_array 	-- array with campaign of fields to be updated
	 */

	public function generic_campaign_update($data) {
		try {
			if (!is_array($data) || empty($data) || count($data) === 0) {
				throw new Exception("Campaign_model::generic_campaign_update::invalid array provided for update", 1);
			} else {

				# variable for status of update
				$status = false;

				# update the campaign_data table for each new campaign
				foreach ($data as $campaign) {
					$this -> db -> where($campaign["where"]);
					$status = $this -> db -> update("campaign_data", $campaign["update"]);
				}

				# return status
				return $status;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	/*
	 * $data = where clause
	 */

	public function generic_campaign_select($data) {
		try {
			if (!is_array($data) || empty($data) || count($data) === 0) {
				throw new Exception("Campaign_model::generic_campaign_select::invalid array provided for select", 1);
			} else {
				# array for returning the results
				$return_array = array();

				# set the where clause
				$this -> db -> where($data);

				# select all the fields from the table
				$this -> db -> select("*");

				# get the result in query object
				$query = $this -> db -> get("campaign_data");

				if ($query -> num_rows() > 0) {
					foreach ($query->result_array() as $row) {
						$return_array[] = $row;
					}
					$query -> free_result();
				}

				return $return_array;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function generic_campaign_insert($data) {
		try {
			//print_r($data);
			//echo count($data);
			if (!is_array($data) || empty($data) || count($data) === 0) {
				throw new Exception("Campaign_model::generic_campaign_insert::invalid array provided for insert", 1);
			} else {
				# variable to show the status of the insert
				$status = false;

				# insert records for each campaign item in the $data array
				foreach ($data as $campaign) {
					$status = $this -> db -> insert("campaign_data", $campaign);
				}

				# return the status
				return $status;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function generic_campaign_select_field($data, $field_name) {
		try {
			//print_r($data);
			if (!is_array($data) || empty($data) || count($data) === 0 || $field_name === "") {
				throw new Exception("Campaign_model::generic_campaign_select_field::invalid array/ or field name provided for select", 1);
			} else {
				# variable for returning the results
				$return_value = "";

				# set the where clause
				$this -> db -> where($data);

				# select all the fields from the table
				$this -> db -> select($field_name);

				# get the result in query object
				$query = $this -> db -> get("campaign_data");
				//print_r($query);
				if ($query -> num_rows() > 0) {
					$row = $query -> first_row();
					//print_r($row);
					$return_value = $row -> $field_name;
					$query -> free_result();
					//return $return_value;
				}
				//echo "campaign-id is".$return_value;
				return $return_value;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function cron_get_campaign_data() {
		try {
			$q = "select `id`,`list_id`,`template_id`,`campaign_id`,`subject`,`sender_name`,`reply_to`,`user_id`,`subscriber_ids`,`smtp_details`,`send_time` as time ,`sent_emails`,`quota` from `campaign_data` where progress in ('1','2') group by `user_id`,`id`,`list_id`,`template_id` having min(send_time)<NOW()";
			$query = $this -> db -> query($q);
			$return_array = array();
			foreach ($query->result() as $row) {
				$return_array[] = $row;
			}
			//print_r($return_array);
			return $return_array;
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	public function run_query($query) {
		try {
			if (empty($query)) {
				throw new Exception("Query is empty", 1);
			} else {
				# array for returning the results
				$return_array = array();

				# get the result in query object
				$query_result = $this -> db -> query($query);

				if ($query_result -> num_rows() > 0) {
					foreach ($query_result->result_array() as $row) {
						$return_array[] = $row;
					}
					$query_result -> free_result();
				}

				return $return_array;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

}
