<?php
namespace App\Controller;

use App\Lib\BackgroundService;
use Cake\ORM\TableRegistry;
use Cake\Event\EventInterface;
use Cake\Routing\Router;

use App\Lib\DesignService;
use App\Lib\TagService;
use App\Lib\UserService;
use App\Lib\TemplatesService;
use App\Model\Table\DesignsTable;

class QuotesController extends AppController
{
    public function initialize() : void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // $this->Auth->allow();
    }

    public function index($category_slug=null)
    {
        $tag_id = null;
        $page = $this->request->getQuery('page', 1);
        $count = 12;
        $category = null;

        if( !is_null($category_slug) ) {
            $category = TableRegistry::get('Tags')->findBySlug($category_slug)->first();
            if( !$category ) {
                // throw new NotFoundException('Could not find that category');
                return $this->redirect('/quotes');
            }
            $tag_id = $category->id;
        }

        $result = DesignService::find($tag_id, $page, $count);

        $title = 'Quotes Images';
        $page_title = 'Quotes Images';

        if( !is_null($tag_id) ) {
            $this->set('tag', $category);
            $title = $category->name_seo;
            $page_title = $category->name_seo;
        }


        $this->set('title', $title);
        $this->set('page_title', $page_title);
        $this->set('recents', $result['result']['data']);
        $this->set('pagination', $result['result']['pagination']);
        $this->set('tag', $category);

        $this->render('index');
    }


    public function view($id=null, $slug=null)
    {
        if (is_numeric($id)) {
		    $quote = DesignService::get($id);
            if($quote->shortcode) {
                return $this->redirect('/p/' . $quote->shortcode, 301);
            }
        } else {
            $quote = DesignService::findByShortcode($id);
        }

        if (!$quote) {
            return $this->redirect('/', 301);
        }

        if ($quote->status === DesignService::STATUS_PRIVATE && $quote->user_id !== $this->user_id) {
            return $this->redirect('/', 301);
        }

        DesignService::incrementView($quote);

        $Session = $this->request->getSession();
        $session_key = 'quote_' . $quote['id'] . '_anon_owner';
        $session_key = 'save_anon_design_id';
        $is_anon_owner = $Session->read($session_key) === $quote['id'];

        // var_dump($is_anon_owner);
        // die;

        // var_dump($this->request);
        // echo $this->request->getRequestTarget();
        // die;
        if (!$this->user_id) {
            $Session->write('redirect_after_google_login', Router::url($this->request->getRequestTarget(), true));
            // if ($is_anon_owner) {
            //     $Session->write('save_anon_design_id', $quote['id']);
            // }
        }
        $description = $quote['views'] . ' likes &bull; ' . $quote['downloads'] .' downloads - ' . 'Your daily dose of beautiful quotes, create your own image with text quote.';
		$this->set(compact('quote'));
        $this->set('title', $quote->text);
        $this->set('description', $description);
        $this->set('share_image', $quote['image_url']);
        $this->set('is_anon_owner', $is_anon_owner);
        $this->set('popularQuotes', DesignService::getPopularImagesQuotes());
        $this->set('keywords', 'free quotes generator, image quote generator, quotes about life generator, quotes for instagram, quotes for tattos, quotes for life, amazing quote generador, quotes for facebook, quotes to share');
    }

    public function like()
    {
        // $this->autoRender = false;
        // if ($this->request->is('post')) {
        //     $id = $this->request->data['id'];

        //     $quote = $this->Quotes->get($id);
        //     $quote->likes++;
        //     $this->Quotes->save($quote);

        //     $response = array('status' => 'ok');
        //     echo json_encode($response); die;
        // }
    }

    public function indexRedirect($category_slug=null) {
        if ($category_slug) {
            return $this->redirect('/quotes/' . $category_slug, 301);
        }

        return $this->redirect('/quotes', 301);
    }

    public function generator()
    {

        // $frase_id = $this->request->query('frase');

        // if (isset($frase_id) && is_numeric($frase_id)) {
        //     $this->loadModel('Frases');
        //     $frase = $this->Frases->get($frase_id);
        //     $this->set('frase', $frase);
        // }

        $this->set('title', 'Online Quote Maker');
        $this->render('creator');
    }

    public function quickStudio($hash=null)
    {
        $Template = null;

        if ($hash && !is_numeric($hash)) {
            $Template = TemplatesService::get($hash);
        }

        $templates = TemplatesService::all();
        $text = $this->request->getQuery('text', "");
        $author = $this->request->getQuery('author', "");
        $action = $this->request->getQuery('action', null);

        if (!$Template) {
            $random_template = array_rand($templates);
            $Template = TemplatesService::get($templates[$random_template]['hash']);
        }

        $this->set('title', 'Create Quote');
    	$this->set('hide_header', 1);
    	$this->set('hide_footer', 1);
        $this->set('template', $Template);
        $this->set('canonical_url', Router::url('/quick-studio', true));
        $this->set('design', null);
        $this->set('text', $text);
        $this->set('author', $author);
        $this->set('action', $action);
        $this->set('templates', $templates);
        $this->set('popular_tags', TagService::getPopularList());
        $this->set('backgrounds', BackgroundService::list(null, 1, 150));
    }

    public function studio($hash=null)
    {
        $Template = null;
        $Design = null;

        if ($hash && !is_numeric($hash)) {
            $Template = TemplatesService::get($hash);
        }

        if ($hash && is_numeric($hash)) {
            $Design = DesignService::get($hash);
            $Design->template = unserialize($Design->template);
        }

        if ($this->user_id) {
            UserService::setLastSeen($this->user_id);
        }

        $text = $this->request->getQuery('text', "");
        $author = $this->request->getQuery('author', "");
        $action = $this->request->getQuery('action', null);

        $templates = TemplatesService::all();

        if (!$Template && !$Design && $action !== 'create_template') {
            $random_template = array_rand($templates);
            $Template = TemplatesService::get($templates[$random_template]['hash']);
        }

        $this->set('title', 'Studio');
        $this->set('template', $Template);
        $this->set('design', $Design);
        $this->set('text', $text);
        $this->set('author', $author);
        $this->set('action', $action);
        $this->set('templates', $templates);
        $this->set('popular_tags', TagService::getPopularList());
        $this->set('backgrounds', BackgroundService::list(null, 1, 150));
        $this->render('studio');
    }

    public function download($id)
    {
        $Design = DesignService::get($id);
        DesignService::incrementDownloads($Design);

        $tempFolder = ROOT . '/tmp/downloads';
        if (!file_exists($tempFolder)) { mkdir($tempFolder, 0777, true); }

        $file = $tempFolder . '/quoootes_com_' . $Design['id'] . '.jpg';
        file_put_contents($file, file_get_contents($Design['image_url']));

        if(!file_exists($file)) {
            die('file not found');
        } else {
            $file_to_send = $file;
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file_to_send));
            header('Content-Length: ' . filesize($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            ob_clean();
            flush();
            readfile($file_to_send);
            unlink($file);
            exit;
        }
    }

    public function migrate()
    {
        $QuotesTable = TableRegistry::getTableLocator()->get('Quotes');

        $query = $QuotesTable->find()
            ->where(['status' => 1])
            ->where(['tag_id IS NULL'])
            ->order(['created' => 'DESC'])
            ->contain(['Tags']);

        foreach($query as $quote) {
            if (isset($quote['tags']) && count($quote['tags']) > 0) {
                $quote->tag_id = $quote['tags'][0]['id'];
                $QuotesTable->save($quote);
            } else {
                echo 'error in quote ' . $quote['id'] . '</br>';
            }
        }

        die;
    }
}
