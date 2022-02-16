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

	function getEmbedCode ($id, $w, $h) {
		return '&lt;iframe style="margin: 20px auto;" src="'. Router::url('/embed/' . $id . '?v=1.0', true).'" height="'.$h.'" width="'.$w.'" frameborder="0" scrolling="no"&gt;&lt;/iframe&gt;';
	}

?>

<div class="row justify-content-md-center mt-5">

	<div class="col-md-9">
		<div class="row">
			<div class="col-md-6">
				<div class="card mb-0">
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
				<h4 class="mb-4"><?=$quote['text'];?></h4>

				<div class="single-quote__share">
					<div class="sharethis-inline-share-buttons my-3"></div>
				</div>

				<div class="d-flex flex-column">
					<div>
						<a target="_blank" href="/download/<?=$quote['id']?>" class="btn btn-primary mt-3">
							<i class="bi bi-download mr-1"></i> Download
						</a>
					</div>
					<div>
						<button data-toggle="modal" data-target="#embedCodeModal" class="btn btn-light mt-3" onclick="trackEmbed()">
							<i class="bi bi-code-slash mr-1"></i> Embed on your Site
						</a>
					</div>
				</div>

			</div>

			<div class="col-12 text-center my-5">
				<div class="sharethis-inline-share-buttons"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="embedCodeModal">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Get Embed Code</h5>
         </div>
         <div class="modal-body">
			<!-- Nav tabs -->
			<ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link" id="home-tab" data-toggle="tab" href="#code-sm" role="tab">Small</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" id="profile-tab" data-toggle="tab" href="#code-md" role="tab">Medium</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="messages-tab" data-toggle="tab" href="#code-lg" role="tab">Large</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="settings-tab" data-toggle="tab" href="#code-rs" role="tab">Responsive</a>
				</li>
			</ul>
         </div>

		 <div class="modal-body">
			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane" id="code-sm" role="tabpanel" aria-labelledby="home-tab">
					<div class="code"><samp><?= getEmbedCode($quote['id'], 250, 440) ?></samp></div>
				</div>
				<div class="tab-pane active" id="code-md" role="tabpanel" aria-labelledby="profile-tab">
					<div class="code"><samp><?= getEmbedCode($quote['id'], 400, 440) ?></samp></div>
				</div>
				<div class="tab-pane" id="code-lg" role="tabpanel" aria-labelledby="messages-tab">
					<div class="code"><samp><?= getEmbedCode($quote['id'], 600, 440) ?></samp></div>
				</div>
				<div class="tab-pane" id="code-rs" role="tabpanel" aria-labelledby="settings-tab">
					<div class="code"><samp><?= getEmbedCode($quote['id'], '100%', 440) ?></samp></div>
				</div>
			</div>
		 </div>

         <div class="modal-footer p-4">
            <button type="button" class="btn btn-light btn-block" data-dismiss="modal">Got it!</button>
         </div>
      </div>
   </div>
</div>

<div class="row justify-content-md-center">
<div class="col-md-10">
    <h1 class="h4 color-lite">More images with quotes</h1>
</div>
</div>
<div class="card-columns card-columns-quotes">
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

	.single-quote__share { min-height: 32px; padding-top: 0px;}
	.single-quote__share #st-1 {
		text-align: left !important;
	}
	.code {
    	font-size: 12px;
    	color: #333;
		padding: 10px 20px;
	}
</style>

<script>
	var trackEmbed = function () {
		mixpanel.track("EmbedDesign");
	}
</script>
