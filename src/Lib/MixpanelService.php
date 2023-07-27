<?php
namespace App\Lib;

use Mixpanel;

class MixpanelService {

    public static $MIXPANEL_TOKEN = null;

    public static function track ($event, $metadata=[]) {
        if (!self::$MIXPANEL_TOKEN) {
            self::$MIXPANEL_TOKEN = env('MIXPANEL_TOKEN');
        }

        $user_id = null;

        if (isset($_SESSION['Auth']['User']['id'])) {
            $user_id = $_SESSION['Auth']['User']['id'];
        }

        if (!$user_id && isset($metadata['user_id'])) {
            $user_id = $metadata['user_id'];
        }

        $mp_token = self::$MIXPANEL_TOKEN;
        $mp = Mixpanel::getInstance($mp_token);

        if (!$user_id && isset($_COOKIE['mp_' . $mp_token .'_mixpanel'])) {
            $mixpanel_cookie = json_decode($_COOKIE['mp_' . $mp_token .'_mixpanel'], true);
            $user_id = $mixpanel_cookie['distinct_id'];
        }

        if ($user_id) {
            $mp->identify($user_id);
        }

        $mp->track($event, $metadata);
    }
}
