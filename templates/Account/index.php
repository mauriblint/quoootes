
<?php
function formatDate($time)
{
    if (method_exists($time, 'i18nFormat')) {
        $time = $time->i18nFormat('unixTimestampFormat');
    }

    if (!is_numeric($time)) {
        $date = DateTime::createFromFormat('d/m/y H:i', $time);

        if ($date && $date->getTimestamp()) {
            $time = $date->getTimestamp();
        }
    }

    if (!is_numeric($time)) {
        $time = strtotime($time);
    }
    // Format parameters
    // https://www.w3schools.com/php/func_date_date_format.asp
    $date = new DateTime();
    $date->setTimestamp($time);
    return $date->format("F d, Y");
}

$plan = ($subscription) ? $subscription->plan : null;
$renewal_date = formatDate($subscription['period_end']);
$is_cancelled = $subscription['is_cancelled'];
$is_subscription_active = $subscription['status'] === 'active';
$is_free = $plan->id === 1;
$subscription_duration = $subscription['type'] === 'year' ? __('Yearly') : __('Monthly');
?>
<div class="container" id="vapp">
    <div class="row">
        <div class="col-12">
            <div class="header-container pb-5">
                <h1>My Account</h1>
                <p v-if="designs.length">Welcome, <?=$user['first_name']?>!</p>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card">
                <!-- <div class="card-header">asdasd</div> -->
                <div class="card-body">
                    <form action="">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">First Name</label>
                                    <input type="text" class="form-control" value="<?=$user['first_name']?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Last Name</label>
                                    <input type="text" class="form-control" value=" <?=$user['last_name']?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" value="<?=$user['email']?>" disabled>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <div class="text-success">
                                    <i class="bi bi-check-lg"></i> You are connected with your Google account</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="">Billing</label>

                                    <div>
                                        <?php if ($subscription['plan']['id'] === 1): ?>
                                            <div>You are not subscribed to any plans.</div>
                                            <a class="btn btn-primary btn-md mt-3" href="/subscriptions/pay/2">Upgrade to Quoootes Pro</a>
                                        <?php endif; ?>

                                        <?php if ($subscription['plan']['id'] > 1 && !$subscription['is_cancelled']): ?>
                                            <div>
                                            You are subscribed to <strong><?php echo $subscription['plan']['name']; ?></strong> Plan. Your subscription renew on <strong><?=$renewal_date?></strong>
                                            </div>
                                            <a class="btn btn-danger mt-3 btn-sm" href="/subscriptions/cancel">Cancel Subscription</a>
                                        <?php endif; ?>

                                        <?php if ($subscription['plan']['id'] > 1 && $subscription['is_cancelled']): ?>
                                            <div>
                                            Your subscription is <strong>cancelled</strong>.
                                            <br>Your plan will remain active until <strong><?=$renewal_date?></strong> and you will no longer be charged.
                                            </div>

                                            <a class="btn btn-success mt-3 btn-sm" href="/subscriptions/reactivate">Reactivate Subscription</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .card { border-radius: 10px; }
    .card-body {padding: 32px; }
    .form-group { margin-bottom: 30px;}
    label { font-weight: 700;}
</style>

