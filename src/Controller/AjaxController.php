<?php
namespace App\Controller;

use App\Lib\BackgroundService;
use App\Lib\UploadService;
use App\Lib\DesignService;

class AjaxController extends AppController
{

    public function save () {
        $response = [
            'status' => false,
            'error' => true,
            'message' => 'Error saving image'
        ];

        if ($this->request->is('post')) {
            $result = DesignService::save($this->request->getData());

            if ($result) {
                $response = [
                    'status' => true,
                    'data' => $result
                ];
            }
        }

        die(json_encode($response));
    }

    public function backgrounds()
    {
    	return $this->renderJSON(true, BackgroundService::all());
    }

    public function uploadBackground()
    {
        $data = $this->request->getData();

        // if (isset($data['url'])) {
        //     $result = UploadService::uploadFromUrl($data['url'], UploadService::BUCKET_TEMP);
        //     return $this->renderJSON(true, $result);
        // }

        if (isset($_FILES["file"])) {
            $path_parts = pathinfo($_FILES["file"]["name"]);
            $extension = $path_parts['extension'];
            $file = $_FILES['file']['tmp_name'];
            $mime_type = UploadService::getMimeType($_FILES["file"]["name"]);
            $upload = UploadService::uploadFromFile($file, UploadService::BUCKET_BACKGROUNDS, $extension, $mime_type, UploadService::CACHE_CONTROL_1_YEAR);

            if ($upload && $upload['public_id']) {

                $result = BackgroundService::create([
                    'active' => 1,
                    'hash' => UploadService::uid(12),
                    'url' => $upload['url'],
                    'storage' => $upload['storage'],
                    'public_id' => $upload['public_id'],
                ]);

                return $this->renderJSON(true, $result);
            } else {
                return $this->renderJSON(false, 'Image upload failed');
            }
        }
    }

    public function deleteBackground()
    {
        $data = $this->request->getData();

        $id = $data['id'];

        $Background = BackgroundService::get($id);

        if ($Background->storage === 's3') {
            UploadService::deleteFile($Background->public_id);
        }

        BackgroundService::delete($id);

        return $this->renderJSON(true);
    }
}
