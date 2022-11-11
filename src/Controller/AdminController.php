<?php
namespace App\Controller;

use App\Lib\TemplatesService;
use App\Lib\UserService;
use Cake\Event\EventInterface;

class AdminController extends AppController
{

    public function initialize() : void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
    }

    public function beforeFilter(EventInterface $event)
    {
        $this->Auth->allow();

        $checkUser = (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== 'quoootes');
        $checkPass = (!isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] !== 'Quoootes123579.');
        $isDev = (env('ENV') === 'DEV') ? true : false;

        if (!$isDev && ($checkUser || $checkPass)) {
            header('WWW-Authenticate: Basic realm="Submit user and password"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Authorization Required.';
            exit;
        }

    }

    public function index()
    {
    	$this->set('template', TemplatesService::all());
    }

    public function auth()
    {
        $id = $this->request->getQuery('id', null);
        $token = $this->request->getQuery('token', null);

        if ($token !== 'aabbccddee123987') {
            echo 'invalid'; die;
        }
        $User = UserService::getById($id);
        if (!$User) {
            echo 'User not found';
            die;
        }
        $this->Auth->setUser($User);
        return $this->redirect('/dashboard');
    }

    public function getAllUsers ()
    {
        $result = UserService::all();

        $this->renderJSON(true, $result);
    }
}
