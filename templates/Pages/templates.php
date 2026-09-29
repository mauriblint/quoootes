<div class="row my-6 justify-content-center">
    <div class="col-12 col-md-10 text-center mb-4">
        <h1>Quotes Templates for Design</h1>
        <p class="lead">Welcome to our collection of beautiful designed quotes images templates made using our <strong>online quote maker application</strong>. Click on any design to make your own quote!</p>
    </div>

    <div class="col-12">
        <div class="row templates-list">
        <?php foreach ($templates as $template) echo $this->element('ui/template', [ 'template' => $template]); ?>
        </div>
    </div>
</div>
<?php $this->start('script_tracking_events'); ?>
	<script>mixpanel.track("ViewTemplates");</script>
<?php $this->end(); ?>

<?= $this->element('ui/template-modals'); ?>
