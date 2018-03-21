<?php
/**
 * User: karan.tuteja26@gmail.com
 */

namespace Ticket\Utilities;


class Validator
{
    public function validateInput($requirements,$input) {
        $errorFlag = false;
        $response = array();
        $response['status'] = true;
        $error = "Input Errors Found: ";

        foreach ($requirements as $key => $requirement) {
            if($requirement['required']) {
                if(is_null($input[$key])) {
                    $error .= " $key is not present. ";
                    $errorFlag = true;
                    continue;
                }
            }

            if($requirement['type'] == "int" && array_key_exists($key,$input)) {
                if(!is_numeric($input[$key])) {
                    $error .="$key is not of correct type. ";
                    $errorFlag = true;
                    continue;
                }
            }

            if($requirement['type'] == 'date' && array_key_exists($key,$input)) {
                $d = \DateTime::createFromFormat('Y-m-d', $input[$key]);
                if(!($d && $d->format('Y-m-d') == $input[$key])) {
                    $error .= "$key is not of correct type.";
                    $errorFlag = true;
                    continue;
                }
                
            }

            if(array_key_exists("range",$requirement) && array_key_exists($key,$input)) {
                if($requirement['type'] == 'int') {
                    $value = (int) $input[$key];
                    if($value < $requirement['range'][0] || $value > $requirement['range'][1]) {
                        $error .= "$key is not in the allowed range. ";
                        $errorFlag = true;
                        continue;
                    }
                }
            }

            if(array_key_exists("allowed_values",$requirement) && array_key_exists($key,$input)) {
                $value = $input[$key];
                if($requirement['type'] == 'int') {
                    $value = (int) $input[$key];
                }

                if(!in_array($value,$requirement['allowed_values']) && array_key_exists($key,$input)) {
                    $error .= " $key is not in the allowed values. ";
                    $errorFlag = true;
                    continue;
                }
            }
        }

        if($errorFlag){
            $response['status'] = false;
            $response['msg'] = $error;
        }

        return $response;
    }
}