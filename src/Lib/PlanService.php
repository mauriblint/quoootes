<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\Cache\Cache;

use App\Lib\MixpanelService;
use App\Lib\ResponseHandler;
use Stripe\Checkout\Session;
use Exception;

class PlanService {

    // const FEATURE_INSTAGRAM_COMMENTS_LIMIT = 'INSTAGRAM_COMMENTS_LIMIT';
    // const FEATURE_YOUTUBE_COMMENTS_LIMIT = 'YOUTUBE_COMMENTS_LIMIT';
    // const FEATURE_TIKTOK_COMMENTS_LIMIT = 'TIKTOK_COMMENTS_LIMIT';
    // const FEATURE_FACEBOOK_COMMENTS_LIMIT = 'FACEBOOK_COMMENTS_LIMIT';
    // const FEATURE_TWITTER_COMMENTS_LIMIT = 'TWITTER_COMMENTS_LIMIT';
    // const FEATURE_PROMOTIONS_PAGE_VIEWS = 'PROMOTIONS_PAGE_VIEWS';
    // const FEATURE_PROMOTIONS_NO_BRANDING = 'PROMOTIONS_NO_BRANDING';
    // const FEATURE_PROMOTIONS_ACTIVE_CAMPAINGS = 'PROMOTIONS_ACTIVE_CAMPAINGS';
    // const FEATURE_PROMOTIONS_PAGE_VIEWS_TOTAL = 'PROMOTIONS_PAGE_VIEWS_TOTAL';

    public static function create ($data) {
        $PlansTable = TableRegistry::getTableLocator()->get('Plans');
        try {
            $Plan = $PlansTable->newEmptyEntity();

            if (isset($data['name'])) {
                $Plan->name = $data['name'];
            }

            if (isset($data['name_long'])) {
                $Plan->name_long = $data['name_long'];
            }

            if (isset($data['visible'])) {
                $Plan->visible = $data['visible'];
            }

            if (isset($data['stripe_pricing_id'])) {
                $Plan->stripe_pricing_id = $data['stripe_pricing_id'];
            }

            if (isset($data['price'])) {
                $Plan->price = $data['price'];
            }

            if (isset($data['type'])) {
                $Plan->type = $data['type'];
            }

            if (isset($data['slug'])) {
                $Plan->slug = $data['slug'];
            }

            if (isset($data['country'])) {
                $Plan->country = $data['country'];
            }

            if (isset($data['currency'])) {
                $Plan->currency = $data['currency'];
            }

            $PlansTable->save($Plan);
        } catch (Exception $e) {
            return false;
        }

        return $Plan;
	}

    public static function update ($plan_id, $data) {
        $PlansTable = TableRegistry::getTableLocator()->get('Plans');
        try {
            $Plan = $PlansTable->get($plan_id);

            if (isset($data['name'])) {
                $Plan->name = $data['name'];
            }

            if (isset($data['name_long'])) {
                $Plan->name_long = $data['name_long'];
            }

            if (isset($data['slug'])) {
                $Plan->slug = $data['slug'];
            }

            if (isset($data['visible'])) {
                $Plan->visible = $data['visible'];
            }

            if (isset($data['stripe_pricing_id'])) {
                $Plan->stripe_pricing_id = $data['stripe_pricing_id'];
            }

            if (isset($data['price'])) {
                $Plan->price = $data['price'];
            }

            if (isset($data['type'])) {
                $Plan->type = $data['type'];
            }

            if (isset($data['country'])) {
                $Plan->country = $data['country'];
            }

            if (isset($data['currency'])) {
                $Plan->currency = $data['currency'];
            }

            $PlansTable->save($Plan);
        } catch (Exception $e) {
            return false;
        }

        return $Plan;
	}

    public static function findById ($plan_id) {
        $PlansTable = TableRegistry::getTableLocator()->get('Plans');
        try {
            $Plan = $PlansTable->get($plan_id);
        } catch (Exception $e) {
            return false;
        }

        return $Plan;
	}

    public static function getPlansRegionByCountry ($country = 'all') {
        switch ($country) {
            case 'BR':
                $_country = 'br';
                break;
            case 'AR':
                $_country = 'ar';
                break;
            case 'IN':
                $_country = 'in';
                break;
            default:
                $_country = 'all';
        }

        return $_country;
    }

    public static function getPlans ($country = 'all', $user = null, $subscription = null) {

        $region = self::getPlansRegionByCountry($country);

        if ($user && $subscription && isset($subscription['plan']['country']) && $subscription['plan_id'] > 1) {
            $region = $subscription['plan']['country'];
        }

        $cache_key = 'query_get_plans__' . $region;

        $PlansTable = TableRegistry::getTableLocator()->get('Plans');

        $plans = $PlansTable
            ->find()
            ->where(['visible' => 1])
            ->where(['OR' => [
                'Plans.id' => 1,
                'country' => $region
            ]])
            ->contain('Features')
            ->order(['Plans.price' => 'DESC'])
            ->cache($cache_key, 'app_data')
            ->toArray();

        foreach ($plans as $plan) {
            $features = array();

            foreach ($plan['features'] as $f) {
                $features[$f['feature']] = (int) $f['value'];
            }
            $plan['features'] = $features;
        }

        return $plans;
	}

    public static function getPlansForUpgradeModal ($country = 'all', $user = null, $subscription = null) {

        $region = self::getPlansRegionByCountry($country);

        if ($user && $subscription && isset($subscription['plan']['country']) && $subscription['plan_id'] > 1) {
            $region = $subscription['plan']['country'];
        }

        $cache_key = 'query_get_plans_upgrade_modal__' . $region;

        $PlansTable = TableRegistry::getTableLocator()->get('Plans');

        $plans = $PlansTable
            ->find()
            ->where(['Plans.id !=' => 1])
            ->where(['Plans.visible' => 1])
            ->where(['Plans.country' => $region])
            ->contain('Features')
            ->order(['Plans.price' => 'ASC'])
            ->cache($cache_key, 'app_data')
            ->toArray();

        foreach ($plans as $plan) {
            $features = array();

            foreach ($plan['features'] as $f) {
                if (
                    $f['feature'] === 'INSTAGRAM_COMMENTS_LIMIT'
                    || $f['feature'] === 'TIKTOK_COMMENTS_LIMIT'
                    || $f['feature'] === 'MUTINETWORK_GIVEAWAYS'
                    || $f['feature'] === 'PROMOTIONS_PAGE_VIEWS'
                    || $f['feature'] === 'PROMOTIONS_ACTIVE_CAMPAINGS'
                    || $f['feature'] === 'PROMOTIONS_NO_BRANDING'
                ) {
                    $features[$f['feature']] = (int) $f['value'];
                }
            }
            $plan['features'] = $features;
        }

        return $plans;
	}

    public static function getPlanList () {
        $PlansTable = TableRegistry::getTableLocator()->get('Plans');

        $plans = $PlansTable
            ->find()
            ->order(['Plans.id' => 'ASC'])
            ->toArray();

        return $plans;
	}

    public static function getPlanByPricingId ($stripe_pricing_id) {
        $PlansTable = TableRegistry::getTableLocator()->get('Plans');

        $Plan = $PlansTable
            ->find()
            ->where(['stripe_pricing_id' => $stripe_pricing_id])
            ->first();

        return $Plan;
    }


    public static function getPlanBySlugAndCountry ($slug, $country) {
        $PlansTable = TableRegistry::getTableLocator()->get('Plans');

        $Plan = $PlansTable
            ->find()
            ->where(['slug' => $slug])
            ->where(['country' => $country])
            ->where(['visible' => 1])
            ->first();

        return $Plan;
    }

    public static function getPlanIdFromStripe ($stripe_pricing_id) {
        $PlansTable = TableRegistry::getTableLocator()->get('Plans');

        $Plan = $PlansTable
            ->find()
            ->where(['stripe_pricing_id' => $stripe_pricing_id])
            ->first();

        return ($Plan) ? $Plan->id : null;
	}

    public static function createFeature($plan_slug, $feature_name, $feature_value) {
        $FeaturesTable = TableRegistry::getTableLocator()->get('Features');

        $Feature = $FeaturesTable->newEmptyEntity();
        $Feature->plan_slug = $plan_slug;
        $Feature->feature = $feature_name;
        $Feature->value = $feature_value;

        $FeaturesTable->save($Feature);
    }

    public static function getFeature2($plan, $feature)
    {
        $feature = self::getFeature($plan, $feature);

        if ($feature === 999999) {
            $feature = 99999999999;
        }

        return $feature;
    }

    public static function getFeature($plan, $feature)
    {
        if (is_numeric($plan)) {
            // find plan by id
            $features = [];
        }

        if (isset($plan['features'])) {
            $features = $plan['features'];
        } elseif (isset($plan['subscription']['plan']['features'])) {
            // User Object
            $features = $plan['subscription']['plan']['features'];
        }

        foreach ($features as $f) {
            if ($f['feature'] === $feature) {
                $v = $f['value'];

                if (is_numeric($v)) {
                    return (int) $v;
                }

                return $v;
            }
        }
    }

    public static function getFeatures($plan)
    {
        if (is_numeric($plan)) {
            // find plan by id
            $features = [];
        }

        if (isset($plan['features'])) {
            $features = $plan['features'];
        } else {
            $features = [];
        }

        $result = array();

        foreach ($features as $f) {
            $result[$f['feature']] = is_numeric($f['value']) ? (int) $f['value'] : $f['value'];
        }

        return $result;
    }

    public static function getCurrencySymbol ($currency) {
        switch ($currency) {
            case 'brl':
                return 'R$';
            case 'eur':
                return '€';
            case 'gbp':
                return '£';
            case 'usd':
                return '$';
            case 'inr':
                return '₹';
            default:
                return '$';
        }
    }
}