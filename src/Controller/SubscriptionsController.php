<?php
namespace App\Controller;

use Cake\Routing\Router;
use Cake\Event\EventInterface;

use App\Lib\UserService;
use App\Lib\PlanService;
use App\Lib\StripeService;
use App\Lib\SubscriptionService;
use App\Lib\LogService;
use App\Lib\MixpanelService;

class SubscriptionsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->set('hide_footer', true);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

    public function completed () {
        return $this->redirect('/studio?payment_success');
    }

    public function cancel () {
        $this->Auth->deny();
        StripeService::cancelSubscription($this->user_id, $this->subscription->subscription_id);
        sleep(2);
        return $this->redirect('/account');
    }

    public function reactivate ()
    {
        StripeService::reactivateSubscription($this->user_id, $this->subscription->subscription_id);
        sleep(2);
        return $this->redirect('/account');
    }

    public function pay($plan_id = null)
    {
        $user_id = $this->user_id;

        if ($plan_id === 'pro') {
            $plan_id = 2;
        }

        $User = UserService::findById($user_id);
        $Plan = PlanService::findById($plan_id);

        if (!$Plan || $Plan->visible === false || $Plan->id === 1) {
            return $this->redirect('plans');
        }

        if (!$User->customer_id) {
            $cus_metadata = array(
                'user_id' => $User->id
            );

            $customer_id = StripeService::createCustomer($User->email, $User->first_name . ' ' . $User->last_name, $cus_metadata);

            if (!$customer_id) {
                echo 'Something went wrong.';
                die;
            }

            UserService::update($user_id, ['customer_id' => $customer_id]);

            $User->customer_id = $customer_id;
        }

        // $Subscription = SubscriptionService::findByUserId($User->id);

        $return_url = $this->request->getQuery('return_url', null);

        if ($return_url) {
            $return_url = Router::url($return_url, true);
        } else {
            $return_url = $this->referer();
        }

        $return_url = Router::url('/quick-studio?payment_cancel', true);

        $subscription_metadata = array(
            'ip' => $this->getClientIP(),
            'country' => $this->country,
            'user_id' => ($this->user) ? $this->user['id'] : '',
        );

        try {

            $Session = StripeService::createSubscriptionSession(array(
                'customer_id' => $User->customer_id,
                'stripe_pricing_id' => $Plan->stripe_pricing_id,
                'metadata' => $subscription_metadata,
                'cancel_url' => $return_url // cancel_url
            ));

        } catch (\Exception $e) {
            $error = $e->getMessage();
            echo 'jeje';
            echo $error;
            die;
        }

        MixpanelService::track('ViewCheckout', array(
            'plan_id' => $plan_id
        ));

        return $this->redirect($Session->url);
    }

    public function ipnStripe  () {

        $payload = @file_get_contents('php://input');
        $payload = json_decode($payload, true);

        if (!$payload) {
            return $this->response->withStatus(403)->withStringBody('invalid request');
        }

        $event = $payload['data']['object'];

        LogService::log('stripe_webhooks', $payload['type'] . ' - ' . $payload['id']);

        switch ($payload['type']) {

            case 'customer.subscription.created':
            case 'customer.subscription.updated':
                $User = UserService::getByCustomerId($event['customer']);
                $StripeSubscription = StripeService::getSubscription($event['id']);

                if ($StripeSubscription->status === 'incomplete' || $StripeSubscription->status === 'incomplete_expired') {
                    return $this->response->withStatus(200)->withStringBody('ignorning incomplete subscription, do nothing keep free plan');
                }

                if ($payload['type'] === 'customer.subscription.created') {
                    $metadata = ($User) ? ['user_id' => $User->id] : [];
                    MixpanelService::track('SubscriptionCreated', $metadata);
                }

                SubscriptionService::update($User->id, $StripeSubscription);
                return $this->response->withStatus(200)->withStringBody($payload['type'] . ' handled ok');
                break;

            case 'invoice.payment_succeeded':
                $User = UserService::getByCustomerId($event['customer']);
                $metadata = ($User) ? ['user_id' => $User->id] : [];
                MixpanelService::track('PaymentCompleted', $metadata);

                return $this->response->withStatus(200)->withStringBody('success');
                break;

            case 'invoice.created':
            case 'invoice.paid':
                if ($event['status'] === 'open') {
                    return $this->response->withStatus(200)->withStringBody('ignorning open invoice event');
                }
                $User = UserService::getByCustomerId($event['customer']);
                $subscription_id = $User->subscription->id;
                SubscriptionService::createInvoice($subscription_id, $event);
                return $this->response->withStatus(200)->withStringBody('success');
                break;

            case 'customer.subscription.deleted':
                $User = UserService::getByCustomerId($event['customer']);

                if (!$User) {
                    LogService::log('stripe_webhooks_cancelation', 'Failed to cancel subscription (user not found with cus): ' . $event['customer']);
                    return $this->response->withStatus(200)->withStringBody('Cus not found in DB, ignoring');
                }

                $StripeSubscription = StripeService::getSubscription($event['id']);
                SubscriptionService::cancel($User->id, $StripeSubscription);
                return $this->response->withStatus(200)->withStringBody('success');
                break;

            case 'charge.refunded':
                return $this->response->withStatus(200)->withStringBody('charge.refunded not handled yet');
                break;

            default:
                return $this->response->withStatus(403)->withStringBody('fail');
                break;
        }
    }

}
