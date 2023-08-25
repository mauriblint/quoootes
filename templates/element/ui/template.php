<?php

?>
<div class="col-6 col-sm-4 col-md-3">
    <div class="template">
        <a href="/quick-studio/<?=$template['hash']?>" onclick="return onTemplateClicked(<?= $template['is_premium']; ?>)">
            <img class="img-fluid" loading="lazy" src="<?=$template['preview_url']?>" alt="">
            <?php if ($template['is_premium']): ?>
                <span class="badge bg-primary">Premium</span>
            <?php endif; ?>
        </a>
    </div>
</div>

<?= $this->element('modals/upgrade'); ?>
<?= $this->element('studio/login-modal'); ?>
<script>
    onTemplateClicked = function (is_premium) {
        if (is_premium) {
            if (!__APP_DATA__.user || __APP_DATA__.user.is_free) {
                get_modal('upgrade').show();

                mixpanel.track("ShowedUpgrade", {
                    feature: 'premium_template',
                    editor: 'quick-editor',
                });

                return false;
            }
        }

        return true;
    };
</script>