<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class ResponseHandler {

    public static function success ($response) {
        return self::send(true, $response);
	}

    public static function error ($response) {
        return self::send(false, $response);
	}

    public static function send ($status, $response) {
        if ($status === false) {
            if (!isset($response[0]) || !is_array($response[0])) {
                $response = array($response);
            }
        }
        $result = array(
            'status' => $status,
            'result' => $response
        );

        return $result;
	}

}
?>
