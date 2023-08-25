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

    $action = $this->request->getParam('action');

    if ($action === 'quickStudio') {
        $canonical_url = Router::url('/quick-studio', true);
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

    <?= $this->Html->css('vendor/bootstrap.v5.3.0.min.css') ?>
    <?= $this->Html->css('styles.css') ?>

    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/satoshi" referrerpolicy="no-referrer" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css" referrerpolicy="no-referrer" media="print" onload="this.onload=null;this.removeAttribute('media');" />
    <?php echo $this->element('meta/analitycs');  ?>
    <?php echo $this->element('meta/og', ['title' => $meta_title, 'description' => $meta_description, 'image' => $meta_image]);  ?>
</head>

<body class="default">
    <script>
        window.__APP_DATA__ = {};
    </script>
    <?php echo (!isset($hide_header)) ? $this->element('layout/header') : null;  ?>

    <div class="fluid-container mb20">
        <?= $this->fetch('fluidcontent');?>
    </div>

    <div class="container" id="mainContainer">
        <?= $this->fetch('content'); ?>
    </div>

    <?= $this->element('modals/login'); ?>

    <?php echo (!isset($hide_footer)) ? $this->element('layout/footer') : null;  ?>

    <script>
        __APP_DATA__.user_id = <?= ($user_id) ? $user_id : 'null' ?>;
        __APP_DATA__.user = <?php echo json_encode($user); ?>;
    </script>

    <?= $this->Html->script([
        'vendor/bootstrap.v5.3.0.bundle.min.js',
        // 'vendor/jquery.min.js',
        // 'vendor/popper.min.js',
        'main.js',
    ], ['defer' => true]) ?>

    <?= $this->fetch('script_from_view'); ?>

    <?= $this->fetch('script_tracking_events'); ?>

    <?php if (!$user): ?>
    <script src="https://accounts.google.com/gsi/client" defer></script>
        <div id="g_id_onload"
        data-client_id="443859332061-cl9dab1raicl1ernph96mot32jpkhlk2.apps.googleusercontent.com"
        data-login_uri="<?= Router::url('/google/callback?redirect_to=' . $this->request->getAttribute("here"), true); ?>">
    </div>
    <?php endif; ?>
</body>

</html>