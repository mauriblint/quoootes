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
            ->where(['active' => 1])
            ->order(['name' => 'ASC'])
            ->enableHydration(false);

        return $query->toArray();
    }

    public static function getPopularList() {

        $TagsTable = TableRegistry::getTableLocator()->get('Tags');

        $query = $TagsTable->find()
            ->where(['active' => 1])
            ->where(['is_popular' => 1])
            ->order(['name' => 'ASC'])
            ->enableHydration(false);

        return $query->toArray();
    }


}
?>
