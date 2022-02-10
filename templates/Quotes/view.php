<?php
    use Cake\Routing\Router;
    use App\Utils\Helpers;

    if (!is_null($quote['image_url'])) {
		$__q_img = $quote['image_url'];
    } else {
		$__q_img = Router::url('/___quotes/'.$quote['id'].'.jpg', true);
    }

	$__q_link = Router::url('/quote-'.$quote['id'], true);
    // unset($quote['created']);

    $quoteFix = array(
        'id' => $quote['id']
    );

	// echo '<pre>';
	// print_r($quote);
	// die();

?>

<div class="row justify-content-md-center mt-5">

	<div class="col-md-9">
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-top">
						<img class="card-img-top" src="<?=$quote['image_url'];?>" />
					</div>
					<div class="card-body">
						<div class="card-stats">
							<span> <i class="bi bi-heart"></i><?=$quote['likes']?></span>
							<span> <i class="bi bi-download"></i> <?=$quote['downloads']?></span>
							<span> <i class="bi bi-eye"></i> <?=Helpers::formatNumber($quote['views']);?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="design__label">
					<a href="<?= Router::url(['_name' => 'QuoteImagesTag']) . $quote['tag']['slug'] ?>"><?=$quote['tag']['name_seo'];?></a>
				</div>
				<h4 class="mb30"><?=$quote['text'];?></h4>

				<div class="addthis_inline_share_toolbox_ro0p mt10"></div>
				<a target="_blank" href="/quotes/download/<?=$quote['id']?>" class="btn btn-primary mt20">Download</a>
			</div>

			<div class="col-12 text-center mt30">

			</div>
		</div>
	</div>
</div>

<div class="row justify-content-md-center">
<div class="col-md-10">
    <h1 class="h4 color-lite">More images with quotes</h1>
</div>
</div>
<div class="card-columns card-columns-quotes" style="">
	<?php foreach( $popularQuotes as $quote ) {?>
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
    				<span><i class="fa fa-eye"></i> <?=Helpers::formatNumber($quote['views']);?></span>
                    <span><i class="fa fa-download"></i> <?=$quote['downloads'];?></span>
    			</div>
			</a>
		</div>
	<?php } ?>
</div>
<!-- /.col -->
<style>
	.design_image-content {
		background-color: #fdfdfd;
		box-shadow: 0 1px 8px rgba(0,0,0,.1);
		border-radius: 8px;
		/*padding: 10px;*/
	}
	.design__image {
		border-radius: 8px;
		width: 100%;
	}
	.design__label {
		font-size: 14px;
		margin-bottom: 10px;
	}
	.design__label .badge {
		padding: 5px 10px;
	}
</style>

<script>
	var QuoteViewData = <?=json_encode($quoteFix);?>;
</script>
