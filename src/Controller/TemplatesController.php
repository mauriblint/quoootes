<?php
namespace App\Controller;

use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Event\EventInterface;

use App\Lib\QuotesService;
use App\Lib\TemplatesService;
use App\Lib\UploadService;

class TemplatesController extends AppController
{

    public function index ()
    {

    }

    public function create ()
    {
        $data = $this->request->getData();

        $template_hash = $this->uid(10);

        $filename = $template_hash . '.jpg';

        $upload = UploadService::uploadFromData($data['thumb'], $filename, UploadService::BUCKET_TEMPLATES, UploadService::EXTENSION_JPG);

        if ( !empty($data['styles']['backgroundImage']) && strpos($data['styles']['backgroundImage'], 'pixabay') !== false) {
            $upload2 = UploadService::uploadFromUrl($data['styles']['backgroundImage'], UploadService::BUCKET_BACKGROUNDS);

            if ($upload2['url']) {
                $data['styles']['backgroundImage'] = $upload2['url'];
            }
        }

        $data['hash'] = $template_hash;
        $data['preview_url'] = $upload['url'];
        $data['active'] = 1;

        $result = TemplatesService::create($data);

        $this->renderJSON(true, $result);
    }

    public function update ()
    {
        $data = $this->request->getData();

        $Template = TemplatesService::get($data['template_id']);

        if (isset($data['thumb'])) {
            $filename = $Template->hash . '.jpg';
            $upload = UploadService::uploadFromData($data['thumb'], $filename, UploadService::BUCKET_TEMPLATES, UploadService::EXTENSION_JPG);

            if ($upload) {
                $data['preview_url'] = $upload['url'];
            }

            $current_file = UploadService::getFilePathFromUrl($Template->preview_url);
            UploadService::deleteFile($current_file);
        }

        if ( !empty($data['styles']['backgroundImage']) && strpos($data['styles']['backgroundImage'], 'pixabay') !== false) {
            $upload2 = UploadService::uploadFromUrl($data['styles']['backgroundImage'], UploadService::BUCKET_BACKGROUNDS);

            if ($upload2['url']) {
                $data['styles']['backgroundImage'] = $upload2['url'];
            }
        }

        $result = TemplatesService::update($data['template_id'], $data);

        $this->renderJSON(true, $result);
    }

    public function list ()
    {
        $result = TemplatesService::list(null, 1, 150);

        $this->renderJSON(true, $result);
    }

    public function all ()
    {
        $result = TemplatesService::all();

        $this->renderJSON(true, $result);
    }

    public function config ()
    {
        $id = $this->request->getQuery('id', null);

        $result = TemplatesService::get($id);

        $this->renderJSON(true, $result);
    }

    private function uid($l=10) {
        return UploadService::uid($l);
    }
}
