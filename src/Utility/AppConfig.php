<?php
namespace App\Utility;

use Cake\ORM\TableRegistry;

class AppConfig {

    static $config;

    public static function init ($config) {
        self::$config = $config;
    }

    public static function get ($name, $default = null) {
        return isset(self::$config[$name]) ? self::$config[$name] : $default;
    }

    public static function has ($name) {
      return array_key_exists($name, self::$config);
    }

    public static function set ($name, $value) {
      self::$config[$name] = $value;
    }
}
?>
