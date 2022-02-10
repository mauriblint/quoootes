<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\I18n\Time;

use App\Lib\MixpanelService;
use App\Lib\ResponseHandler;

class FraseService {

    public static function create ($data) {

        $FrasesTable = TableRegistry::get('Frases');

        $Frase = $FrasesTable->newEntity();
        $Frase->text = $data['text'];
        $Frase->tag_id = $data['tag_id'];
        $Frase->author = $data['author'];
        $Frase->vendor = $data['vendor'];
        $Frase->external_url = $data['external_url'];
        // $Frase->external_id = $data['external_id'];
        $FrasesTable->save($Frase);

        return true;
    }

    public static function search ($q=null, $page=1, $count=40) {

        if (!$q) {
            return self::getRandomList();
        }

        $query = TableRegistry::get('Frases')
            ->find('all')
            ->select(['id', 'text', 'tag_id'])
            ->hydrate(false)
            ->where(['Frases.text LIKE' => '%'.$q.'%'])
            ->page($page)
            ->limit($count)
            ->toArray();

        return $query;
    }

    public static function getRandomList ($page=1, $count=40) {

        $query = TableRegistry::get('Frases')
            ->find('all')
            ->select(['id', 'text', 'tag_id'])
            ->order(['RAND()'])
            ->hydrate(false)
            ->limit($count)
            ->toArray();

        return $query;
    }


}
?>
