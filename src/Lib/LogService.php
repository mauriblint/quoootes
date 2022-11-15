<?php
namespace App\Lib;

class LogService {

    const INVALID_URL = 'invalid_url';

    public static function log ($file=null, $message) {

        if ($file === null) {
            return false;
        }

        $log_file = LOGS . $file . '.log';
        $time = date('y-m-d h:i:s');
        $log = "[$time] $message" . PHP_EOL;

        file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX);

        return true;
    }
}
?>
