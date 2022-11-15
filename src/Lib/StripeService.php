<?php
namespace App\Lib;

use Cake\Routing\Router;
use Cake\Cache\Cache;

class StripeService {

    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_PAST_DUE = 'past_due';

    public static function createSession ($customer_id, $reference_id, $name, $amount, $metadata=[], $return_url=null) {
        $stripe_api_key = env('STRIPE_API_SECRET');

        \Stripe\Stripe::setApiKey($stripe_api_key);

        $session = \Stripe\Checkout\Session::create([
            'customer' => $customer_id,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'quantity' => 1,
                    'amount' => $amount*100,
                    'currency' => 'usd',
                    'name' => $name,
                    // 'price_data' => [
                    //     'currency' => 'usd',
                    //     'product_data' => ['name' => 'Instagram Giveaway 1000'],
                    //     'unit_amount' => 199,
                    // ],
                    // 'images' => ['https://instagram.fcor5-1.fna.fbcdn.net/v/t51.2885-15/e35/83936572_2800767210053104_78195178703949855_n.jpg?_nc_ht=instagram.fcor5-1.fna.fbcdn.net&_nc_cat=1&_nc_ohc=Me8b_7CeGVAAX8DiqRo&oh=53fe585a5a07e5f609e68e6db25085ba&oe=5F454AD4']
                ]
            ],
            'mode' => 'payment',
            'client_reference_id' => $reference_id,
            'billing_address_collection' => (isset($metadata['country']) && $metadata['country'] === 'BR') ? 'required' : 'auto',
            'payment_intent_data' => [
                'metadata' => $metadata
            ],
            'success_url' => Router::url('/es/process-payment?session_id={CHECKOUT_SESSION_ID}', true),
            'cancel_url' => ($return_url) ? $return_url : Router::url('/es/cancel-payment', true),
        ]);

        return $session->id;
    }

    public static function getSession ($session_id=null) {
        $stripe_api_key = env('STRIPE_API_SECRET');

        if (!$session_id) {
            return null;
        }

        $stripe = new \Stripe\StripeClient($stripe_api_key);

        try {
            $session =  $stripe->checkout->sessions->retrieve($session_id, []);
        } catch (\Throwable $th) {
            return false;
        }

        return $session;
    }

    public static function getCustomer ($customer_id=null) {
        $stripe_api_key = env('STRIPE_API_SECRET');

        if (!$customer_id) {
            return null;
        }

        $stripe = new \Stripe\StripeClient($stripe_api_key);

        return $stripe->customers->retrieve($customer_id);
    }

    public static function getPaymentIntent ($payment_intent_id=null) {
        $stripe_api_key = env('STRIPE_API_SECRET');

        if (!$payment_intent_id) {
            return null;
        }

        $stripe = new \Stripe\StripeClient($stripe_api_key);

        return $stripe->paymentIntents->retrieve($payment_intent_id);
    }

    public static function getSubscription ($subscription_id=null) {
        $stripe_api_key = env('STRIPE_API_SECRET');

        if (!$subscription_id) {
            return null;
        }

        $stripe = new \Stripe\StripeClient($stripe_api_key);

        return $stripe->subscriptions->retrieve($subscription_id);
    }

    public static function getInvoice ($invoice_id=null) {
        $stripe_api_key = env('STRIPE_API_SECRET');

        if (!$invoice_id) {
            return null;
        }

        $stripe = new \Stripe\StripeClient($stripe_api_key);

        return $stripe->invoices->retrieve($invoice_id);
    }

    public static function getUpcomingInvoicePreview($params = [])
    {
        $stripe_api_key = env('STRIPE_API_SECRET');

        $stripe = new \Stripe\StripeClient($stripe_api_key);

        $invoice = $stripe->invoices->upcoming($params);
        // echo json_encode($invoice); die;
        return [
            'total' => $invoice->total / 100,
            'unused_amount' => $invoice->lines->data[0]->amount / 100,
            'unused_description' => $invoice->lines->data[0]->description,
            'new_plan_amount' => $invoice->lines->data[1]->amount / 100,
            'new_plan_description' => $invoice->lines->data[1]->description,
            'new_plan_end_time' => $invoice->lines->data[1]->period->end,
        ];
    }

    public static function createCustomer ($email, $name, $metadata = array()) {

        $stripe_api_key = env('STRIPE_API_SECRET');
        \Stripe\Stripe::setApiKey($stripe_api_key);


        $Customer = \Stripe\Customer::create([
          'email' => $email,
          'name' => $name,
          'metadata' => $metadata
        ]);

        if ($Customer && $Customer->id) {
            return $Customer->id;
        }

        return false;
    }

    public static function createCustomerPortalSession ($stripe_customer_id) {

        $stripe_api_key = env('STRIPE_API_SECRET');
        \Stripe\Stripe::setApiKey($stripe_api_key);

        // if ($return_url !== null) {
        //     if (strpos($return_url, 'http') === false) {
        //         $return_url = Router::url($return_url, true);
        //     }
        // } else {
        //     $return_url = Router::url('/settings/billing', true);
        // }

        // https://dev.app-sorteos.com/subscriptions/pay/12?return_url=/es/apps/sorteo-instagram

        // $d = Router::url('/settings/billing?d=1', true);
        // $a = ($asd !== null) ? $asd : $d;

        $session = \Stripe\BillingPortal\Session::create([
          'customer' => $stripe_customer_id,
          'return_url' => Router::url('/settings/billing', true),
        ]);

        return $session;
    }

    public static function createSubscriptionSession ($data=array()) {
        $stripe_api_key = env('STRIPE_API_SECRET');

        \Stripe\Stripe::setApiKey($stripe_api_key);

        $customer_id = (isset($data['customer_id'])) ? $data['customer_id'] : null;
        $reference_id = (isset($data['reference_id'])) ? $data['reference_id'] : null;
        $stripe_pricing_id = (isset($data['stripe_pricing_id'])) ? $data['stripe_pricing_id'] : null;
        $cancel_url = (isset($data['cancel_url'])) ? $data['cancel_url'] : Router::url('/subscriptions/canceled', true);
        $metadata = (isset($data['metadata'])) ? $data['metadata'] : null;


        $success_url = base64_encode($cancel_url);

        $billing_address_collection = 'auto';

        // if (isset($metadata['country'])) {
        //     $required_countries = array("BR", "AR", "CL", "MX", "CO");

        //     if (in_array($metadata['country'], $required_countries)) {
        //         $billing_address_collection = 'required';
        //     }
        // }

        $session = \Stripe\Checkout\Session::create([
            'customer' => $customer_id,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'quantity' => 1,
                    'price' => $stripe_pricing_id,
                ]
            ],
            'mode' => 'subscription',
            'client_reference_id' => $reference_id,
            'billing_address_collection' => $billing_address_collection,
            'subscription_data' => [
                'metadata' => $metadata
            ],
            'success_url' => Router::url('/subscriptions/completed?session_id={CHECKOUT_SESSION_ID}', true),
            'cancel_url' => $cancel_url,
        ]);

        return $session;
    }

    public static function cancelSubscription ($user_id, $stripe_subscription_id) {
        $stripe_api_key = env('STRIPE_API_SECRET');
        $stripe = new \Stripe\StripeClient($stripe_api_key);

        $Subscription = self::getSubscription($stripe_subscription_id);

        if ($Subscription->status === self::STATUS_PAST_DUE) {
            $stripe->subscriptions->cancel($stripe_subscription_id);
            LogService::log('subscription_updates', 'Subscription from user ' . $user_id . ' has been canceled (' . $stripe_subscription_id . ')');
        } else {
            $stripe->subscriptions->update($stripe_subscription_id, [
                'cancel_at_period_end' => true
            ]);
            LogService::log('subscription_updates', 'Subscription from user ' . $user_id . ' has been updated to cancel_at_period_end (' . $stripe_subscription_id . ')');
        }

        return true;
    }

    public static function reactivateSubscription ($user_id, $stripe_subscription_id) {
        $stripe_api_key = env('STRIPE_API_SECRET');
        $stripe = new \Stripe\StripeClient($stripe_api_key);

        $stripe->subscriptions->update($stripe_subscription_id, [
            'cancel_at_period_end' => false
        ]);

        LogService::log('subscription_updates', 'Subscription from user ' . $user_id . ' has been reactivated (' . $stripe_subscription_id . ')');

        return true;
    }

    public static function updateSubscription($stripe_subscription_id, $data=array())
    {
        $stripe_api_key = env('STRIPE_API_SECRET');
        $stripe = new \Stripe\StripeClient($stripe_api_key);

        $result =  $stripe->subscriptions->update($stripe_subscription_id, $data);

        return $result;
    }


    public static function updateCustomer($stripe_customer_id, $data = array())
    {
        $stripe_api_key = env('STRIPE_API_SECRET');
        $stripe = new \Stripe\StripeClient($stripe_api_key);

        $result =  $stripe->customers->update($stripe_customer_id, $data);

        return $result;
    }

    public static function addCustomerCreditCard($stripe_customer_id, $token = NULL)
    {
        $stripe_api_key = env('STRIPE_API_SECRET');
        $stripe = new \Stripe\StripeClient($stripe_api_key);

        $result =  $stripe->customers->update($stripe_customer_id, array(
            'source' => $token
        ));

        // echo '<pre>';
        // print_r($result);
        // die();

        return $result;
    }

    public static function getCustomerCreditCards ($customer_id) {
        $stripe_api_key = env('STRIPE_API_SECRET');
        $stripe = new \Stripe\StripeClient($stripe_api_key);

        $result =  $stripe->paymentMethods->all([
            'customer' => $customer_id,
            'type' => 'card',
          ]);

        return $result;
    }

    public static function getPlan ($stripe_pricing_id) {

        $cache_key = 'stripe_pricing_' . $stripe_pricing_id;

        if (($data = Cache::read($cache_key)) !== false) {
            return $data;
        }

        $stripe_api_key = env('STRIPE_API_SECRET');
        $stripe = new \Stripe\StripeClient($stripe_api_key);

        $result = $stripe->plans->retrieve($stripe_pricing_id, []);

        Cache::write($cache_key, $result);

        return $result;
    }

    public static function createTestSessionARS ($customer_id, $reference_id, $name, $amount, $metadata=[], $return_url=null, $stripe_ars_amount=null) {
        $stripe_api_key = env('STRIPE_API_SECRET');

        \Stripe\Stripe::setApiKey($stripe_api_key);

        $session = \Stripe\Checkout\Session::create([
            'customer' => $customer_id,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'quantity' => 1,
                    'amount' => $stripe_ars_amount*100,
                    'currency' => 'ARS',
                    'name' => $name,
                ]
            ],
            'mode' => 'payment',
            'client_reference_id' => $reference_id,
            'billing_address_collection' => (isset($metadata['country']) && $metadata['country'] === 'BR') ? 'required' : 'auto',
            'payment_intent_data' => [
                'metadata' => $metadata
            ],
            'success_url' => Router::url('/es/process-payment?session_id={CHECKOUT_SESSION_ID}', true),
            'cancel_url' => ($return_url) ? $return_url : Router::url('/es/cancel-payment', true),
        ]);

        return $session->id;
    }
}