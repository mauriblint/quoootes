<?php
    use Cake\Routing\Router;
?>
<?php $this->start('fluidcontent'); ?>
<div class="hero">
	<div class="jumbotron gr">
		<div class="container">
			<div class="row justify-content-center jumbotron-content">
                <div class="col-12">
                    <h1 class="text-white mb-5 mt-3">Inspirational Quotes Maker</h1>
                </div>
				<div class="col-md-8">
					<p class="lead text-white">Create <strong>Inspirational Quotes</strong> with our online and <strong>free quote generator</strong>. Pick a template, enter your text quote, and download a beatifuel image with your text!</p>
                    <div class="text-centear mt-5">
                        <a href="<?=Router::url(['_name' => 'Templates']);?>" class="btn btn-dark py-3 px-4 btn-lg">Start Now</a>
                    </div>
				</div>
			</div>
		</div>
	</div>
    <img src="/img/curve.svg" style="position: absolute; bottom: 0; left: 0; width: 100%;">
</div>
<?php $this->end(); ?>

<div class="container my-6">
    <div class="row justify-content-center my-6 text-center">
        <div class="col-12 col-md-8">
            <h2 class="mb-3">Design your Own Inspirational Quotes</h2>
            <p>Whether you’re having a rough day and need a pick-me-up to assure yourself that it’s all going to be OK, or whether you’re just looking for a mission statement for the day, these inspirational quotes will help you put one foot in front of the other and make the small or sweeping changes that you desire. Find an inspirational quote that speaks to your drive and determination. Here are the best inspirational quotes for you!</p>

            <h2 class="mt-6 mb-3">Share Inspirational Quotes</h2>
            <p>Looking for Inspiration? For over 20 years we've collated the very best Inspirational Quotes we can find to positively enhance your life and well being. We want to help you move along your path to success and that means adopting the right mind set every day! If you read these quotes regularly, it helps improve your attitude, outlook and positivity. And let me tell you, being more positive has a powerful beneficial effects on our wellbeing over time. Believe me, I know! So I hope that you take these uplifting and inspirational words to heart. May they motivate you to be the best that you can be!</p>

            <h3 class="mt-5 mb-3">Inspirational Quotes for Instagram</h3>
            <p>Looking for the <strong>best inspirational quotes for Instagram</strong> and other social networks? You are in the rigth place. You can <a href="/quotes/inspirational">explore</a> already created quotes from our comunitty, or create your own Inspirational Quotes with our super simple <a href="/studio">online quote maker</a></p>
            <div class="text-centear mt-5">
                <a href="/quotes/inspirational" class="btn btn-primary btn-lg" title="Explore Inspirational Quotes">Explore</a>
                <a href="<?=Router::url(['_name' => 'Templates']);?>" class="btn btn-primary btn-lg" title="Start Creating Quotes">Create</a>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #fff; }
    p { line-height: 1.7;}
</style>