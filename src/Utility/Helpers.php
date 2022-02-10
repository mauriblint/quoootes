<?php
namespace App\Utility;

use Cake\ORM\TableRegistry;

class Helpers {

    public static function slugify ($str, $replace=array(), $delimiter='-', $limit=60)
    {
        if (strlen($str) > $limit)
        {
            $str = wordwrap($str, $limit);
            $str = substr($str, 0, strpos($str, "\n"));
        }

        $words = array('in', 'at', 'on', 'the', 'this','of', 'is');
        $pattern = '/\b(?:' . join('|', $words) . ')\b/i';
        $str = preg_replace($pattern, '', $str);


        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        $clean = trim($clean, '-');

        return $clean;
    }

    // private function keywords($keyword) {
    //     $keyword = strtolower($keyword);
    //     $str  = '';
    //     $str .= 'quotes about '.$keyword. ',';
    //     $str .= 'free quotes about '.$keyword. ',';
    //     $str .= $keyword. ' quotes,';
    //     $str .= $keyword. ' quotes generator,';
    //     $str .= 'tumblr quotes '.$keyword. ',';
    //     $str .= 'images quotes '.$keyword. ',';
    //     $str .= 'images quotes about '.$keyword;
    //     return $str;
    // }

}
