<?php

use Cake\Routing\Router;
use App\Utils\Helpers;

if (!is_null($quote['image_url'])) {
	$__q_img = $quote['image_url'];
} else {
	$__q_img = Router::url('/___quotes/' . $quote['id'] . '.jpg', true);
}

$__q_link = \App\Lib\DesignService::url($quote, true);
// unset($quote['created']);

$quoteFix = array(
	'id' => $quote['id']
);

// echo '<pre>';
// var_dump($_SESSION);
// // print_r($quote);
// die();

$aspect_ratio = 1;
$is_portrait = false;

if ($quote->width && $quote->height) {
	$aspect_ratio = ($quote->width / $quote->height);
}

if ($aspect_ratio<1) {
	$is_portrait = true;
}

$is_portrait = true;


function getEmbedCode($id, $w, $h) {
	return '&lt;iframe style="margin: 20px auto;" src="' . Router::url('/embed/' . $id . '?v=1.0', true) . '" height="' . $h . '" width="' . $w . '" frameborder="0" scrolling="no"&gt;&lt;/iframe&gt;';
}

?>

<script type="application/ld+json"><?= json_encode(array_filter([
	'@context' => 'https://schema.org',
	'@type' => 'ImageObject',
	'contentUrl' => $quote['image_url'],
	'url' => $__q_link,
	'name' => $quote['text'],
	'caption' => $quote['text'] . ($quote['text_footer'] ? ' - ' . $quote['text_footer'] : ''),
	'width' => $quote['width'],
	'height' => $quote['height'],
	'datePublished' => $quote['created'] ? $quote['created']->format('c') : null,
	'publisher' => ['@type' => 'Organization', 'name' => 'Quoootes', 'url' => 'https://quoootes.com'],
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>

<div class="ooo-view row justify-content-md-center mt-3 mt-md-5">

	<div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-9 justify-content-center">

		<?php if ($is_anon_owner) : ?>
			<div class="row d-none">
				<div class="col-12">
					<div class="alert alert-info">
						asdasd
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="row">

			<!-- Image -->
			<div class="col-md-6 ooo-view__preview">
				<div class="card mb-0">
					<div class="card-top">
						<img class="card-img-top full-radius" src="<?= $quote['image_url']; ?>" alt="<?= h($quote['text']) ?>" />
					</div>
					<div class="card-body d-none">
						<div class="card-stats ">
							<span> <i class="bi bi-heart"></i><?= $quote['likes'] ?></span>
							<span> <i class="bi bi-download"></i> <?= $quote['downloads'] ?></span>
							<span> <i class="bi bi-eye"></i> <?= Helpers::formatNumber($quote['views']); ?></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Sidebar -->
			<div class="col-md-5 ooo-view__sidebar mt-3 mt-md-0">
				<div class="card">
					<div class="card-body p-0">
						<h1><?= h($quote['text']) ?></h1>
					</div>
					<div class="card-body">
					<?php if (isset($quote['tag'])): ?>
						<div class="design__label">
							<a href="<?= Router::url(['_name' => 'QuoteImagesTag']) . $quote['tag']['slug'] ?>"><?= $quote['tag']['name_seo']; ?></a>
						</div>
					<?php endif; ?>

				<div class="single-quote__share">
					<div class="sharethis-inline-share-buttons my-3"></div>
				</div>

				<div class="d-flex flex-column">
					<div>
						<a target="_blank" href="/download/<?= $quote['id'] ?>" rel="nofollow" class="btn btn-primary mt-3">
							<i class="bi bi-download mr-1"></i> Download
						</a>
					</div>
					<div>
						<button data-bs-toggle="modal" data-bs-target="#embed-code-modal" class="btn btn-light mt-3" onclick="trackEmbed()">
							<i class="bi bi-code-slash mr-1"></i> Embed on your Site
						</button>
					</div>
				</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="modal modal-v2 signup-modal" tabindex="-1" role="dialog" id="s-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Create an Account</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i class="bi bi-x-lg"></i>
				</button>
			</div>
			<div class="modal-body text-center">
				<p class="text-muted">Create a free account to save, delete, and organize your designs!</p>

				<div class="my-4 mx-auto d-flex justify-content-center">
                <div class="g_id_signin"
                    data-type="standard"
                    data-size="large"
                    data-theme="outline"
                    data-text="sign_in_with"
                    data-shape="rectangular"
                    data-logo_alignment="left">
                </div>
            </div>
			</div>
		</div>
	</div>
</div>

<?php echo $this->element('modals/embed-code'); ?>

<div class="row justify-content-md-center mt-5">
	<div class="col-md-10">
		<h3 class="h4 color-lite">More images with quotes</h3>
	</div>
</div>

<div class="card-columns card-columns-quotes">
	<?php foreach ($popularQuotes as $quote) { ?>
		<?php
		$__q_link = \App\Lib\DesignService::url($quote, true);
		if (!is_null($quote['image_url'])) {
			$__q_img = $quote['image_url'];
			$__q_thu = $quote['thumb_url'];
		} else {
			$__q_img = Router::url('/___quotes/' . $quote['id'] . '.jpg', true);
			$__q_thu = Router::url('/___quotes/' . $quote['id'] . '_s.jpg', true);
		}
		?>
		<div class="card">
			<a href="<?= \App\Lib\DesignService::url($quote) ?>">
				<div class="card-top">
					<img class="card-img-top full-radius" src="<?= $__q_thu ?>" alt="<?= htmlspecialchars($quote['text']) ?>" title="<?= htmlspecialchars($quote['text']) ?>" />
				</div>
				<!-- <div class="card-body">
					<p class="card-text"><?= wordwrap($quote['text'], 25, "<br />\n"); ?></p>
				</div> -->
				<!-- <div class="card-statss">
					<span><i class="fa fa-heart"></i> <?= $quote['likes']; ?></span>
					<span><i class="fa fa-eye"></i> <?= Helpers::formatNumber($quote['views']); ?></span>
					<span><i class="fa fa-download"></i> <?= $quote['downloads']; ?></span>
				</div> -->
			</a>
		</div>
	<?php } ?>
</div>
<style>
	/* .ooo-view__sidebar { max-width: 380px; } */
	/* .ooo-view__sidebar .card-body { padding: 0; } */
	.ooo-view__sidebar h1 { font-style: italic; font-size: 18px; font-weight: 300; text-align: center; border-bottom: 1px solid rgba(0, 0, 0, .1); padding: 20px; }
</style>
<style>
	.design_image-content {
		background-color: #fdfdfd;
		box-shadow: 0 1px 8px rgba(0, 0, 0, .1);
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

	.single-quote__share {
		min-height: 32px;
		padding-top: 0px;
	}

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
	var trackEmbed = function() {
		mixpanel.track("EmbedDesign");
	}
</script>

<?php if ($is_anon_owner) : ?>
<script>
setTimeout(function () {
	get_modal('create-account').show();
}, 5000);
</script>
<?php endif; ?>