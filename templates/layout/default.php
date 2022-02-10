<?php
    $title = isset($title) ? $title : __('Free Meme Generator');
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quoootes - <?= $title ?></title>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link href="/favicon.ico?nocached" type="image/x-icon" rel="icon"/>
    <link href="/favicon.ico?nocached" type="image/x-icon" rel="shortcut icon"/>
    <meta name="p:domain_verify" content="f0b7d6f5e9bac3d19dc79d01bcaa8826"/>
    <meta name="google-site-verification" content="AlLuCCNbeu8LykxQXBebfUV5pcRMZSEFUsAGOT184do" />
    <meta property="og:site_name" content="Quoootes"/>
    <meta property="fb:app_id" content="85493958355" />
    <meta property="og:locale" content="en_US" />
    <!-- <link href="https://fonts.googleapis.com/css?family=Lato:400,700&display=swap" rel="stylesheet"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" referrerpolicy="no-referrer" />




    <?= $this->Html->css('/bootstrap4/css/bootstrap.min.css') ?>
    <?= $this->Html->css('styles.css') ?>
    <?= $this->Html->css('helpers.css') ?>
    <?php // $this->Html->css('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') ?>
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

    <script>
    </script>
    <?= $this->Html->script([
        'vendor/jquery.min.js',
        'vendor/popper.min.js',
        '/bootstrap4/js/bootstrap.min.js',
        'vendor/html2canvas_old.min.js'
    ], ['defer' => true]) ?>
    <?= $this->fetch('script_from_view'); ?>
</body>

</html>