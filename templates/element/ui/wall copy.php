<?php
	use Cake\Routing\Router;
	use App\Utils\PaginationLinks;
	use App\Utils\Helpers;
?>

<div class="card-columns card-columns-quotes" style="">
	<?php $i=0; foreach( $quotes as $quote ) { $i++;?>
		<?php
			$__q_link = Router::url('/quote-'.$quote['id'], true);
		    if (!is_null($quote['image_url'])) {
				$__q_img = $quote['image_url'];
				$__q_thu = $quote['thumb_url'];
		    } else {
				$__q_img = Router::url('/___quotes/'.$quote['id'].'.jpg', true);
				$__q_thu = Router::url('/___quotes/'.$quote['id'].'_s.jpg', true);
		    }
		?>

        <?php if($i===2): ?>
            <div class="card card-ads" style="padding: 15px 0;">
                <?= $this->element('ads/ad-ingrid'); ?>
            </div>
        <?php endif; ?>
        <?php if($i===6): ?>
            <div class="card card__create">
                <a href="<?=Router::url(['_name' => 'Editor']);?>" class="card__create-inner bg-brand">
                    Have a Quote in mind?
                    <button class="btn btn-md btn-block btn-success card__create-btn">Create Now!</button>
                </a>
            </div>
        <?php endif; ?>
        <?php if($i===12): ?>
            <div class="card card-ads" style="padding: 15px 0;">
                <?= $this->element('ads/ad-ingrid'); ?>
            </div>
        <?php endif; ?>

		<div class="card">
			<a href="/quote-<?=$quote['id']?>">
    			<div class="card-top">
    				<img class="card-img-top" src="<?=$__q_thu ?>" alt="<?=htmlspecialchars($quote['text'])?>" title="<?=htmlspecialchars($quote['text'])?>" />
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
	<?php } ?>
</div>
<!-- /.col -->
