<?php
namespace App\Controller;

use App\Lib\BackgroundService;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Event\EventInterface;

use App\Lib\DesignService;
use App\Lib\TemplatesService;

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

        $title = 'Beautiful Images with Quotes ready to share';
        $page_title = 'Create beautiful Images with Quotes ready to share.';
        // $keywords = '';

        if( !is_null($tag_id) ) {
            $this->set('tag', $category);
            $title = 'Images with ' . $category->name_seo;
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
		$quote = DesignService::get($id);

        if (!$quote) {
            return $this->redirect('/', 301);
        }

        DesignService::incrementView($quote);

		$this->set(compact('quote'));
        $this->set('title', $quote->text);
        $this->set('description', htmlspecialchars($quote['text']));
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

    public function studio($hash=null)
    {
        $Template = null;

        if ($hash) {
            $Template = TemplatesService::get($hash);
        }

        $text = $this->request->getQuery('text', "");
        $action = $this->request->getQuery('action', null);

        $this->set('title', 'Studio');
        $this->set('template', $Template);
        $this->set('text', $text);
        $this->set('action', $action);
        $this->set('backgrounds', BackgroundService::list(null, 1, 150));
        $this->render('studio');
    }

    public function download($id)
    {
        // $quote = $this->Quotes->get($id);
        // $quote->downloads++;
        // $this->Quotes->save($quote);

        // $downloadUrl = str_replace('image/upload', 'image/upload/fl_attachment', $quote->image_url);

        // $this->redirect($downloadUrl);
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
