<?php
    $meta_title = isset($title) ? $title : 'Free Quote Maker and Generator';
    $meta_description = isset($description) ? $description : 'Simple Quote Maker and Generator. Create beautiful quotes for Instagram, Pinterest and social networks. Best images quote generator.';
    $meta_keywords = isset($keywords) ? $keywords : 'Free Quote Maker for Instagram & Pinterest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Quoootes - <?= $meta_title ?></title>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $meta_description ?>">
    <meta name="keywords" content="<?= $meta_keywords ?>">
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="icon"/>
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="shortcut icon"/>
    <meta name="p:domain_verify" content="f0b7d6f5e9bac3d19dc79d01bcaa8826"/>
    <meta name="google-site-verification" content="AlLuCCNbeu8LykxQXBebfUV5pcRMZSEFUsAGOT184do" />
    <meta property="og:site_name" content="Quoootes"/>
    <meta property="fb:app_id" content="85493958355" />
    <meta property="og:locale" content="en_US" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" referrerpolicy="no-referrer" media="print" onload="this.onload=null;this.removeAttribute('media');" />
    <?= $this->Html->css('/bootstrap4/css/bootstrap.min.css') ?>
    <?= $this->Html->css('styles.css') ?>
    <?php echo $this->element('meta/analitycs');  ?>
</head>

<body class="default">

    <?php echo $this->element('layout/header');  ?>

    <div class="fluid-container mb20">
        <?= $this->fetch('fluidcontent');?>
    </div>

    <div class="container" id="mainContainer">
        <?= $this->fetch('content'); ?>
    </div>

    <?php echo $this->element('layout/footer');  ?>

    <?= $this->Html->script([
        // 'main.js',
        'vendor/jquery.min.js',
        'vendor/popper.min.js',
        '/bootstrap4/js/bootstrap.min.js',
    ], ['defer' => true]) ?>
    <?= $this->fetch('script_from_view'); ?>
    <?= $this->fetch('script_tracking_events'); ?>
</body>

</html>