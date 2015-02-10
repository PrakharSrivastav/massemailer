<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is a custom class for reading the csv files.
 * init_method:: needs 2 inputs file_name and number_of_fields in the file
 */
class Csvhandler {

    private $file_name = "";
    private $col_count = "";

    function __construct($argument) {
        $this->file_name = $argument["file_name"];
        $this->col_count = $argument["count"];
    }

    public function read_csv_data() {
        try {
            if (trim($this->file_name) === "" || trim($this->col_count) === "") {
                throw new Exception("Csvhandler :: Please initialize the class by providing valid parameters");
            } else {
                $handle = fopen($this->file_name, "r");
                $return_data = array();
                $count = 0;
                $subscriber_header = array("email","phone","mobile","place","email-status","fname","lname");
                while (!feof($handle)) {
                    $data = fgetcsv($handle);
                    //print_r($data);
                    if ($count === 0) {
                        $diff = array_diff($data, $subscriber_header);
                        //print_r($subscriber_header);

                        if (count($diff) > 0) {
                            throw new Exception("Csvhandler :: Improper csv files header, Please make sure that the header matches the format \"email,phone,mobile,place;email-status,fname,lname\"", 1);
                        }
                        $count++;
                        continue;
                    }
                    //echo "count is: ". count($data);
                    if (!PHP_EOL && count($data) !== $this->col_count) {
                        throw new Exception("Csvhandler :: The number of the fields in the csv file does not match the configuration", 1);
                    } else if (feof($handle)) {
                        break;
                    } else {
                        $return_data[] = $data;
                    }
                }
                return $return_data;
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

}
