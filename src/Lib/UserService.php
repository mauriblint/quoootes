<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\I18n\FrozenTime;

use App\Lib\MixpanelService;
use App\Lib\ResponseHandler;

class UserService {

    const BASE_URL = 'https://res.cloudinary.com/';

    const QUERY_QUOTES_FIELDS = ['Quotes.id', 'Quotes.hash','Quotes.thumb','Quotes.type','Quotes.network'];

    const QUERY_IMAGES_FIELDS = ['quote_id', 'public_id', 'format', 'filename', 'cloud_service', 'cloud_name', 'cloud_container', 'version'];


    private static function getTable () {
        return TableRegistry::getTableLocator()->get('Users');
    }

    public static function findById ($user_id, $contain='Subscriptions.Plans') {
        $query = TableRegistry::getTableLocator()->get('Users')
            ->get($user_id, ['contain' => $contain]);
        return $query;
    }

    public static function all () {
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        return $UsersTable->find()
            ->enableHydration(false)
            ->contain(['Subscriptions'])
            ->order(['Users.created' => 'DESC'])
            ->all()
            ->toArray();
    }

    public static function hasActiveSubscription ($user_id=null) {
        if (!$user_id) {
            return false;
        }

        $Subscription = SubscriptionService::findByUserId($user_id);

        if (!$Subscription) {
            return false;
        }

        if ($Subscription->plan_id > 1 && $Subscription->status === 'active') {
            return true;
        }

        return false;
    }

    public static function handleLoginWithGoogle ($google_data) {
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $User = self::findByGoogleIdOrEmail($google_data['id'], $google_data['email']);

        if (!$User) {
            $User = self::createFromGoogle($google_data);

            MixpanelService::track('SignUp', array(
                'source' => 'google',
                // 'user_id' => $User['id'],
            ));

        } else {
            $User->picture = $google_data['picture'];
            $User->last_login = FrozenTime::now();
            if ($google_data['country'] && !$User->country) {
                $User->country = $google_data['country'];
            }
            $UsersTable->save($User);
        }

        return $User;
    }

    public static function setLastSeen ($user_id) {
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $User = $UsersTable->get($user_id);
        if ($User) {
            $User->last_login = FrozenTime::now();
            $UsersTable->save($User);
            return true;
        }

        return false;
    }

    public static function createFromGoogle ($google_data) {
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        $User = $UsersTable->newEmptyEntity();

        $User->google_id = $google_data['id'];
        $User->email = $google_data['email'];
        $User->email_verified = (bool) $google_data['email_verified'];
        $User->name = $google_data['name'];
        $User->first_name = $google_data['given_name'];
        $User->last_name = $google_data['family_name'];
        $User->picture = $google_data['picture'];
        $User->country = (isset($google_data['country']) && !empty($google_data['country'])) ? $google_data['country'] : null;
        $User->last_login = FrozenTime::now();

        if ($UsersTable->save($User)) {
            return $User;
        } else {
            return false;
        }
    }

    public static function authenticate ($AuthComponent, $data) {


        if( $AuthComponent->user() ) {
            return $result = ResponseHandler::success(['logged_in' => true]);
    	}

        if (Router::getRequest()->is('post')) {
            $User = $AuthComponent->identify();

            if ($User) {
                $AuthComponent->setUser($User);
                $u = self::getTable()->get( $User['id'] );
                $u->last_login = Time::now();
                self::getTable()->save($u);

                MixpanelService::track('SignIn', ['method' => 'email']);

                return $result = ResponseHandler::success(['logged_in' => true]);
            } else {
                return $result = ResponseHandler::error(['logged_in' => false]);
            }
        }

        return $result = ResponseHandler::error(['logged_in' => false]);
    }

    public static function setUserSession ($AuthComponent, $data) {
        $AuthComponent->setUser($data);
    }

    public static function getById ($user_id) {

        $query = TableRegistry::get('Users')->get($user_id);

        return $query->toArray();
    }

    public static function setLastLogin ($user_id) {
        $u = self::getTable()->get($user_id);
        $u->last_login = Time::now();
        self::getTable()->save($u);
    }

    public static function getByCustomerId ($customer_id) {

        $query = TableRegistry::get('Users')
            ->find()
            ->where(['customer_id' => $customer_id])
            ->contain('Subscriptions')
            ->first();

        return $query;
    }

    public static function update ($user_id, $data) {
        $User = self::getTable()->get($user_id);


        if (isset($data['first_name'])) {
            $User->set('first_name', $data['first_name']);
        }
        if (isset($data['last_name'])) {
            $User->set('last_name', $data['last_name']);
        }
        if (isset($data['customer_id'])) {
            $User->set('customer_id', $data['customer_id']);
        }
        if (isset($data['business_name'])) {
            $User->set('business_name', $data['business_name']);
        }
        if (isset($data['business_address'])) {
            $User->set('business_address', $data['business_address']);
        }
        if (isset($data['business_tax_id'])) {
            $User->set('business_tax_id', $data['business_tax_id']);
        }
        if (isset($data['picture'])) {
            $User->set('picture', $data['picture']);
        }
        if (isset($data['facebook_id'])) {
            $User->set('facebook_id', $data['facebook_id']);
        }
        if (isset($data['__email'])) {
            $User->set('email', $data['__email']);

            if ($User->customer_id) {
                StripeService::updateCustomer($User->customer_id, array('email' => $data['__email']));
            }
        }

        self::getTable()->save($User);

        return $User;
    }


    public static function updatePicture ($user_id, $picture) {
        $u = self::getTable()->get($user_id);
        $u->picture = $picture;
        self::getTable()->save($u);
    }

    public static function getBoardList ($user_id) {
        $query = TableRegistry::get('Boards')->find('all')
            ->where(['user_id = ' => $user_id])->toArray();

        return [
            'status' => true,
            'result' => $query
        ];
    }

    public static function findByUsername ($username) {
        $user = TableRegistry::get('Users')
            ->findByUsername($username)
            ->hydrate(false)
            ->first();

        if (is_null($user)) {
            return false;
        }

        return $user;
	}

    public static function findByGoogleIdOrEmail ($google_id, $email) {
	    $user = self::getTable()->find()
            ->where(['email' => $email])
            // ->where(['OR' => ['google_id' => $google_id]])
            ->first();

        return $user;
	}

    public function findByFacebookIdOrEmail ($facebook_id, $email) {
        $user = self::getTable()->find()
            ->where(['email = ' => $email])
            ->orWhere(['fbid = ' => $facebook_id])
            ->first();

        return $user;
	}

    public function connectAccountWithFacebook ($user_id, $userData, $picture=null) {
		$user = self::getTable()->get($user_id);

        if (empty($user->fbid)) {
            $user->fbid = $userData['id'];
        }
        if (empty($user->name)) {
            $user->name = $userData['name'];
        }
        if (empty($user->gender)) {
            $user->gender = $userData['gender'];
        }
        if (empty($user->first_name)) {
            $user->first_name = $userData['first_name'];
        }
        if (empty($user->last_name)) {
            $user->last_name = $userData['last_name'];
        }
        if (!is_null($picture)) {
            $user->picture = $picture;
        }

        self::getTable()->save($user);

        return ResponseHandler::success([
            'updated' => true,
            'data' => $user->toArray()
        ]);
	}

    public function confirmEmail ($token) {
        // echo $token;
        $User = self::getTable()->findByActivationToken($token)
            ->first();

        if ($User) {
            $User->active = 1;
            self::getTable()->save($User);
            return true;
        }

        return false;
    }

    public static function createFromFacebook ($data, $picture=null) {

        $check_username = TableRegistry::get('Users')->exists(['username'=> $data['username']]);

        if ($check_username) {
            $data['username'] = $data['username'] . rand(100, 999);
        }

        $data['picture'] = $picture;

        $user = TableRegistry::get('Users')->newEntity();

        $user = TableRegistry::get('Users')->patchEntity($user, $data);

        if (TableRegistry::get('Users')->save($user)) {

            return ResponseHandler::success([
                'updated' => true,
                'data' => $user->toArray()
            ]);
        } else {
            echo 'somehing went wrong tring to create the user';
            die;
        }
	}

}
?>
