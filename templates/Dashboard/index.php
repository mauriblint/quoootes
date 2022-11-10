<div class="container" id="vapp">
    <div class="row">
        <div class="col-12">
            <div class="header-container">
                <h1>Your Quotes</h1>
                <p v-if="designs.length">Beautiful, aren't they?</p>
                <div v-else>
                    <p>You don't have any saved designs yet.</p>
                    <a href="/studio" class="btn btn-primary btn-lg mt-3">Create your first design</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row templates-list templates-list__square">
        <div class="col-12 col-sm-6 col-md-3" v-for="item in designs">
            <div class="template">
                <a :href="'/quote-' + item.id">
                    <img class="img-fluid" :src="item.image_url">
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    var __APP_DATA__ = {
        user_id: <?= $user_id ?>,
        user_designs: <?= json_encode($user_designs); ?>,
    };
</script>
<?php
if ($env === 'DEV') {
    echo $this->Html->script([
        'studio/vue.js',
        'dashboard.js',
    ], ['defer' => true]);
}
if ($env === 'PROD') {
    echo $this->Html->script([
        'studio/vue.min.js',
        'dashboard.js',
    ], ['defer' => true]);
}
?>