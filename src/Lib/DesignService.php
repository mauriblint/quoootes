<?php
namespace App\Lib;

use Cake\Core\Configure;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\I18n\Time;

use App\Lib\MixpanelService;
use App\Lib\ResponseHandler;

use App\Utility\Helpers;

class DesignService {

    public static function get ($id) {
        $DesignsTable = TableRegistry::getTableLocator()->get('Designs');

        try {
            $Design = $DesignsTable->get($id, ['contain' => ['Tags']]);
            return $Design;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function save ($requestData) {
        // if ($this->request->is('post')) {

            $plain = trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $requestData['text']));
            $data = [
                'text' => $plain,
                'tag_id' => isset($requestData['tag_id']) ? $requestData['tag_id'] : NULL,
                'template' => isset($requestData['template']) ? serialize($requestData['template']) : NULL,
                'template_id' => isset($requestData['template_id']) ? $requestData['template_id'] : NULL,
                'status' => 0,
                'views' => 1,
                'layout' => isset($requestData['layout']) ? $requestData['layout'] : NULL,
                'slug' => Helpers::slugify($plain),
            ];

            if (!empty($requestData['user_id'])) {
                $data['user_id'] = $requestData['user_id'];
            }

            if (!empty($requestData['frase_id'])) {
                $data['frase_id'] = $requestData['frase_id'];
            }

            $DesignsTable = TableRegistry::getTableLocator()->get('Designs');
            $Design = $DesignsTable->newEntity($data);


            if ($DesignsTable->save($Design)) {
                $hash = 'quoootes_' . $Design->id;
                $filename = $hash . '.jpg';

                $env = env('ENV', 'PROD');
                $env = 'prod';

                $image_data = $requestData['image'];

                if ($env==='DEV') {
                    $result = UploadService::saveToLocalDisk($filename, $data, UploadService::BUCKET_UPLOADS);
                    $Design->status = 1;
                } else {
                    $result = UploadService::uploadFromData($image_data, $filename, UploadService::BUCKET_UPLOADS, UploadService::EXTENSION_JPG);
                }

                $Design->image_filename = $result['filename'];
                $Design->image_public_id = $result['public_id'];
                $Design->image_format = $result['format'];
                $Design->image_url = $result['url'];
                $Design->thumb_url = $result['url'];
                $Design->image_storage = $result['storage'];
                $Design->image_container = $result['container'];

                $DesignsTable->save($Design);

                $result = [
                    'status' => true,
                    'data' => $Design
                ];

                return $Design;
                // die(json_encode($result));
            } else {
                return false;
            }
    }

    public static function getRecentImagesQuotes ($page=1, $count=12) {

        $DesignsTable = TableRegistry::getTableLocator()->get('Designs');

        $query = $DesignsTable->find()
            ->where(['status' => 1])
            // ->orWhere(['likes > 10'])
            // ->orWhere(['user_id = 1'])
            ->order(['created' => 'DESC'])
            // ->order(['RAND()'])
            ->limit($count)
            ->page($page)
            ->contain(['Tags']);

        return $query->toArray();
    }

    public static function getPopularImagesQuotes ($page=1, $count=12) {

        $DesignsTable = TableRegistry::getTableLocator()->get('Designs');

        $query = $DesignsTable->find()
            // ->where(['likes >=' => 60])
            ->where(['user_id =' => 1])
            ->order(['created' => 'DESC'])
            ->limit($count)
            ->page($page)
            ->contain(['Tags']);

        return $query->toArray();
    }

    public static function searchPopularImagesQuotes ($page=1, $count=12) {

        $DesignsTable = TableRegistry::getTableLocator()->get('Designs');

        $query = $DesignsTable->find()
            ->where(['likes >=' => 60])
            ->order(['created' => 'DESC'])
            ->limit($count)
            ->page($page)
            ->contain(['Tags']);

        return $query->toArray();
    }


    public static function search ($status=null, $tag_id=null, $q=null, $page=1, $count=12, $order=['created' => 'DESC']) {

        $DesignsTable = TableRegistry::getTableLocator()->get('Designs');

        $query = $DesignsTable->find()
            ->order($order)
            ->limit($count)
            ->page($page)
            ->contain(['Tags'])
            ->enableHydration(false);

        if (!is_null($tag_id)) {
            $query->where(['tag_id' => $tag_id]);
        }

        if (!is_null($status)) {
            $query->where(['status' => $status]);
        }

        $items = $query->toArray();

        $total = $query->count();

        return array(
            'data' => $items,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $count,
                'pages' => ceil($total / $count),
            ]
        );
    }

    public static function find ($tag_id=null, $page=1, $count=12) {

        $DesignsTable = TableRegistry::getTableLocator()->get('Designs');

        $query = $DesignsTable->find()
            ->where(['status' => 1])
            // ->orWhere(['likes > 10'])
            // ->orWhere(['user_id = 1'])
            ->order(['created' => 'DESC'])
            ->limit($count)
            ->page($page)
            ->contain(['Tags'])
            ->enableHydration(false);

        if( !is_null($tag_id) ) {
            $query->matching('Tags', function ($q) use ($tag_id) {
                return $q->where(['Tags.id' => $tag_id]);
            });
        }

        $total = $query->count();

         $result = ResponseHandler::success([
             'data' => $query->toArray(),
             'pagination' => [
                 'total' => $total,
                 'page' => $page,
                 'per_page' => $count,
                 'pages' => ceil( $total / $count),
             ]
         ]);

        return $result;
    }

    public static function incrementView($Design) {
        $DesignsTable = TableRegistry::getTableLocator()->get('Designs');
        $Design->views++;
        $DesignsTable->save($Design);
        return true;
    }

    public static function update ($id, $data) {
        $DesignsTable = TableRegistry::getTableLocator()->get('Designs');

        $Design = $DesignsTable->get($id);

        if (isset($data['status'])) {
            $Design->status = (int) $data['status'];
        }

        $id = $DesignsTable->save($Design);

        return $Design;
    }
}
?>
