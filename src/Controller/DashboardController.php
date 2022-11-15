<?php
namespace App\Controller;

use App\Lib\DesignService;
use App\Lib\UserService;
use Cake\Event\EventInterface;

class DashboardController extends AppController
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
        UserService::setLastSeen($this->user_id);
        $user_designs = DesignService::findByUserId($this->user_id);

        $this->set('title', 'My Quotes');
        $this->set('hide_share_buttons', 1);
        $this->set('user_designs', $user_designs['result']['data']);
        $this->set('user_designs_pagination', $user_designs['result']['pagination']);
    }

}
