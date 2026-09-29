<?php
    use Cake\Routing\Router;
    use App\Utils\Helpers;
?>
<div class="card">
    <a href="<?= \App\Lib\DesignService::url($quote) ?>">
        <div class="card-top">
            <img class="card-img-top" src="<?=$quote['thumb_url'];?>" alt="<?=htmlspecialchars($quote['text'])?>" title="<?=htmlspecialchars($quote['text'])?>" />
        </div>
        <div class="card-body">
            <p class="card-text"><?=wordwrap($quote['text'], 25, "<br />\n");?></p>
        </div>
        <div class="card-statss">
            <span><i class="fa fa-heart"></i> <?=$quote['likes'];?></span>
            <span><i class="fa fa-download"></i> <?=$quote['downloads'];?></span>
            <span><i class="fa fa-eye"></i><?=Helpers::formatNumber($quote['views']);?></span>
        </div>
    </a>
</div>
