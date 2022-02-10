<?php
namespace App\Utility;

use Cake\ORM\TableRegistry;

class Html {

    public static function getKeywords ($lang) {
        return (self::KEYWORDS[$lang]) ? self::KEYWORDS[$lang] : self::KEYWORDS['en'];
    }

    public static function jsFiles ($folder) {
        $files = [];

        foreach(glob(WWW_ROOT .'js/' . $folder . '/*.js') as $file) {
            if (strpos($file, '.compiled') === false) {
                $files[] = $folder . '/' . basename($file);
            }
        }

        return $files;
    }

    public static function cssFiles ($folder=false) {
        $files = [];
        $dir = WWW_ROOT .'css/*.css';

        if ($folder) {
            $dir = WWW_ROOT . 'css/' . $folder . '/*.css';
        }

        foreach(glob($dir) as $file) {
            if (strpos($file, '.compiled') === false &&
                strpos($file, 'admin.css') === false &&
                strpos($file, 'cake.css') === false) {
                if ($folder) {
                    $files[] = $folder . '/' . basename($file);
                } else {
                    $files[] = basename($file);
                }
            }
        }

        return $files;
    }

    public static function componentTemplatesFiles ($folder=false) {
        $files = [];
        $dir = APP . 'Template/Element/components/*.ctp';

        if ($folder) {
            $dir = APP . 'Template/Element/components/' . $folder . '/*.ctp';
        }

        foreach(glob($dir) as $file) {
            if (strpos($file, '.compiled') === false &&
                strpos($file, 'admin.css') === false &&
                strpos($file, 'cake.css') === false) {
                $f = str_replace('.ctp', '', basename($file));
                if ($folder) {
                    $files[] = $folder . '/' . $f;
                } else {
                    $files[] = $f;
                }
            }
        }

        return $files;
    }

    public static function rsearch ($folder, $pattern) {
        $dir = new RecursiveDirectoryIterator($folder);
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
        $fileList = array();
        foreach($files as $file) {
            $fileList = array_merge($fileList, $file);
        }
        return $fileList;
    }

    public static function rglob ($pattern, $flags = 0) {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::rglob($dir.'/'.basename($pattern), $flags));
        }



        return $files;
    }

    public static function Load_VUE_Components_Files ($fileType) {
        $files = [];
        $dir = WWW_ROOT . 'Components/*.' . $fileType;

        $replace = function ($el) {
            return str_replace(WWW_ROOT . 'Components/', '', $el);
        };

        $result = self::rglob($dir);

        $result = array_map($replace, $result);

        return $result;
    }

    public static function Load_VUE_Components_Templates () {
        $files = Html::Load_VUE_Components_Files('php');

        $replace = function ($el) {
            return str_replace('.php', '', $el);
        };

        $result = array_map($replace, $files);

        return $result;
    }

    public static function Load_VUE_Components_JS () {
        $files = Html::Load_VUE_Components_Files('js');

        $replace = function ($el) {
            return '/Components/' . $el;
        };

        $result = array_map($replace, $files);

        return $result;
    }

    public static function Load_VUE_Components_CSS () {
        $files = Html::Load_VUE_Components_Files('css');

        $replace = function ($el) {
            return '/Components/' . $el;
        };

        $result = array_map($replace, $files);

        return $result;
    }

    public static function Load_EDITOR_Images () {
        $files = [];
        $dir = WWW_ROOT . 'assets/backgrounds/*';

        $replace = function ($el) {
            return str_replace(WWW_ROOT . 'assets/backgrounds/', '', $el);
        };

        $result = self::rglob($dir);

        $result = array_map($replace, $result);

        return $result;
    }

}
?>
