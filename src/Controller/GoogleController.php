<?php
namespace App\Controller;

use App\Lib\BackgroundService;
use App\Lib\UploadService;
use App\Lib\DesignService;
use App\Lib\TemplatesService;
use App\Lib\UserService;
use Cake\Routing\Router;
use Google;

class GoogleController extends AppController
{

    public function login () {
        $client =  new Google\Client();

        $client_id = env('GOOGLE_CLIENT_ID');
        $client_secret = env('GOOGLE_CLIENT_SECRET');
        $redirect_url = Router::url('/google/callback', true);

        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_url);
        $client->addScope("email");
        $client->addScope("profile");

        return $this->redirect($client->createAuthUrl());
    }

    public function callback () {
        $client =  new Google\Client();

        $client_id = env('GOOGLE_CLIENT_ID');
        $client_secret = env('GOOGLE_CLIENT_SECRET');
        $redirect_url = Router::url('/google/callback', true);
        $google_account_info = null;

        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_url);
        $client->addScope("email");
        $client->addScope("profile");

        if ($this->request->is('post')) {
            $credential = $this->request->getData('credential', null);
            $g_csrf_token = $this->request->getData('g_csrf_token', null);

            $payload = $client->verifyIdToken($credential);

            if ($payload && $payload['aud'] === $client_id) {
                $google_account_info = [
                    'id' => $payload['sub'],
                    'name' => $payload['name'],
                    'given_name' => $payload['given_name'],
                    'family_name' => $payload['family_name'],
                    'picture' => $payload['picture'],
                    'email' => $payload['email'],
                    'email_verified' => $payload['email_verified'],
                ];
            }
        }

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);
            $google_oauth = new Google\Service\Oauth2($client);
            $payload = $google_oauth->userinfo->get();

            $google_account_info = [
                'id' => $payload['id'],
                'name' => $payload['name'],
                'given_name' => $payload['givenName'],
                'family_name' => $payload['familyName'],
                'picture' => $payload['picture'],
                'email' => $payload['email'],
                'email_verified' => $payload['verifiedEmail'],
            ];
        }

        if ($google_account_info) {

            $User = UserService::handleLoginWithGoogle($google_account_info);

            if ($User) {
                $this->Auth->setUser($User);
            }

            return $this->redirect('/dashboard');
        }

        return $this->redirect('/?login_error');

        // [
        //     {
        //     internal_gapi_mappings:protected: {
        //     familyName: "family_name",
        //     givenName: "given_name",
        //     verifiedEmail: "verified_email"
        //     },
        //     email: "mauriblint@gmail.com",
        //     familyName: "Blint",
        //     gender: "",
        //     givenName: "Mauricio Iván",
        //     hd: "",
        //     id: "103052169027531335647",
        //     link: "",
        //     locale: "es-419",
        //     name: "Mauricio Iván Blint",
        //     picture: "https://lh3.googleusercontent.com/a/ALm5wu1tADh_QjIeOlWsfpvMF6EWQOkCHGSxvmWqcdW4hA=s96-c",
        //     verifiedEmail: "1",
        //     modelData:protected: {
        //     verified_email: "1",
        //     given_name: "Mauricio Iván",
        //     family_name: "Blint"
        //     },
        //     processed:protected: { }
        //     },
        //     "mauriblint@gmail.com"
        //     ]

    }

    public function logout()
    {
        $session = $this->request->getSession();
        $session->destroy();
        return $this->redirect('/');
    }


}
