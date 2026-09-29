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
