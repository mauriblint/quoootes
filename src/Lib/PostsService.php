<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\I18n\Time;

use App\Lib\MixpanelService;
use App\Lib\ResponseHandler;

class PostsService {

    public static function getRecentPosts($page=1, $count=12) {

        $PostsTable = TableRegistry::getTableLocator()->get('Posts');

        $query =$PostsTable->find()
            ->page($page)
            ->limit($count)
            ->order(['created' => 'DESC'])
            ->hydrate(false);

        return $query->toArray();
    }


}
?>
