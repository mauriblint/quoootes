<?php
    use Cake\Routing\Router;
    $here = $this->getRequest()->getRequestTarget();
?>
    <meta property="og:site_name" content="Quoootes"/>
    <meta property="fb:app_id" content="85493958355" />
    <meta property="og:locale" content="en_US" />

    <meta property="og:title" content="<?= $title ?>" />
    <meta property="og:description" content="<?= $description ?>" />
    <meta property="og:url" content="<?= Router::url($here, true); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?= $image ?>" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="613" />
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="<?= $title ?>">
    <meta property="twitter:description" content="<?= $description ?>">
    <meta property="twitter:image" content="<?= $image ?>">