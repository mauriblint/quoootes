<?php
    use Cake\Routing\Router;
?>
<div class="card-columns card-columns-quotes">
	<?php $i=0; foreach( $quotes as $quote ) { $i++;?>
        <?php if($i===2): ?>
            <div class="card card-ads" style="padding: 15px 0;">
                <?= $this->element('ads/ad-ingrid'); ?>
            </div>
        <?php endif; ?>
        <?php if($i===6): ?>
            <div class="card card__create text-center">
                <a href="<?=Router::url(['_name' => 'Editor']);?>" class="card__create-inner gr pt-4">
                    Have a Quote in mind?
                    <button class="btn btn-md btn-block btn-light card__create-btn">Create!</button>
                </a>
            </div>
        <?php endif; ?>
        <?php if($i===12): ?>
            <div class="card card-ads" style="padding: 15px 0;">
                <?= $this->element('ads/ad-ingrid'); ?>
            </div>
        <?php endif; ?>
        <?php echo $this->element('quotes/quote-card', ['quote' => $quote]) ?>
	<?php } ?>
</div>
