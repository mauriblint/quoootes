<?php
    use Cake\Routing\Router;
?>
<?php $this->start('fluidcontent'); ?>
<style>
    .hero-bullets { list-style: none; padding: 0; margin: 1.25rem 0 0; }
    .hero-bullets li { margin-bottom: .4rem; font-size: 1.05rem; }
    .hero-bullets i { color: #f9384d; margin-right: .5rem; }
    .hero-preview { aspect-ratio: 1 / 1; border-radius: .75rem; margin-bottom: 1rem; padding: 2rem; display: flex; flex-direction: column; justify-content: center; text-align: center; color: #fff; background: linear-gradient(135deg, #f9384d 0%, #7b2ff7 100%); box-shadow: 0 10px 30px rgba(0,0,0,.12); overflow: hidden; }
    .hero-preview__text { font-family: Georgia, 'Times New Roman', serif; font-size: 1.5rem; line-height: 1.3; word-break: break-word; }
    .hero-preview__author { margin-top: 1rem; font-size: .95rem; opacity: .85; text-transform: uppercase; letter-spacing: .08em; }
    @media (max-width: 575px) { .hero-preview { aspect-ratio: 4 / 3; padding: 1.25rem; } .hero-preview__text { font-size: 1.2rem; } }
</style>
<div class="container col-xl-10 col-xxl-8 px-4 py-3 py-md-5">
    <div class="row align-items-center g-lg-5 py-3 py-md-5">
        <div class="col-lg-6 text-center text-lg-start">
            <h1 class="display-5 fw-bold lh-1 mb-3">Free Quote Maker: turn any quote into a <b style="color: #f9384d;">beautiful image</b></h1>
            <p class="col-lg-11 fs-5 text-muted">Write your quote, pick a template and download it in seconds. No sign-up, no design skills needed.</p>
            <ul class="hero-bullets d-none d-md-block">
                <li><i class="bi bi-lightning-charge-fill"></i>Type it and see it live</li>
                <li><i class="bi bi-person-check-fill"></i>No account needed to create and download</li>
                <li><i class="bi bi-phone-fill"></i>Ready for Instagram, Stories, Pinterest and WhatsApp</li>
            </ul>
        </div>
        <div class="col-md-10 mx-auto col-lg-6">
            <form class="p-3 p-md-4 border rounded-3 bg-light write-quote-form" action="<?=Router::url(['_name' => 'QuickStudio']);?>" method="GET">
                <div class="hero-preview" aria-hidden="true">
                    <div class="hero-preview__text" id="heroPreviewText">The best time to plant a tree was 20 years ago. The second best time is now.</div>
                    <div class="hero-preview__author" id="heroPreviewAuthor">Chinese Proverb</div>
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="heroQuoteText" placeholder="Write your quote" name="text" maxlength="300" style="height: 90px"></textarea>
                    <label for="heroQuoteText">Write your quote</label>
                </div>
                <div class="form-floating mb-3 mt-2">
                    <input type="text" class="form-control" id="heroQuoteAuthor" placeholder="Author (optional)" name="author" maxlength="40">
                    <label for="heroQuoteAuthor">Author (optional)</label>
                </div>
                <input type="hidden" name="ref" value="home_hero">
                <button class="w-100 btn btn-lg btn-brand" type="submit">Create my quote image &rarr;</button>
                <small class="d-block text-center text-muted mt-2">Free &middot; No sign-up &middot; Change the design in the next step</small>
            </form>
        </div>
    </div>
</div>
<script>
    (function () {
        var text = document.getElementById('heroQuoteText');
        var author = document.getElementById('heroQuoteAuthor');
        var previewText = document.getElementById('heroPreviewText');
        var previewAuthor = document.getElementById('heroPreviewAuthor');
        var defaultText = previewText.textContent;
        var defaultAuthor = previewAuthor.textContent;
        function sync() {
            var t = text.value.trim();
            previewText.textContent = t || defaultText;
            previewText.style.fontSize = t.length > 160 ? '1.1rem' : (t.length > 80 ? '1.3rem' : '');
            previewAuthor.textContent = author.value.trim() || (t ? '' : defaultAuthor);
        }
        text.addEventListener('input', sync);
        author.addEventListener('input', sync);
    })();
</script>
<?php $this->end(); ?>

<div class="row justify-content-center py-5">
    <div class="col-12 col-lg-9">
        <div class="create-account-banner">
            Starting today, you can create an account to <strong>save your designs</strong>!
            <div class="mt-4 mx-auto d-flex justify-content-center">
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
                <a href="<?=Router::url(['_name' => 'Templates']);?>" class="btn btn-primary btn-lg">Explore all Templates</a>
            </div>
		</section>
	</div>
</div>

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

<div class="row home-section justify-content-center">
	<div class="col-12 col-md-10 col-lg-8 text-center">
        <h2>Quoootes Maker <br> Frequently Asked Questions</h2>
        <p class="lead mt-4"></p>
	</div>

    <div class="col-12 col-xl-10 col-xxl-8 px-4 py-5">
        <?php echo $this->element('general/faqs'); ?>
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

<?php $this->start('script_tracking_events'); ?>
	<script>mixpanel.track("ViewHomepage");</script>
<?php $this->end(); ?>

<?= $this->element('ui/template-modals'); ?>
