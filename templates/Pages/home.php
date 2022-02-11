<?php
    use Cake\Routing\Router;
?>
<?php $this->start('fluidcontent'); ?>
<div class="hero">
	<div class="jumbotron gr">
		<div class="container">
			<div class="row justify-content-center jumbotron-content">
                <div class="col-12">
                    <h1 class="text-white mb-5 mt-3">Quote Maker & Generator. <br/> Simple, online & free.</h1>
                </div>
				<div class="col-md-8">
					<p class="lead text-white">Create images with text using our <strong>quotes creator app</strong>. Discover and get Inspired, create your own images with quotes. Ready to download or share.</p>
                    <div class="text-centear mt-5">
                        <a href="<?=Router::url(['_name' => 'Studio']);?>" class="btn btn-dark py-3 px-4 btn-lg">Create Quote Images</a>
                    </div>
				</div>
			</div>
		</div>
	</div>
    <img src="https://pixelied.com/assets/img/landing-page/curve-section-process-info.svg" style="position: absolute; bottom: 0; left: 0; width: 100%;">
</div>
<?php $this->end(); ?>

<div class="row home-section">
	<div class="col-md-4">
        <div class="featured">
            <div class="featured__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
            </div>
            <h4>1. Pick a Template</h4>
            <p>Choose from our selection of templates</p>
        </div>
	</div>
	<div class="col-md-4">
        <div class="featured">
            <div class="featured__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            <h4>2. Customize</h4>
            <p>Edit colors, text, fonts and photos</p>
        </div>
	</div>
	<div class="col-md-4">
        <div class="featured">
            <div class="featured__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            </div>
            <h4>3. Share</h4>
            <p>Save, download and share your quote!</p>
        </div>
	</div>
</div>

<div class="row home-section">
	<div class="col-md-12">
		<section class="mt-6">
            <div class="text-center mb-5">
			    <h2>Quotes Templates</h2>
                <p class="lead">Browse our Free Quote Templates ready to edit. Pick one, enter some text, and download a beautiful quote!</p>
            </div>
            <div class="row templates-list templates-list__square">
                <?php foreach ($templates as $template) echo $this->element('ui/template', [ 'template' => $template]); ?>
            </div>
            <div class="text-center mt-3">
                <a href="<?=Router::url(['_name' => 'Studio']);?>" class="btn btn-primary btn-lg">Explore all Templates</a>
            </div>
		</section>
	</div>
</div>

<!-- <div class="row d-none">
	<div class="col-md-12">
		<section class="mt-5">
            <div class="text-center mb-5">
			    <h2>Recents Images with Quotes</h2>
                <p>Check out this images with text recently created with our <a href="/quote-maker">quote maker app</a> online.</p>
            </div>
            <div class="row">
			    <?php echo $this->element('ui/wall', ['quotes' => $recentImages]) ?>
            </div>

            <div class="text-center mt50">
                <a href="<?=Router::url(['_name' => 'QuoteImages']);?>" class="btn btn-primary btn-lg">Explore more Quotes</a>
            </div>
		</section>
	</div>
</div> -->

<div class="row home-section">
	<div class="col-md-12 mb-5">
		<section class="mt-5">
            <div class="text-center mb-4">
			    <h2>Quotes by Topics</h2>
                <p class="lead mt-3">Browse your favourite topic quotes like Happiness, Motivational Quotes, Love, Success, etc</p>
            </div>
            <div class="d-flex flex-wrap justify-content-center">
                <?php foreach ($category_list as $cat):?>
                    <a class="m-3" href="<?= Router::url(['_name' => 'QuoteImagesTag']) . $cat['slug'] ?>"><?= $cat['name']; ?></a>
                <?php endforeach; ?>
            </div>
		</section>
	</div>
</div>

<div class="row home-section justify-content-center">
	<div class="col-12 col-md-10 col-lg-8 text-center">
        <h2>What is Quoootes?</h2>
        <p class="lead mt-4">Quoootes.com is an online and <strong>free quote generator</strong>. This <strong>quote maker</strong> turns any text you write into beautiful graphics for Instagram, Pinterest and social media. You don't need to be a designer expert to <strong>create beautiful images with quotes</strong>. Just type your quote, pick one of out awesome Templates, and download it!</p>

        <h2 class="mt-6">How this Quote Generator works?</h2>
        <p class="lead mt-4">This <strong>free quote maker</strong> works on all web browers (desktop computer or phone), just open Quoootes.com using your browser and start designing.
        By entering any text, you will be able to downlod a beatuful designed <strong>quotes for Instagram</strong> and any social network.</p>
	</div>
</div>

<div class="row">
	<div class="col-md-12 mt50 mb40">
		<p class="homeText">Welcome to <strong>Quoootes.com</strong>. Is an online <strong>quotes creator</strong> that allow you to create images quotes online and free. Create quotes ready to share on Pinterest, Facebook, Whatsapp, Twitter, Instagram. Also you will find <i>beautiful quotes</i>, quotes about love, life, <strong>positive quotes</strong>. Create your own images with online and super easy. </p>
	</div>

</div>

<style>
    body {
        background: white;
    }
section.homeSection {
    margin-top: 40px;
}
	section.homeSection > h1 {
		font-size: 32px;
		margin-bottom: 5px;
        margin-left: 45px;
		padding-bottom: 10px;
		letter-spacing: -1px;
	}

    .featured { text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; }
    .featured__icon { text-align: center; background: transparent; width: 80px; height: 80px; border-radius: 20px; display: flex; align-items: center; justify-content: center;}
    .featured__icon svg { width: 50px; color: var(--primary-color);}
</style>
