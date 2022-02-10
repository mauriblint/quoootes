<?php
namespace App\Lib;


use Cake\ORM\TableRegistry;


class BackgroundService {

    public static function create ($data) {
        $BackgroundsTable = TableRegistry::getTableLocator()->get('Backgrounds');

        $Background = $BackgroundsTable->newEntity([]);

        $Background->hash = $data['hash'];
        $Background->url = $data['url'];
        $Background->active = (bool) $data['active'];
        $Background->storage = $data['storage'];
        $Background->public_id = $data['public_id'];

        $BackgroundsTable->save($Background);

        return $Background;
    }

    // public static function update ($id, $data) {
    //     $BackgroundsTable = TableRegistry::getTableLocator()->get('Backgrounds');

    //     $Template = $TemplatesTable->get($id);

    //     $Template->styles = serialize($data['styles']);
    //     $Template->preview_url = $data['preview_url'];

    //     $id = $TemplatesTable->save($Template);

    //     return $Template;
    // }

    public static function delete ($id) {
        $BackgroundsTable = TableRegistry::getTableLocator()->get('Backgrounds');
        $Background = self::get($id);
        $BackgroundsTable->delete($Background);
        return true;
    }

    public static function get ($id_or_hash) {

        $BackgroundsTable = TableRegistry::getTableLocator()->get('Backgrounds');

        if (is_numeric($id_or_hash)) {
            $Background = $BackgroundsTable->get($id_or_hash);
        } else {
            $Background = $BackgroundsTable->find()->where(['hash' => $id_or_hash])->first();
        }

        return $Background;
    }

    public static function list ($tag_id=null, $page=1, $count=12) {

        $BackgroundsTable = TableRegistry::getTableLocator()->get('Backgrounds');

        $query = $BackgroundsTable->find()
            ->where(['active' => 1])
            ->order(['created' => 'DESC'])
            ->limit($count)
            ->page($page)
            ->enableHydration(false);

        return $query->toArray();
    }

    public static function all () {

        $BackgroundsTable = TableRegistry::getTableLocator()->get('Backgrounds');

        $query = $BackgroundsTable->find()
            ->order(['created' => 'DESC'])
            ->enableHydration(false);

        return $query->toArray();
    }
}
?>
