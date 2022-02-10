<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\Cache\Cache;
use Cake\Core\Configure;

use App\Lib\S3;

class UploadService {

    const BUCKET_TEMPLATES= 'templates';
    const BUCKET_BACKGROUNDS = 'backgrounds';
    const BUCKET_UPLOADS = 'uploads';

    const CACHE_CONTROL_1_YEAR = 31536000;
    const EXTENSION_JPG = 'jpg';

    // Instance
    private static $s3 = NULL;

    // Credentials
    private static $AWS_S3_KEY = NULL;
    private static $AWS_S3_SECRET = NULL;
    private static $AWS_S3_BUCKET = NULL;
    private static $CDN_URL = NULL;
    private static $AWS_S3_REGION = NULL;
    private static $AWS_S3_URL = NULL;

    // const DEV_AWS_S3_BUCKET = 'as-media-upload-dev';
    // const DEV_CDN_URL = 'https://as-dev.us-east-1.linodeobjects.com/';

    private static function _getS3 () {

        if (self::$s3) {
            return self::$s3;
        }

        self::$AWS_S3_KEY = env('OBJECT_STORAGE_KEY');
        self::$AWS_S3_SECRET = env('OBJECT_STORAGE_SECRET');
        self::$AWS_S3_BUCKET = env('OBJECT_STORAGE_BUCKET');
        self::$CDN_URL = env('OBJECT_STORAGE_BUCKET_URL');
        self::$AWS_S3_REGION = 'us-east-1';
        self::$AWS_S3_URL = 'us-east-1.linodeobjects.com';

        $s3 = new S3();
        $s3->setAuth(UploadService::$AWS_S3_KEY, UploadService::$AWS_S3_SECRET);
        $s3->setRegion(UploadService::$AWS_S3_REGION);
        $s3->setEndpoint(UploadService::$AWS_S3_URL);

        self::$s3 = $s3;
        return self::$s3;
    }

    public static function isCdnFile ($path) {
        // Local File
        if (strpos($path, '/') === 0) {
            return false;
        }

        // External File
        if (strpos($path, 'http') === 0) {
            return false;
        }

        // CDN File
        return true;
    }

    public static function getUrl ($path) {
        // Local File
        if (strpos($path, '/') === 0) {
            return $path;
        }

        // External File
        if (strpos($path, 'http') === 0) {
            return $path;
        }

        // CDN File
        return 'https://' . env('OBJECT_STORAGE_BUCKET') . '.us-east-1.linodeobjects.com/' . $path;
    }

    public static function saveToLocalDisk($filename, $data, $container) {

        $env = env('ENV', 'DEV');

        $file = WWW_ROOT . $container . DS . $filename;

        file_put_contents($file, $data);

        if (file_exists($file)) {

            if ($env==='DEV') {
                $image_url = env('APP_SITE_URL') . DS . $container . DS . $filename;
                $thumb_url = env('APP_SITE_URL') . DS . $container . DS . $filename;
            }

            return array(
                'public_id' => $container . '/' . $filename,
                'filename' => $filename,
                'version' => time(),
                'container' => $container,
                'format' => 'jpg',
                'image_url' => $image_url,
                'thumb_url' => $thumb_url,
                'storage' => 'local',
            );
        }

        return false;
    }

    public static function deleteFile($filename) {
        $s3 = self::_getS3();

        $response = $s3->deleteObject(UploadService::$AWS_S3_BUCKET, $filename);

        return $response;
    }

    // public static function saveToS3Bucket($filename, $data, $container) {

    //     $file = TMP . DS . $filename;
    //     $public_id = $container . '/' . $filename;

    //     file_put_contents($file, $data);

    //     if (file_exists($file)) {

    //         $s3 = $s3 = self::_getS3();

    //         try {
    //             $response = $s3->putObject(
    //                 S3::inputFile($file),
    //                 UploadService::$AWS_S3_BUCKET,
    //                 $public_id,
    //                 S3::ACL_PUBLIC_READ,
    //                 array('Access-Control-Allow-Origin' => '*'),
    //                 array('Access-Control-Allow-Origin' => '*')
    //             );

    //             if ($response === true) {

    //                 unlink($file);

    //                 return array(
    //                     'public_id' => $container . '/' . $filename,
    //                     'filename' => $filename,
    //                     'version' => time(),
    //                     'container' => $container,
    //                     'format' => 'jpg',
    //                     'image_url' => UploadService::$CDN_URL . $public_id,
    //                     'thumb_url' => NULL,
    //                     'storage' => 's3',
    //                 );
    //             }
    //         } catch (\Exception $e) {
    //             return false;
    //         }
    //     } else {
    //         return false;
    //     }
    // }

    public static function uploadFromUrl($url, $container = null)
    {
        $filename = self::getFilenameFromUrl($url, 'jpg');
        $tmp_file = TMP . $filename;
        $content = file_get_contents($url);

        file_put_contents($tmp_file, $content);

        if (file_exists($tmp_file)) {
            $extension = self::getFileExtension($filename);
            $content_type = self::getMimeType($filename);
            $response = self::uploadFromFile($tmp_file, $container, $extension, $content_type);
            return $response;
        }
    }

    public static function uploadFromData($data, $filename, $container=null, $extension=null, $cache_control=31536000) {

        $base64img = str_replace('data:image/jpeg;base64,', '', $data);
        $base64img = str_replace('data:image/png;base64,', '', $base64img);
        $data = base64_decode($base64img);

        $tmp_file = TMP . $filename;
        file_put_contents($tmp_file, $data);

        if (file_exists($tmp_file)) {
            $mime_type = UploadService::getMimeType($filename);
            $response = self::uploadFromFile($tmp_file, $container, $extension, $mime_type, $cache_control);
            return $response;
        }
    }

    public static function get_contents($URL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public static function downloadFile($public_id) {
        $tmp_file = TMP . $public_id;

        if (file_exists($tmp_file)) {
            return $tmp_file;
        }

        $dir = pathinfo($tmp_file, PATHINFO_DIRNAME);

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $file_url = self::getUrl($public_id);
        $content = self::get_contents($file_url);

        if ($content) {
            $tmp = file_put_contents($tmp_file, $content);
        } else {
            return false;
        }

        return $tmp_file;
    }

    public static function uploadFromFile($file, $container=null, $extension=null, $content_type= 'application/octet-stream', $cache_control=3600) {

        if ($container === null) {
            return false;
        }

        $env = env('ENV', 'DEV');

        // if ($env === 'DEV') {
        //     if ($container !== 'backgrounds') {
        //         $container = $container . '-dev';
        //     }
        // }

        if (file_exists($file)) {

            $ext = ($extension) ? $extension : pathinfo($file, PATHINFO_EXTENSION);
            $filename = self::generateFileName($ext);
            $public_id = $container . '/' . $filename;

            $s3 = self::_getS3();

            try {
                $response = $s3->putObject(
                    S3::inputFile($file),
                    UploadService::$AWS_S3_BUCKET,
                    $public_id,
                    S3::ACL_PUBLIC_READ,
                    [],
                    array(
                        'Cache-Control' => 'public, max-age='.$cache_control,
                        'Content-Type' => $content_type,
                        'Access-Control-Allow-Origin' => '*',
                    )
                );

                if ($response === true) {

                    unlink($file);

                    return array(
                        'public_id' => $container . '/' . $filename,
                        'filename' => $filename,
                        'version' => time(),
                        'container' => $container,
                        'format' => $ext,
                        'url' => UploadService::$CDN_URL . $public_id,
                        'storage' => 's3',
                    );
                }
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function generateFileName($strExt = '.tmp')
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        $unique = vsprintf('%s%s-%s%s', str_split(bin2hex($data), 4));

        // get both seconds and microseconds parts of the time
        list($usec, $sec) = explode(' ', microtime());

        // fudge the time we just got to create two 16 bit words
        $usec = (int) ($usec * 65536);
        $sec = ((int) $sec) & 0xFFFF;

        // fun bit--convert the remote client's IP into a 32 bit
        // hex number then tag on the time.
        // Result of this operation looks like this xxxxxxxx-xxxx-xxxx
        $strUid = uniqid() . '-' . sprintf("%s-%04x-%04x", $unique, $sec, $usec);

        if (strpos($strExt, '.') === false) {
            $strExt = '.' . $strExt;
        }

        // tack on the extension and return the filename
        return $strUid . $strExt;
    }

    public static function getMimeType($filename)
    {
        $idx = explode('.', $filename);
        $count_explode = count($idx);
        $idx = strtolower($idx[$count_explode - 1]);

        $mimet = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',


            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        if (isset($mimet[$idx])) {
            return $mimet[$idx];
        } else {
            return 'application/octet-stream';
        }
    }

    public static function getFileExtension($file, $default=NULL)
    {
        if (file_exists($file)) {
            $path_parts = pathinfo($file);
            $extension = $path_parts['extension'];

            return $extension;
        }

        if ($default) {
            return $default;
        }

        return '';
    }

    public static function getFilenameFromUrl($url, $default_extension=NULL)
    {
        // Remove Query Strings from URL
        $url = preg_replace('/\?.*/', '', $url);
        $url = preg_replace('/\#.*/', '', $url);
        $basename = basename($url);

        $extension = self::getFileExtension($basename);

        if (!$extension) {
            $basename = '.' . $default_extension;
        }

        return $basename;
    }

    public static function moveFile ($filename, $container) {
        $s3 = self::_getS3();

        $file = explode('/', $filename);
        $new_filename = $container . '/' . $file[1];
        // var_dump($filename);
        // var_dump($new_filename); die;
        $response = $s3->copyObject(UploadService::$AWS_S3_BUCKET, $filename, UploadService::$AWS_S3_BUCKET, $new_filename, S3::ACL_PUBLIC_READ);

        if ($response) {
            return $new_filename;
        }

        return false;
    }

    public static function getBucketContent($prefix=NULL, $count=30)
    {
        $s3 = self::_getS3();

        $response = $s3->getBucket(UploadService::$AWS_S3_BUCKET, $prefix, NULL, $count);

        if ($response) {
            return $response;
        }

        return [];
    }

    public static function uid($l=10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $l);
    }
}
?>
