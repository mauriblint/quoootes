<?php
namespace App\Controller;

use App\Lib\DesignService;
use Cake\Routing\Router;
use Cake\Event\EventInterface;

class AccountController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->set('hide_footer', true);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->deny();
    }

    public function index () {
        $this->set('title', 'My Account');
        $this->set('hide_share_buttons', 1);

        // $user_designs = DesignService::findByUserId($this->user_id);
        // $this->set('user_designs', $user_designs['result']['data']);
        // $this->set('user_designs_pagination', $user_designs['result']['pagination']);
    }

}
