<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;
use App\Lib\PlanService;
use App\Lib\StripeService;

class SubscriptionService {


    const STATUS_ACTIVE = 'active';

    public static function findByUserId ($user_id = null) {

        if (is_null($user_id)) {
            return null;
        }

        $SubscriptionTable = TableRegistry::getTableLocator()->get('Subscriptions');

        $Subscription = $SubscriptionTable
            ->find()
            ->where(['user_id' => $user_id])
            ->contain('Plans')
            ->first();

        if (is_null($Subscription)) {
            $Subscription = $SubscriptionTable->newEmptyEntity();
            $Subscription->user_id = $user_id;
            $Subscription->plan_id = 1;
            $Subscription->status = SubscriptionService::STATUS_ACTIVE;
            $SubscriptionTable->save($Subscription);

            $Subscription = $SubscriptionTable
                ->find()
                ->where(['user_id' => $user_id])
                ->contain('Plans')
                ->first();
        }

        return $Subscription;
	}

    public static function update ($user_id, $StripeSubscription) {
        $Subscription = self::findByUserId($user_id);
        $SubscriptionTable = TableRegistry::getTableLocator()->get('Subscriptions');

        if (!$Subscription) {
            $Subscription = $SubscriptionTable->newEmptyEntity();

            $Subscription->user_id = $user_id;
            $Subscription->customer_id = $StripeSubscription->customer;
        }

        $Subscription->plan_id = PlanService::getPlanIdFromStripe($StripeSubscription->plan->id);
        $Subscription->period_start = $StripeSubscription->current_period_start;
        $Subscription->period_end = $StripeSubscription->current_period_end;
        $Subscription->subscription_id = $StripeSubscription->id;
        $Subscription->status = $StripeSubscription->status;
        $Subscription->is_cancelled = $StripeSubscription->cancel_at_period_end;
        $Subscription->pricing_id = $StripeSubscription->plan->id;
        $Subscription->type = $StripeSubscription->plan->interval;

        $SubscriptionTable->save($Subscription);

        return $Subscription;
	}

    public static function cancel($user_id, $StripeSubscription)
    {
        $Subscription = self::findByUserId($user_id);
        $SubscriptionTable = TableRegistry::getTableLocator()->get('Subscriptions');

        if (!$Subscription) {
            return false;
        }

        $Subscription->plan_id = 1;
        $Subscription->status = self::STATUS_ACTIVE;
        $Subscription->period_start = NULL;
        $Subscription->period_end = NULL;
        $Subscription->subscription_id = NULL;
        $Subscription->is_cancelled = false;
        $Subscription->pricing_id = NULL;
        $Subscription->type = NULL;

        $SubscriptionTable->save($Subscription);

        return $Subscription;
    }

    public static function createInvoice ($subscription_id, $data) {
        if (!$data['subscription']) {
            return false;
        }

        $InvoicesTable = TableRegistry::getTableLocator()->get('Invoices');

        $Invoice = $InvoicesTable->find()->where(['stripe_invoice_id' => $data['id']])->first();

        if (!$Invoice) {
            $Invoice = $InvoicesTable->newEmptyEntity();
        }

        $stripe_pricing_id = isset($data['lines']['data'][0]['price']['id']) ? $data['lines']['data'][0]['price']['id'] : null;

        $plan_id = NULL;

        if ($stripe_pricing_id) {
            $plan_id = PlanService::getPlanIdFromStripe($stripe_pricing_id);
        }

        $Invoice->subscription_id = $subscription_id;
        $Invoice->stripe_invoice_id = $data['id'];
        $Invoice->stripe_customer_id = $data['customer'];
        $Invoice->stripe_subscription_id = $data['subscription'];
        $Invoice->plan_id = $plan_id;
        $Invoice->status = $data['status'];
        $Invoice->amount = $data['amount_due'];
        $Invoice->amount_paid = $data['amount_paid'];
        $Invoice->currency = $data['currency'];
        $Invoice->billing_reason = $data['billing_reason'];

        if ($InvoicesTable->save($Invoice) ) {
            return true;
        }

        return false;
    }

    public static function getInvoices($subscription_id)
    {
        $InvoicesTable = TableRegistry::getTableLocator()->get('Invoices');

        $Invoices = $InvoicesTable
            ->find()
            ->where(['subscription_id' => $subscription_id])
            ->where(['status' => 'paid'])
            ->order(['created' => 'DESC']);

        return $Invoices->toArray();
    }

    public static function getInvoice($invoice_id)
    {
        $InvoicesTable = TableRegistry::getTableLocator()->get('Invoices');

        $Invoices = $InvoicesTable
            ->find()
            ->where(['Invoices.id' => $invoice_id])
            ->contain(['Subscriptions', 'Plans']);

        return $Invoices->first();
    }

    public static function setPlanManually ($user_id, $plan_id) {
        $Subscription = self::findByUserId($user_id);

        if (!$Subscription) {
            return false;
        }

        $SubscriptionTable = TableRegistry::getTableLocator()->get('Subscriptions');

        $Subscription->plan_id = $plan_id;
        $Subscription->period_start = null;
        $Subscription->period_end = null;
        $Subscription->subscription_id = null;
        $Subscription->status = 'active';
        $Subscription->is_cancelled = null;
        $Subscription->pricing_id = null;
        $Subscription->type = null;

        $SubscriptionTable->save($Subscription);

        return $Subscription;
	}

}