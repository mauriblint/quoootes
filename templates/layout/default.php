<?php
    use Cake\Routing\Router;
    $meta_title = isset($title) ? $title : 'Free Quote Maker and Generator';
    $meta_description = isset($description) ? $description : 'Simple Quote Maker and Generator. Create beautiful quotes for Instagram, Pinterest and social networks. Best images quote generator.';
    $meta_keywords = isset($keywords) ? $keywords : 'Free Quote Maker for Instagram & Pinterest';
    $meta_image = isset($share_image) ? $share_image : Router::url('/img/og-image.jpg', true);

    $canonical_url = null;

    if (isset($_GET['ref']) || isset($_GET['via'])) {
        $canonical_url = Router::url($this->request->getAttribute("here"), true);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Quoootes - <?= $meta_title ?></title>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $meta_description ?>">
    <meta name="keywords" content="<?= $meta_keywords ?>">
    <?php if ($canonical_url): ?><link rel="canonical" href="<?=$canonical_url?>" /><?php endif; ?>
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="icon"/>
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="shortcut icon"/>
    <meta name="p:domain_verify" content="f0b7d6f5e9bac3d19dc79d01bcaa8826"/>
    <meta name="google-site-verification" content="AlLuCCNbeu8LykxQXBebfUV5pcRMZSEFUsAGOT184do" />
    <meta name="msvalidate.01" content="D2347BCC2A4799B81EEDFC25EB9A2AE1" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css" referrerpolicy="no-referrer" media="print" onload="this.onload=null;this.removeAttribute('media');" />
    <?= $this->Html->css('/bootstrap4/css/bootstrap.min.css') ?>
    <?= $this->Html->css('styles.css') ?>
    <?php echo $this->element('meta/analitycs');  ?>
    <?php echo $this->element('meta/og', ['title' => $meta_title, 'description' => $meta_description, 'image' => $meta_image]);  ?>
</head>

<body class="default">

    <?php echo (!isset($hide_header)) ? $this->element('layout/header') : null;  ?>

    <div class="fluid-container mb20">
        <?= $this->fetch('fluidcontent');?>
    </div>

    <div class="container" id="mainContainer">
        <?= $this->fetch('content'); ?>
    </div>

    <?php echo (!isset($hide_footer)) ? $this->element('layout/footer') : null;  ?>

    <?= $this->Html->script([
        // 'main.js',
        'vendor/jquery.min.js',
        'vendor/popper.min.js',
        '/bootstrap4/js/bootstrap.min.js',
    ], ['defer' => true]) ?>
    <?= $this->fetch('script_from_view'); ?>
    <?= $this->fetch('script_tracking_events'); ?>

    <?php if (!$user): ?>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
        <div id="g_id_onload"
        data-client_id="443859332061-cl9dab1raicl1ernph96mot32jpkhlk2.apps.googleusercontent.com"
        data-login_uri="<?= Router::url('/google/callback', true); ?>">
    </div>
    <?php endif; ?>
</body>

</html>