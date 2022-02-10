<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\I18n\Time;

use App\Lib\MixpanelService;
use App\Lib\ResponseHandler;

class TagService {

    public static function getAll() {

        $TagsTable = TableRegistry::getTableLocator()->get('Tags');

        $query = $TagsTable->find()
            ->enableHydration(false);

        return $query->toArray();
    }


}
?>
