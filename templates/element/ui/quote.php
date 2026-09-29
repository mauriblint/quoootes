<?php
	use Cake\Routing\Router;
	use App\Utils\Helpers;
?>
<div class="col-12 col-md-3">
    <div class="card">
        <a href="<?= \App\Lib\DesignService::url($quote) ?>">
            <div class="card-top">
                <img class="card-img-top" src="<?= $quote['image_url']; ?>" alt="<?=htmlspecialchars($quote['text'])?>" title="<?=htmlspecialchars($quote['text'])?>" />
            </div>
            <div class="card-body">
                <p class="card-text"><?=$quote['text']?></p>
            </div>
            <div class="card-statss">
                <span><i class="bi bi-heart"></i> <?=$quote['likes'];?></span>
                <span><i class="bi bi-download"></i> <?=$quote['downloads'];?></span>
                <span><i class="bi bi-eye"></i><?=Helpers::formatNumber($quote['views']);?></span>
            </div>
        </a>
    </div>
</div>