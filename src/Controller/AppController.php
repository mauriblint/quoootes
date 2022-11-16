<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use App\Lib\TagService;
use App\Lib\TemplatesService;
use App\Lib\SubscriptionService;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->checkSSL();

        $this->set('category_list', TagService::getAll() );
        $this->set('env', env('ENV', 'DEV') );

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginAction' => '/',
            'loginRedirect' => '/dashboard',
            'logoutRedirect' => '/',
            'unauthorizedRedirect' => '/',
        ]);

        $this->user = null;
        $this->user_id = null;
        $this->subscription = null;
        $this->env = (env('DEBUG') === 'true') ? 'dev' : 'prod';
        $this->country = $this->getCountry();

        if ($User = $this->Auth->user()) {
            $this->user_id = (isset($User['id'])) ? $User['id'] : null;
            $this->subscription = SubscriptionService::findByUserId($this->user_id);
            $this->is_customer = ($this->subscription['plan_id'] > 1 && $this->subscription['status'] === 'active') ? true : false;
            $this->is_free = !$this->is_customer;
            $User['is_customer'] = $this->is_customer;
            $User['is_free'] = $this->is_free;
            $User['plan_id'] = $this->subscription['plan_id'];
            $this->user = $User;
        }

        $this->set('user', $User);
        $this->set('user_id', $this->user_id);
        $this->set('subscription', $this->subscription);

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

    private function checkSSL () {
        if (!$this->is_ssl()) {
            return $this->redirect('https://' . env('SERVER_NAME') . $this->request->getRequestTarget());
        }
    }

    private function is_ssl() {
        if ( isset( $_SERVER['HTTPS'] ) ) {
            if ( 'on' == strtolower( $_SERVER['HTTPS'] ) ) {
                return true;
            }

            if ( '1' == $_SERVER['HTTPS'] ) {
                return true;
            }
        } elseif ( isset($_SERVER['SERVER_PORT'] ) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO'] ) && ( 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
            return true;
        }

        return false;
    }

    public function renderJSON ($status, $data=[], $pagination = null) {
        $response = array(
            'success' => $status,
            'data' => $data
        );

        if ($pagination) {
            $response['pagination'] = $pagination;
        }

        echo json_encode($response);
        die;
    }

    public function getClientIP()
    {
        $ipaddress = 'UNKNOWN';
        $keys=array('HTTP_CF_CONNECTING_IP','HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR');
        foreach($keys as $k)
        {
            if (isset($_SERVER[$k]) && !empty($_SERVER[$k]) && filter_var($_SERVER[$k], FILTER_VALIDATE_IP))
            {
                $ipaddress = $_SERVER[$k];
                break;
            }
        }
        return $ipaddress;
    }

    public function getCountry ()
    {
        $userCountry = false;
        $isDev = ($this->env === 'dev');

        if ($isDev) {
            $userCountry = 'US';
        }

        if (isset($_SESSION['emulate_user_country'])) {
            return $_SESSION['emulate_user_country'];
        }

        if(isset($_SERVER["HTTP_CF_IPCOUNTRY"])){
            $userCountry = $_SERVER["HTTP_CF_IPCOUNTRY"];
        }

        return $userCountry;
    }
}
