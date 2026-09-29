<?php // Include once per page that renders ui/template cards. ?>
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