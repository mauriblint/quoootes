<?php
namespace App\Lib;


use Cake\ORM\TableRegistry;


class TemplatesService {

    public static function create ($data) {
        $TemplatesTable = TableRegistry::getTableLocator()->get('Templates');

        $Template = $TemplatesTable->newEntity([]);

        $Template->styles = serialize($data['styles']);
        $Template->preview_url = $data['preview_url'];
        $Template->hash = $data['hash'];
        $Template->layout = $data['layout'];
        $Template->active = 1;

        $id = $TemplatesTable->save($Template);

        return $Template;
    }

    public static function update ($id, $data) {
        $TemplatesTable = TableRegistry::getTableLocator()->get('Templates');

        $Template = $TemplatesTable->get($id);

        if (isset($data['styles'])) {
            $Template->styles = serialize($data['styles']);
        }

        if (isset($data['preview_url'])) {
            $Template->preview_url = $data['preview_url'];
        }

        if (isset($data['layout'])) {
            $Template->layout = $data['layout'];
        }

        if (isset($data['active'])) {
            $Template->active = (bool) $data['active'];
        }

        $id = $TemplatesTable->save($Template);

        return $Template;
    }

    public static function get ($id_or_hash) {

        $TemplatesTable = TableRegistry::getTableLocator()->get('Templates');

        if (is_numeric($id_or_hash)) {
            $Template = $TemplatesTable->get($id_or_hash);
        } else {
            $Template = $TemplatesTable->find()->where(['hash' => $id_or_hash])->first();
        }

        return self::_transform($Template);
    }

    private static function _transform ($templates) {
        if (is_array($templates)) {

        } else {
            $Template = $templates;

            $Template->styles = unserialize($Template->styles);
            return $Template;
        }
    }

    public static function list ($tag_id=null, $page=1, $count=12) {

        $TemplatesTable = TableRegistry::getTableLocator()->get('Templates');

        $query = $TemplatesTable->find()
            ->where(['active' => 1])
            ->order(['created' => 'DESC'])
            ->limit($count)
            ->page($page)
            ->enableHydration(false);

        $result = $query->toArray();

        foreach($result as &$item) {
            unset($item['styles']);
        }

        return $result;
    }

    public static function all () {

        $TemplatesTable = TableRegistry::getTableLocator()->get('Templates');

        $query = $TemplatesTable->find()
            ->order(['created' => 'DESC'])
            ->enableHydration(false);

        return $query->toArray();
    }
}
?>
