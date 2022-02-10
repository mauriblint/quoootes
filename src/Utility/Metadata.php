<?php
namespace App\Utility;

use Cake\ORM\TableRegistry;

class Metadata {

    const DESC = 'Create beautiful Images with Quotes online and free with our App! Download and share quotes in all social networks.';

    const KEYWORDS = 'frases para compartir, frases online, crear carteles online, carteles para facebook, carteles para compartir en facebook, los mejores carteles para facebook, frases en imagenes para reflexionar, frases en imagenes, crear frases para facebook';

    const TITLE = 'Create beautiful Images with Quotes online and free.';


    public static function getKeywords () {
        return Metadata::KEYWORDS;
    }

    public static function getTitle ($title=false) {
        if (!$title) {
            $title = Metadata::TITLE;
        }

        return 'Quoootes &ndash; ' . $title;
    }

    public static function getDescription ($desc=false) {
        if (!$desc) {
            $desc = Metadata::DESC;
        }

        return $desc;
    }

}
?>
