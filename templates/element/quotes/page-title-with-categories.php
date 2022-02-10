<?php
    use Cake\Routing\Router;
?>
<div class="my-6">
    <div class="text-center">
        <h1 class="mb-4"><?=$page_title?></h1>
        <?php if ($tag): ?>
        <p class="lead mb-5 mw-800 mx-auto">
            Looking for beautiful <strong><?=$tag['name_seo']?></strong>? Check this collection with the best <?=$tag['name_seo']?> we picked for you. You can share the image or create your own <?=$tag['name_seo']?> images.
        </p>
        <?php endif; ?>
    </div>
    <nav class="tags-hlist mb-5 d-flex justify-content-center">
        <?php foreach ($categories as $cat) {
            echo '<a class="badge badge-light cat-badge" href="'.Router::url(['_name' => 'QuoteImagesTag']).$cat['slug'].'">'.$cat['name'].'</a>';
        } ?>
    </nav>
</div>
