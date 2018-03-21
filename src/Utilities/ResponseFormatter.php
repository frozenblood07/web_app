<?php
/**
 * User: karan.tuteja26@gmail.com
 */

namespace Ticket\Utilities;


class ResponseFormatter
{
    public function generatorAPIResponseForSuccess($type,$data) {
        $response = array();
        $response['status'] = true;
        $response['outputParams']['data'] = $data;

        $responseSettings = array();
        $responseSettings['statusCode']  = $this->getStatusCodeByType($type);
        $responseSettings['response'] = json_encode($response);

        return $responseSettings;

    }

    public function generatorAPIResponseForFailure($type,$msg) {
        $response = array();
        $response['status'] = false;
        $response['errorMsg'] = $msg;

        $responseSettings = array();
        $responseSettings['statusCode'] = $this->getStatusCodeByType($type);
        $responseSettings['response'] = json_encode($response);

        return $responseSettings;
    }

    private static function getStatusCodeByType($type) {
        switch ($type) {
            case DATA_FOUND: return 200;
                break;
            case BAD_REQUEST: return 400;
                break;
            default: return 200;
        }
    }
}