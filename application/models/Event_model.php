<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Event_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function delete($data) {
		try {
			if (!is_array($data) || empty($data) || count($data) === 0) {
				throw new Exception("Invalid array provided for delete", 1);
			} else {
				# variable for status of deletion process
				$status = false;

				# delete the records from events table for each new campaign
				foreach ($data as $campaign) {
					$this -> db -> where($campaign["where"]);
					$status = $this -> db -> delete("events");
				}

				# return status
				return $status;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function update($data) {
		try {
			if (!is_array($data) || empty($data) || count($data) === 0) {
				throw new Exception("Invalid array provided for update", 1);
			} else {

				# variable for status of update
				$status = false;

				# update the campaign_data table for each new campaign
				foreach ($data as $campaign) {
					$this -> db -> where($campaign["where"]);
					$status = $this -> db -> update("events", $campaign["update"]);
				}

				# return status
				return $status;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function select($data) {
		try {
			if (!is_array($data) || empty($data) || count($data) === 0) {
				throw new Exception("Invalid array provided for select", 1);
			} else {
				# array for returning the results
				$return_array = array();

				# set the where clause
				$this -> db -> where($data);

				# select all the fields from the table
				$this -> db -> select("*");

				# get the result in query object
				$query = $this -> db -> get("events");

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

	public function insert($data) {
		try {
			//print_r($data);
			//echo count($data);
			if (!is_array($data) || empty($data) || count($data) === 0) {
				throw new Exception("Invalid array provided for insert", 1);
			} else {
				# variable to show the status of the insert
				$status = false;

				# insert records for each campaign item in the $data array
				foreach ($data as $campaign) {
					$status = $this -> db -> insert("events", $campaign);
				}

				# return the status
				return $status;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function select_field($data, $field_name) {
		try {
			//print_r($data);
			if (!is_array($data) || empty($data) || count($data) === 0 || $field_name === "") {
				throw new Exception("Invalid array/ or field name provided for select", 1);
			} else {
				# variable for returning the results
				$return_value = "";

				# set the where clause
				$this -> db -> where($data);

				# select all the fields from the table
				$this -> db -> select($field_name);

				# get the result in query object
				$query = $this -> db -> get("events");
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
	
	public function select_orderby($data,$orderby) {
		try {
			if (!is_array($data) || empty($data) || count($data) === 0) {
				throw new Exception("Invalid array provided for select", 1);
			} else {
				# array for returning the results
				$return_array = array();
				
				$this->db->order_by($orderby);
				# set the where clause
				$this -> db -> where($data);

				# select all the fields from the table
				$this -> db -> select("*");

				# get the result in query object
				$query = $this -> db -> get("events");

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
	
	public function run_query($query){
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
